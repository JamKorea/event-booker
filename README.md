# Event Booker (Laravel Project)

## About
This project is developed as part of a university assignment.  
The goal is to build an **event management system** with support for bookings and waitlists.  
It is implemented in **Laravel 12**, using **SQLite** for persistence and **Breeze** for authentication.

## Features (current progress)
- **Authentication**: Breeze (register, login, dashboard)
- **Database schema**:
  - `users` with `role` (organiser / attendee)
  - `events` (title, description, datetime, location, capacity)
  - `bookings` (attendee reservations; unique per event/user)
  - `waitlists` (entries when event is full; unique per event/user)
  - `holds` (time-limited claim for the next waitlisted user)
- **Eloquent relationships** defined across all models
- **Factories & Seeders**:
  - 2 organisers
  - 10 attendees
  - 15 events (3 tiny, 4 mid, 8 large)
  - Auto-generated bookings and waitlists  
    (first 3 events are fully booked with waitlisted users)

## Setup Instructions
```bash
# Copy environment file
cp .env.example .env

# Update .env:
#   DB_CONNECTION=sqlite
#   DB_FOREIGN_KEYS=true

# Create SQLite database file
touch database/database.sqlite

# Install dependencies
composer install
php artisan key:generate
php artisan migrate:fresh --seed

# Frontend build
npm install
npm run dev

# Run server
php artisan serve
```
Then open http://127.0.0.1:8000 in your browser.

Next Steps
	•	Event CRUD for organisers
	•	Booking / cancellation logic with capacity checks
	•	Waitlist join/leave and “My Waitlists” page
	•	Dashboard (Raw SQL report: capacity, bookings, remaining spots)
	•	Excellence Markers:
	    •  	Auto-email notification to waitlisted users when a spot opens
	    •   Time-limited Claim & Cascade system
