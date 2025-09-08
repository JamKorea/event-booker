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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->foreignId('attendee_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->timestamps();
        
            // Prevent a user from booking the same event multiple times
            $table->unique(['event_id', 'attendee_id']);
            $table->index('event_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
