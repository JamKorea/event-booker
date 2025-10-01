<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Waitlists') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-4">Events You Are Waitlisted For</h1>

                {{-- Check if user has any waitlists --}}
                @if($waitlists->isEmpty())
                    <p class="text-gray-600">You are not on any waitlists at the moment.</p>
                @else
                    <table class="w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 px-4 py-2 text-left">Event</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Capacity</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Bookings</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Waitlist Position</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($waitlists as $waitlist)
                                <tr>
                                    <td class="border border-gray-300 px-4 py-2">
                                        {{ $waitlist->event->title }}
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        {{ $waitlist->event->capacity }}
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        {{ $waitlist->event->bookings_count }}
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        #{{ $waitlist->position }} of {{ $waitlist->event->waitlists_count }}
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        {{-- Leave waitlist --}}
                                        <form action="{{ route('waitlists.leave', $waitlist->event_id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                                Leave
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>