<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoundSectionResponse extends Model
{
    protected $fillable = [
        'round_id',
        'round_section_id',
        'visited_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'visited_at' => 'datetime',
        ];
    }

    public function round()
    {
        return $this->belongsTo(Round::class);
    }

    public function section()
    {
        return $this->belongsTo(RoundSection::class, 'round_section_id');
    }

    public function questionAnswers()
    {
        return $this->hasMany(RoundQuestionAnswer::class);
    }
}
