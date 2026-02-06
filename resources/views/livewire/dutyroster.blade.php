<?php

use Livewire\Volt\Component;
use App\Models\Employee;
use App\Models\DutyRoster;
use App\Models\DutyRosterSlot;
use Flux\Flux;
use Livewire\Attributes\Title;

new class extends Component {
    #[Title('Duty Roster')]

    public $employees = [];
    public $selectedEmployeeId = null;
    public $mode = 'template'; // 'template' | 'override'
    public $overrideDate = null;

    // Roster data: for template, keyed by day_of_week (0-6); for override, single roster
    public $rostersByDay = []; // day_of_week => DutyRoster with slots
    public $overrideRoster = null;

    // Slot form (modal)
    public $slotRosterId = null;
    public $slotDayOfWeek = null; // for template
    public $slotStartTime = '07:00';
    public $slotEndTime = '17:00';
    public $slotPlace = '';
    public $slotRole = '';
    public $editingSlotId = null;

    public static $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    public function mount()
    {
        $this->employees = Employee::orderBy('full_name')->get();
        $this->overrideDate = now()->toDateString();
    }

    public function updatedSelectedEmployeeId()
    {
        $this->loadRosters();
    }

    public function updatedMode()
    {
        $this->loadRosters();
    }

    public function updatedOverrideDate()
    {
        $this->loadRosters();
    }

    public function loadRosters()
    {
        $this->rostersByDay = [];
        $this->overrideRoster = null;

        if (!$this->selectedEmployeeId) {
            return;
        }

        if ($this->mode === 'template') {
            for ($d = 0; $d <= 6; $d++) {
                $roster = DutyRoster::where('employee_id', $this->selectedEmployeeId)
                    ->whereNull('date')
                    ->where('day_of_week', $d)
                    ->with('slots')
                    ->first();
                $this->rostersByDay[$d] = $roster;
            }
        } else {
            if ($this->overrideDate) {
                $this->overrideRoster = DutyRoster::where('employee_id', $this->selectedEmployeeId)
                    ->where('date', $this->overrideDate)
                    ->with('slots')
                    ->first();
            }
        }
    }

    public function openAddSlotModal($dayOfWeek = null, $rosterId = null)
    {
        $this->editingSlotId = null;
        $this->slotRosterId = $rosterId;
        $this->slotDayOfWeek = $dayOfWeek;
        $this->slotStartTime = '07:00';
        $this->slotEndTime = '17:00';
        $this->slotPlace = '';
        $this->slotRole = '';
    }

    public function openEditSlotModal($slotId)
    {
        $slot = DutyRosterSlot::findOrFail($slotId);
        $this->editingSlotId = $slot->id;
        $this->slotRosterId = $slot->duty_roster_id;
        $this->slotDayOfWeek = null;
        $this->slotStartTime = substr($slot->start_time, 0, 5);
        $this->slotEndTime = substr($slot->end_time, 0, 5);
        $this->slotPlace = $slot->place;
        $this->slotRole = $slot->role;
    }

    public function saveSlot()
    {
        $this->validate([
            'slotStartTime' => 'required|date_format:H:i',
            'slotEndTime' => 'required|date_format:H:i|after:slotStartTime',
            'slotPlace' => 'required|string|max:255',
            'slotRole' => 'required|string|max:255',
        ]);

        $startTime = strlen((string) $this->slotStartTime) === 5 ? $this->slotStartTime . ':00' : $this->slotStartTime;
        $endTime = strlen((string) $this->slotEndTime) === 5 ? $this->slotEndTime . ':00' : $this->slotEndTime;

        if ($this->editingSlotId) {
            DutyRosterSlot::findOrFail($this->editingSlotId)->update([
                'start_time' => $startTime,
                'end_time' => $endTime,
                'place' => $this->slotPlace,
                'role' => $this->slotRole,
            ]);
            session()->flash('message', 'Slot updated.');
        } else {
            $roster = null;
            if ($this->mode === 'template' && $this->slotDayOfWeek !== null) {
                $roster = DutyRoster::firstOrCreate(
                    [
                        'employee_id' => $this->selectedEmployeeId,
                        'date' => null,
                        'day_of_week' => $this->slotDayOfWeek,
                    ],
                    ['employee_id' => $this->selectedEmployeeId, 'date' => null, 'day_of_week' => $this->slotDayOfWeek]
                );
            } elseif ($this->mode === 'override' && $this->overrideDate) {
                $roster = DutyRoster::firstOrCreate(
                    [
                        'employee_id' => $this->selectedEmployeeId,
                        'date' => $this->overrideDate,
                        'day_of_week' => null,
                    ],
                    ['employee_id' => $this->selectedEmployeeId, 'date' => $this->overrideDate, 'day_of_week' => null]
                );
            } elseif ($this->slotRosterId) {
                $roster = DutyRoster::find($this->slotRosterId);
            }

            if (!$roster) {
                session()->flash('error', 'Could not resolve roster.');
                return;
            }

            $maxOrder = $roster->slots()->max('sort_order') ?? 0;
            DutyRosterSlot::create([
                'duty_roster_id' => $roster->id,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'place' => $this->slotPlace,
                'role' => $this->slotRole,
                'sort_order' => $maxOrder + 1,
            ]);
            session()->flash('message', 'Slot added.');
        }

        $this->loadRosters();
        Flux::modal('slot-form')->close();
    }

    public function deleteSlot($slotId)
    {
        DutyRosterSlot::findOrFail($slotId)->delete();
        session()->flash('message', 'Slot removed.');
        $this->loadRosters();
    }

    public function copyFromTemplate()
    {
        if (!$this->overrideDate || !$this->selectedEmployeeId) {
            return;
        }
        $date = \Carbon\Carbon::parse($this->overrideDate);
        $dayOfWeek = $date->dayOfWeek;
        $template = DutyRoster::where('employee_id', $this->selectedEmployeeId)
            ->whereNull('date')
            ->where('day_of_week', $dayOfWeek)
            ->with('slots')
            ->first();

        if (!$template || $template->slots->isEmpty()) {
            session()->flash('error', 'No template for this weekday.');
            return;
        }

        $roster = DutyRoster::firstOrCreate(
            [
                'employee_id' => $this->selectedEmployeeId,
                'date' => $this->overrideDate,
                'day_of_week' => null,
            ],
            ['employee_id' => $this->selectedEmployeeId, 'date' => $this->overrideDate, 'day_of_week' => null]
        );

        $roster->slots()->delete();
        foreach ($template->slots as $i => $s) {
            DutyRosterSlot::create([
                'duty_roster_id' => $roster->id,
                'start_time' => $s->start_time,
                'end_time' => $s->end_time,
                'place' => $s->place,
                'role' => $s->role,
                'sort_order' => $i,
            ]);
        }
        session()->flash('message', 'Copied from template.');
        $this->loadRosters();
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
}; ?>

<div>
    <div class="max-w-6xl mx-auto px-4 py-8">

        <div class="mb-8">
            <h1 class="text-3xl font-black dark:text-white">Duty Roster</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Assign staff to time slots, place and role. Use weekly template or override for specific dates.</p>
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

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 mb-8 shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Employee</label>
                    <select wire:model.live="selectedEmployeeId" class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white px-4 py-2 text-sm">
                        <option value="">Select employee</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->full_name }} ({{ $emp->employee_code }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Mode</label>
                    <select wire:model.live="mode" class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white px-4 py-2 text-sm">
                        <option value="template">Weekly template</option>
                        <option value="override">Override for date</option>
                    </select>
                </div>
                @if($mode === 'override')
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Date</label>
                        <input type="date" wire:model.live="overrideDate" class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white px-4 py-2 text-sm">
                    </div>
                @endif
            </div>
        </div>

        @if($selectedEmployeeId)
            @if($mode === 'template')
                <div class="space-y-6">
                    @foreach([0,1,2,3,4,5,6] as $d)
                        @php $roster = $rostersByDay[$d] ?? null; @endphp
                        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/50">
                                <h2 class="font-bold dark:text-white">{{ self::$dayNames[$d] }}</h2>
                                <flux:modal.trigger name="slot-form">
                                    <flux:button size="sm" wire:click="openAddSlotModal({{ $d }}, null)" icon="plus">Add slot</flux:button>
                                </flux:modal.trigger>
                            </div>
                            <div class="p-6">
                                @if($roster && $roster->slots->isNotEmpty())
                                    <table class="w-full text-sm">
                                        <thead>
                                            <tr class="text-left text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                                                <th class="pb-2 pr-4">Time</th>
                                                <th class="pb-2 pr-4">Place</th>
                                                <th class="pb-2 pr-4">Role</th>
                                                <th class="pb-2 text-right">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($roster->slots as $slot)
                                                <tr class="border-b border-slate-100 dark:border-slate-800 last:border-0">
                                                    <td class="py-3 pr-4 font-medium dark:text-white">{{ $this->formatTime($slot->start_time) }} – {{ $this->formatTime($slot->end_time) }}</td>
                                                    <td class="py-3 pr-4 text-slate-600 dark:text-slate-400">{{ $slot->place }}</td>
                                                    <td class="py-3 pr-4 text-slate-600 dark:text-slate-400">{{ $slot->role }}</td>
                                                    <td class="py-3 text-right">
                                                        <flux:modal.trigger name="slot-form">
                                                            <button wire:click="openEditSlotModal({{ $slot->id }})" class="text-blue-600 dark:text-blue-400 text-xs font-bold mr-2">Edit</button>
                                                        </flux:modal.trigger>
                                                        <button wire:click="deleteSlot({{ $slot->id }})" wire:confirm="Remove this slot?" class="text-red-600 dark:text-red-400 text-xs font-bold">Delete</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p class="text-slate-500 dark:text-slate-400 text-sm">No slots. Add one above.</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/50">
                        <h2 class="font-bold dark:text-white">Slots for {{ \Carbon\Carbon::parse($overrideDate)->format('l, M j, Y') }}</h2>
                        <div class="flex gap-2">
                            <flux:button size="sm" variant="ghost" wire:click="copyFromTemplate">Copy from template</flux:button>
                            <flux:modal.trigger name="slot-form">
                                <flux:button size="sm" wire:click="openAddSlotModal(null, {{ $overrideRoster?->id ?? 'null' }})" icon="plus">Add slot</flux:button>
                            </flux:modal.trigger>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($overrideRoster && $overrideRoster->slots->isNotEmpty())
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="text-left text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                                        <th class="pb-2 pr-4">Time</th>
                                        <th class="pb-2 pr-4">Place</th>
                                        <th class="pb-2 pr-4">Role</th>
                                        <th class="pb-2 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($overrideRoster->slots as $slot)
                                        <tr class="border-b border-slate-100 dark:border-slate-800 last:border-0">
                                            <td class="py-3 pr-4 font-medium dark:text-white">{{ $this->formatTime($slot->start_time) }} – {{ $this->formatTime($slot->end_time) }}</td>
                                            <td class="py-3 pr-4 text-slate-600 dark:text-slate-400">{{ $slot->place }}</td>
                                            <td class="py-3 pr-4 text-slate-600 dark:text-slate-400">{{ $slot->role }}</td>
                                            <td class="py-3 text-right">
                                                <flux:modal.trigger name="slot-form">
                                                    <button wire:click="openEditSlotModal({{ $slot->id }})" class="text-blue-600 dark:text-blue-400 text-xs font-bold mr-2">Edit</button>
                                                </flux:modal.trigger>
                                                <button wire:click="deleteSlot({{ $slot->id }})" wire:confirm="Remove this slot?" class="text-red-600 dark:text-red-400 text-xs font-bold">Delete</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-slate-500 dark:text-slate-400 text-sm">No slots for this date. Add one or copy from template.</p>
                        @endif
                    </div>
                </div>
            @endif
        @else
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-12 text-center">
                <p class="text-slate-500 dark:text-slate-400">Select an employee to manage roster.</p>
            </div>
        @endif

        <flux:modal name="slot-form" class="md:w-[480px]">
            <form wire:submit="saveSlot" class="space-y-4">
                <div>
                    <flux:heading size="lg">{{ $editingSlotId ? 'Edit slot' : 'Add slot' }}</flux:heading>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <flux:input wire:model="slotStartTime" type="time" label="Start time" required />
                    <flux:input wire:model="slotEndTime" type="time" label="End time" required />
                </div>
                <flux:input wire:model="slotPlace" label="Place" placeholder="e.g. Reception, OPD, Ward" required />
                <flux:input wire:model="slotRole" label="Role" placeholder="e.g. Nurse, Front desk" required />
                @if($errors->has('slotEndTime'))
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $errors->first('slotEndTime') }}</p>
                @endif
                <div class="flex gap-2 pt-2">
                    <flux:spacer />
                    <flux:modal.close><flux:button variant="ghost">Cancel</flux:button></flux:modal.close>
                    <flux:button type="submit" variant="primary">Save</flux:button>
                </div>
            </form>
        </flux:modal>
    </div>
</div>
