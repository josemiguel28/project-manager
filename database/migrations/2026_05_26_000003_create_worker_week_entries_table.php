<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('worker_week_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_week_id')->constrained()->cascadeOnDelete();
            $table->foreignId('worker_id')->constrained()->cascadeOnDelete();
            $table->decimal('base_pay', 10, 2)->nullable();
            $table->decimal('extra_amount', 10, 2)->nullable();
            $table->text('extra_notes')->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'partial', 'refund'])->default('pending');
            $table->string('payment_notes')->nullable();
            $table->date('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('worker_week_entries');
    }
};
