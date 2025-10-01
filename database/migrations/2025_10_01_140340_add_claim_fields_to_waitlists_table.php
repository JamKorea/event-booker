<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    // Add claim fields to waitlists table for time-limited claim flow
    public function up(): void
    {
        Schema::table('waitlists', function (Blueprint $table) {
            // until when this user's claim is valid
            $table->timestamp('claim_expires_at')->nullable()->after('created_at');

            // email notification sent flag
            $table->boolean('notified')->default(false)->after('claim_expires_at');

            // helpful indexes for cascade/lookup
            $table->index(['event_id', 'created_at']);
            $table->index('claim_expires_at');
        });
    }

    public function down(): void
    {
        Schema::table('waitlists', function (Blueprint $table) {
            // drop indexes by name
            $table->dropIndex('waitlists_event_id_created_at_index');
            $table->dropIndex('waitlists_claim_expires_at_index');

            // drop added columns
            $table->dropColumn(['claim_expires_at', 'notified']);
        });
    }
};