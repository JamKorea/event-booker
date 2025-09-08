<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hold extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'token',
        'expires_at',
    ];

    /**
     * Attribute casting.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'event_id'   => 'integer',
            'user_id'    => 'integer',
            'expires_at' => 'datetime',
        ];
    }

    // ======================================
    // Relationships
    // ======================================

    /**
     * The event this hold is attached to.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * The user who currently holds the claim.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}