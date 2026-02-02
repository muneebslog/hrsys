<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\Complaint;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    use WithPagination;

    public $selectedComplaint = null;

    public function with()
    {
        // Accessing the relationship we just set up [cite: 27, 57]
        $employeeId = Auth::user()->employee->id; 
        return [
            'complaints' => Complaint::where('employee_id', $employeeId)
                ->latest()
                ->paginate(10)
        ];
    }

    public function selectComplaint($id)
    {
        $this->selectedComplaint = Complaint::find($id);
    }

    public function closeDetail()
    {
        $this->reset('selectedComplaint');
    }
}; ?>

<div class="-m-6 lg:-m-8 p-6 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen transition-colors duration-200">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">My Complaint Tickets</h2>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($complaints as $ticket)
                    <tr wire:click="selectComplaint({{ $ticket->id }})" class="cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $ticket->created_at->format('M d, Y') }} </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">{{ $ticket->title }} </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusClasses = [
                                    'new' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                    'in_progress' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                    'resolved' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                    'closed' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                ][$ticket->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClasses }}">
                                {{ strtoupper($ticket->status) }} 
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4 dark:text-gray-400">
        {{ $complaints->links() }}
    </div>

    @if($selectedComplaint)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <div class="relative w-full max-w-2xl bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
                    <h3 class="text-lg font-bold">Ticket Details</h3>
                    <button wire:click="closeDetail" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 text-2xl">&times;</button>
                </div>
                
                <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                    <div>
                        <label class="text-xs uppercase text-gray-500 font-bold">Category</label>
                        <p class="text-sm">{{ $selectedComplaint->category }} </p>
                    </div>

                    <div>
                        <label class="text-xs uppercase text-gray-500 font-bold">Your Description</label>
                        <div class="mt-1 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm border dark:border-gray-600">
                            {{ $selectedComplaint->description }} 
                        </div>
                    </div>

                    @if($selectedComplaint->attachment_path)
                        <div>
                            <label class="text-xs uppercase text-gray-500 font-bold">Attachment</label>
                            <a href="{{ asset('storage/' . $selectedComplaint->attachment_path) }}" target="_blank" class="block mt-1 text-sm text-blue-500 hover:underline">
                                📎 View Attached Document 
                            </a>
                        </div>
                    @endif

                    <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/30 rounded-lg border border-blue-100 dark:border-blue-800">
                        <label class="text-xs uppercase text-blue-600 dark:text-blue-400 font-bold">Admin Response</label>
                        <p class="mt-2 text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                            {{ $selectedComplaint->admin_remarks ?? 'No remarks yet. Your ticket is currently being reviewed.' }} 
                        </p>
                    </div>
                </div>

                <div class="p-4 bg-gray-50 dark:bg-gray-800 text-right border-t dark:border-gray-700">
                    <button wire:click="closeDetail" class="px-5 py-2 text-sm font-medium bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>