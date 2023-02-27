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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('submitter_id');
            $table->string('name');
            $table->dateTime('date');
            $table->decimal('lat', 10, 8);
            $table->decimal('long', 11, 8);
            $table->timestamps();
        });

        Schema::table('locations', function (Blueprint $table) {
            $table->unique(['submitter_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};