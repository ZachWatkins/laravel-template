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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('number')->default(1)->index();
            $table->enum('payment_state', ["pending","paid","partially_refunded","refunded"])->default('pending');
            $table->enum('shipping_state', ["pending","shipped","delivered","returned"])->default('pending');
            $table->integer('items_total');
            $table->decimal('total', 10, 2);
            $table->string('token_value', 255)->nullable();
            $table->string('customer_ip', 255)->nullable();
            $table->boolean('created_by_guest')->default(false);
            $table->text('notes');
            $table->foreignId('customer_id');
            $table->foreignId('currency_id');
            $table->foreignId('locale_id');
            $table->unique(['customer_id', 'number']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
