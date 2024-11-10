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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 20);
            $table->string('shipping_street_1', 100);
            $table->string('shipping_street_2', 100);
            $table->string('shipping_city', 100);
            $table->string('shipping_state', 100);
            $table->string('shipping_zip_code', 10);
            $table->string('shipping_instructions', 500);
            $table->string('billing_street_1', 100);
            $table->string('billing_street_2', 100);
            $table->string('billing_city', 100);
            $table->string('billing_state', 100);
            $table->string('billing_zip_code', 10);
            $table->string('billing_card_name', 100);
            $table->string('billing_card_number', 20);
            $table->string('billing_card_expiration', 5);
            $table->string('billing_card_cvv', 3);
            $table->foreignId('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
