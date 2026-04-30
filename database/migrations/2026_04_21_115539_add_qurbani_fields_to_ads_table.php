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
            $table->boolean('is_qurbani')->default(false);
            $table->string('org_name')->nullable();
            $table->integer('total_hisse')->default(7);
            $table->integer('booked_hisse')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropColumn(['is_qurbani', 'org_name', 'total_hisse', 'booked_hisse']);
        });
    }
};
