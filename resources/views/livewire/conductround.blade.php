<?php

use Livewire\Volt\Component;
use App\Models\Round;
use App\Models\RoundSection;
use App\Models\RoundSectionResponse;
use App\Models\RoundQuestionAnswer;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
new class extends Component {
    #[Title('Conduct Round')]

    public $roundId = null;
    public $currentStep = 0;
    public $sections = [];
    public $currentSectionNotes = '';
    public $answers = []; // question_id => value

    public function mount()
    {
        $this->sections = RoundSection::with('questions')->orderBy('sort_order')->orderBy('id')->get();
    }

    #[Computed]
    public function getCurrentSectionProperty()
    {
        if ($this->sections->isEmpty() || $this->currentStep < 0 || $this->currentStep >= $this->sections->count()) {
            return null;
        }
        return $this->sections->get($this->currentStep);
    }

    #[Computed]
    public function getIsFirstStepProperty()
    {
        return $this->currentStep === 0;
    }
    #[Computed]
    public function getIsLastStepProperty()
    {
        return $this->sections->isNotEmpty() && $this->currentStep === $this->sections->count() - 1;
    }

    #[Computed]
    public function getTotalStepsProperty()
    {
        return $this->sections->count();
    }

    public function startRound()
    {
        if ($this->sections->isEmpty()) {
            session()->flash('error', 'No round sections configured. Add sections first.');
            return;
        }

        $round = Round::create([
            'user_id' => auth()->id(),
            'started_at' => now(),
            'completed_at' => null,
        ]);

        $this->roundId = $round->id;
        $this->currentStep = 0;
        $this->loadCurrentStepData();
    }

    public function loadCurrentStepData()
    {
        $section = $this->currentSection;
        if (!$section || !$this->roundId) {
            return;
        }

        $response = RoundSectionResponse::where('round_id', $this->roundId)
            ->where('round_section_id', $section->id)
            ->first();

        $this->currentSectionNotes = $response->notes ?? '';

        $this->answers = [];
        if ($response) {
            foreach ($response->questionAnswers as $a) {
                $this->answers[$a->round_question_id] = $a->value;
            }
        }
        foreach ($section->questions as $q) {
            if (!isset($this->answers[$q->id])) {
                $this->answers[$q->id] = $q->type === 'yes_no' ? '' : '';
            }
        }
    }

        public function handleSubmit()
    {
        if ($this->isLastStep) {
            $this->submitRound();
        } else {
            $this->nextStep();
        }
    }


    public function saveCurrentStepData()
    {
        $section = $this->currentSection;
        if (!$section || !$this->roundId) {
            return;
        }

        $response = RoundSectionResponse::updateOrCreate(
            [
                'round_id' => $this->roundId,
                'round_section_id' => $section->id,
            ],
            [
                'visited_at' => now(),
                'notes' => $this->currentSectionNotes ?: null,
            ]
        );

        $response->questionAnswers()->delete();

        foreach ($section->questions as $question) {
            $value = $this->answers[$question->id] ?? null;
            if ($value !== null && $value !== '') {
                RoundQuestionAnswer::create([
                    'round_section_response_id' => $response->id,
                    'round_question_id' => $question->id,
                    'value' => (string) $value,
                ]);
            }
        }
    }

    public function validateCurrentStep()
    {
        $section = $this->currentSection;
        if (!$section) {
            return true;
        }

        foreach ($section->questions as $q) {
            if ($q->is_required) {
                $value = $this->answers[$q->id] ?? null;
                if ($value === null || $value === '') {
                    $this->addError('answers.' . $q->id, 'This question is required.');
                    return false;
                }
            }
        }
        return true;
    }

    public function nextStep()
    {
        if (!$this->validateCurrentStep()) {
            return;
        }
        $this->saveCurrentStepData();
        $this->currentStep++;
        $this->loadCurrentStepData();
    }

    public function prevStep()
    {
        $this->currentStep--;
        $this->loadCurrentStepData();
    }

    public function submitRound()
    {
        if (!$this->validateCurrentStep()) {
            return;
        }
        $this->saveCurrentStepData();

        Round::findOrFail($this->roundId)->update(['completed_at' => now()]);

        session()->flash('message', 'Round completed successfully.');
        $this->redirect(route('dashboard'), navigate: true);
    }
}; ?>

<div class="-m-6 lg:-m-8">
    <div class="bg-slate-50 dark:bg-slate-950 min-h-screen">

        @if (!$this->roundId)
            <div class="max-w-2xl mx-auto px-4 py-16 text-center">
                <div
                    class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-8 shadow-sm">
                    <div
                        class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                            </path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-black dark:text-white mb-2">Conduct Ward Round</h1>
                    <p class="text-slate-500 dark:text-slate-400 mb-6">You will visit {{ $this->totalSteps }}
                        section(s) and answer questions for each. Start when ready.</p>
                    @if ($this->sections->isEmpty())
                        <p class="text-amber-600 dark:text-amber-400 text-sm mb-4">No sections configured. Ask an admin to add
                            round sections first.</p>
                    @else
                        <flux:button wire:click="startRound" variant="primary" icon="play">Start Round</flux:button>
                    @endif
                </div>
            </div>
            @if (session()->has('error'))
                <div
                    class="max-w-2xl mx-auto px-4 mt-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                    <p class="text-sm font-semibold text-red-800 dark:text-red-400">{{ session('error') }}</p>
                </div>
            @endif
        @elseif ($this->currentSection)
            <nav class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 sticky top-0 z-10">
                <div class="max-w-3xl mx-auto px-4 py-4">
                    <div class="flex items-center justify-between mb-3">
                        <h1 class="text-lg font-bold dark:text-white">Round in progress</h1>
                        <span class="text-sm text-slate-500 dark:text-slate-400">Step {{ $this->currentStep + 1 }} of
                            {{$this->totalSteps }}</span>
                    </div>
                    <div class="flex gap-1">
                        @foreach($this->sections as $i => $sec)
                            <div class="flex-1 h-2 rounded-full transition
                                            {{ $i < $this->currentStep ? 'bg-green-500' : ($i === $this->currentStep ? 'bg-blue-500' : 'bg-slate-200 dark:bg-slate-700') }}"
                                title="{{ $sec->name }}"></div>
                        @endforeach
                    </div>
                </div>
            </nav>

            <main class="max-w-3xl mx-auto px-4 py-8">
                <div
                    class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-200 dark:border-slate-800">
                        <h2 class="text-xl font-black dark:text-white">{{ $this->currentSection->name }}</h2>
                        @if($this->currentSection->description)
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $this->currentSection->description }}</p>
                        @endif
                    </div>
                    <form wire:submit="handleSubmit" class="p-6 space-y-6">

                    @php
                        $questions = $this->currentSection?->questions ?? collect();
                    @endphp
                    
                    @foreach($questions as $question)
                    
                    <div>
                        @if($question->type === 'yes_no')
                            <flux:select
                                wire:model="answers.{{ $question->id }}"
                                label="{{ $question->label }}"
                                placeholder="Select..."
                                :required="$question->is_required"
                            >
                                <flux:select.option value="">—</flux:select.option>
                                <flux:select.option value="yes">Yes</flux:select.option>
                                <flux:select.option value="no">No</flux:select.option>
                            </flux:select>
                        @elseif($question->type === 'text')
                            <flux:input
                                wire:model="answers.{{ $question->id }}"
                                label="{{ $question->label }}"
                                placeholder="Enter..."
                                :required="$question->is_required"
                            />
                        @else
                            <flux:input
                                wire:model="answers.{{ $question->id }}"
                                type="number"
                                label="{{ $question->label }}"
                                placeholder="0"
                                :required="$question->is_required"
                            />
                        @endif
                    </div>
                    @endforeach
                    

                        <div>
                            <flux:textarea
                                wire:model="currentSectionNotes"
                                label="Section notes (optional)"
                                placeholder="Any additional notes for this section..."
                                rows="3"
                            />
                        </div>

                        @if ($errors->any())
                            <div class="p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                                <p class="text-sm font-semibold text-red-800 dark:text-red-400">Please answer all required questions.</p>
                            </div>
                        @endif

                        <div class="flex gap-3 pt-4">
                            @if (!$this->isFirstStep)
                                <flux:button type="button" wire:click="prevStep" variant="ghost">Previous</flux:button>
                            @endif
                            <flux:spacer />
                            @if ($this->isLastStep)
                                <flux:button type="submit" variant="primary">Submit Round</flux:button>
                            @else
                                <flux:button type="submit" variant="primary">Next</flux:button>
                            @endif
                        </div>
                    </form>

                </div>
            </main>
        @else
            <nav class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 sticky top-0 z-10">
                <div class="max-w-3xl mx-auto px-4 py-4">
                    <div class="flex items-center justify-between mb-3">
                        <h1 class="text-lg font-bold dark:text-white">Round in progress</h1>
                        <span class="text-sm text-slate-500 dark:text-slate-400">Step {{ $this->currentStep + 1 }} of
                            {{$this->totalSteps }}</span>
                    </div>
                    <div class="flex gap-1">
                        @foreach($this->sections as $i => $sec)
                            <div class="flex-1 h-2 rounded-full bg-slate-200 dark:bg-slate-700" title="{{ $sec->name }}"></div>
                        @endforeach
                    </div>
                </div>
            </nav>
            <main class="max-w-3xl mx-auto px-4 py-8">
                <p class="text-slate-500 dark:text-slate-400">Loading section...</p>
            </main>
        @endif
    </div>
</div>