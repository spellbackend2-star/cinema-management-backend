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
        Schema::create('cinemas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name', 150);
            $table->string('slug', 150);
            $table->string('address', 300);
            $table->string('country', 100)->default('Nepal');
            $table->string('city', 100);
            $table->string('area', 100)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('contact_number', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('timezone', 50)->default('Asia/Kathmandu');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Foreign key
            $table->foreign('company_id')
                  ->references('id')
                  ->on('companies')
                  ->onDelete('cascade');

            // Unique constraint
            $table->unique(['company_id', 'slug'], 'uq_cinema_slug');

            // Indexes
            $table->index('city', 'idx_cinemas_city');
            $table->index('company_id', 'idx_cinemas_company');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cinemas');
    }
};
