<x-layouts.app :title="__('Dashboard')">
   
    @if(auth()->user()->role->name === 'admin')
        <livewire:admindashboard/>
    @else
        <livewire:empdashboard/>
    @endif
   
</x-layouts.app>
