<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Waitlists') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">
                <h1 class="text-2xl font-bold mb-4">Events You Are Waitlisted For</h1>

                @if($waitlists->isEmpty())
                    <p>You are not currently on any waitlists.</p>
                @else
                    <ul class="space-y-2">
                        @foreach($waitlists as $waitlist)
                            <li class="flex justify-between items-center border-b pb-2">
                                <div>
                                    {{ $waitlist->event->title }}
                                    (Capacity: {{ $waitlist->event->capacity }},
                                    Current Bookings: {{ $waitlist->event->bookings->count() }})
                                </div>
                                <div>
                                    <form action="{{ route('waitlists.leave', $waitlist->event->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">
                                            Leave Waitlist
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>