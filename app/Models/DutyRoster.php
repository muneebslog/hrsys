<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DutyRoster extends Model
{
    protected $fillable = [
        'employee_id',
        'date',
        'day_of_week',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function slots()
    {
        return $this->hasMany(DutyRosterSlot::class)->orderBy('sort_order')->orderBy('start_time');
    }

    /**
     * Resolve roster for an employee on a given date: override first, else weekly template.
     */
    public static function forEmployeeOnDate(int $employeeId, $date): ?self
    {
        $date = $date instanceof \Carbon\Carbon ? $date : \Carbon\Carbon::parse($date);

        $override = self::where('employee_id', $employeeId)
            ->where('date', $date->toDateString())
            ->with('slots')
            ->first();

        if ($override) {
            return $override;
        }

        return self::where('employee_id', $employeeId)
            ->whereNull('date')
            ->where('day_of_week', $date->dayOfWeek)
            ->with('slots')
            ->first();
    }
}
