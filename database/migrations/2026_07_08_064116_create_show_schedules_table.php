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
        Schema::create('show_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')
                ->constrained('movies')
                ->cascadeOnDelete();

            $table->foreignId('screen_id')
                ->constrained('screens')
                ->cascadeOnDelete();

            $table->date('start_date');
            $table->date('end_date');

            $table->time('show_time');

            // Store as comma-separated values: 1,2,3,4,5,6,7
            $table->set('days_of_week', ['1', '2', '3', '4', '5', '6', '7'])
                ->default('1,2,3,4,5,6,7');

            $table->foreignId('language_id')
                ->constrained('languages')
                ->cascadeOnDelete();

            $table->enum('format', [
                '2D',
                '3D',
                'IMAX',
                '4DX'
            ])->default('2D');

            $table->integer('booking_opens_offset_min')->default(0);
            $table->integer('booking_closes_offset_min')->default(15);

            $table->boolean('is_active')->default(true);

            $table->unsignedBigInteger('created_by')->nullable();

            $table->timestamps();


            $table->index(['screen_id', 'start_date', 'end_date']);
            $table->index('movie_id');
        });
            
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('show_schedules');
    }
};
