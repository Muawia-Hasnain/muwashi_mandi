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
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 12, 2);
            $table->enum('animal_type', ['cow', 'goat', 'buffalo', 'bull', 'sheep', 'other']);
            $table->string('breed')->nullable();
            $table->string('age_info')->nullable();
            $table->string('city');
            $table->string('area')->nullable();
            $table->enum('status', ['payment_pending', 'pending', 'approved', 'rejected', 'sold', 'expired'])->default('pending');
            $table->string('rejection_reason')->nullable();
            $table->bigInteger('views_count')->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamp('featured_expires_at')->nullable();
            $table->boolean('is_boosted')->default(false);
            $table->timestamp('boost_expires_at')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index(['animal_type', 'status', 'city']);
            $table->index('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};
