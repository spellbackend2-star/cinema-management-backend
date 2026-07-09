<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shows', function (Blueprint $table) {

            $table->id();

            $table->foreignId('schedule_id')
                ->nullable()
                ->constrained('show_schedules')
                ->nullOnDelete();

            $table->foreignId('movie_id')
                ->constrained('movies')
                ->cascadeOnDelete();

            $table->foreignId('screen_id')
                ->constrained('screens')
                ->cascadeOnDelete();

            $table->dateTime('start_time');
            $table->dateTime('end_time');

            // Generated column
            $table->date('show_date')->storedAs('DATE(start_time)');

            $table->foreignId('language_id')
                ->constrained('languages')
                ->cascadeOnDelete();

            $table->dateTime('booking_open_at')->nullable();
            $table->dateTime('booking_close_at')->nullable();

            $table->enum('format', [
                '2D',
                '3D',
                'IMAX',
                '4DX'
            ])->default('2D');

            $table->enum('status', [
                'SCHEDULED',
                'CANCELLED',
                'COMPLETED'
            ])->default('SCHEDULED');

            $table->timestamps();

            $table->index(['screen_id', 'start_time'], 'idx_shows_screen_time');
            $table->index(['movie_id', 'show_date'], 'idx_shows_movie_date');
            $table->index(['show_date', 'status'], 'idx_shows_date_status');
        });

        // Optional DB check constraint (MySQL 8+)
        DB::statement("
            ALTER TABLE shows
            ADD CONSTRAINT chk_show_time
            CHECK (end_time > start_time)
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shows');
    }
};