<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_phase_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('phase'); // planning, execution, completion
            $table->foreignId('reviewer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('comment')->nullable();
            $table->string('status_at_feedback'); // approved or rejected
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_phase_feedbacks');
    }
};


