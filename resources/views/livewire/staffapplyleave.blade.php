<?php

use App\Notifications\LeaveAppliedSlackNotification;
use Illuminate\Support\Facades\Notification;

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\Employee;
use App\Events\LeaveRequestCreated;

new class extends Component {
    use WithFileUploads;

    public $leaveTypes = [];
    public $leave_type_id = '';
    public $start_date = '';
    public $end_date = '';
    public $reason = '';
    public $handover_instructions = '';
    public $attachment;


    public function mount()
    {
        $this->leaveTypes = LeaveType::all();
    }

    public function updatedAttachment()
    {
        $this->validate([
            'attachment' => 'nullable|file|max:5120|mimes:pdf,jpg,jpeg,png',
        ]);
    }

    public function submit()
    {
        $this->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|min:10|max:1000',
            'handover_instructions' => 'nullable|string|max:1000',
            'attachment' => 'nullable|file|max:5120|mimes:pdf,jpg,jpeg,png',
        ]);

        // Get current user's employee record
        $employee = Employee::where('user_id', auth()->id())->first();

        if (!$employee) {
            session()->flash('error', 'Employee record not found. Please contact HR.');
            return;
        }

        // Handle file upload
        $attachmentPath = null;
        if ($this->attachment) {
            $attachmentPath = $this->attachment->store('leave-attachments', 'public');
        }

        // Create leave request
        $leaveRequest = LeaveRequest::create([
            'employee_id' => $employee->id,
            'leave_type_id' => $this->leave_type_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'reason' => $this->reason,
            'handover_instructions' => $this->handover_instructions,
            'attachment' => $attachmentPath,
            'status' => 'pending',
        ]);
        // Send Slack notification (Bot OAuth)
// Find this block around line 74:
// Notification::route('slack', config('services.slack.notifications.channel')) // Changed from 'dummy'
//     ->notify(
//         new LeaveAppliedSlackNotification(
//             $leaveRequest->load('employee.department', 'leaveType')
//         )
//     );


        // Broadcast event to notify admins
        // broadcast(new LeaveRequestCreated($leaveRequest->load('employee', 'leaveType')));

        session()->flash('message', 'Leave application submitted successfully! Your manager will review it shortly.');

        // Reset form
        $this->reset(['leave_type_id', 'start_date', 'end_date', 'reason', 'handover_instructions', 'attachment']);
    }
}; ?>

<div class="-m-6 lg:-m-8">
    
    <nav class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h1 class="font-bold text-lg">Apply for Leave</h1>
            </div>
            <a href="/dashboard" class="text-sm font-bold text-slate-500 hover:text-blue-600 transition">Cancel</a>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-4 py-10">
        
        <!-- Success Message -->
        @if (session()->has('message'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <p class="text-sm font-semibold text-green-800 dark:text-green-400">{{ session('message') }}</p>
                </div>
            </div>
        @endif

        <!-- Error Message -->
        @if (session()->has('error'))
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <p class="text-sm font-semibold text-red-800 dark:text-red-400">{{ session('error') }}</p>
                </div>
            </div>
        @endif
        
        <div class="grid lg:grid-cols-3 gap-8">
            

            <div class="lg:col-span-3">
                
                <div class="bg-white mb-4 dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
                    <h4 class="font-bold text-sm mb-3">Policy Reminders</h4>
                    <ul class="text-xs text-slate-500 dark:text-slate-400 space-y-3">
                        <li class="flex gap-2">
                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Apply 48 hours in advance for planned leaves.
                        </li>
                        <li class="flex gap-2">
                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Medical certificate required for >2 sick days.
                        </li>
                    </ul>
                </div>

                <form wire:submit="submit" class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-8 shadow-sm space-y-6">
                    
                    <div class="grid grid-cols-1 ">
                        <div>
                           <flux:select label="Leave Type" wire:model="leave_type_id">
                            <flux:select.option value="" disabled selected>Select Leave Type</flux:select.option>
                            @foreach ($leaveTypes as $type)
                                <flux:select.option value="{{ $type->id }}">{{ $type->name }}</flux:select.option>
                            @endforeach
                           </flux:select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                           <flux:input 
                                type="date" 
                                label="Start Date" 
                                wire:model="start_date" 
                            />
                        </div>
                        <div>
                           <flux:input 
                                type="date" 
                                label="End Date" 
                                wire:model="end_date" 
                            />
                        </div>
                    </div>

                    <div>
                       <flux:textarea
    label="Reason for Leave"
    wire:model="reason"
    placeholder="Provide a detailed reason for your leave request."
    
/>
                    </div>

                    <div class="p-5 bg-blue-50 dark:bg-blue-900/20 rounded-2xl border border-blue-100 dark:border-blue-800/50">
                        <div class="flex items-center gap-3 mb-3 text-blue-700 dark:text-blue-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <h4 class="text-sm font-bold uppercase tracking-tight">Handover Instructions</h4>
                        </div>
                        <flux:input 
                            wire:model="handover_instructions" 
                            type="text" 
                            placeholder="Who is covering your responsibilities?" 
                            class="mb-3"
                        />
                       
                        <p class="text-[10px] text-blue-600/70 dark:text-blue-400/70 italic mt-3">Crucial for continuous workflow in your department.</p>
                    </div>

                        <div class=" p-4 border rounded-2xl border-blue-600">
                       <flux:input wire:model="attachment" type="file" label="Attachments (Medical Certificates, etc.)"/>
    

                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-2xl font-black text-sm shadow-xl shadow-blue-200 dark:shadow-none transition transform hover:-translate-y-0.5 active:scale-95">
                        Submit Leave Application
                    </button>
                </form>
            </div>
        </div>
    </main>


</div>
