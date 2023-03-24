<div>

    <br>
    <div class="option-field">
        <h2 class="mt-2 mb-4 text-center">{{__("Recherche de réservations")}} :</h2>
        <p class="my-8 float-right">{{__("N'afficher que les réservations qui ont des places de voiture disponible")}} <input type="checkbox" wire:model="displayOnlyWithPlaces" wire:click="filterReservations()"></p>
        <p class="my-8">{{__("Afficher les")}} <button wire:click="getReservationsHere">{{__("Personnes présentes")}}</button></p>
        <p class="my-8">{{__("Afficher les")}} <input type="number" step=1 wire:model="amountDisplayedReservation"> {{__("prochaines arrivées")}} : <button wire:click="getReservationsComing">{{__("Chercher")}}</button></p>
        <p class="my-8"><strong>{{__("Ou rechercher par nom d'un visiteur")}} :</strong> <input type="text" wire:keyup="getReservationsByVisitorName" wire:model="visitorSearch"></p>
        <p class="my-8">{{__("Afficher les")}} <button wire:click="getLastConfirmations">{{__("dernières confirmations")}}</button></p>
        <p class="text-center text-sm mt-4 mb-6"><span wire:click="$toggle('advancedSearch')" class="cursor-pointer">{{__("Recherche avancée")}}<x-buttons.arrow-chevron :up="$advancedSearch" size=4/></span></p>
        @if ($advancedSearch)
        <div>
            <p class="my-8">{{__("Ou sélectionner les personnes présentes entre ces dates")}} : {{__("Du")}} <input type="date" wire:model="presenceBeginDate"> {{__("Au")}} <input wire:change="getPresenceBetweenDates" type="date" wire:model="presenceEndDate" min="{{$presenceBeginDate}}"></p>
            <p class="my-8">{{__("Ou sélectionner par date d'")}}<strong>{{__("arrivée")}}</strong> : {{__("Du")}} <input type="date" wire:model="beginDate"> {{__("Au")}} <input wire:change="getReservationsWhereArrivalInBetween" type="date" wire:model="endDate" min="{{$beginDate}}"></p>
            <p class="my-8">{{__("Ou sélectionner par date de")}} <strong>{{__("départ")}}</strong> : {{__("Du")}} <input type="date" wire:model="beginDateForDeparture"> {{__("Au")}} <input wire:change="getReservationsWhereDepartureInBetween" type="date" wire:model="endDateForDeparture" min="{{$beginDateForDeparture}}"></p>
            <p class="my-8">{{__("Afficher les")}} <button wire:click="getWaitingConfirmation">{{__("confirmations en attente")}}</button></p>

        </div>
        @endif
    </div>

    <br>
    <h2 id="reservationsTitle" class="text-center mt-8 text-lg">{{ $listTitle }}</h2>
    @foreach ( $reservations as $key => $reservation )
        <livewire:reservation.reservation-card :wire:key="'reservation'.$reservation->id" :reservation="$reservation" :rKey="$key">
    @endforeach

    <livewire:room-selection-form >
    <livewire:reservation.split-reservation >
    <livewire:reservation.reservation-recap />


    <script>
        $(document).ready(function(){
            Livewire.on('scrollToReservationList', () => {
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#reservationsTitle").offset().top
                }, 500);
            });
        });
    </script>

</div>
