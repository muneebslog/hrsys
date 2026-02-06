<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DutyRosterSlot extends Model
{
    protected $fillable = [
        'duty_roster_id',
        'start_time',
        'end_time',
        'place',
        'role',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function dutyRoster()
    {
        return $this->belongsTo(DutyRoster::class);
    }
}
