<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'employee_code',
        'full_name',
        'father_name',
        'designation',
        'marital_status',
        'blood_group',
        'qualification',
        'department_id',
        'manager_id',
        'phone',
        'emergency_contact',
        'cnic',
        'joining_date',
        'employment_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    public function subordinates()
    {
        return $this->hasMany(Employee::class, 'manager_id');
    }

    public function documents()
    {
        return $this->hasMany(EmployeeDocument::class);
    }

    public function leaves()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    public function dutyRosters()
    {
        return $this->hasMany(DutyRoster::class);
    }
}
