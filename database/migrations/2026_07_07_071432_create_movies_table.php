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
        Schema::create('movies', function (Blueprint $table) {
            $table->id();

            $table->string('title', 200);
            $table->string('original_title', 200)->nullable();
            $table->text('description')->nullable();

            $table->unsignedSmallInteger('duration_min');

            $table->date('release_date')->nullable();

            $table->string('production_house', 150)->nullable();
            $table->string('country', 100)->nullable();

            $table->enum('censor_rating', [
                'U',
                'U/A',
                'A',
                'S'
            ])->default('U/A');

            $table->string('poster_url', 500)->nullable();
            $table->string('banner_url', 500)->nullable();
            $table->string('trailer_url', 500)->nullable();

            $table->enum('status', [
                'UPCOMING',
                'RUNNING',
                'ENDED'
            ])->default('UPCOMING');

            $table->string('genre')->nullable();

            $table->decimal('imdb_rating', 3, 1)->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index('release_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
