<?php

use Livewire\Volt\Component;
use App\Models\LeaveType;
use Flux\Flux;

new class extends Component {
    public $leaveTypes;
    public $name = '';
    public $description = '';
    public $editingId = null;

    public function mount()
    {
        $this->loadLeaveTypes();
    }

    public function loadLeaveTypes()
    {
        $this->leaveTypes = LeaveType::withCount('leaveRequests')->orderBy('name')->get();
    }

    public function openAddModal()
    {
        $this->reset(['name', 'description', 'editingId']);
        $this->resetValidation();
    }

    public function openEditModal($id)
    {
        $leaveType = LeaveType::findOrFail($id);
        $this->editingId = $leaveType->id;
        $this->name = $leaveType->name;
        $this->description = $leaveType->description;
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:leave_types,name,' . $this->editingId,
            'description' => 'nullable|string|max:500',
        ]);

        try {
            if ($this->editingId) {
                // Update existing
                $leaveType = LeaveType::findOrFail($this->editingId);
                $leaveType->update([
                    'name' => $this->name,
                    'description' => $this->description,
                ]);
                session()->flash('message', 'Leave type updated successfully.');
            } else {
                // Create new
                LeaveType::create([
                    'name' => $this->name,
                    'description' => $this->description,
                ]);
                session()->flash('message', 'Leave type created successfully.');
            }

            $this->loadLeaveTypes();
            Flux::modal('leave-type-form')->close();
            $this->reset(['name', 'description', 'editingId']);
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to save leave type: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $leaveType = LeaveType::findOrFail($id);
            
            // Check if leave type is being used
            if ($leaveType->leaveRequests()->count() > 0) {
                session()->flash('error', 'Cannot delete leave type that is being used in leave requests.');
                return;
            }

            $leaveType->delete();
            session()->flash('message', 'Leave type deleted successfully.');
            $this->loadLeaveTypes();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete leave type: ' . $e->getMessage());
        }
    }
}; ?>

<div>
    <div class="max-w-6xl mx-auto px-4 py-8">
        
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-black dark:text-white">Leave Types</h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Manage different types of leaves available for employees</p>
            </div>
            <flux:modal.trigger name="leave-type-form">
                <flux:button wire:click="openAddModal" icon="plus">Add Leave Type</flux:button>
            </flux:modal.trigger>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl">
                <p class="text-sm font-semibold text-green-800 dark:text-green-400">{{ session('message') }}</p>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                <p class="text-sm font-semibold text-red-800 dark:text-red-400">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Table -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800">
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Leave Type</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Usage Count</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($leaveTypes as $leaveType)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-bold dark:text-white">{{ $leaveType->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ $leaveType->description ?? 'No description' }}</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                        {{ $leaveType->leave_requests_count }} {{ $leaveType->leave_requests_count == 1 ? 'request' : 'requests' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <flux:modal.trigger name="leave-type-form">
                                            <button wire:click="openEditModal({{ $leaveType->id }})" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>
                                        </flux:modal.trigger>
                                        <button 
                                            wire:click="delete({{ $leaveType->id }})" 
                                            wire:confirm="Are you sure you want to delete this leave type?"
                                            class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition"
                                            @if($leaveType->leave_requests_count > 0) disabled title="Cannot delete - in use" @endif
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center">
                                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-slate-500 dark:text-slate-400 font-semibold">No leave types found</p>
                                            <p class="text-slate-400 text-sm mt-1">Create your first leave type to get started</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add/Edit Modal -->
        <flux:modal name="leave-type-form" class="md:w-[600px]">
            <form wire:submit="save" class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ $editingId ? 'Edit Leave Type' : 'Add Leave Type' }}</flux:heading>
                    <flux:text class="mt-2">{{ $editingId ? 'Update the leave type information below.' : 'Create a new leave type for employees to request.' }}</flux:text>
                </div>

                <div class="space-y-4">
                    <flux:input 
                        wire:model="name" 
                        label="Leave Type Name" 
                        placeholder="e.g., Sick Leave, Annual Leave, Casual Leave" 
                        required 
                    />
                    @error('name') 
                        <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                    @enderror

                    <flux:textarea 
                        wire:model="description" 
                        label="Description" 
                        placeholder="Describe when this leave type should be used..."
                        rows="3"
                    />
                    @error('description') 
                        <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="primary">
                        {{ $editingId ? 'Update' : 'Create' }} Leave Type
                    </flux:button>
                </div>
            </form>
        </flux:modal>

    </div>
</div>
