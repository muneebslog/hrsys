<?php

use Livewire\Volt\Component;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Complaint;
use Livewire\Volt\Attributes\Title;
use App\Models\Department;
use Carbon\Carbon;

new 
#[Title('Admin Dashboard')]
class extends Component {
    public $stats = [];
    public $recentLeaves = [];
    public $recentComplaints = [];
    public $leaveStats = [];

    protected $listeners = ['newComplaintCreated', 'newLeaveRequestCreated'];

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function newComplaintCreated()
    {
        $this->loadDashboardData();
        $this->dispatch('notify', message: 'New complaint received!', type: 'info');
    }

    public function newLeaveRequestCreated()
    {
        $this->loadDashboardData();
        $this->dispatch('notify', message: 'New leave request received!', type: 'info');
    }

    public function loadDashboardData()
    {
        // General statistics
        $this->stats = [
            'total_employees' => Employee::count(),
            'total_departments' => Department::count(),
            'pending_leaves' => LeaveRequest::where('status', 'pending')->count(),
            'new_complaints' => Complaint::where('status', 'new')->count(),
            'on_leave_today' => LeaveRequest::where('status', 'approved')
                ->whereDate('start_date', '<=', Carbon::today())
                ->whereDate('end_date', '>=', Carbon::today())
                ->count(),
        ];

        // Leave statistics
        $this->leaveStats = [
            'pending' => LeaveRequest::where('status', 'pending')->count(),
            'approved' => LeaveRequest::where('status', 'approved')->count(),
            'rejected' => LeaveRequest::where('status', 'rejected')->count(),
            'this_month' => LeaveRequest::whereMonth('created_at', Carbon::now()->month)->count(),
        ];

        // Recent leave requests
        $this->recentLeaves = LeaveRequest::with(['employee.user', 'leaveType'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Recent complaints
        $this->recentComplaints = Complaint::with(['employee.user'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    }
}; ?>

<div class="min-h-screen -m-6 lg:-m-8  bg-slate-50 dark:bg-slate-950">
    
    <!-- Header -->
    <div class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-black dark:text-white">Admin Dashboard</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ Carbon::now()->format('l, F j, Y') }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Quick Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-black dark:text-white">{{ $stats['total_employees'] }}</p>
                <p class="text-xs font-bold text-slate-400 uppercase mt-1">Total Employees</p>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-black dark:text-white">{{ $stats['total_departments'] }}</p>
                <p class="text-xs font-bold text-slate-400 uppercase mt-1">Departments</p>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm border-l-4 border-l-amber-500">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-black text-amber-600">{{ $stats['pending_leaves'] }}</p>
                <p class="text-xs font-bold text-slate-400 uppercase mt-1">Pending Leaves</p>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm border-l-4 border-l-rose-500">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-rose-100 dark:bg-rose-900/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-black text-rose-600">{{ $stats['new_complaints'] }}</p>
                <p class="text-xs font-bold text-slate-400 uppercase mt-1">New Complaints</p>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm border-l-4 border-l-green-500">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-black text-green-600">{{ $stats['on_leave_today'] }}</p>
                <p class="text-xs font-bold text-slate-400 uppercase mt-1">On Leave Today</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mb-8">
            <h2 class="text-lg font-bold dark:text-white mb-4">Quick Actions</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('staffdirectory') }}" wire:navigate class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-4 hover:border-blue-500 transition group">
                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-bold dark:text-white">Staff Directory</p>
                </a>

                <a href="{{ route('leaverequests') }}" wire:navigate class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-4 hover:border-green-500 transition group">
                    <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-bold dark:text-white">Leave Requests</p>
                </a>

                <a href="{{ route('feedlogs') }}" wire:navigate class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-4 hover:border-rose-500 transition group">
                    <div class="w-10 h-10 bg-rose-100 dark:bg-rose-900/30 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-bold dark:text-white">Complaints & Feedback</p>
                </a>
                 <a href="{{ route('admin.guide') }}" wire:navigate class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-4 hover:border-indigo-500 transition group">
                    <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-bold dark:text-white">Admin Guide</p>
                </a>

                <a href="{{ route('panel') }}" wire:navigate class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-4 hover:border-amber-500 transition group">
                    <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-bold dark:text-white">Setting</p>
                </a>

              
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Recent Leave Requests -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <div class="p-6 border-b border-slate-200 dark:border-slate-800">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold dark:text-white">Recent Leave Requests</h3>
                        <a href="/leaverequest" class="text-xs font-bold text-blue-600 hover:text-blue-700">View All →</a>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    @forelse($recentLeaves as $leave)
                        <div class="flex items-start gap-3 pb-4 border-b border-slate-100 dark:border-slate-800 last:border-0">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-purple-500 rounded-lg flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                                {{ substr($leave->employee->user->name ?? 'U', 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold dark:text-white">{{ $leave->employee->user->name ?? 'Unknown' }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $leave->leaveType->name ?? 'Leave' }} • {{ Carbon::parse($leave->start_date)->format('M d') }} - {{ Carbon::parse($leave->end_date)->format('M d') }}</p>
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
                        <p class="text-sm text-slate-400 text-center py-8">No recent leave requests</p>
                    @endforelse
                </div>
            </div>

            <!-- Recent Complaints/Feedback -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <div class="p-6 border-b border-slate-200 dark:border-slate-800">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold dark:text-white">Recent Feedback</h3>
                        <a href="/feedbacklogs" class="text-xs font-bold text-blue-600 hover:text-blue-700">View All →</a>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    @forelse($recentComplaints as $complaint)
                        <div class="flex items-start gap-3 pb-4 border-b border-slate-100 dark:border-slate-800 last:border-0">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0
                                @if($complaint->category === 'complaint') bg-rose-100 dark:bg-rose-900/30 text-rose-600
                                @elseif($complaint->category === 'suggestion') bg-blue-100 dark:bg-blue-900/30 text-blue-600
                                @else bg-green-100 dark:bg-green-900/30 text-green-600
                                @endif">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($complaint->category === 'complaint')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    @elseif($complaint->category === 'suggestion')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.674a1 1 0 00.951-.69l1.419-4.674a1 1 0 00-.951-1.309H13.63L14.653 6H10.32l-1.023 4.327h1.419a1 1 0 01.951 1.309l-1.419 4.674a1 1 0 01-.951.69H9.663z"></path>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                    @endif
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold dark:text-white truncate">{{ $complaint->title }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                    {{ $complaint->is_anonymous ? 'Anonymous' : ($complaint->employee->user->name ?? 'Unknown') }} • 
                                    {{ $complaint->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <span class="px-2 py-1 rounded text-[10px] font-bold uppercase
                                @if($complaint->status === 'new') bg-rose-100 text-rose-600 dark:bg-rose-900/40 dark:text-rose-400
                                @elseif($complaint->status === 'in_progress') bg-amber-100 text-amber-600 dark:bg-amber-900/40 dark:text-amber-400
                                @else bg-green-100 text-green-600 dark:bg-green-900/40 dark:text-green-400
                                @endif">
                                {{ str_replace('_', ' ', $complaint->status) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400 text-center py-8">No recent feedback</p>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Leave Statistics -->
        <div class="mt-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl p-8 text-white">
            <h3 class="text-xl font-bold mb-6">Leave Statistics Overview</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div>
                    <p class="text-3xl font-black">{{ $leaveStats['pending'] }}</p>
                    <p class="text-sm text-blue-100 mt-1">Pending Approval</p>
                </div>
                <div>
                    <p class="text-3xl font-black">{{ $leaveStats['approved'] }}</p>
                    <p class="text-sm text-blue-100 mt-1">Approved</p>
                </div>
                <div>
                    <p class="text-3xl font-black">{{ $leaveStats['rejected'] }}</p>
                    <p class="text-sm text-blue-100 mt-1">Rejected</p>
                </div>
                <div>
                    <p class="text-3xl font-black">{{ $leaveStats['this_month'] }}</p>
                    <p class="text-sm text-blue-100 mt-1">This Month</p>
                </div>
            </div>
        </div>

    </main>

</div>
