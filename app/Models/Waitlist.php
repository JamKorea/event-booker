<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Waitlist extends Model
{
    use HasFactory;

    public $timestamps = false; // Only created_at is used (handled in migration)

    protected $fillable = [
        'event_id',
        'user_id',
        'created_at',
        'claim_expires_at',
        'notified',
    ];

    /**
     * Attribute casting for columns.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'event_id'         => 'integer',
            'user_id'          => 'integer',
            'created_at'       => 'datetime',
            'claim_expires_at' => 'datetime',
            'notified'         => 'boolean',
        ];
    }

    // The event this waitlist entry belongs to
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // The user who joined the waitlist
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Check if this waitlist entry currently has an active claim
    public function isClaimActive(): bool
    {
        return $this->claim_expires_at !== null 
            && Carbon::now()->lt($this->claim_expires_at);
    }

    // Mark this waitlist entry as notified
    public function markAsNotified(): void
    {
        $this->update(['notified' => true]);
    }

    // Assign a new claim window (default: 120 minutes)
    public function assignClaimWindow(int $minutes = 120): void
    {
        $this->update([
            'claim_expires_at' => Carbon::now()->addMinutes($minutes),
        ]);
    }

    // Release the claim (clear claim_expires_at)
    public function releaseClaim(): void
    {
        $this->update([
            'claim_expires_at' => null,
        ]);
    }

    // Scope: get only expired claims
    public function scopeExpiredClaims($query)
    {
        return $query->whereNotNull('claim_expires_at')
                     ->where('claim_expires_at', '<', Carbon::now());
    }
}