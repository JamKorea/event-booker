<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Spot Available - {{ $event->title }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6;">
    <h2>Hello {{ $user->name }},</h2>

    <p>
        A spot has just opened up for the event:
        <strong>{{ $event->title }}</strong>.
    </p>

    <p>
        <strong>Date & Time:</strong> {{ \Carbon\Carbon::parse($event->datetime)->format('M d, Y h:i A') }} <br>
        <strong>Location:</strong> {{ $event->location ?? 'TBA' }}
    </p>

    @if($claimExpiresAt)
        <p>
            Please note: You have until 
            <strong>{{ $claimExpiresAt->format('M d, Y h:i A') }}</strong> 
            to claim this spot. After that, your claim will expire and the next person on the waitlist will be notified.
        </p>
    @endif

    <p style="margin-top: 20px;">
        <a href="{{ url('/events/' . $event->id) }}" 
           style="display:inline-block;padding:10px 15px;background-color:#2563eb;color:#fff;text-decoration:none;border-radius:5px;">
            View Event & Claim Spot
        </a>
    </p>

    <p style="margin-top: 30px;">Thanks,<br>The Event Booker Team</p>
</body>
</html>