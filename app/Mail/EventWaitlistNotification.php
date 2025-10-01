<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventWaitlistNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $user;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\Event  $event
     * @param  \App\Models\User   $user
     */
    public function __construct(Event $event, User $user)
    {
        $this->event = $event;
        $this->user = $user;
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