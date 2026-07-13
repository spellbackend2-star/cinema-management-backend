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
        Schema::create('seat_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('screen_id');
            $table->string('name', 50);
            $table->string('image_icon', 255)->nullable();
            $table->tinyInteger('display_order')->unsigned()->default(0);
           
            $table->timestamps(); 
            $table->softDeletes();

            // Foreign key
            $table->foreign('screen_id')
                  ->references('id')
                  ->on('screens')
                  ->onDelete('cascade');

            // Unique constraint
            $table->unique(['screen_id', 'name'], 'uq_seatcat_name');
        });
    }

    /**
     * Reverse the migrations.  
     */
    public function down(): void
    {
        Schema::dropIfExists('seat_categories');
    }
};