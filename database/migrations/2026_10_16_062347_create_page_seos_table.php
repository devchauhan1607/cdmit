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
        Schema::create('page_seos', function (Blueprint $table) {
            $table->id();
            $table->string('page_key')->unique();
            $table->string('title', 70);
            $table->string('description', 170);
            $table->string('canonical_url')->nullable();
            $table->string('social_title', 70)->nullable();
            $table->string('social_description', 170)->nullable();
            $table->string('social_image')->nullable();
            $table->boolean('noindex')->default(false);
            $table->json('structured_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_seos');
    }
};
