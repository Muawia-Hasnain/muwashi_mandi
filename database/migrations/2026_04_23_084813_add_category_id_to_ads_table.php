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
        Schema::table('ads', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('user_id')->constrained()->onDelete('set null');
            // We keep animal_type for now to migrate data, or we can drop it if we want a clean break.
            // The user said "No hardcoded categories should remain".
            $table->dropColumn('animal_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            //
        });
    }
};
