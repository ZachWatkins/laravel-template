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
        Schema::create('order_payment_cards', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('number', 20);
            $table->string('expiration', 5);
            $table->foreignId('order_payment_id');
            $table->foreignId('_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_payment_cards');
    }
};
