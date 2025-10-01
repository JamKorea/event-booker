<?php

namespace App\Jobs;

use App\Mail\EventWaitlistNotification;
use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ProcessExpiredClaims implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Handle expired claims and cascade to next users
    public function handle(): void
    {
        $now = now();

        // 1) Events with free spots (capacity > current bookings)
        $events = Event::withCount('bookings')->get()->filter(function ($e) {
            return $e->capacity > $e->bookings_count;
        });

        foreach ($events as $event) {

            // 2) If there is an active claim (not expired), do nothing (respect current priority)
            $activeClaim = $event->waitlists()
                ->whereNotNull('claim_expires_at')
                ->where('claim_expires_at', '>', $now)
                ->orderBy('created_at')
                ->first();

            if ($activeClaim) {
                continue; // a user still has priority
            }

            // 3) Release any expired claims (cleanup so we don't re-process them)
            $expiredClaims = $event->waitlists()
                ->whereNotNull('claim_expires_at')
                ->where('claim_expires_at', '<=', $now)
                ->get();

            foreach ($expiredClaims as $wl) {
                $wl->releaseClaim(); // sets claim_expires_at = null, keeps notified = true
            }

            // 4) How many free spots are there right now?
            $free = $event->capacity - $event->bookings()->count();
            if ($free <= 0) {
                continue;
            }

            // 5) For each free spot, notify the next user who has not been notified yet
            while ($free > 0) {
                $next = $event->waitlists()
                    ->whereNull('claim_expires_at')   // not currently holding a claim
                    ->where('notified', false)        // not notified before
                    ->orderBy('created_at')           // FIFO
                    ->first();

                if (!$next) {
                    break; // nobody left to notify
                }

                // Assign a 2-hour claim window and mark as notified
                $next->assignClaimWindow(120);
                $next->markAsNotified();

                // Send email with the claim expiry timestamp
                Mail::to($next->user->email)->send(
                    new EventWaitlistNotification($event, $next->user, $next->claim_expires_at)
                );

                $free--;
            }
        }
    }
}