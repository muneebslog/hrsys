<?php

use Livewire\Volt\Component;
use App\Models\LeaveRequest;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

new class extends Component {
    public $leaveRequests;
    public $filterStatus = 'all';
    public $filterDate = '';
    
    // Statistics
    public $pendingCount = 0;
    public $onLeaveToday = 0;
    public $availableStaff = 0;

    protected $listeners = ['newLeaveRequestCreated', 'refreshData'];

    public function mount()
    {
        $this->filterDate = Carbon::today()->format('Y-m-d');
        $this->loadData();
    }

    public function newLeaveRequestCreated()
    {
        $this->loadData();
        $this->dispatch('notify', message: 'New leave request received!', type: 'info');
    }

    public function refreshData()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // Load leave requests with relationships
        $query = LeaveRequest::with(['employee.user', 'employee.department', 'leaveType', 'approver']);

        // Apply status filter
        if ($this->filterStatus !== 'all') {
            $query->where('status', $this->filterStatus);
        }

        // Apply date filter if set
        if ($this->filterDate) {
            $query->where(function($q) {
                $q->whereDate('start_date', '<=', $this->filterDate)
                  ->whereDate('end_date', '>=', $this->filterDate);
            });
        }

        $this->leaveRequests = $query->orderBy('created_at', 'desc')->get();

        // Calculate statistics
        $this->calculateStatistics();
    }

    public function calculateStatistics()
    {
        $this->pendingCount = LeaveRequest::where('status', 'pending')->count();
        
        // Count employees on leave today
        $today = Carbon::today();
        $this->onLeaveToday = LeaveRequest::where('status', 'approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->distinct('employee_id')
            ->count('employee_id');
        
        // Calculate available staff (total employees - on leave today)
        $totalEmployees = Employee::count();
        $this->availableStaff = $totalEmployees - $this->onLeaveToday;
    }

    public function setFilter($status)
    {
        $this->filterStatus = $status;
        $this->loadData();
    }

    public function updatedFilterDate()
    {
        $this->loadData();
    }

    public function approveLeave($leaveId)
    {
        try {
            $leave = LeaveRequest::findOrFail($leaveId);
            
            $leave->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
            ]);

            session()->flash('message', 'Leave request approved successfully.');
            $this->loadData();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to approve leave: ' . $e->getMessage());
        }
    }

    public function rejectLeave($leaveId)
    {
        try {
            $leave = LeaveRequest::findOrFail($leaveId);
            
            $leave->update([
                'status' => 'rejected',
                'approved_by' => auth()->id(),
            ]);

            session()->flash('message', 'Leave request rejected.');
            $this->loadData();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to reject leave: ' . $e->getMessage());
        }
    }

    public function getDuration($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $days = $start->diffInDays($end) + 1;
        
        return $days . ' ' . ($days > 1 ? 'Days' : 'Day');
    }
}; ?>

<div>
   

    <nav class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center gap-3">
                   
                    <div>
                        <span class="text-xl font-bold tracking-tight block">Leave Management</span>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 flex items-center gap-5 shadow-sm">
                <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center text-amber-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Pending Review</p>
                    <p class="text-2xl font-black dark:text-white">{{ str_pad($pendingCount, 2, '0', STR_PAD_LEFT) }} Requests</p>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 flex items-center gap-5 shadow-sm border-l-4 border-l-blue-600">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">On Leave Today</p>
                    <p class="text-2xl font-black dark:text-white">{{ $onLeaveToday }} Staff</p>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 flex items-center gap-5 shadow-sm">
                <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Available Staff</p>
                    <p class="text-2xl font-black dark:text-white">{{ $availableStaff }} Present</p>
                </div>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <div class="flex-1 flex gap-2 overflow-x-auto pb-2 md:pb-0">
                <button wire:click="setFilter('all')" class="px-5 py-2 {{ $filterStatus === 'all' ? 'bg-blue-600 text-white' : 'bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }} rounded-lg text-sm font-bold whitespace-nowrap transition">All Requests</button>
                <button wire:click="setFilter('pending')" class="px-5 py-2 {{ $filterStatus === 'pending' ? 'bg-blue-600 text-white' : 'bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }} rounded-lg text-sm font-bold whitespace-nowrap transition">Pending</button>
                <button wire:click="setFilter('approved')" class="px-5 py-2 {{ $filterStatus === 'approved' ? 'bg-blue-600 text-white' : 'bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }} rounded-lg text-sm font-bold whitespace-nowrap transition">Approved</button>
                <button wire:click="setFilter('rejected')" class="px-5 py-2 {{ $filterStatus === 'rejected' ? 'bg-blue-600 text-white' : 'bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }} rounded-lg text-sm font-bold whitespace-nowrap transition">Rejected</button>
            </div>
            <div class="flex gap-2">
                <input wire:model.live="filterDate" type="date" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50">
                            <th class="p-5 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Staff Member</th>
                            <th class="p-5 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Leave Type</th>
                            <th class="p-5 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Duration</th>
                            <th class="p-5 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Reason/Note</th>
                            <th class="p-5 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-center">Status</th>
                            <th class="p-5 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($leaveRequests as $leave)
                            @php
                                $startDate = \Carbon\Carbon::parse($leave->start_date);
                                $endDate = \Carbon\Carbon::parse($leave->end_date);
                                $duration = $startDate->diffInDays($endDate) + 1;
                                
                                $statusColors = [
                                    'pending' => 'bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400',
                                    'approved' => 'bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400',
                                    'rejected' => 'bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400',
                                ];
                                $statusClass = $statusColors[$leave->status] ?? $statusColors['pending'];
                            @endphp
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition group">
                                <td class="p-5">
                                    <div class="flex items-center gap-3">
                                        <flux:avatar name="{{ $leave->employee->full_name }}" size="sm" color="auto" color:seed="{{ $leave->employee->id }}" />
                                        <div>
                                            <div class="text-sm font-bold dark:text-white capitalize">{{ $leave->employee->full_name }}</div>
                                            <div class="text-[10px] text-slate-400 font-bold uppercase">{{ $leave->employee->department->name ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-5 text-sm font-medium dark:text-slate-300">{{ $leave->leaveType->name ?? 'Leave' }}</td>
                                <td class="p-5">
                                    <div class="text-sm font-bold dark:text-white">{{ $duration }} {{ $duration > 1 ? 'Days' : 'Day' }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $startDate->format('M d') }} - {{ $endDate->format('M d') }}</div>
                                </td>
                                <td class="p-5">
                                    <p class="text-xs text-slate-500 dark:text-slate-400 max-w-xs truncate">{{ $leave->reason ?? 'No reason provided' }}</p>
                                </td>
                                <td class="p-5 text-center">
                                    <span class="px-3 py-1 {{ $statusClass }} text-[10px] font-bold rounded-full uppercase">{{ $leave->status }}</span>
                                </td>
                                <td class="p-5 text-right">
                                    @if($leave->status === 'pending')
                                        <div class="flex justify-end gap-2">
                                            <button wire:click="approveLeave({{ $leave->id }})" class="p-2 bg-emerald-50 text-emerald-600 dark:bg-emerald-900/20 dark:text-emerald-400 rounded-lg hover:bg-emerald-600 hover:text-white transition shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </button>
                                            <button wire:click="rejectLeave({{ $leave->id }})" class="p-2 bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-400 rounded-lg hover:bg-red-600 hover:text-white transition shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </div>
                                    @else
                                        <div class="text-xs text-slate-400">
                                            @if($leave->approver)
                                                <p class="font-semibold">{{ ucfirst($leave->status) }} by</p>
                                                <p>{{ $leave->approver->name }}</p>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center">
                                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-slate-500 dark:text-slate-400 font-semibold">No leave requests found</p>
                                            <p class="text-slate-400 text-sm mt-1">Try adjusting your filters</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8 bg-blue-50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-900/30 rounded-2xl p-6 flex items-start gap-4">
            <div class="text-blue-600 mt-1">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
            </div>
            <div>
                <h4 class="text-sm font-bold text-blue-900 dark:text-blue-400">Hospital Staffing Rule</h4>
                <p class="text-sm text-blue-700 dark:text-blue-500/80 leading-relaxed">No more than 20% of staff per department can be on leave simultaneously. Approving Dr. Zubair's request will bring OPD to 15% leave status.</p>
            </div>
        </div>

    </main>

</div>
