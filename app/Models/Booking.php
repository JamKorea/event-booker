<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'attendee_id',
    ];

    /**
     * Attribute casting.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'event_id'    => 'integer',
            'attendee_id' => 'integer',
        ];
    }

    // ======================================
    // Relationships
    // ======================================

    /**
     * The event this booking belongs to.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * The attendee (user) who made this booking.
     */
    public function attendee()
    {
        return $this->belongsTo(User::class, 'attendee_id');
    }
}