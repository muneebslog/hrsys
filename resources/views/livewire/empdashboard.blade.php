<?php

use Livewire\Volt\Component;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Complaint;
use Carbon\Carbon;

new class extends Component {
    public $employee;
    public $stats = [];
    public $recentLeaves = [];
    public $recentComplaints = [];
    public $upcomingLeaves = [];

    public function mount()
    {
        $this->loadEmployeeDashboard();
    }

    public function loadEmployeeDashboard()
    {
        // Get current user's employee record
        $this->employee = Employee::with(['user', 'department', 'manager.user'])
            ->where('user_id', auth()->id())
            ->first();

        if (!$this->employee) {
            return;
        }

        // Calculate statistics
        $allLeaves = LeaveRequest::where('employee_id', $this->employee->id)->get();
        $thisYearLeaves = LeaveRequest::where('employee_id', $this->employee->id)
            ->whereYear('created_at', Carbon::now()->year)
            ->get();

        $this->stats = [
            'total_leaves' => $allLeaves->count(),
            'pending_leaves' => $allLeaves->where('status', 'pending')->count(),
            'approved_leaves' => $thisYearLeaves->where('status', 'approved')->count(),
            'rejected_leaves' => $thisYearLeaves->where('status', 'rejected')->count(),
            'days_taken' => $this->calculateDaysTaken($thisYearLeaves->where('status', 'approved')),
            'pending_complaints' => Complaint::where('employee_id', $this->employee->id)
                ->whereIn('status', ['new', 'in_progress'])
                ->count(),
        ];

        // Recent leave requests
        $this->recentLeaves = LeaveRequest::with('leaveType')
            ->where('employee_id', $this->employee->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Upcoming approved leaves
        $this->upcomingLeaves = LeaveRequest::with('leaveType')
            ->where('employee_id', $this->employee->id)
            ->where('status', 'approved')
            ->where('start_date', '>=', Carbon::today())
            ->orderBy('start_date', 'asc')
            ->take(3)
            ->get();

        // Recent complaints/feedback
        $this->recentComplaints = Complaint::where('employee_id', $this->employee->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
    }

    private function calculateDaysTaken($leaves)
    {
        $days = 0;
        foreach ($leaves as $leave) {
            $start = Carbon::parse($leave->start_date);
            $end = Carbon::parse($leave->end_date);
            $days += $start->diffInDays($end) + 1;
        }
        return $days;
    }
}; ?>

<div class="min-h-screen bg-slate-50 dark:bg-slate-950">
    
    @if(!$employee)
        <div class="max-w-4xl mx-auto px-4 py-12 text-center">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-12">
                <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-bold dark:text-white mb-2">Employee Record Not Found</h2>
                <p class="text-slate-500 dark:text-slate-400">Please contact HR to set up your employee profile.</p>
            </div>
        </div>
    @else

    <!-- Header with Profile -->
    <div class="bg-gradient-to-br from-blue-500 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center text-2xl font-black">
                        {{ substr($employee->user->name ?? 'E', 0, 1) }}
                    </div>
                    <div>
                        <h1 class="text-2xl font-black">Welcome back, {{ explode(' ', $employee->user->name ?? 'Employee')[0] }}!</h1>
                        <p class="text-blue-100 text-sm mt-1">{{ $employee->designation ?? 'Employee' }} • {{ $employee->department->name ?? 'N/A' }}</p>
                        <p class="text-blue-200 text-xs mt-0.5">{{ Carbon::now()->format('l, F j, Y') }}</p>
                    </div>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-xl px-4 py-3">
                    <p class="text-xs text-blue-100 uppercase font-bold">Employee Code</p>
                    <p class="text-xl font-black mt-1">{{ $employee->employee_code }}</p>
                </div>
            </div>
        </div>
    </div>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Quick Stats -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
            <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-4 shadow-sm">
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <p class="text-2xl font-black dark:text-white">{{ $stats['total_leaves'] }}</p>
                <p class="text-[10px] font-bold text-slate-400 uppercase mt-1">Total Leaves</p>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-4 shadow-sm border-l-4 border-l-amber-500">
                <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-2xl font-black text-amber-600">{{ $stats['pending_leaves'] }}</p>
                <p class="text-[10px] font-bold text-slate-400 uppercase mt-1">Pending</p>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-4 shadow-sm border-l-4 border-l-green-500">
                <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-2xl font-black text-green-600">{{ $stats['approved_leaves'] }}</p>
                <p class="text-[10px] font-bold text-slate-400 uppercase mt-1">Approved</p>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-4 shadow-sm border-l-4 border-l-red-500">
                <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-2xl font-black text-red-600">{{ $stats['rejected_leaves'] }}</p>
                <p class="text-[10px] font-bold text-slate-400 uppercase mt-1">Rejected</p>
            </div>

           

            <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-4 shadow-sm">
                <div class="w-10 h-10 bg-rose-100 dark:bg-rose-900/30 rounded-lg flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                    </svg>
                </div>
                <p class="text-2xl font-black dark:text-white">{{ $stats['pending_complaints'] }}</p>
                <p class="text-[10px] font-bold text-slate-400 uppercase mt-1">Open Tickets</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mb-8">
            <h2 class="text-lg font-bold dark:text-white mb-4">Quick Actions</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <a href="/staffapplyleave" class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-6 hover:border-blue-500 hover:shadow-lg transition group">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                    <p class="text-sm font-bold dark:text-white">Apply for Leave</p>
                    <p class="text-xs text-slate-400 mt-1">Request time off</p>
                </a>

                <a href="/staffcomplaintscell" class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-6 hover:border-rose-500 hover:shadow-lg transition group">
                    <div class="w-12 h-12 bg-rose-100 dark:bg-rose-900/30 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition">
                        <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                        </svg>
                    </div>
                    <p class="text-sm font-bold dark:text-white">Submit Feedback</p>
                    <p class="text-xs text-slate-400 mt-1">Share your voice</p>
                </a>

                <a href="{{ route('emp.profile', ['emp' => $employee->id]) }}" wire:navigate class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-6 hover:border-purple-500 hover:shadow-lg transition group">
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <p class="text-sm font-bold dark:text-white">My Profile</p>
                    <p class="text-xs text-slate-400 mt-1">View details</p>
                </a>

                {{-- <a href="" class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-6 hover:border-green-500 hover:shadow-lg transition group">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <p class="text-sm font-bold dark:text-white">Staff Directory</p>
                    <p class="text-xs text-slate-400 mt-1">Find colleagues</p>
                </a> --}}
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Upcoming Leaves -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                    <div class="p-6 border-b border-slate-200 dark:border-slate-800">
                        <h3 class="text-lg font-bold dark:text-white">Upcoming Approved Leaves</h3>
                    </div>
                    <div class="p-6">
                        @forelse($upcomingLeaves as $leave)
                            <div class="flex items-center gap-4 p-4 bg-green-50 dark:bg-green-900/20 rounded-xl mb-3 last:mb-0">
                                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/40 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-bold dark:text-white">{{ $leave->leaveType->name ?? 'Leave' }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ Carbon::parse($leave->start_date)->format('M d, Y') }} - {{ Carbon::parse($leave->end_date)->format('M d, Y') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-bold text-green-600">{{ Carbon::parse($leave->start_date)->diffInDays(Carbon::today()) }} days</p>
                                    <p class="text-[10px] text-slate-400">until leave</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm text-slate-400">No upcoming leaves</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Leave Requests -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                    <div class="p-6 border-b border-slate-200 dark:border-slate-800">
                        <h3 class="text-lg font-bold dark:text-white">Recent Leave Requests</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        @forelse($recentLeaves as $leave)
                            <div class="flex items-center gap-3 pb-3 border-b border-slate-100 dark:border-slate-800 last:border-0">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0
                                    @if($leave->status === 'pending') bg-amber-100 dark:bg-amber-900/30 text-amber-600
                                    @elseif($leave->status === 'approved') bg-green-100 dark:bg-green-900/30 text-green-600
                                    @else bg-red-100 dark:bg-red-900/30 text-red-600
                                    @endif">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($leave->status === 'pending')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        @elseif($leave->status === 'approved')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        @endif
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-bold dark:text-white">{{ $leave->leaveType->name ?? 'Leave' }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ Carbon::parse($leave->start_date)->format('M d') }} - {{ Carbon::parse($leave->end_date)->format('M d, Y') }}</p>
                                </div>
                                <span class="px-2 py-1 rounded text-[10px] font-bold uppercase
                                    @if($leave->status === 'pending') bg-amber-100 text-amber-600 dark:bg-amber-900/40 dark:text-amber-400
                                    @elseif($leave->status === 'approved') bg-green-100 text-green-600 dark:bg-green-900/40 dark:text-green-400
                                    @else bg-red-100 text-red-600 dark:bg-red-900/40 dark:text-red-400
                                    @endif">
                                    {{ $leave->status }}
                                </span>
                            </div>
                        @empty
                            <p class="text-sm text-slate-400 text-center py-8">No leave requests yet</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                
                <!-- Manager Info -->
                @if($employee->manager)
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-slate-400 uppercase mb-4">Your Manager</h3>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-purple-500 rounded-xl flex items-center justify-center text-white font-bold">
                                {{ substr($employee->manager->user->name ?? 'M', 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold dark:text-white">{{ $employee->manager->user->name ?? 'Unknown' }}</p>
                                <p class="text-xs text-slate-400">{{ $employee->manager->designation ?? 'Manager' }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Recent Feedback -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                    <div class="p-6 border-b border-slate-200 dark:border-slate-800">
                        <h3 class="text-sm font-bold text-slate-400 uppercase">My Recent Feedback</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        @forelse($recentComplaints as $complaint)
                            <div class="pb-3 border-b border-slate-100 dark:border-slate-800 last:border-0">
                                <div class="flex items-start gap-2 mb-2">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                        @if($complaint->category === 'complaint') bg-rose-100 text-rose-600 dark:bg-rose-900/40 dark:text-rose-400
                                        @elseif($complaint->category === 'suggestion') bg-blue-100 text-blue-600 dark:bg-blue-900/40 dark:text-blue-400
                                        @else bg-green-100 text-green-600 dark:bg-green-900/40 dark:text-green-400
                                        @endif">
                                        {{ $complaint->category }}
                                    </span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                        @if($complaint->status === 'new') bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400
                                        @elseif($complaint->status === 'in_progress') bg-amber-100 text-amber-600 dark:bg-amber-900/40 dark:text-amber-400
                                        @else bg-green-100 text-green-600 dark:bg-green-900/40 dark:text-green-400
                                        @endif">
                                        {{ str_replace('_', ' ', $complaint->status) }}
                                    </span>
                                </div>
                                <p class="text-xs font-bold dark:text-white">{{ Str::limit($complaint->title, 40) }}</p>
                                <p class="text-[10px] text-slate-400 mt-1">{{ $complaint->created_at->diffForHumans() }}</p>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 text-center py-4">No feedback submitted</p>
                        @endforelse
                    </div>
                </div>

                <!-- Quick Stats Card -->
                <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl p-6 text-white">
                    <h3 class="text-sm font-bold uppercase mb-4">This Year</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-3xl font-black">{{ $stats['days_taken'] }}</p>
                            <p class="text-xs text-blue-100">Days of leave taken</p>
                        </div>
                        <div class="pt-3 border-t border-white/20">
                            <p class="text-2xl font-black">{{ $stats['approved_leaves'] }}</p>
                            <p class="text-xs text-blue-100">Approved requests</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </main>

    @endif

</div>
