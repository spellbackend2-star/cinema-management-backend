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
        Schema::create('screens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cinema_id');

            $table->string('name', 50);

            $table->enum('screen_type', [
                'STANDARD',
                'IMAX',
                '3D',
                '4DX',
                'DOLBY_ATMOS',
                'RECLINER_HALL'
            ])->default('STANDARD');

            $table->unsignedSmallInteger('capacity');

            $table->string('sound_system', 50)->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            // Foreign key
            $table->foreign('cinema_id')
                ->references('id')
                ->on('cinemas')
                ->onDelete('cascade');

            // Unique constraint
            $table->unique(['cinema_id', 'name'], 'uq_screen_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('screens');
    }
};