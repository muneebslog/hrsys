<?php

use Livewire\Volt\Component;
use App\Models\RoundSection;
use App\Models\RoundQuestion;
use Flux\Flux;

new class extends Component {
    public $sections = [];
    public $name = '';
    public $description = '';
    public $sort_order = 0;
    public $editingId = null;

    // Questions modal state
    public $selectedSectionId = null;
    public $sectionQuestions = [];
    public $questionLabel = '';
    public $questionType = 'yes_no';
    public $questionRequired = true;
    public $questionSortOrder = 0;
    public $editingQuestionId = null;

    public function mount()
    {
        $this->loadSections();
    }

    public function loadSections()
    {
        $this->sections = RoundSection::withCount('questions')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    public function openAddModal()
    {
        $this->reset(['name', 'description', 'editingId']);
        $this->sort_order = (RoundSection::max('sort_order') ?? 0) + 1;
    }

    public function openEditModal($id)
    {
        $section = RoundSection::findOrFail($id);
        $this->editingId = $id;
        $this->name = $section->name;
        $this->description = $section->description ?? '';
        $this->sort_order = $section->sort_order;
    }

    public function saveSection()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:round_sections,name,' . ($this->editingId ?? 'NULL'),
            'description' => 'nullable|string|max:500',
            'sort_order' => 'required|integer|min:0',
        ]);

        if ($this->editingId) {
            RoundSection::findOrFail($this->editingId)->update([
                'name' => $this->name,
                'description' => $this->description ?: null,
                'sort_order' => $this->sort_order,
            ]);
            session()->flash('message', 'Section updated successfully!');
        } else {
            RoundSection::create([
                'name' => $this->name,
                'description' => $this->description ?: null,
                'sort_order' => $this->sort_order,
            ]);
            session()->flash('message', 'Section created successfully!');
        }

        $this->loadSections();
        $this->reset(['name', 'description', 'sort_order', 'editingId']);
        Flux::modal('section-form')->close();
    }

    public function deleteSection($id)
    {
        RoundSection::findOrFail($id)->delete();
        session()->flash('message', 'Section deleted successfully!');
        $this->loadSections();
        if ($this->selectedSectionId == $id) {
            $this->selectedSectionId = null;
            $this->sectionQuestions = [];
            Flux::modal('questions-modal')->close();
        }
    }

    public function openQuestionsModal($sectionId)
    {
        $this->selectedSectionId = $sectionId;
        $this->loadSectionQuestions();
        $this->resetQuestionForm();
    }

    public function loadSectionQuestions()
    {
        if (!$this->selectedSectionId) {
            return;
        }
        $this->sectionQuestions = RoundQuestion::where('round_section_id', $this->selectedSectionId)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    public function resetQuestionForm()
    {
        $this->questionLabel = '';
        $this->questionType = 'yes_no';
        $this->questionRequired = true;
        $this->questionSortOrder = $this->sectionQuestions->max('sort_order') + 1 ?? 0;
        $this->editingQuestionId = null;
    }

    public function openAddQuestionModal()
    {
        $this->resetQuestionForm();
        $this->questionSortOrder = (RoundQuestion::where('round_section_id', $this->selectedSectionId)->max('sort_order') ?? 0) + 1;
    }

    public function openEditQuestionModal($questionId)
    {
        $q = RoundQuestion::findOrFail($questionId);
        $this->editingQuestionId = $q->id;
        $this->questionLabel = $q->label;
        $this->questionType = $q->type;
        $this->questionRequired = $q->is_required;
        $this->questionSortOrder = $q->sort_order;
    }

    public function saveQuestion()
    {
        $this->validate([
            'questionLabel' => 'required|string|max:255',
            'questionType' => 'required|in:yes_no,text,number',
            'questionRequired' => 'boolean',
            'questionSortOrder' => 'required|integer|min:0',
        ]);

        $data = [
            'round_section_id' => $this->selectedSectionId,
            'label' => $this->questionLabel,
            'type' => $this->questionType,
            'is_required' => $this->questionRequired,
            'sort_order' => $this->questionSortOrder,
        ];

        if ($this->editingQuestionId) {
            RoundQuestion::findOrFail($this->editingQuestionId)->update($data);
            session()->flash('message', 'Question updated successfully!');
        } else {
            RoundQuestion::create($data);
            session()->flash('message', 'Question added successfully!');
        }

        $this->loadSectionQuestions();
        $this->resetQuestionForm();
        Flux::modal('question-form')->close();
    }

    public function deleteQuestion($questionId)
    {
        RoundQuestion::findOrFail($questionId)->delete();
        session()->flash('message', 'Question deleted successfully!');
        $this->loadSectionQuestions();
        $this->resetQuestionForm();
    }
}; ?>

<div>
    <div class="max-w-7xl mx-auto px-4 py-8">

        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-black dark:text-white">Round Sections</h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Sections the supervisor visits during a ward round. Add questions per section.</p>
            </div>
            <flux:modal.trigger name="section-form">
                <flux:button wire:click="openAddModal" icon="plus">Add Section</flux:button>
            </flux:modal.trigger>
        </div>

        @if (session()->has('message'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl">
                <p class="text-sm font-semibold text-green-800 dark:text-green-400">{{ session('message') }}</p>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                <p class="text-sm font-semibold text-red-800 dark:text-red-400">{{ session('error') }}</p>
            </div>
        @endif

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800">
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Section</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Order</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Questions</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($sections as $section)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold dark:text-white">{{ $section->name }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @if($section->description)
                                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ Str::limit($section->description, 60) }}</p>
                                    @else
                                        <span class="text-slate-400 italic text-xs">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                        {{ $section->sort_order }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400">
                                        {{ $section->questions_count }} {{ $section->questions_count == 1 ? 'question' : 'questions' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <flux:modal.trigger name="questions-modal">
                                            <button wire:click="openQuestionsModal({{ $section->id }})" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition" title="Manage questions">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </button>
                                        </flux:modal.trigger>
                                        <flux:modal.trigger name="section-form">
                                            <button wire:click="openEditModal({{ $section->id }})" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>
                                        </flux:modal.trigger>
                                        <button
                                            wire:click="deleteSection({{ $section->id }})"
                                            wire:confirm="Are you sure you want to delete this section and all its questions?"
                                            class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center">
                                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-slate-500 dark:text-slate-400 font-semibold">No round sections yet</p>
                                            <p class="text-slate-400 text-sm mt-1">Add sections the supervisor will visit during a round</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <flux:modal name="section-form" class="md:w-[600px]">
            <form wire:submit="saveSection" class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ $editingId ? 'Edit Section' : 'Add Section' }}</flux:heading>
                    <flux:text class="mt-2">{{ $editingId ? 'Update the section.' : 'Add a section/area the supervisor will visit.' }}</flux:text>
                </div>
                <flux:input wire:model="name" label="Section Name" placeholder="e.g., Ward A, Emergency" required />
                <flux:textarea wire:model="description" label="Description (optional)" placeholder="Brief description..." rows="2" />
                <flux:input wire:model="sort_order" type="number" min="0" label="Sort order" description="Order in the wizard (lower = first)" />
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="primary">{{ $editingId ? 'Update' : 'Create' }} Section</flux:button>
                </div>
            </form>
        </flux:modal>

        <flux:modal name="questions-modal" class="md:w-[700px]">
            @if($selectedSectionId)
                @php
                    $selectedSection = $sections->firstWhere('id', $selectedSectionId);
                @endphp
                <div class="space-y-6">
                    <div>
                        <flux:heading size="lg">Questions: {{ $selectedSection?->name ?? 'Section' }}</flux:heading>
                        <flux:text class="mt-2">These questions appear in the wizard for this section.</flux:text>
                    </div>

                    @if (session()->has('message'))
                        <div class="p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                            <p class="text-sm font-semibold text-green-800 dark:text-green-400">{{ session('message') }}</p>
                        </div>
                    @endif

                    <flux:modal.trigger name="question-form">
                        <flux:button wire:click="openAddQuestionModal" icon="plus" size="sm">Add Question</flux:button>
                    </flux:modal.trigger>

                    <div class="border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800">
                                    <th class="px-4 py-3 text-left font-bold text-slate-500 dark:text-slate-400">Label</th>
                                    <th class="px-4 py-3 text-left font-bold text-slate-500 dark:text-slate-400">Type</th>
                                    <th class="px-4 py-3 text-center font-bold text-slate-500 dark:text-slate-400">Required</th>
                                    <th class="px-4 py-3 text-right font-bold text-slate-500 dark:text-slate-400">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @forelse($sectionQuestions as $q)
                                    <tr>
                                        <td class="px-4 py-3 dark:text-white">{{ $q->label }}</td>
                                        <td class="px-4 py-3 text-slate-500 dark:text-slate-400">{{ $q->type === 'yes_no' ? 'Yes/No' : ucfirst($q->type) }}</td>
                                        <td class="px-4 py-3 text-center">{{ $q->is_required ? 'Yes' : 'No' }}</td>
                                        <td class="px-4 py-3 text-right">
                                            <flux:modal.trigger name="question-form">
                                                <button wire:click="openEditQuestionModal({{ $q->id }})" class="p-1.5 text-slate-400 hover:text-blue-600 rounded">Edit</button>
                                            </flux:modal.trigger>
                                            <button wire:click="deleteQuestion({{ $q->id }})" wire:confirm="Delete this question?" class="p-1.5 text-slate-400 hover:text-red-600 rounded">Delete</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-6 text-center text-slate-500 dark:text-slate-400">No questions yet. Add one above.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="flex gap-2">
                        <flux:spacer />
                        <flux:modal.close>
                            <flux:button variant="primary">Done</flux:button>
                        </flux:modal.close>
                    </div>
                </div>
            @endif
        </flux:modal>

        <flux:modal name="question-form" class="md:w-[500px]">
            <form wire:submit="saveQuestion" class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ $editingQuestionId ? 'Edit Question' : 'Add Question' }}</flux:heading>
                </div>
                <flux:input wire:model="questionLabel" label="Question label" placeholder="e.g., Was place clean?" required />
                <flux:select wire:model="questionType" label="Type">
                    <flux:select.option value="yes_no">Yes/No</flux:select.option>
                    <flux:select.option value="text">Text</flux:select.option>
                    <flux:select.option value="number">Number</flux:select.option>
                </flux:select>
                <flux:checkbox wire:model="questionRequired" label="Required" />
                <flux:input wire:model="questionSortOrder" type="number" min="0" label="Sort order" />
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="primary">{{ $editingQuestionId ? 'Update' : 'Add' }} Question</flux:button>
                </div>
            </form>
        </flux:modal>
    </div>
</div>
