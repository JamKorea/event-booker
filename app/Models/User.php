<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // organiser or attendee
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ======================================
    // Relationships
    // ======================================

    /**
     * Events organised (owned) by this user.
     */
    public function organisedEvents()
    {
        return $this->hasMany(Event::class, 'organiser_id');
    }

    /**
     * Bookings this user has made as an attendee.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'attendee_id');
    }

    /**
     * Waitlist entries this user has joined.
     */
    public function waitlists()
    {
        return $this->hasMany(Waitlist::class, 'user_id');
    }

    /**
     * Holds (claim windows) currently assigned to this user.
     */
    public function holds()
    {
        return $this->hasMany(Hold::class, 'user_id');
    }
}