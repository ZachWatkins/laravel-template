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
            $table->date('birthday')->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->boolean('subscribed_to_newsletter')->default(false);
            $table->foreignId('user_id');
            $table->foreignId('shipping_address_id');
            $table->foreignId('billing_address_id');
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
