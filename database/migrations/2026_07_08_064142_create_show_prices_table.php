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
        Schema::create('show_prices', function (Blueprint $table) {

            $table->id();

            $table->foreignId('show_id')
                ->constrained('shows')
                ->cascadeOnDelete();

            $table->foreignId('category_id')
                ->constrained('seat_categories')
                ->cascadeOnDelete();

            $table->decimal('base_price', 10, 2);

            $table->decimal('tax_percent', 5, 2)
                ->default(13.00);

            $table->timestamps();

            $table->unique(['show_id', 'category_id'], 'uq_show_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('show_prices');
    }
};