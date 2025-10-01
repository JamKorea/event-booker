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
                    {{-- Attendee actions --}}
                    @if(auth()->user()->role === 'attendee')
                        @php
                            $alreadyBooked = $event->bookings->where('attendee_id', auth()->id())->count() > 0;
                            $onWaitlist = $event->waitlists->where('user_id', auth()->id())->count() > 0;
                            $isFull = $event->bookings->count() >= $event->capacity;
                        @endphp

                        @if($alreadyBooked)
                            {{-- Cancel booking --}}
                            <form action="{{ route('bookings.destroy', $event->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                    Cancel Booking
                                </button>
                            </form>
                        @elseif(!$isFull)
                            {{-- Book event --}}
                            <form action="{{ route('bookings.store', $event->id) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    Book This Event
                                </button>
                            </form>
                        @else
                            {{-- Waitlist actions when event is full --}}
                            @if($onWaitlist)
                                <form action="{{ route('waitlists.leave', $event->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">
                                        Leave Waitlist
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('waitlists.join', $event->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                                        Join Waitlist
                                    </button>
                                </form>
                            @endif
                        @endif
                    @endif

                    {{-- Organiser actions --}}
                    @if(auth()->user()->role === 'organiser')
                        <a href="{{ route('events.edit', $event->id) }}" 
                           class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            Edit Event
                        </a>
                    @endif
                </div>

                {{-- Organiser: Waitlist table --}}
                @if(auth()->user()->role === 'organiser')
                    <div class="mt-8">
                        <h2 class="text-xl font-semibold mb-2">Waitlist</h2>

                        @if($event->waitlists->isEmpty())
                            <p class="text-gray-600">No one is on the waitlist for this event.</p>
                        @else
                            <table class="w-full border-collapse border border-gray-300">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="border border-gray-300 px-4 py-2 text-left">#</th>
                                        <th class="border border-gray-300 px-4 py-2 text-left">Name</th>
                                        <th class="border border-gray-300 px-4 py-2 text-left">Email</th>
                                        <th class="border border-gray-300 px-4 py-2 text-left">Joined At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($event->waitlists as $index => $waitlist)
                                        <tr>
                                            <td class="border border-gray-300 px-4 py-2">{{ $index + 1 }}</td>
                                            <td class="border border-gray-300 px-4 py-2">{{ $waitlist->user->name }}</td>
                                            <td class="border border-gray-300 px-4 py-2">{{ $waitlist->user->email }}</td>
                                            <td class="border border-gray-300 px-4 py-2">{{ $waitlist->created_at->format('M d, Y h:i A') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>