<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {

            $table->id();

            $table->string('booking_reference', 20)
                  ->unique();

            // Customer who owns booking
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Staff/admin user who created booking at counter
            $table->foreignId('booked_by_user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->foreignId('show_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('coupon_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();


            $table->decimal('subtotal',10,2)
                  ->default(0);

            $table->decimal('tax_amount',10,2)
                  ->default(0);

            $table->decimal('discount_amount',10,2)
                  ->default(0);

            $table->decimal('convenience_fee',10,2)
                  ->default(0);

            $table->decimal('total_amount',10,2);


            $table->enum('status',[
                'PENDING',
                'CONFIRMED',
                'CANCELLED',
                'EXPIRED'
            ])
            ->default('PENDING');


            $table->enum('payment_status',[
                'UNPAID',
                'PAID',
                'PARTIALLY_REFUNDED',
                'REFUNDED'
            ])
            ->default('UNPAID');


            $table->enum('booking_source',[
                'WEB',
                'APP',
                'COUNTER',
                'KIOSK'
            ])
            ->default('WEB');


            $table->dateTime('expires_at')
                  ->nullable();

            $table->dateTime('confirmed_at')
                  ->nullable();

            $table->dateTime('cancelled_at')
                  ->nullable();


            $table->timestamps();


            $table->index('status');
            $table->index('expires_at');

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};