<div 
    x-data="{ 
        show: false, 
        message: '', 
        type: 'info',
        timeout: null,
        init() {
            window.addEventListener('notify', (event) => {
                this.message = event.detail.message || 'Notification';
                this.type = event.detail.type || 'info';
                this.show = true;
                
                clearTimeout(this.timeout);
                this.timeout = setTimeout(() => {
                    this.show = false;
                }, 5000);
            });
        }
    }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-2"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform translate-y-2"
    class="fixed top-4 right-4 z-[9999] max-w-md"
    style="display: none;"
>
    <div 
        class="flex items-center gap-3 rounded-lg border shadow-lg p-4 backdrop-blur-sm"
        :class="{
            'bg-blue-50/95 dark:bg-blue-900/95 border-blue-200 dark:border-blue-800': type === 'info',
            'bg-green-50/95 dark:bg-green-900/95 border-green-200 dark:border-green-800': type === 'success',
            'bg-amber-50/95 dark:bg-amber-900/95 border-amber-200 dark:border-amber-800': type === 'warning',
            'bg-red-50/95 dark:bg-red-900/95 border-red-200 dark:border-red-800': type === 'error'
        }"
    >
        <!-- Icon -->
        <div class="flex-shrink-0">
            <svg x-show="type === 'info'" class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <svg x-show="type === 'success'" class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <svg x-show="type === 'warning'" class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <svg x-show="type === 'error'" class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>

        <!-- Message -->
        <div class="flex-1">
            <p 
                class="text-sm font-semibold"
                :class="{
                    'text-blue-800 dark:text-blue-200': type === 'info',
                    'text-green-800 dark:text-green-200': type === 'success',
                    'text-amber-800 dark:text-amber-200': type === 'warning',
                    'text-red-800 dark:text-red-200': type === 'error'
                }"
                x-text="message"
            ></p>
        </div>

        <!-- Close Button -->
        <button 
            @click="show = false"
            class="flex-shrink-0 rounded-lg p-1 hover:bg-white/50 dark:hover:bg-black/20 transition"
        >
            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
</div>
