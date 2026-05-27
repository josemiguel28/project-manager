<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('worker_day_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_week_entry_id')->constrained()->cascadeOnDelete();
            $table->date('work_date');
            $table->string('assignment_notes')->nullable();
            $table->timestamps();

            $table->unique(['worker_week_entry_id', 'work_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('worker_day_assignments');
    }
};
