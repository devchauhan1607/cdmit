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
        Schema::create('case_studies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('client_name')->nullable();
            $table->string('industry')->nullable()->index();
            $table->text('summary');
            $table->longText('overview');
            $table->longText('challenge');
            $table->longText('solution');
            $table->longText('implementation')->nullable();
            $table->json('results')->nullable();
            $table->string('project_url')->nullable();
            $table->string('cover_image')->nullable();
            $table->json('screenshots')->nullable();
            $table->string('status')->default('draft')->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->unsignedInteger('display_order')->default(0);
            $table->string('meta_title', 70)->nullable();
            $table->string('meta_description', 170)->nullable();
            $table->string('canonical_url')->nullable();
            $table->boolean('noindex')->default(false);
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('case_study_technology', function (Blueprint $table) {
            $table->foreignId('case_study_id')->constrained()->cascadeOnDelete();
            $table->foreignId('technology_id')->constrained()->cascadeOnDelete();
            $table->primary(['case_study_id', 'technology_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_study_technology');
        Schema::dropIfExists('case_studies');
    }
};
