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
        Schema::create('part_images', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ["thumbnail","main","additional"])->default('additional');
            $table->string('path', 255);
            $table->foreignId('part_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('part_images');
    }
};
