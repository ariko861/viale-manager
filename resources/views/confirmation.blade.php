<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Confirmation de réservation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-5">
                <livewire:reservation.confirmation-form :reservation="$reservation" :link="$link">
                <livewire:reservation.success-confirm >
            </div>
        </div>
    </div>

</x-guest-layout>
