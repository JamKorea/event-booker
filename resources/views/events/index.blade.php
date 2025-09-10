<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Events') }}
            </h2>
            @if(auth()->user()->role === 'organiser')
                <a href="{{ route('events.create') }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    + Create Event
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">
                <h1 class="text-2xl font-bold mb-4">Event List</h1>

                @if($events->isEmpty())
                    <p>No events available.</p>
                @else
                    <ul class="space-y-2">
                        @foreach($events as $event)
                            <li class="flex justify-between items-center border-b pb-2">
                                <div>
                                    {{ $event->title }} (Capacity: {{ $event->capacity }})
                                </div>
                                <div class="flex space-x-2">
                                    <!-- Everyone sees View -->
                                    <a href="{{ route('events.show', $event) }}" class="text-blue-600">View</a>

                                    @if(auth()->user()->role === 'organiser')
                                        <!-- Only organisers see Edit/Delete -->
                                        <a href="{{ route('events.edit', $event) }}" class="text-green-600">Edit</a>
                                        <form action="{{ route('events.destroy', $event) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600"
                                                    onclick="return confirm('Are you sure you want to delete this event?')">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>