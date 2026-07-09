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
        Schema::create('show_seats', function (Blueprint $table) {

            $table->id();

            $table->foreignId('show_id')
                ->constrained('shows')
                ->cascadeOnDelete();

            $table->foreignId('seat_id')
                ->constrained('seats')
                ->cascadeOnDelete();

            $table->enum('status', [
                'AVAILABLE',
                'LOCKED',
                'BOOKED',
                'BLOCKED',
            ])->default('AVAILABLE');

            $table->foreignId('locked_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->dateTime('locked_until')->nullable();

            $table->decimal('price', 10, 2);

            $table->timestamps();

            $table->unique(['show_id', 'seat_id'], 'uq_show_seat');

            $table->index(['show_id', 'status'], 'idx_show_seats_status');
            $table->index('locked_until', 'idx_show_seats_lock_expiry');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('show_seats');
    }
};