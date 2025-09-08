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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organiser_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->string('title', 100);
            $table->text('description')->nullable();
            $table->dateTime('datetime');
            $table->string('location', 255);
            $table->unsignedInteger('capacity'); // 1~1000
            $table->timestamps();
        
            $table->index('organiser_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
