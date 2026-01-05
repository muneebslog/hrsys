<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('employee_code')->unique();
            $table->string('full_name');
            $table->string('father_name');
            $table->string('designation');
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
            $table->string('employment_status')->default('active');
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
