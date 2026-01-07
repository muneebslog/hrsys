<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 'Active' => 'bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400',
            *                'On Leave' => 'bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400',
             *               'Terminated' => 'bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400',
              *              'Resigned
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('employee_code')->unique()->nullable();
            $table->string('full_name')->nullable();
            $table->string('father_name')->nullable();
            $table->string('designation')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('qualification')->nullable();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->foreignId('manager_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('phone')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->string('cnic')->nullable();
            $table->date('joining_date')->nullable();
            $table->date('leave_date')->nullable();
            $table->enum('employment_status', ['active', 'leave', 'terminated', 'resigned'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
