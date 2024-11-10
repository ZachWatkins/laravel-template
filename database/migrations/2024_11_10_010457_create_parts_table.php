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
        Schema::create('parts', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ["active","inactive"])->default('active')->index();
            $table->string('number', 100)->index();
            $table->string('name', 100);
            $table->string('sku', 100);
            $table->integer('inventory');
            $table->decimal('price', 8, 2);
            $table->decimal('weight', 8, 2);
            $table->string('weight_unit', 10);
            $table->string('filename', 100);
            $table->timestamp('published_at')->nullable();
            $table->foreignId('part_type_id');
            $table->foreignId('manufacturer_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parts');
    }
};
