<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoundQuestionAnswer extends Model
{
    protected $fillable = [
        'round_section_response_id',
        'round_question_id',
        'value',
    ];

    public function sectionResponse()
    {
        return $this->belongsTo(RoundSectionResponse::class, 'round_section_response_id');
    }

    public function question()
    {
        return $this->belongsTo(RoundQuestion::class, 'round_question_id');
    }
}
