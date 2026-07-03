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
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('screen_id');
            $table->unsignedBigInteger('category_id');
        
             
            
            $table->string('row_label', 5);

            $table->string('seat_number', 10);

            $table->string('seat_label', 30)
                ->nullable();

            $table->enum('seat_type', [
                'NORMAL',
                'RECLINER',
                'WHEELCHAIR',
                'COUPLE'
            ])->default('NORMAL');

            $table->smallInteger('pos_x')->nullable();
            $table->smallInteger('pos_y')->nullable();

            $table->smallInteger('rotation')->default(0);

            $table->smallInteger('width')->default(1);
            $table->smallInteger('height')->default(1);

            $table->boolean('is_active')->default(true);

             $table->timestamps();
            $table->softDeletes();
           

           
            $table->foreign('screen_id')
                  ->references('id')
                  ->on('screens')
                  ->onDelete('cascade');
            
            $table->foreign('category_id')
                  ->references('id')
                  ->on('seat_categories')
                  ->onDelete('restrict');

            // Unique constraint
            $table->unique(['screen_id', 'row_label', 'seat_number'], 'uq_seat_position');
            
           // Indexes
            $table->index('screen_id', 'idx_seats_screen');
            $table->index('category_id', 'idx_seats_category');
            $table->index('is_active', 'idx_seats_active');
            $table->index('seat_type', 'idx_seats_seat_type');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
