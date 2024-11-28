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
        Schema::create('customer_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('card_name', 100);
            $table->string('card_number', 20);
            $table->string('card_expiration', 5);
            $table->string('street_1', 100);
            $table->string('street_2', 100);
            $table->string('city', 100);
            $table->string('state', 100);
            $table->string('zip_code', 10);
            $table->foreignId('customer_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_payment_methods');
    }
};
