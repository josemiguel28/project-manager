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
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->string('store_number');
            $table->string('site');
            $table->string('service_description');
            $table->string('work_order_id')->unique();
            $table->string('gmail_link')->nullable();
            $table->date('date_started')->nullable();
            $table->date('day_done')->nullable();
            $table->string('assigned_name')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('invoice_link')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};
