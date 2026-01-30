<?php

use Livewire\Volt\Component;
use App\Models\Complaint;
use Flux\Flux;

new class extends Component {
    public $complaints = [];
    public $stats = [];
    public $selectedComplaint = null;
    public $newStatus = '';
    public $adminRemarks = '';
    public $filterStatus = 'all';
    public $filterCategory = 'all';

    protected $listeners = ['newComplaintCreated', 'refreshData'];

    public function mount()
    {
        $this->loadData();
    }

    public function newComplaintCreated()
    {
        $this->loadData();
        $this->dispatch('notify', message: 'New complaint/feedback received!', type: 'info');
    }

    public function refreshData()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $query = Complaint::with(['employee.user'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($this->filterStatus !== 'all') {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterCategory !== 'all') {
            $query->where('category', $this->filterCategory);
        }

        $this->complaints = $query->get();

        // Calculate statistics
        $allComplaints = Complaint::all();
        $this->stats = [
            'total' => $allComplaints->count(),
            'open' => $allComplaints->whereIn('status', ['new', 'in_progress'])->count(),
            'resolved' => $allComplaints->where('status', 'resolved')->count(),
            'suggestions' => $allComplaints->where('category', 'suggestion')->count(),
        ];
    }

    public function openStatusModal($complaintId)
    {
        $this->selectedComplaint = Complaint::with(['employee.user'])->findOrFail($complaintId);
        $this->newStatus = $this->selectedComplaint->status;
        $this->adminRemarks = $this->selectedComplaint->admin_remarks ?? '';
    }

    public function updateStatus()
    {
        $this->validate([
            'newStatus' => 'required|in:new,in_progress,resolved,closed',
            'adminRemarks' => 'nullable|string|max:1000',
        ]);

        $this->selectedComplaint->update([
            'status' => $this->newStatus,
            'admin_remarks' => $this->adminRemarks,
        ]);

        session()->flash('message', 'Status updated successfully!');
        $this->loadData();
        $this->reset(['selectedComplaint', 'newStatus', 'adminRemarks']);
        Flux::modal('status-modal')->close();
    }

    public function getStatusColor($status)
    {
        return match($status) {
            'new' => 'rose',
            'in_progress' => 'amber',
            'resolved' => 'emerald',
            'closed' => 'slate',
            default => 'slate',
        };
    }

    public function getCategoryColor($category)
    {
        return match($category) {
            'complaint' => 'rose',
            'suggestion' => 'blue',
            'feedback' => 'emerald',
            default => 'slate',
        };
    }

    public function getCategoryIcon($category)
    {
        return match($category) {
            'complaint' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
            'suggestion' => 'M9.663 17h4.674a1 1 0 00.951-.69l1.419-4.674a1 1 0 00-.951-1.309H13.63L14.653 6H10.32l-1.023 4.327h1.419a1 1 0 01.951 1.309l-1.419 4.674a1 1 0 01-.951.69H9.663z',
            'feedback' => 'M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5',
            default => 'M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z',
        };
    }
}; ?>

<div>


    <nav class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-rose-600 rounded-xl flex items-center justify-center shadow-lg shadow-rose-200 dark:shadow-none">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                    </div>
                    <div>
                        <span class="text-xl font-bold tracking-tight block">Mohsin Medical</span>
                        <span class="text-[10px] uppercase font-bold text-rose-600 dark:text-rose-400 tracking-widest">Feedback & Grievance</span>
                    </div>
                </div>
               
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl">
                <p class="text-sm font-semibold text-green-800 dark:text-green-400">{{ session('message') }}</p>
            </div>
        @endif

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <p class="text-xs font-bold text-slate-400 uppercase mb-2">Total Tickets</p>
                <span class="text-3xl font-black dark:text-white">{{ $stats['total'] }}</span>
            </div>
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm border-l-4 border-l-rose-500">
                <p class="text-xs font-bold text-slate-400 uppercase mb-2">Open Complaints</p>
                <span class="text-3xl font-black text-rose-500">{{ str_pad($stats['open'], 2, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm border-l-4 border-l-emerald-500">
                <p class="text-xs font-bold text-slate-400 uppercase mb-2">Resolved</p>
                <span class="text-3xl font-black text-emerald-500">{{ str_pad($stats['resolved'], 2, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm border-l-4 border-l-blue-500">
                <p class="text-xs font-bold text-slate-400 uppercase mb-2">Suggestions</p>
                <span class="text-3xl font-black text-blue-500">{{ str_pad($stats['suggestions'], 2, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>

        <!-- Filters -->
        <div class="mb-6 flex gap-3">
            <select wire:model.live="filterStatus" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2 text-sm font-semibold">
                <option value="all">All Status</option>
                <option value="new">New</option>
                <option value="in_progress">In Progress</option>
                <option value="resolved">Resolved</option>
                <option value="closed">Closed</option>
            </select>
            
            <select wire:model.live="filterCategory" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2 text-sm font-semibold">
                <option value="all">All Categories</option>
                <option value="complaint">Complaints</option>
                <option value="suggestion">Suggestions</option>
                <option value="feedback">Feedback</option>
            </select>
        </div>

        <div class="space-y-4">
            @forelse($complaints as $complaint)
                @php
                    $categoryColor = $this->getCategoryColor($complaint->category);
                    $statusColor = $this->getStatusColor($complaint->status);
                    $isResolved = in_array($complaint->status, ['resolved', 'closed']);
                @endphp
                
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition {{ $isResolved ? 'opacity-75' : '' }}">
                    <div class="flex flex-col md:flex-row justify-between gap-4">
                        <div class="flex gap-4">
                            <div class="w-12 h-12 bg-{{ $categoryColor }}-50 dark:bg-{{ $categoryColor }}-900/20 rounded-xl flex-shrink-0 flex items-center justify-center text-{{ $categoryColor }}-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $this->getCategoryIcon($complaint->category) }}"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-1 flex-wrap">
                                    <h4 class="font-bold text-lg dark:text-white {{ $isResolved ? 'line-through' : '' }}">{{ $complaint->title }}</h4>
                                    <span class="px-2 py-0.5 bg-{{ $categoryColor }}-100 dark:bg-{{ $categoryColor }}-900/40 text-{{ $categoryColor }}-600 dark:text-{{ $categoryColor }}-400 text-[10px] font-bold rounded uppercase">
                                        {{ ucfirst($complaint->category) }}
                                    </span>
                                    <span class="px-2 py-0.5 bg-{{ $statusColor }}-100 dark:bg-{{ $statusColor }}-900/40 text-{{ $statusColor }}-600 dark:text-{{ $statusColor }}-400 text-[10px] font-bold rounded uppercase">
                                        {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                                    </span>
                                    <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-500 text-[10px] font-bold rounded uppercase">
                                        Ticket #{{ str_pad($complaint->id, 3, '0', STR_PAD_LEFT) }}
                                    </span>
                                </div>
                                <p class="text-sm text-slate-600 dark:text-slate-400 mb-4 leading-relaxed">
                                    {{ Str::limit($complaint->description, 200) }}
                                </p>
                                @if($complaint->admin_remarks)
                                    <div class="mb-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                                        <p class="text-xs font-bold text-blue-600 dark:text-blue-400 mb-1">Admin Remarks:</p>
                                        <p class="text-xs text-slate-600 dark:text-slate-400">{{ $complaint->admin_remarks }}</p>
                                    </div>
                                @endif
                                <div class="flex items-center gap-6 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        @if($complaint->is_anonymous)
                                            Anonymous
                                        @else
                                            {{ $complaint->employee->user->name ?? 'Unknown' }}
                                        @endif
                                    </span>
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $complaint->created_at->format('M d, Y') }}
                                    </span>
                                    @if($complaint->attachment_path)
                                        <span class="flex items-center gap-1.5 text-blue-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                            </svg>
                                            Attachment
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex md:flex-col justify-end gap-2 shrink-0">
                            <flux:modal.trigger name="status-modal">
                                <button wire:click="openStatusModal({{ $complaint->id }})" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-xs font-bold transition">
                                    Update Status
                                </button>
                            </flux:modal.trigger>
                            @if($complaint->attachment_path)
                                <a href="{{ Storage::url($complaint->attachment_path) }}" target="_blank" class="bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 px-4 py-2 rounded-lg text-xs font-bold transition text-center">
                                    View Attachment
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-12 text-center">
                    <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                        </svg>
                    </div>
                    <p class="text-slate-500 dark:text-slate-400 font-semibold">No feedback found</p>
                    <p class="text-slate-400 text-sm mt-1">All feedback will appear here</p>
                </div>
            @endforelse
        </div>

        <!-- Status Update Modal -->
        <flux:modal name="status-modal" class="md:w-[600px]">
            @if($selectedComplaint)
                <form wire:submit="updateStatus" class="space-y-6">
                    <div>
                        <flux:heading size="lg">Update Ticket Status</flux:heading>
                        <flux:text class="mt-2">Ticket #{{ str_pad($selectedComplaint->id, 3, '0', STR_PAD_LEFT) }} - {{ $selectedComplaint->title }}</flux:text>
                    </div>

                    <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                        <p class="text-xs font-bold text-slate-400 uppercase mb-2">Original Message</p>
                        <p class="text-sm text-slate-600 dark:text-slate-400">{{ $selectedComplaint->description }}</p>
                        <div class="flex items-center gap-4 mt-3 text-xs text-slate-400">
                            <span>From: {{ $selectedComplaint->is_anonymous ? 'Anonymous' : ($selectedComplaint->employee->user->name ?? 'Unknown') }}</span>
                            <span>{{ $selectedComplaint->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <flux:select wire:model="newStatus" label="Status" required>
                            <flux:select.option value="new">New</flux:select.option>
                            <flux:select.option value="in_progress">In Progress</flux:select.option>
                            <flux:select.option value="resolved">Resolved</flux:select.option>
                            <flux:select.option value="closed">Closed</flux:select.option>
                        </flux:select>

                        <flux:textarea 
                            wire:model="adminRemarks" 
                            label="Admin Remarks" 
                            placeholder="Add notes about actions taken or resolution..."
                            rows="4"
                        />
                    </div>

                    <div class="flex gap-2">
                        <flux:spacer />
                        <flux:modal.close>
                            <flux:button variant="ghost">Cancel</flux:button>
                        </flux:modal.close>
                        <flux:button type="submit" variant="primary">
                            Update Status
                        </flux:button>
                    </div>
                </form>
            @endif
        </flux:modal>

        <div class="mt-12 p-8 bg-slate-900 rounded-[2rem] text-white flex flex-col md:flex-row items-center justify-between gap-8 overflow-hidden relative">
            <div class="absolute top-0 right-0 w-32 h-32 bg-rose-500/20 blur-3xl rounded-full -mr-16 -mt-16"></div>
            <div class="relative z-10">
                <h3 class="text-xl font-bold mb-2">Staff Satisfaction Insight</h3>
                <p class="text-slate-400 text-sm max-w-md">80% of recent complaints are related to "Maintenance." Addressing the AC and lighting issues could improve staff morale by 15% this month.</p>
            </div>
            <div class="flex gap-4 relative z-10">
                <div class="text-center">
                    <div class="text-3xl font-black text-rose-500">80%</div>
                    <div class="text-[10px] uppercase font-bold text-slate-500">Facility Issues</div>
                </div>
                <div class="w-px h-12 bg-slate-800"></div>
                <div class="text-center">
                    <div class="text-3xl font-black text-emerald-500">20%</div>
                    <div class="text-[10px] uppercase font-bold text-slate-500">Policy Issues</div>
                </div>
            </div>
        </div>

    </main>

</div>
