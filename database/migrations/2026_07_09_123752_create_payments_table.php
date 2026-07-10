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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')
                ->constrained()
                ->cascadeOnDelete();


            $table->decimal('amount', 10, 2);


            $table->string('currency', 3)
                ->default('NPR');


            $table->enum('payment_method', [

                'CARD',
                'ESEWA',
                'KHALTI',
                'WALLET',
                'CASH',
                'LOYALTY_POINTS'

            ]);


            $table->string('transaction_id')
                ->nullable();


            $table->json('gateway_response')
                ->nullable();


            $table->enum('status', [

                'PENDING',
                'SUCCESS',
                'FAILED',
                'REFUNDED'

            ])
                ->default('PENDING');


            $table->dateTime('paid_at')
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
