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
        Schema::create('duty_roster_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('duty_roster_id')->constrained('duty_rosters')->cascadeOnDelete();
            $table->time('start_time');
            $table->time('end_time');
            $table->string('place');
            $table->string('role');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('duty_roster_slots');
    }
};
