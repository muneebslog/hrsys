<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Notifications\ComplaintSubmittedSlackNotification;

use App\Models\Complaint;
use App\Models\Employee;
use App\Events\ComplaintCreated;

new class extends Component {
    use WithFileUploads;

    public $category = 'complaint';
    public $title = '';
    public $description = '';
    public $is_anonymous = false;
    public $attachment;

    public function updatedAttachment()
    {
        $this->validate([
            'attachment' => 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx',
        ]);
    }

    public function submit()
    {
        $this->validate([
            'category' => 'required|in:complaint,suggestion,feedback',
            'title' => 'required|string|min:5|max:255',
            'description' => 'required|string|min:20|max:2000',
            'is_anonymous' => 'boolean',
            'attachment' => 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx',
        ]);

        // Get current user's employee record
        $employee = Employee::where('user_id', auth()->id())->first();

        if (!$employee && !$this->is_anonymous) {
            session()->flash('error', 'Employee record not found. Please contact HR.');
            return;
        }

        // Handle file upload
        $attachmentPath = null;
        if ($this->attachment) {
            $attachmentPath = $this->attachment->store('complaint-attachments', 'public');
        }

        // Create complaint
        $complaint = Complaint::create([
            'employee_id' => $this->is_anonymous ? null : $employee->id,
            'category' => $this->category,
            'title' => $this->title,
            'description' => $this->description,
            'is_anonymous' => $this->is_anonymous,
            'attachment_path' => $attachmentPath,
            'status' => 'new',
        ]);
        auth()->user()->notify(
    new ComplaintSubmittedSlackNotification(
        $complaint->load('employee')
    )
);

        // Broadcast event to notify admins
        broadcast(new ComplaintCreated($complaint->load('employee')));

        session()->flash('message', 'Your feedback has been submitted successfully! Management will review it shortly.');

        // Reset form
        $this->reset(['category', 'title', 'description', 'is_anonymous', 'attachment']);
        $this->category = 'complaint';
    }
}; ?>

<div>
    <nav class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 sticky top-0 z-50">
        <div class="max-w-3xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-rose-600 rounded-lg flex items-center justify-center text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                </div>
                <h1 class="font-bold text-lg">Staff Voice</h1>
            </div>
            <a href="#" class="text-sm font-bold text-slate-500 hover:text-rose-600 transition">My Previous Tickets</a>
        </div>
    </nav>

    <main class="max-w-3xl mx-auto px-4 py-12">
        
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
        
        <div class="text-center mb-10">
            <h2 class="text-3xl font-black mb-3 dark:text-white">How can we improve?</h2>
            <p class="text-slate-500 dark:text-slate-400 text-sm max-w-md mx-auto leading-relaxed">
                Your feedback helps us make Mohsin Medical a better place to work. Whether it's a complaint or a new idea, we're listening.
            </p>
        </div>

        <form wire:submit="submit" class="space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <label class="relative cursor-pointer group">
                    <input wire:model="category" type="radio" name="type" value="complaint" class="peer sr-only">
                    <div class="p-4 bg-white dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 rounded-2xl peer-checked:border-rose-500 peer-checked:bg-rose-50 dark:peer-checked:bg-rose-900/10 transition group-hover:border-rose-200">
                        <div class="w-10 h-10 bg-rose-100 dark:bg-rose-900/30 rounded-xl flex items-center justify-center text-rose-600 mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <p class="font-bold text-sm dark:text-white">Complaint</p>
                        <p class="text-[10px] text-slate-400 font-medium uppercase mt-1">Report an issue</p>
                    </div>
                </label>

                <label class="relative cursor-pointer group">
                    <input wire:model="category" type="radio" name="type" value="suggestion" class="peer sr-only">
                    <div class="p-4 bg-white dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 rounded-2xl peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/10 transition group-hover:border-blue-200">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center text-blue-600 mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.674a1 1 0 00.951-.69l1.419-4.674a1 1 0 00-.951-1.309H13.63L14.653 6H10.32l-1.023 4.327h1.419a1 1 0 01.951 1.309l-1.419 4.674a1 1 0 01-.951.69H9.663z"></path></svg>
                        </div>
                        <p class="font-bold text-sm dark:text-white">Suggestion</p>
                        <p class="text-[10px] text-slate-400 font-medium uppercase mt-1">Share an idea</p>
                    </div>
                </label>

                <label class="relative cursor-pointer group">
                    <input wire:model="category" type="radio" name="type" value="feedback" class="peer sr-only">
                    <div class="p-4 bg-white dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 rounded-2xl peer-checked:border-emerald-500 peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-900/10 transition group-hover:border-emerald-200">
                        <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center text-emerald-600 mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path></svg>
                        </div>
                        <p class="font-bold text-sm dark:text-white">Feedback</p>
                        <p class="text-[10px] text-slate-400 font-medium uppercase mt-1">General thoughts</p>
                    </div>
                </label>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-8 shadow-sm space-y-6">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Subject *</label>
                    <input wire:model="title" type="text" placeholder="Brief summary of the issue..." class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm font-semibold focus:ring-2 focus:ring-rose-500 transition">
                    @error('title') 
                        <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Detailed Description *</label>
                    <textarea wire:model="description" rows="5" placeholder="Tell us exactly what happened or what you're proposing (minimum 20 characters)..." class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-rose-500 transition"></textarea>
                    @error('description') 
                        <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white dark:bg-slate-800 rounded-full flex items-center justify-center text-slate-400 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold dark:text-white">Submit Anonymously</p>
                            <p class="text-[10px] text-slate-400">Management will not see your name.</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input wire:model="is_anonymous" type="checkbox" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-600"></div>
                    </label>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Supporting Image/File (Optional)</label>
                    <div class="border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-xl p-4 hover:border-rose-300 transition">
                        <input wire:model="attachment" type="file" id="attachment" class="hidden" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                        <label for="attachment" class="cursor-pointer flex flex-col items-center justify-center gap-2 py-2">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            <span class="text-xs font-bold text-slate-400">Attach Evidence</span>
                            <span class="text-[10px] text-slate-400">PDF, JPG, PNG, DOC up to 10MB</span>
                        </label>
                        @if ($attachment)
                            <div class="mt-3 flex items-center justify-center gap-2 text-xs text-green-600 dark:text-green-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>{{ $attachment->getClientOriginalName() }}</span>
                            </div>
                        @endif
                        <div wire:loading wire:target="attachment" class="mt-3 text-xs text-rose-600 text-center">
                            Uploading...
                        </div>
                    </div>
                    @error('attachment') 
                        <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-slate-900 dark:bg-rose-600 dark:hover:bg-rose-700 text-white py-4 rounded-2xl font-black text-sm transition transform hover:-translate-y-0.5 active:scale-95 shadow-xl shadow-rose-200 dark:shadow-none">
                    Send Feedback
                </button>
            </div>
        </form>

        <p class="mt-8 text-center text-[11px] text-slate-400 px-10">
            <strong>Security Notice:</strong> All submissions are encrypted. If you choose to remain anonymous, the system will strip all identifying metadata from your request.
        </p>

    </main>

</div>
