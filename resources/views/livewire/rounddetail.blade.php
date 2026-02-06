<?php

use Livewire\Volt\Component;
use App\Models\Round;
use Livewire\Attributes\Title;

new class extends Component {
    #[Title('Round Report')]

    public $round;

    public function mount(Round $round)
    {
        $round->load([
            'user',
            'sectionResponses.section',
            'sectionResponses.questionAnswers.question',
        ]);
        $round->setRelation(
            'sectionResponses',
            $round->sectionResponses->sortBy(fn ($r) => $r->section->sort_order ?? 0)->values()
        );
        $this->round = $round;
    }
}; ?>

<div class="-m-6 lg:-m-8">
    <div class="max-w-4xl mx-auto px-4 py-8">

        <div class="mb-6">
            <a href="{{ route('rounds.report') }}" wire:navigate class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Round Reports
            </a>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden mb-8">
            <div class="p-6 border-b border-slate-200 dark:border-slate-800">
                <h1 class="text-2xl font-black dark:text-white">Round Report</h1>
                <div class="flex flex-wrap gap-4 mt-2 text-sm text-slate-500 dark:text-slate-400">
                    <span><strong class="text-slate-700 dark:text-slate-300">Conducted by:</strong> {{ $round->user->name ?? 'Unknown' }}</span>
                    <span><strong class="text-slate-700 dark:text-slate-300">Started:</strong> {{ $round->started_at->format('M d, Y H:i') }}</span>
                    <span><strong class="text-slate-700 dark:text-slate-300">Completed:</strong> {{ $round->completed_at?->format('M d, Y H:i') ?? '—' }}</span>
                </div>
            </div>
        </div>

        <div class="space-y-8">
            @foreach($round->sectionResponses as $response)
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
                        <h2 class="text-lg font-bold dark:text-white">{{ $response->section->name }}</h2>
                        @if($response->section->description)
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">{{ $response->section->description }}</p>
                        @endif
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-2">Visited: {{ $response->visited_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div class="p-6 space-y-4">
                        @foreach($response->questionAnswers as $answer)
                            <div class="flex flex-col gap-2 py-3 border-b border-slate-100 dark:border-slate-800 last:border-0">
                                <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                    <dt class="text-sm font-semibold text-slate-700 dark:text-slate-300 sm:w-2/5">{{ $answer->question->label }}</dt>
                                    <dd class="text-sm text-slate-600 dark:text-slate-400 sm:w-3/5">
                                        @if($answer->question->type === 'yes_no')
                                            <span class="px-2 py-0.5 rounded text-xs font-bold {{ $answer->value === 'yes' ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400' }}">
                                                {{ ucfirst($answer->value ?? '—') }}
                                            </span>
                                        @else
                                            {{ $answer->value ?? '—' }}
                                        @endif
                                    </dd>
                                </div>
                                @if($answer->attachment)
                                    <div class="sm:ml-0 sm:pl-0 sm:w-3/5">
                                        <a href="{{ asset('storage/' . $answer->attachment) }}" target="_blank" rel="noopener" class="inline-block">
                                            <img src="{{ asset('storage/' . $answer->attachment) }}" alt="Attachment" class="max-w-xs rounded-lg border border-slate-200 dark:border-slate-700 shadow-sm hover:opacity-90 transition object-cover max-h-48">
                                        </a>
                                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400"><a href="{{ asset('storage/' . $answer->attachment) }}" target="_blank" rel="noopener" class="font-semibold text-blue-600 dark:text-blue-400 hover:underline">Open full size</a></p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                        @if($response->notes)
                            <div class="pt-4 mt-4 border-t border-slate-100 dark:border-slate-800">
                                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Section notes</p>
                                <p class="text-sm text-slate-600 dark:text-slate-400">{{ $response->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if($round->sectionResponses->isEmpty())
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-12 text-center">
                <p class="text-slate-500 dark:text-slate-400">No section responses recorded for this round.</p>
            </div>
        @endif
    </div>
</div>
