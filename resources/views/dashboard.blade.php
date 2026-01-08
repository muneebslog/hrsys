<x-layouts.app :title="__('Dashboard')">
   
    @if(auth()->user()->role->name === 'Admin')
        <livewire:admindashboard/>
    @else
        <livewire:empdashboard/>
    @endif
   
</x-layouts.app>
