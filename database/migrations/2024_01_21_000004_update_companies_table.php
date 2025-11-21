<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            if (!Schema::hasColumn('companies', 'owner_id')) {
                $table->foreignId('owner_id')->nullable()->after('id')->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('companies', 'subscription_plan_id')) {
                $table->foreignId('subscription_plan_id')->nullable()->after('owner_id')->constrained()->onDelete('set null');
            }
            if (!Schema::hasColumn('companies', 'max_members')) {
                $table->integer('max_members')->default(5)->after('subscription_plan_id');
            }
            if (!Schema::hasColumn('companies', 'settings')) {
                $table->json('settings')->nullable()->after('max_members');
            }
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign(['owner_id']);
            $table->dropForeign(['subscription_plan_id']);
            $table->dropColumn(['owner_id', 'subscription_plan_id', 'max_members', 'settings']);
        });
    }
};
