<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoundQuestion extends Model
{
    protected $fillable = [
        'round_section_id',
        'label',
        'type',
        'options',
        'is_required',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'options' => 'array',
            'is_required' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function section()
    {
        return $this->belongsTo(RoundSection::class, 'round_section_id');
    }
}
