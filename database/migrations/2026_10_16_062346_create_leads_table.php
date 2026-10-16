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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('email')->index();
            $table->string('company')->nullable();
            $table->string('country')->nullable();
            $table->string('phone')->nullable();
            $table->string('budget')->nullable();
            $table->text('message');
            $table->boolean('consent')->default(false);
            $table->string('status')->default('new')->index();
            $table->string('source')->default('contact');
            $table->string('duplicate_hash', 64)->nullable()->index();
            $table->string('ip_hash', 64)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->string('notification_status')->default('pending')->index();
            $table->text('notification_error')->nullable();
            $table->timestamp('notification_sent_at')->nullable();
            $table->timestamp('last_contacted_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
