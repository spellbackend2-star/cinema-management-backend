<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('company_id')
                ->references('id')->on('companies')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('cinema_id')
                ->references('id')->on('cinemas')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            
            $table->index('company_id');
            $table->index('cinema_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropForeign(['cinema_id']);
        });
    }
};
