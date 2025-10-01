<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class EventWaitlistNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $user;
    public $claimExpiresAt;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\Event  $event  The event the spot opened up for
     * @param  \App\Models\User   $user   The user being notified
     * @param  \Carbon\Carbon|null $claimExpiresAt  Optional expiry time for claim window
     */
    public function __construct(Event $event, User $user, ?Carbon $claimExpiresAt = null)
    {
        $this->event = $event;
        $this->user = $user;
        $this->claimExpiresAt = $claimExpiresAt;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('A spot is now available for ' . $this->event->title)
                    ->view('emails.waitlist_notification');
    }
}