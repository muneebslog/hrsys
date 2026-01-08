<?php

use Livewire\Volt\Component;
use App\Models\Department;
use App\Models\Employee;
use Flux\Flux;

new class extends Component {
    public $departments = [];
    public $employees = [];
    public $name = '';
    public $description = '';
    public $prefix = '';
    public $head_employee_id = '';
    public $editingId = null;

    public function mount()
    {
        $this->loadDepartments();
        $this->employees = Employee::with('user')->get();
    }

    public function loadDepartments()
    {
        $this->departments = Department::with(['head.user', 'employees'])
            ->withCount('employees')
            ->get();
    }

    public function openAddModal()
    {
        $this->reset(['name', 'description','prefix', 'head_employee_id', 'editingId']);
    }

    public function openEditModal($id)
    {
        $department = Department::findOrFail($id);
        $this->editingId = $id;
        $this->name = $department->name;
        $this->description = $department->description;
        $this->prefix = $department->prefix;
        $this->head_employee_id = $department->head_employee_id;
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . ($this->editingId ?? 'NULL'),
            'description' => 'nullable|string|max:500',
            'prefix' => 'required|string|max:10|unique:departments,prefix,' . ($this->editingId ?? 'NULL'),
            'head_employee_id' => 'nullable|exists:employees,id',
        ]);

        if ($this->editingId) {
            Department::findOrFail($this->editingId)->update($validated);
            session()->flash('message', 'Department updated successfully!');
        } else {
            Department::create($validated);
            session()->flash('message', 'Department created successfully!');
        }

        $this->loadDepartments();
        $this->reset(['name', 'description', 'prefix', 'head_employee_id', 'editingId']);
        Flux::modal('department-form')->close();
    }

    public function delete($id)
    {
        $department = Department::findOrFail($id);
        
        if ($department->employees()->count() > 0) {
            session()->flash('error', 'Cannot delete department with assigned employees. Please reassign them first.');
            return;
        }

        $department->delete();
        session()->flash('message', 'Department deleted successfully!');
        $this->loadDepartments();
    }
}; ?>

<div>
    <div class="max-w-7xl mx-auto px-4 py-8">
        
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-black dark:text-white">Departments</h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Manage organization departments and their heads</p>
            </div>
            <flux:modal.trigger name="department-form">
                <flux:button wire:click="openAddModal" icon="plus">Add Department</flux:button>
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
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Department</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Prefix</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Head</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Employees</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($departments as $department)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold dark:text-white">{{ $department->name }}</p>
                                            @if($department->description)
                                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ Str::limit($department->description, 50) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                        {{ $department->prefix }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($department->head)
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-purple-500 rounded-lg flex items-center justify-center text-white text-xs font-bold">
                                                {{ substr($department->head->user->name ?? 'N', 0, 1) }}
                                            </div>
                                            <span class="text-sm font-semibold dark:text-white">{{ $department->head->user->name ?? 'Unknown' }}</span>
                                        </div>
                                    @else
                                        <span class="text-sm text-slate-400 italic">No head assigned</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400">
                                        {{ $department->employees_count }} {{ $department->employees_count == 1 ? 'employee' : 'employees' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <flux:modal.trigger name="department-form">
                                            <button wire:click="openEditModal({{ $department->id }})" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>
                                        </flux:modal.trigger>
                                        <button 
                                            wire:click="delete({{ $department->id }})"
                                            wire:confirm="Are you sure you want to delete this department?"
                                            class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition"
                                            @if($department->employees_count > 0) disabled title="Cannot delete - has employees" @endif
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
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center">
                                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-slate-500 dark:text-slate-400 font-semibold">No departments found</p>
                                            <p class="text-slate-400 text-sm mt-1">Create your first department to get started</p>
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
        <flux:modal name="department-form" class="md:w-[600px]">
            <form wire:submit="save" class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ $editingId ? 'Edit Department' : 'Add Department' }}</flux:heading>
                    <flux:text class="mt-2">{{ $editingId ? 'Update the department information below.' : 'Create a new department for the organization.' }}</flux:text>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <flux:input 
                            wire:model="name" 
                            label="Department Name" 
                            placeholder="e.g., Human Resources" 
                            required 
                        />
                        
                        <flux:input 
                            wire:model="prefix" 
                            label="Prefix" 
                            placeholder="e.g., HR" 
                            required 
                        />
                    </div>

                    <flux:textarea 
                        wire:model="description" 
                        label="Description" 
                        placeholder="Describe the department's responsibilities..."
                        rows="3"
                    />

                    {{-- <flux:select wire:model="head_employee_id" label="Department Head (Optional)">
                        <flux:select.option value="">No head assigned</flux:select.option>
                        @foreach($employees as $employee)
                            <flux:select.option value="{{ $employee->id }}">
                                {{ $employee->user->name ?? 'Unknown' }} ({{ $employee->employee_code }})
                            </flux:select.option>
                        @endforeach
                    </flux:select> --}}
                </div>

                @if ($errors->any())
                    <div class="p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                        <ul class="text-xs text-red-600 dark:text-red-400 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="primary">
                        {{ $editingId ? 'Update' : 'Create' }} Department
                    </flux:button>
                </div>
            </form>
        </flux:modal>

    </div>
</div>
