<?php

use Livewire\Volt\Component;
use App\Models\Employee;
use App\Models\EmployeeDocument;
use App\Models\Role;
use Flux\Flux;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    public $employee;
    public $activeTab = 'personal';

    // Editable fields
    public $full_name;
    public $father_name;
    public $designation;
    public $qualification;
    public $phone;
    public $emergency_contact;
    public $cnic;
    public $marital_status;
    public $blood_group;
    public $joining_date;
    public $department_id;
    public $manager_id;
    public $role_id;

    public function mount($emp)
    {
        $this->employee = Employee::with(['user.role', 'department', 'manager', 'documents', 'leaves.leaveType', 'leaves.approver'])
            ->findOrFail($emp);
        
        // Populate editable fields
        $this->full_name = $this->employee->full_name;
        $this->father_name = $this->employee->father_name;
        $this->designation = $this->employee->designation;
        $this->qualification = $this->employee->qualification;
        $this->phone = $this->employee->phone;
        $this->emergency_contact = $this->employee->emergency_contact;
        $this->cnic = $this->employee->cnic;
        $this->marital_status = $this->employee->marital_status;
        $this->blood_group = $this->employee->blood_group;
        $this->joining_date = $this->employee->joining_date;
        $this->department_id = $this->employee->department_id;
        $this->manager_id = $this->employee->manager_id;
        $this->role_id = $this->employee->user->role_id;
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function updateEmployee()
    {
        $this->validate([
            'full_name' => 'required|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'qualification' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'emergency_contact' => 'nullable|string|max:20',
            'cnic' => 'nullable|string|max:20',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'blood_group' => 'nullable|string|max:10',
            'joining_date' => 'nullable|date',
            'department_id' => 'required|exists:departments,id',
            'manager_id' => 'nullable|exists:employees,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        try {
            // Prevent demoting the last admin
            $user = $this->employee->user;
            $isCurrentlyAdmin = $user->role && $user->role->name === 'admin';
            $newRole = Role::find($this->role_id);
            if ($isCurrentlyAdmin && $newRole && $newRole->name !== 'admin') {
                $adminCount = \App\Models\User::where('role_id', $user->role_id)->count();
                if ($adminCount <= 1) {
                    session()->flash('error', 'Cannot change role: at least one admin must remain.');
                    return;
                }
            }

            $this->employee->update([
                'full_name' => $this->full_name,
                'father_name' => $this->father_name,
                'designation' => $this->designation,
                'qualification' => $this->qualification,
                'phone' => $this->phone,
                'emergency_contact' => $this->emergency_contact,
                'cnic' => $this->cnic,
                'marital_status' => $this->marital_status,
                'blood_group' => $this->blood_group,
                'joining_date' => $this->joining_date,
                'department_id' => $this->department_id,
                'manager_id' => $this->manager_id,
            ]);

            // Update user name and role
            $this->employee->user->update([
                'name' => $this->full_name,
                'role_id' => $this->role_id,
            ]);

            // Refresh employee data
            $this->employee->refresh();
            $this->employee->load(['user.role', 'department', 'manager', 'documents', 'leaves.leaveType', 'leaves.approver']);

            Flux::modal('edit-profile')->close();
            session()->flash('message', 'Employee profile updated successfully.');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update employee: ' . $e->getMessage());
        }
    }

    public function downloadDocument($documentId)
    {
        $document = EmployeeDocument::findOrFail($documentId);
        
        // Check if file exists
        if (!Storage::disk('public')->exists($document->file_path)) {
            session()->flash('error', 'File not found.');
            return;
        }
        
        // Return file download
        return Storage::disk('public')->download($document->file_path, $document->document_name);
    }

    #[On('document-uploaded')]
    public function refreshDocuments()
    {
        $this->employee->load('documents');
    }
}; ?>

<div>
    <div class="">

    <nav class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('staffdirectory') }}" wire:navigate class="p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-full transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h1 class="font-bold text-lg">Back to Directory</h1>
            </div>
          
            @if (auth()->user()->role->name === 'admin')
                
            <div class="flex items-center gap-3">
                    <button class="px-4 py-2 text-sm font-bold text-red-600 bg-red-50 dark:bg-red-900/20 rounded-lg hover:bg-red-100 transition">Deactivate Staff</button>
                    <flux:modal.trigger name="edit-profile">
                        <flux:button>Edit Profile</flux:button>
                    </flux:modal.trigger>
                </div>
            @endif

        </div>
    </nav>

    <flux:modal name="edit-profile" class="md:w-[800px]">
        <form wire:submit="updateEmployee" class="space-y-6">
            <div>
                <flux:heading size="lg">Update Employee Profile</flux:heading>
                <flux:text class="mt-2">Make changes to employee's personal and professional details.</flux:text>
            </div>

            <div class="space-y-4">
                <flux:subheading>Professional Information</flux:subheading>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:input wire:model="full_name" label="Full Name" placeholder="Enter full name" required />
                    <flux:input wire:model="designation" label="Designation" placeholder="e.g., Senior Cardiologist" />
                    <flux:select wire:model="department_id" label="Department" placeholder="Select department" required>
                        @foreach(\App\Models\Department::all() as $dept)
                            <flux:select.option value="{{ $dept->id }}">{{ $dept->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:select wire:model="manager_id" label="Reporting Manager" placeholder="Select manager">
                        <flux:select.option value="">No Manager</flux:select.option>
                        @foreach(\App\Models\Employee::where('id', '!=', $employee->id)->get() as $emp)
                            <flux:select.option value="{{ $emp->id }}">{{ $emp->full_name }} ({{ $emp->employee_code }})</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:select wire:model="role_id" label="Role" placeholder="Select role" required>
                        @foreach(Role::orderBy('name')->get() as $role)
                            <flux:select.option value="{{ $role->id }}">{{ ucfirst($role->name) }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:input wire:model="qualification" label="Qualification" placeholder="e.g., MBBS, FCPS" />
                    <flux:input wire:model="joining_date" type="date" label="Joining Date" />
                </div>
            </div>

            <div class="space-y-4">
                <flux:subheading>Personal Details</flux:subheading>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:input wire:model="father_name" label="Father's Name" placeholder="Enter father's name" />
                    <flux:input wire:model="cnic" label="CNIC Number" placeholder="xxxxx-xxxxxxx-x" />
                    <flux:select wire:model="marital_status" label="Marital Status" placeholder="Select status">
                        <flux:select.option value="single">Single</flux:select.option>
                        <flux:select.option value="married">Married</flux:select.option>
                        <flux:select.option value="divorced">Divorced</flux:select.option>
                        <flux:select.option value="widowed">Widowed</flux:select.option>
                    </flux:select>
                    <flux:input wire:model="blood_group" label="Blood Group" placeholder="e.g., O+, A-, B+" />
                </div>
            </div>

            <div class="space-y-4">
                <flux:subheading>Contact Information</flux:subheading>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:input wire:model="phone" label="Phone Number" placeholder="+92 3xx xxxxxxx" />
                    <flux:input wire:model="emergency_contact" label="Emergency Contact" placeholder="+92 3xx xxxxxxx" />
                </div>
            </div>

            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary">Save Changes</flux:button>
            </div>
        </form>
    </flux:modal>

    <main class="max-w-6xl mx-auto px-4 mt-8">
        
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 mb-8 shadow-sm">
            <div class="flex flex-col md:flex-row items-center gap-6">
                <flux:avatar size="lg" name="{{ $employee->full_name }}" color="auto" color:seed="{{ $employee->id }}" />
                <div class="flex-1 text-center md:text-left">
                    <div class="flex flex-col md:flex-row md:items-center gap-2 mb-1">
                        <h2 class="text-2xl font-black dark:text-white capitalize">{{ $employee->department->prefix ?? '' }} {{ $employee->full_name }}</h2>
                        @php
                            $statusColors = [
                                'active' => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400',
                                'leave' => 'bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400',
                                'terminated' => 'bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400',
                                'resigned' => 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400',
                            ];
                            $statusClass = $statusColors[$employee->employment_status] ?? $statusColors['active'];
                        @endphp
                        <span class="w-max mx-auto md:mx-0 px-3 py-1 {{ $statusClass }} text-[10px] font-bold rounded-full uppercase">{{ $employee->employment_status }}</span>
                        @if($employee->user && $employee->user->role)
                            <span class="w-max mx-auto md:mx-0 px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-[10px] font-bold rounded-full uppercase">{{ ucfirst($employee->user->role->name) }}</span>
                        @endif
                    </div>
                    <p class="text-slate-500 dark:text-slate-400 font-medium">{{ $employee->designation ?? 'No Designation' }} • {{ $employee->department->name ?? 'No Department' }}</p>
                </div>
                <div class="text-right hidden md:block border-l dark:border-slate-800 pl-6">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Employee Code</p>
                    <p class="text-xl font-black text-blue-600">{{ $employee->employee_code }}</p>
                </div>
            </div>
        </div>
<div class="m-5 mt-0 flex gap-8 border-b border-slate-200 dark:border-slate-800 overflow-x-auto pb-px">
                    <button wire:click="setTab('personal')" class="pb-4 text-sm font-bold {{ $activeTab === 'personal' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-200' }} whitespace-nowrap transition">Personal Info</button>
                    <button wire:click="setTab('documents')" class="pb-4 text-sm font-bold {{ $activeTab === 'documents' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-200' }} whitespace-nowrap transition">Document Vault ({{ count($employee->documents) }})</button>
                    <button wire:click="setTab('leaves')" class="pb-4 text-sm font-bold {{ $activeTab === 'leaves' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-200' }} whitespace-nowrap transition">Leave History ({{ count($employee->leaves) }})</button>
                    <button wire:click="setTab('payroll')" class="pb-4 text-sm font-bold {{ $activeTab === 'payroll' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-200' }} whitespace-nowrap transition">Payroll Details</button>
                </div>
        <section id="personal_info" class="grid lg:grid-cols-3 gap-8 {{ $activeTab !== 'personal' ? 'hidden' : '' }}">
            
            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
                    <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                        <h3 class="font-bold text-sm uppercase tracking-wider text-slate-500">Professional Information</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-12">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Full Name</label>
                            <p class="text-sm font-semibold dark:text-white capitalize">{{ $employee->full_name }}</p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Designation</label>
                            <p class="text-sm font-semibold dark:text-white">{{ $employee->designation ?? 'Not Specified' }}</p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Department</label>
                            <p class="text-sm font-semibold dark:text-white">{{ $employee->department->name ?? 'Not Assigned' }} @if($employee->department)(ID: {{ $employee->department->id }})@endif</p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Reporting Manager</label>
                            @if($employee->manager)
                                <p class="text-sm font-semibold text-blue-600 dark:text-blue-400 underline cursor-pointer capitalize">{{ $employee->manager->full_name }} (ID: {{ $employee->manager->employee_code }})</p>
                            @else
                                <p class="text-sm font-semibold text-slate-400">No Manager Assigned</p>
                            @endif
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Qualification</label>
                            <p class="text-sm font-semibold dark:text-white">{{ $employee->qualification ?? 'Not Specified' }}</p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Joining Date</label>
                            <p class="text-sm font-semibold dark:text-white">{{ $employee->joining_date ? \Carbon\Carbon::parse($employee->joining_date)->format('F d, Y') : 'Not Specified' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
                    <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                        <h3 class="font-bold text-sm uppercase tracking-wider text-slate-500">Personal Details</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-12">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Father's Name</label>
                            <p class="text-sm font-semibold dark:text-white capitalize">{{ $employee->father_name ?? 'Not Specified' }}</p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">CNIC Number</label>
                            <p class="text-sm font-semibold dark:text-white">{{ $employee->cnic ?? 'Not Specified' }}</p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Marital Status</label>
                            <p class="text-sm font-semibold dark:text-white capitalize">{{ $employee->marital_status ?? 'Not Specified' }}</p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Blood Group</label>
                            @if($employee->blood_group)
                                <span class="px-2 py-0.5 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 text-xs font-bold rounded">{{ $employee->blood_group }}</span>
                            @else
                                <p class="text-sm font-semibold text-slate-400">Not Specified</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
                    <h3 class="font-bold text-sm uppercase tracking-wider text-slate-500 mb-6">Contact Channels</h3>
                    <div class="space-y-5">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center text-slate-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase">Email</p>
                                <p class="text-sm font-bold dark:text-white break-all">{{ $employee->user->email ?? 'Not Specified' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center text-slate-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase">Primary Phone</p>
                                <p class="text-sm font-bold dark:text-white">{{ $employee->phone ?? 'Not Specified' }}</p>
                            </div>
                        </div>
                        @if($employee->emergency_contact)
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-rose-50 dark:bg-rose-900/20 rounded-xl flex items-center justify-center text-rose-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-rose-500 uppercase">Emergency Contact</p>
                                <p class="text-sm font-bold dark:text-white">{{ $employee->emergency_contact }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
                    <h3 class="font-bold text-sm uppercase tracking-wider text-slate-500 mb-6">Verified Documents</h3>
                    <div class="space-y-3">
                        @forelse($employee->documents as $document)
                        <div class="p-3 border border-slate-100 dark:border-slate-800 rounded-xl flex items-center justify-between group hover:border-blue-200 transition">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                <div>
                                    <span class="text-xs font-bold dark:text-slate-300">{{ $document->document_name }}</span>
                                    <p class="text-[10px] text-slate-400">{{ ucfirst($document->document_type) }}</p>
                                </div>
                            </div>
                            <button wire:click="downloadDocument({{ $document->id }})" class="text-slate-400 hover:text-blue-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg></button>
                        </div>
                        @empty
                        <p class="text-sm text-slate-400 text-center py-4">No documents uploaded yet</p>
                        @endforelse
                        <flux:modal.trigger name="upload-document">
                            <button type="button" class="w-full mt-2 py-3 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-xl text-xs font-bold text-slate-400 hover:border-blue-400 hover:text-blue-600 transition">
                                + Add New Document
                            </button>
                        </flux:modal.trigger>
                    </div>
                </div>

            </div>
        </section>
        
        <section id="leavehistory" class="space-y-6 {{ $activeTab !== 'leaves' ? 'hidden' : '' }}">
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                    <h3 class="font-bold text-sm uppercase tracking-wider text-slate-500">Leave History</h3>
                </div>
                <div class="p-6">
                    @forelse($employee->leaves->sortByDesc('created_at') as $leave)
                        @php
                            $statusColors = [
                                'pending' => ['bg' => 'bg-amber-50 dark:bg-amber-900/10', 'border' => 'border-amber-100 dark:border-amber-900/20', 'text' => 'text-amber-700 dark:text-amber-400', 'icon' => 'bg-amber-100 dark:bg-amber-900/30 text-amber-600'],
                                'approved' => ['bg' => 'bg-emerald-50 dark:bg-emerald-900/10', 'border' => 'border-emerald-100 dark:border-emerald-900/20', 'text' => 'text-emerald-700 dark:text-emerald-400', 'icon' => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600'],
                                'rejected' => ['bg' => 'bg-red-50 dark:bg-red-900/10', 'border' => 'border-red-100 dark:border-red-900/20', 'text' => 'text-red-700 dark:text-red-400', 'icon' => 'bg-red-100 dark:bg-red-900/30 text-red-600'],
                            ];
                            $colors = $statusColors[$leave->status] ?? $statusColors['pending'];
                            $startDate = \Carbon\Carbon::parse($leave->start_date);
                            $endDate = \Carbon\Carbon::parse($leave->end_date);
                            $duration = $startDate->diffInDays($endDate) + 1;
                        @endphp
                        <div class="mb-4 p-4 rounded-2xl {{ $colors['bg'] }} border {{ $colors['border'] }}">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 {{ $colors['icon'] }} rounded-xl flex items-center justify-center">
                                        @if($leave->status === 'approved')
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"></path></svg>
                                        @elseif($leave->status === 'rejected')
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"></path></svg>
                                        @else
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"></path></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="font-bold {{ $colors['text'] }} text-sm">{{ $leave->leaveType->name ?? 'Leave' }}</h4>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $duration }} {{ $duration > 1 ? 'days' : 'day' }}</p>
                                    </div>
                                </div>
                                <span class="px-2 py-1 {{ $colors['text'] }} text-[10px] font-bold rounded uppercase">{{ $leave->status }}</span>
                            </div>
                            <div class="space-y-2 text-xs">
                                <div class="flex justify-between">
                                    <span class="text-slate-500 dark:text-slate-400">Period:</span>
                                    <span class="font-semibold dark:text-white">{{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}</span>
                                </div>
                                @if($leave->reason)
                                <div>
                                    <span class="text-slate-500 dark:text-slate-400">Reason:</span>
                                    <p class="font-medium dark:text-white mt-1">{{ $leave->reason }}</p>
                                </div>
                                @endif
                                @if($leave->approver)
                                <div class="flex justify-between">
                                    <span class="text-slate-500 dark:text-slate-400">{{ $leave->status === 'approved' ? 'Approved by:' : 'Reviewed by:' }}</span>
                                    <span class="font-semibold dark:text-white">{{ $leave->approver->name }}</span>
                                </div>
                                @endif
                                @if($leave->approval_comment)
                                <div>
                                    <span class="text-slate-500 dark:text-slate-400">Comment:</span>
                                    <p class="font-medium dark:text-white mt-1">{{ $leave->approval_comment }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <p class="text-slate-500 dark:text-slate-400 font-semibold">No leave history found</p>
                            <p class="text-slate-400 text-sm mt-2">This employee hasn't requested any leaves yet</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
        <section id="documents" class="space-y-6 {{ $activeTab !== 'documents' ? 'hidden' : '' }}">
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                    <h3 class="font-bold text-sm uppercase tracking-wider text-slate-500">Document Vault</h3>
                </div>
                <div class="p-6">
                    @forelse($employee->documents as $document)
                        <div class="mb-4 p-5 border border-slate-200 dark:border-slate-800 rounded-2xl hover:border-blue-200 dark:hover:border-blue-800 transition group">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start gap-4 flex-1">
                                    <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/20 rounded-xl flex items-center justify-center text-blue-600 flex-shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-bold dark:text-white mb-1">{{ $document->document_name }}</h4>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-2">{{ ucfirst(str_replace('_', ' ', $document->document_type)) }}</p>
                                        <div class="flex items-center gap-4 text-xs text-slate-400">
                                            @if($document->uploader)
                                                <span>Uploaded by: <span class="font-semibold">{{ $document->uploader->name }}</span></span>
                                            @endif
                                            <span>{{ \Carbon\Carbon::parse($document->created_at)->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <button wire:click="downloadDocument({{ $document->id }})" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    </button>
                                    <button class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            </div>
                            <p class="text-slate-500 dark:text-slate-400 font-semibold">No documents uploaded</p>
                            <p class="text-slate-400 text-sm mt-2">Upload employee documents to keep records organized</p>
                        </div>
                    @endforelse
                    <flux:modal.trigger name="upload-document">
                        <button type="button" class="w-full mt-4 py-4 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-xl text-sm font-bold text-slate-400 hover:border-blue-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/10 transition">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Add New Document
                        </button>
                    </flux:modal.trigger>
                </div>
            </div>
        </section>
        
        <section id="payroll" class="space-y-6 {{ $activeTab !== 'payroll' ? 'hidden' : '' }}">
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                    <h3 class="font-bold text-sm uppercase tracking-wider text-slate-500">Payroll Details</h3>
                </div>
                <div class="p-6">
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-slate-500 dark:text-slate-400 font-semibold">Payroll information coming soon</p>
                        <p class="text-slate-400 text-sm mt-2">Salary and payment details will be displayed here</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <livewire:employeedocupload :emp="$employee->id" />

    </div>

</div>
