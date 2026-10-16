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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('eyebrow')->nullable();
            $table->text('summary');
            $table->longText('overview');
            $table->json('benefits')->nullable();
            $table->json('deliverables')->nullable();
            $table->json('process')->nullable();
            $table->json('technologies')->nullable();
            $table->string('icon')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('status')->default('draft')->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->unsignedInteger('display_order')->default(0);
            $table->string('meta_title', 70)->nullable();
            $table->string('meta_description', 170)->nullable();
            $table->string('canonical_url')->nullable();
            $table->boolean('noindex')->default(false);
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();

            $table->index(['status', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
