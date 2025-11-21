<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('token_usage_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_subscription_id')->constrained()->onDelete('cascade');
            $table->integer('tokens_used');
            $table->integer('tokens_before');
            $table->integer('tokens_after');
            $table->string('action')->comment('translation, api_call, etc');
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'created_at']);
            $table->index('user_subscription_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('token_usage_logs');
    }
};
