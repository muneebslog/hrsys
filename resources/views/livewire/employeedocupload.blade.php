<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Models\Employee;
use App\Models\EmployeeDocument;
use Illuminate\Support\Facades\Storage;
use Flux\Flux;

new class extends Component {
    use WithFileUploads;

    public $employee;
    public $document;
    public $document_type = '';
    public $document_name = '';

    public function mount($emp)
    {
        $this->employee = Employee::findOrFail($emp);
    }

    public function updatedDocument()
    {
        $this->validate([
            'document' => 'file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx', // 10MB Max
        ]);
    }

    public function uploadDocument()
    {
        $this->validate([
            'document' => 'required|file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx',
            'document_type' => 'required|string',
            'document_name' => 'required|string|max:255',
        ]);

        try {
            // Store the file
            $path = $this->document->store('employee-documents', 'public');

            // Create document record
            EmployeeDocument::create([
                'employee_id' => $this->employee->id,
                'document_type' => $this->document_type,
                'document_name' => $this->document_name,
                'file_path' => $path,
                'uploaded_by' => auth()->id(),
            ]);

            // Reset form
            $this->reset(['document', 'document_type', 'document_name']);

            Flux::modal('upload-document')->close();
            
            session()->flash('message', 'Document uploaded successfully.');
            $this->dispatch('document-uploaded');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to upload document: ' . $e->getMessage());
        }
    }
}; ?>

<div>
    <flux:modal name="upload-document" class="md:w-[600px]">
        <form wire:submit="uploadDocument" class="space-y-6">
            <div>
                <flux:heading size="lg">Upload Employee Document</flux:heading>
                <flux:text class="mt-2">Upload and categorize employee documents securely.</flux:text>
            </div>

            <div class="space-y-4">
                <div>
                    <flux:input 
                        wire:model="document_name" 
                        label="Document Name" 
                        placeholder="e.g., CNIC Front Copy, Degree Certificate" 
                        required 
                    />
                </div>

                <div>
                    <flux:select 
                        wire:model="document_type" 
                        label="Document Type" 
                        placeholder="Select document type" 
                        required
                    >
                        <flux:select.option value="cnic">CNIC / National ID</flux:select.option>
                        <flux:select.option value="degree">Educational Degree</flux:select.option>
                        <flux:select.option value="certificate">Certificate</flux:select.option>
                        <flux:select.option value="license">Professional License</flux:select.option>
                        <flux:select.option value="contract">Employment Contract</flux:select.option>
                        <flux:select.option value="medical">Medical Report</flux:select.option>
                        <flux:select.option value="experience">Experience Letter</flux:select.option>
                        <flux:select.option value="resume">Resume/CV</flux:select.option>
                        <flux:select.option value="other">Other</flux:select.option>
                    </flux:select>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2 dark:text-white">
                        Upload File <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="file" 
                            wire:model="document" 
                            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                            class="block w-full text-sm text-slate-500 dark:text-slate-400
                                file:mr-4 file:py-3 file:px-4
                                file:rounded-lg file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100
                                dark:file:bg-blue-900/20 dark:file:text-blue-400
                                dark:hover:file:bg-blue-900/30
                                cursor-pointer
                                border border-slate-200 dark:border-slate-700 rounded-xl p-2
                                dark:bg-slate-900"
                        />
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                        Supported formats: PDF, JPG, PNG, DOC, DOCX (Max: 10MB)
                    </p>
                    
                    @error('document')
                        <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                @if ($document)
                    <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/40 rounded-lg flex items-center justify-center text-blue-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-blue-900 dark:text-blue-200">{{ $document->getClientOriginalName() }}</p>
                                <p class="text-xs text-blue-600 dark:text-blue-400">{{ number_format($document->getSize() / 1024, 2) }} KB</p>
                            </div>
                            <button 
                                type="button" 
                                wire:click="$set('document', null)" 
                                class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif

                <div wire:loading wire:target="document" class="text-center py-4">
                    <div class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-400">
                        <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-sm font-semibold">Processing file...</span>
                    </div>
                </div>
            </div>

            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button 
                    type="submit" 
                    variant="primary"
                    :disabled="!$document"
                >
                    <span wire:loading.remove wire:target="uploadDocument">Upload Document</span>
                    <span wire:loading wire:target="uploadDocument">Uploading...</span>
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
