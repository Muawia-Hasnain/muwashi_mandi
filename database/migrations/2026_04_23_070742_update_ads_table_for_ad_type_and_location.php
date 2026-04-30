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
            $table->enum('ad_type', ['for_sale', 'qurbani', 'ijtamai_hissa'])->default('for_sale')->after('animal_type');
            $table->foreignId('district_id')->nullable()->constrained()->nullOnDelete()->after('age_info');
            $table->foreignId('tehsil_id')->nullable()->constrained()->nullOnDelete()->after('district_id');
            $table->string('village')->nullable()->after('tehsil_id');
            
            // Make old city and area nullable
            $table->string('city')->nullable()->change();
            $table->string('area')->nullable()->change();
        });
        
        // Migrate existing data (if any)
        \Illuminate\Support\Facades\DB::table('ads')->where('is_qurbani', true)->update(['ad_type' => 'ijtamai_hissa']);
        
        Schema::table('ads', function (Blueprint $table) {
            $table->dropColumn('is_qurbani');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->boolean('is_qurbani')->default(false);
            $table->dropForeign(['district_id']);
            $table->dropForeign(['tehsil_id']);
            $table->dropColumn(['ad_type', 'district_id', 'tehsil_id', 'village']);
            // Reverting city/area to non-nullable might fail if there's null data, so we leave them nullable.
        });
    }
};
