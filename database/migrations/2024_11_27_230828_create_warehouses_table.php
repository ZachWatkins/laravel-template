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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('description', 400);
            $table->string('address_line_1', 255);
            $table->string('address_line_2', 255);
            $table->string('city', 100)->nullable();
            $table->string('state_province_region', 100)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('country_code', 100)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('space_station', 100)->nullable();
            $table->string('planet_or_moon', 100)->nullable();
            $table->string('star_system', 100)->nullable();
            $table->string('sector', 100)->nullable();
            $table->string('quadrant', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
