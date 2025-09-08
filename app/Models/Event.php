<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'organiser_id',
        'title',
        'description',
        'datetime',
        'location',
        'capacity',
    ];

    /**
     * Attribute casting.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'datetime' => 'datetime',
            'capacity' => 'integer',
        ];
    }

    // ======================================
    // Relationships
    // ======================================

    /**
     * The organiser (owner) of this event.
     */
    public function organiser()
    {
        return $this->belongsTo(User::class, 'organiser_id');
    }

    /**
     * Bookings made for this event.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Waitlist entries for this event.
     */
    public function waitlists()
    {
        return $this->hasMany(Waitlist::class);
    }

    /**
     * The active hold for this event (at most one).
     */
    public function hold()
    {
        return $this->hasOne(Hold::class);
    }
}