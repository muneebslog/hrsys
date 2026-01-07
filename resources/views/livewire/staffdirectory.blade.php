<?php

use Livewire\Volt\Component;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use App\Models\Role;
use Livewire\Attributes\On;
use Flux\Flux;
use Illuminate\Support\Facades\DB;

new class extends Component {
    public $employees;
    public $departments;

    // Variable for Employee Model
    public $employeeName;
    public $departmentId;

    // Filter and Search Variables
    public $search = '';
    public $filterDepartment = 'All Departments';
    public $filterStatus = 'All Status';

    public function mount(){
        $this->employees = Employee::with('department')->get();
        $this->departments = Department::all();
    }

    public function getFilteredEmployeesProperty()
    {
        return $this->employees->filter(function ($employee) {
            // Search filter
            $matchesSearch = empty($this->search) || 
                str_contains(strtolower($employee->full_name), strtolower($this->search)) ||
                str_contains(strtolower($employee->employee_code), strtolower($this->search)) ||
                str_contains(strtolower($employee->designation ?? ''), strtolower($this->search)) ||
                str_contains(strtolower($employee->department->name ?? ''), strtolower($this->search));

            // Department filter
            $matchesDepartment = $this->filterDepartment === 'All Departments' ||
                ($employee->department && $employee->department->name === $this->filterDepartment);

            // Status filter
            $matchesStatus = $this->filterStatus === 'All Status' ||
                ($this->filterStatus === 'Active' && $employee->employment_status === 'active') ||
                ($this->filterStatus === 'On Leave' && $employee->employment_status === 'leave') ||
                ($this->filterStatus === 'Missing Docs' && count($employee->documents) === 0);

            return $matchesSearch && $matchesDepartment && $matchesStatus;
        });
    }

    #[On('employee-saved')]
    public function refreshEmployees()
    {
        $this->employees = Employee::with('department')->get();
    }

    #[On('department-saved')]
    public function refreshDepartments()
    {
        $this->departments = Department::all();
    }

    public function addEmployee(){
        $this->validate([
            'employeeName' => 'required|string|max:255',
            'departmentId' => 'required|exists:departments,id',
        ]);

        try {
            DB::beginTransaction();
            
            $employeeEmail = $this->generateEmail($this->employeeName, $this->departmentId);
            
            // Determine role based on department
            $department = Department::find($this->departmentId);
            $deptName = strtolower($department->name);
            
            $roleName = 'Employee'; // default
            if (str_contains($deptName, 'doctor') || str_contains($deptName, 'dr')) {
                $roleName = 'Doctor';
            } elseif (str_contains($deptName, 'staff') || str_contains($deptName, 'nurse') || str_contains($deptName, 'nurses')) {
                $roleName = 'Staff';
            }
            
            $role = Role::where('name', $roleName)->first();
            
            $user = User::create([
                'email' => $employeeEmail, 
                'name' => $this->employeeName,
                'password' => 'password',
                'email_verified_at' => now(),
                'role_id' => $role->id,
                'is_active' => true,
            ]);
     
            $employeeCode = explode('@', $employeeEmail)[0];
            
            Employee::create([
                'user_id' => $user->id, 
                'full_name' => $this->employeeName,
                'department_id' => $this->departmentId,
                'employee_code' => $employeeCode,
            ]);

            DB::commit();
            
            Flux::modal('add-employee')->close();
            $this->reset(['employeeName', 'departmentId']);
            
            session()->flash('message', 'Employee added successfully.');
            $this->dispatch('employee-saved');
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to add employee: ' . $e->getMessage());
        }
    }

    public function generateEmail($name, $departmentId)
    {
        // Get department name
        $department = Department::find($departmentId);
        $deptName = strtolower($department->name ?? 'emp');
        
        // Determine prefix based on department name
        $prefix = '';
        if (str_contains($deptName, 'doctor') || str_contains($deptName, 'dr')) {
            $prefix = 'dr.';
        } elseif (str_contains($deptName, 'staff')) {
            $prefix = 'staff.';
        } else {
            $prefix = 'emp.';
        }
        
        // Extract appropriate name part
        $nameParts = explode(' ', trim($name));
        
        // If only one word, use it
        if (count($nameParts) === 1) {
            $emailName = $nameParts[0];
        } else {
            // Check if first name is a variation of Muhammad
            $firstName = strtolower($nameParts[0]);
            $muhammadVariations = ['muhammad', 'mohammad', 'mohammed'];
            
            if (in_array($firstName, $muhammadVariations) && count($nameParts) > 1) {
                // Use second word if first is Muhammad variant
                $emailName = $nameParts[1];
            } else {
                // Use first word
                $emailName = $nameParts[0];
            }
        }
        
        // Create base email
        $baseEmail = $prefix . strtolower($emailName);
        $email = $baseEmail . '@mmc.com';
        
        // Check if email exists and add number if needed
        $counter = 1;
        while (User::where('email', $email)->exists()) {
            $counter++;
            $email = $baseEmail . $counter . '@mmc.com';
        }
        
        return $email;
    }
    
}; ?>
 
<div>

    <div class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 antialiased min-h-screen">

        <nav class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <div class="flex items-center gap-3">

                        <div>
                            <span class="text-xl font-bold tracking-tight block">Staff
                                Directory</span>

                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                      <flux:modal.trigger name="add-employee">
                            <flux:button>Add Employee</flux:button>
                        </flux:modal.trigger>

                        <flux:modal name="add-employee" class="md:w-96">
                            <div class="space-y-6">
                                <div>
                                    <flux:heading size="lg">Add Employee</flux:heading>
                                    {{-- <flux:text class="mt-2">Add Employee and Department.</flux:text> --}}
                                </div>

                                <flux:input wire:model="employeeName" label="Employee Name" placeholder="Employee name" description="Dont Use Prefizes As Dr. Or Mr etc" />

                                <div class="flex  items-center gap-1">
                                    <div class="w-full flex-1">
                                        
                                <flux:select label="Select Department" wire:model="departmentId" >
                                    @if (count($departments) === 0)
                                        <flux:select.option disabled selected>No Departments Available</flux:select.option>                             
                                        
                                    @else

                                    <flux:select.option>Select Department</flux:select.option>
                                    @foreach($departments as $department)
                                        <flux:select.option value="{{ $department->id }}">{{ $department->name }}</flux:select.option>
                                    @endforeach
                                    @endif

                                </flux:select>
                                    </div>
                                <div class=" pt-4">
                                    <livewire:managedepartmentmodel/>
                                </div>

                                </div>

                                <div class="flex">
                                    <flux:spacer />

                                    <flux:button type="button" wire:click="addEmployee" variant="primary">Save changes</flux:button>
                                </div>
                            </div>
                        </flux:modal>

                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            <div
                class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col md:flex-row gap-4 mb-8">
                <div class="relative flex-1">
                    <svg class="w-5 h-5 absolute left-4 top-3.5 text-slate-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input wire:model.live="search" type="text" placeholder="Search by name, role, or ID..."
                        class="w-full pl-12 pr-4 py-3 bg-slate-50 dark:bg-slate-800 border-none rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                </div>
                <div class="flex gap-2">
                   <flux:select wire:model.live="filterDepartment" 
                        class="bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm font-semibold outline-none focus:ring-2 focus:ring-blue-500">
                        <flux:select.option>All Departments</flux:select.option>
                        @foreach($departments as $department)
                            <flux:select.option>{{ $department->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:select wire:model.live="filterStatus"
                        class="bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm font-semibold outline-none focus:ring-2 focus:ring-blue-500">
                        <flux:select.option>All Status</flux:select.option>
                        <flux:select.option>Active</flux:select.option>
                        <flux:select.option>On Leave</flux:select.option>
                        <flux:select.option>Missing Docs</flux:select.option>
                    </flux:select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

                @forelse($this->filteredEmployees as $employee)
                    @php
                        // Status badge colors
                        $statusColors = [
                            'active' => 'bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400',
                            'leave' => 'bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400',
                            'terminated' => 'bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400',
                            'resigned' => 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400',
                        ];
                        
                        $statusClass = $statusColors[$employee->employment_status] ?? $statusColors['Active'];
                    @endphp
                    
                    <div
                        class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 hover:shadow-xl hover:shadow-blue-500/5 transition group">
                        <div class="flex justify-between items-start mb-4">
                            <flux:avatar name="{{ $employee->full_name }}" color="auto" color:seed="{{ $employee->id }}" />
                            <span
                                class="px-2 py-1 {{ $statusClass }} text-[10px] font-bold rounded-lg uppercase tracking-tight">
                                {{ $employee->employment_status }}
                            </span>
                        </div>
                        <h3 class="font-bold text-lg dark:text-white capitalize group-hover:text-blue-600 transition">
                           {{ $employee->department->prefix ?? '' }} {{ $employee->full_name }}
                        </h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm mb-4 font-medium">
                            {{ $employee->designation ? $employee->designation.'-' : '' }}  {{ $employee->department->name ?? 'No Department' }}
                        </p>


                        <div class="space-y-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                            <div class="flex justify-between text-xs font-semibold">
                                <span class="text-slate-400 uppercase">Employee ID</span>
                                <span class="dark:text-slate-300">{{ $employee->employee_code }}</span>
                            </div>
                            
                             <div class="flex justify-between text-xs font-semibold">
                                <span class="text-slate-400 uppercase">Documents</span>
                                <span class="{{ count($employee->documents) > 0 ? ' text-green-600' : 'text-red-600' }} ">{{ count($employee->documents) > 0 ? count($employee->documents) : 'None' }}</span>
                            </div>
                           
                            @if($employee->joining_date)
                                <div class="flex justify-between text-xs font-semibold">
                                    <span class="text-slate-400 uppercase">Joined</span>
                                    <span class="dark:text-slate-300">{{ \Carbon\Carbon::parse($employee->joining_date)->format('M d, Y') }}</span>
                                </div>
                            @endif
                        </div>
                        <button
                            class="w-full mt-6 bg-slate-50 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 py-3 rounded-xl font-bold text-xs transition">
                            View Full Profile
                        </button>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <div class="text-slate-400 text-lg font-semibold">No employees found</div>
                        <p class="text-slate-500 text-sm mt-2">Add your first staff member to get started</p>
                    </div>
                @endforelse

            </div>

          
        </main>
    </div>

</div>