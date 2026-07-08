<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('movie_cast', function (Blueprint $table) {

            $table->id();


            $table->foreignId('movie_id')
                ->constrained('movies')
                ->cascadeOnDelete();


            $table->foreignId('person_id')
                ->constrained('people')
                ->cascadeOnDelete();


            $table->enum('credit_type',[
                'ACTOR',
                'DIRECTOR',
                'PRODUCER',
                'WRITER',
                'MUSIC'
            ])
            ->default('ACTOR');


            $table->string('character_name',150)
                ->nullable();


            $table->unsignedTinyInteger('display_order')
                ->default(0);


            $table->unique([
                'movie_id',
                'person_id',
                'credit_type',
                'character_name'
            ],'uq_movie_person_role');


            $table->index('movie_id');
            $table->index('person_id');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('movie_cast');
    }
};