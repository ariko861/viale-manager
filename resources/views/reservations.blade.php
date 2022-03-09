<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Réservations') }}
        </h2>
            <livewire:arrivals-and-departures-today >
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-5">
                    @can('reservation-create')
                        <livewire:reservation.add-new-reservation >
                        <livewire:reservation.super-quick-reservation >
                    @endcan
                    @canany(['reservation-create', 'reservation-edit'])
                        <livewire:reservation.create-link >
                    @endcanany
                    @can('visitor-create')
                        <livewire:visitor.new-visitor-form >
                    @endcan
                    <livewire:reservation.reservations-list :reservation_id='$reservation_id'>


            </div>
        </div>
    </div>

</x-app-layout>
