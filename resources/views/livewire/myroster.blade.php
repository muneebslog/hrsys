<?php

use Livewire\Volt\Component;
use App\Models\DutyRoster;
use Livewire\Attributes\Title;

new class extends Component {
    #[Title('My Roster')]

    public $employee = null;
    public $viewDate = null;
    public $viewWeekStart = null; // for week view
    public $viewMode = 'date'; // 'date' | 'week'

    public function mount()
    {
        $this->employee = auth()->user()->employee;
        $this->viewDate = now()->toDateString();
        $this->viewWeekStart = now()->startOfWeek()->toDateString();
    }

    public function getRosterForDate($date)
    {
        if (!$this->employee) {
            return null;
        }
        return DutyRoster::forEmployeeOnDate($this->employee->id, $date);
    }

    public function getDatesForWeek()
    {
        if (!$this->viewWeekStart) {
            return [];
        }
        $start = \Carbon\Carbon::parse($this->viewWeekStart);
        $dates = [];
        for ($i = 0; $i < 7; $i++) {
            $dates[] = $start->copy()->addDays($i);
        }
        return $dates;
    }

    public function formatTime($time)
    {
        if (!$time) {
            return '—';
        }
        $t = is_string($time) ? $time : $time->format('H:i');
        $parts = explode(':', $t);
        $h = (int) ($parts[0] ?? 0);
        $m = (int) ($parts[1] ?? 0);
        $ampm = $h >= 12 ? 'pm' : 'am';
        $h = $h % 12 ?: 12;
        return $h . ':' . str_pad((string) $m, 2, '0') . ' ' . $ampm;
    }

    public function previousWeek()
    {
        $this->viewWeekStart = \Carbon\Carbon::parse($this->viewWeekStart)->subWeek()->toDateString();
    }

    public function nextWeek()
    {
        $this->viewWeekStart = \Carbon\Carbon::parse($this->viewWeekStart)->addWeek()->toDateString();
    }
}; ?>

<div class="-m-6 lg:-m-8">
    <div class="max-w-4xl mx-auto px-4 py-8">

        <div class="mb-8">
            <h1 class="text-3xl font-black dark:text-white">My Roster</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Your duty slots by date. Overrides apply over the weekly template.</p>
        </div>

        @if(!$employee)
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-12 text-center">
                <p class="text-slate-500 dark:text-slate-400">No employee record linked to your account. Contact HR.</p>
            </div>
        @else
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 mb-6 shadow-sm">
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2">View date</label>
                <div class="flex flex-wrap gap-4 items-center">
                    <input type="date" wire:model.live="viewDate" class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white px-4 py-2 text-sm">
                    <span class="text-sm text-slate-500 dark:text-slate-400">or</span>
                    <div class="flex items-center gap-2">
                        <flux:button size="sm" variant="ghost" wire:click="previousWeek">Previous week</flux:button>
                        <span class="text-sm font-semibold dark:text-white">
                            Week of {{ \Carbon\Carbon::parse($viewWeekStart)->format('M j') }}
                        </span>
                        <flux:button size="sm" variant="ghost" wire:click="nextWeek">Next week</flux:button>
                    </div>
                </div>
            </div>

            {{-- Single date view --}}
            <div class="mb-8">
                <h2 class="text-lg font-bold dark:text-white mb-4">{{ \Carbon\Carbon::parse($viewDate)->format('l, F j, Y') }}</h2>
                @php $roster = $this->getRosterForDate($viewDate); @endphp
                @if($roster && $roster->slots->isNotEmpty())
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/50 text-left text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                                    <th class="px-6 py-4 font-bold">Time</th>
                                    <th class="px-6 py-4 font-bold">Place</th>
                                    <th class="px-6 py-4 font-bold">Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roster->slots as $slot)
                                    <tr class="border-b border-slate-100 dark:border-slate-800 last:border-0">
                                        <td class="px-6 py-4 font-medium dark:text-white">{{ $this->formatTime($slot->start_time) }} – {{ $this->formatTime($slot->end_time) }}</td>
                                        <td class="px-6 py-4 text-slate-600 dark:text-slate-400">{{ $slot->place }}</td>
                                        <td class="px-6 py-4 text-slate-600 dark:text-slate-400">{{ $slot->role }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-8 text-center">
                        <p class="text-slate-500 dark:text-slate-400">No duty slots for this date.</p>
                    </div>
                @endif
            </div>

            {{-- Week view --}}
            <div>
                <h2 class="text-lg font-bold dark:text-white mb-4">Week at a glance</h2>
                <div class="space-y-4">
                    @foreach($this->getDatesForWeek() as $date)
                        @php
                            $roster = $this->getRosterForDate($date);
                        @endphp
                        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                            <div class="px-6 py-3 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 flex items-center justify-between">
                                <span class="font-bold dark:text-white">{{ $date->format('l, M j') }}</span>
                                @if($roster && $roster->slots->isNotEmpty())
                                    <span class="text-xs text-slate-500 dark:text-slate-400">{{ $roster->slots->count() }} slot(s)</span>
                                @else
                                    <span class="text-xs text-slate-400 italic">No slots</span>
                                @endif
                            </div>
                            @if($roster && $roster->slots->isNotEmpty())
                                <div class="p-4">
                                    <ul class="space-y-2 text-sm">
                                        @foreach($roster->slots as $slot)
                                            <li class="flex flex-wrap gap-2 items-center text-slate-600 dark:text-slate-400">
                                                <span class="font-medium dark:text-white">{{ $this->formatTime($slot->start_time) }} – {{ $this->formatTime($slot->end_time) }}</span>
                                                <span>·</span>
                                                <span>{{ $slot->place }}</span>
                                                <span>·</span>
                                                <span>{{ $slot->role }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <div class="p-4 text-sm text-slate-500 dark:text-slate-400 italic">No duty slots.</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
