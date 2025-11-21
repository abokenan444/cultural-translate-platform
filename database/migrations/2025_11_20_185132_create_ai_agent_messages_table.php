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
        Schema::create('ai_agent_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('role')->default('user'); // user, assistant, system
            $table->text('message');
            $table->text('response')->nullable();
            $table->json('meta')->nullable(); // للمعلومات الإضافية مثل model, tokens, etc
            $table->string('status')->default('pending'); // pending, processing, completed, failed
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('role');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_agent_messages');
    }
};
