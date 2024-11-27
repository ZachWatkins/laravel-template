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
        Schema::create('part_webpages', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ["active","inactive"])->default('active')->index();
            $table->string('path', 100);
            $table->string('title', 100);
            $table->string('meta_title', 100);
            $table->string('meta_description', 255);
            $table->string('meta_keywords', 255);
            $table->text('content');
            $table->foreignId('part_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('part_webpages');
    }
};
