<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Waitlist extends Model
{
    use HasFactory;

    public $timestamps = false; // created_at only, provided by migration

    protected $fillable = [
        'event_id',
        'user_id',
        'created_at',
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
            'created_at' => 'datetime',
        ];
    }

    // ======================================
    // Relationships
    // ======================================

    /**
     * The event this waitlist entry belongs to.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * The user who joined the waitlist.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}