<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = [
        'employee_id',
        'category',
        'title',
        'attachment_path',
        'description',
        'is_anonymous',
        'status',
        'admin_remarks',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
