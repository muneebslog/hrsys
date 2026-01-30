import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
    forceTLS: true
});

// Listen for admin notifications
window.Echo.channel('admin-notifications')
    .listen('.complaint.created', (e) => {
        console.log('New complaint received:', e);
        
        // Trigger Livewire events
        if (window.Livewire) {
            window.Livewire.dispatch('newComplaintCreated');
            window.Livewire.dispatch('refreshData');
        }
        
        // Show browser notification
        showNotification('New Complaint', `${e.title} - ${e.category}`);
    })
    .listen('.leave-request.created', (e) => {
        console.log('New leave request received:', e);
        
        // Trigger Livewire events
        if (window.Livewire) {
            window.Livewire.dispatch('newLeaveRequestCreated');
            window.Livewire.dispatch('refreshData');
        }
        
        // Show browser notification
        const employeeName = e.employee ? `${e.employee.first_name} ${e.employee.last_name}` : 'Unknown';
        showNotification('New Leave Request', `${employeeName} requested leave`);
    });

// Helper function to show browser notifications
function showNotification(title, body) {
    if ('Notification' in window && Notification.permission === 'granted') {
        new Notification(title, {
            body: body,
            icon: '/favicon.svg',
            badge: '/favicon.svg'
        });
    } else if ('Notification' in window && Notification.permission !== 'denied') {
        Notification.requestPermission().then(permission => {
            if (permission === 'granted') {
                new Notification(title, {
                    body: body,
                    icon: '/favicon.svg',
                    badge: '/favicon.svg'
                });
            }
        });
    }
}

