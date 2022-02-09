<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                {{-- <x-jet-welcome /> --}}
                <livewire:reservations-calendar before-calendar-view="/components/calendar-buttons" event-view="/components/calendar-event" week-starts-at="1">

            </div>
        </div>
    </div>

</x-app-layout>
