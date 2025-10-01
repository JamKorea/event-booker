<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Organiser Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-4">Event Reports</h1>

                {{-- Check if stats exist --}}
                @if(empty($stats) || count($stats) === 0)
                    <p class="text-gray-600">No events found.</p>
                @else
                    {{-- Raw SQL based event stats --}}
                    <table class="w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100 text-left">
                                <th class="border px-4 py-2">Event</th>
                                <th class="border px-4 py-2">Capacity</th>
                                <th class="border px-4 py-2">Bookings</th>
                                <th class="border px-4 py-2">Remaining</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats as $row)
                                <tr>
                                    <td class="border px-4 py-2">{{ $row->title }}</td>
                                    <td class="border px-4 py-2 text-center">{{ $row->capacity }}</td>
                                    <td class="border px-4 py-2 text-center">{{ $row->bookings_count }}</td>
                                    <td class="border px-4 py-2 text-center">{{ $row->remaining }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>