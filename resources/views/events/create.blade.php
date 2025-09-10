<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Event') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-4">New Event</h1>

                <!-- Event creation form -->
                <form method="POST" action="{{ route('events.store') }}">
                    @csrf

                    <!-- Title -->
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="title" id="title" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" 
                               required>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3" 
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                    </div>

                    <!-- DateTime -->
                    <div class="mb-4">
                        <label for="datetime" class="block text-sm font-medium text-gray-700">Date & Time</label>
                        <input type="datetime-local" name="datetime" id="datetime" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" 
                               required>
                    </div>

                    <!-- Capacity -->
                    <div class="mb-4">
                        <label for="capacity" class="block text-sm font-medium text-gray-700">Capacity</label>
                        <input type="number" name="capacity" id="capacity" min="1" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" 
                               required>
                    </div>

                    <!-- Submit -->
                    <div class="mt-6">
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Create Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>