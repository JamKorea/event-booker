<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $event->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-3xl font-bold mb-4">{{ $event->title }}</h1>

                <p class="mb-2"><strong>Description:</strong> {{ $event->description ?? 'No description provided.' }}</p>
                <p class="mb-2"><strong>Date & Time:</strong> {{ \Carbon\Carbon::parse($event->datetime)->format('M d, Y h:i A') }}</p>
                <p class="mb-2"><strong>Capacity:</strong> {{ $event->capacity }}</p>
                <p class="mb-2"><strong>Bookings:</strong> {{ $event->bookings->count() }}</p>
                <p class="mb-4"><strong>Remaining Spots:</strong> {{ $event->capacity - $event->bookings->count() }}</p>

                <div class="flex space-x-4">
                    @if(auth()->user()->role === 'attendee')
                        @php
                            $alreadyBooked = $event->bookings->where('attendee_id', auth()->id())->count() > 0;
                        @endphp

                        @if($alreadyBooked)
                            <!-- Cancel booking -->
                            <form action="{{ route('bookings.destroy', $event->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                    Cancel Booking
                                </button>
                            </form>
                        @elseif($event->bookings->count() < $event->capacity)
                            <!-- Book event -->
                            <form action="{{ route('bookings.store', $event->id) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    Book This Event
                                </button>
                            </form>
                        @else
                            <p class="text-gray-500">This event is full. (Waitlist coming soon!)</p>
                        @endif
                    @endif

                    @if(auth()->user()->role === 'organiser')
                        <a href="{{ route('events.edit', $event->id) }}" 
                           class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            Edit Event
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>