<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoundSection extends Model
{
    protected $fillable = [
        'name',
        'description',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function questions()
    {
        return $this->hasMany(RoundQuestion::class)->orderBy('sort_order');
    }
}
