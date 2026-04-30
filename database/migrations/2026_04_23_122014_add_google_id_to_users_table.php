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
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_id')->nullable()->after('id');
            $table->string('password')->nullable()->change();
            // phone and city might be required in original registration, but google doesn't provide them. 
            // So they need to be nullable or we need to prompt the user after google login.
            $table->string('phone')->nullable()->change();
            $table->string('city')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('google_id');
            $table->string('password')->nullable(false)->change();
            $table->string('phone')->nullable(false)->change();
            $table->string('city')->nullable(false)->change();
        });
    }
};
