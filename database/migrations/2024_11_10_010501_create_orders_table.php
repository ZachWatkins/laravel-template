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
            $table->enum('status', ["paid","processing","shipped","delivered","canceled","returned","partially_refunded","refunded","completed"])->default('paid')->index();
            $table->decimal('total', 10, 2);
            $table->text('notes');
            $table->foreignId('customer_id');
            $table->foreignId('customer_payment_method_id');
            $table->foreignId('customer_shipping_address_id');
            $table->foreignId('customer_billing_address_id');
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
