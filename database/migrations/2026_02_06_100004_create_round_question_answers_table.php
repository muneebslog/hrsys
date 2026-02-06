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
        Schema::create('round_question_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('round_section_response_id')->constrained('round_section_responses')->cascadeOnDelete();
            $table->foreignId('round_question_id')->constrained('round_questions')->cascadeOnDelete();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('round_question_answers');
    }
};
