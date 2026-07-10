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
        Schema::create('loyalty_transactions', function (Blueprint $table) {

            $table->id();



            $table->foreignId('loyalty_account_id')
                ->constrained()
                ->cascadeOnDelete();



            // NULL for manual point adjustment
            $table->foreignId('booking_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();



            // positive = earn
            // negative = redeem
            $table->integer('points');



            $table->string('reason', 150)
                ->nullable();



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyalty_transactions');
    }
};
