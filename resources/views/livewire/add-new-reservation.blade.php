@unless ( $showReservationForm )
    <div class="w-full border-4 p-5">
        <svg xmlns="http://www.w3.org/2000/svg" class="float-left h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <button class="rounded-full" wire:click="$toggle('showReservationForm')">{{ $message }}</button>
    </div>
@endunless

@if ($showReservationForm )
<div>
 <h3 class="text-center uppercase text-lg m-4">{{ __("Ajouter une nouvelle réservation") }}</h3>
 <form method="POST" action="/reservations" autocomplete="off">
    @csrf
    <div class="w-full px-8 grid grid-cols-3 gap-4">
        <label class="col-span-1">{{ __("Date d'arrivée") }}</label>
        <input class="col-span-2" type="date" wire:model="reservation.arrivaldate" required min="{{ $today }}" wire:change="setMinDate($event.target.value)">
        <label class="col-span-1">{{ __("Date de départ") }}</label>
        <input class="col-span-2 disabled:opacity-50" type="date" wire:model="reservation.departuredate" required min="{{ $mindeparturedate }}" @if ( $reservation['nodeparturedate'] ) disabled @endif>
        <label class="col-span-1">{{ __("Ne connais pas sa date de départ") }}</label>
        <input class="col-span-2" type="checkbox" wire:model="reservation.nodeparturedate">
        <label class="col-span-1">{{ __("Personne de contact") }}</label>
        <div class="col-span-2">
            <livewire:visitor-search >
        </div>
        <!-- Bouton pour ajouter des visiteurs -->
        <div class="col-span-full">
            <button class="btn w-full" wire:click="addNewOtherVisitor">{{ __("Ajouter un autre visiteur") }}</button>
        </div>
<!--    Pour chaque visiteur ajouté à la réservation -->
        @foreach ( $otherVisitorsArray as $visitor )
            <div class="card col-span-full">
                <label class="col-span-1">{{ __("Visiteur") }}</label>
                <div class="col-span-2">

                </div>
            </div>
        @endforeach
<!--         Checkbox pour autoriser l'ajout de visiteurs par la personne de contact -->
        <label class="col-span-1">{{ __("Autoriser la personne de contact à ajouter d'autres visiteurs") }}</label>
        <input class="col-span-2" type="checkbox" wire:model="reservation.otherVisitorsAuthorized">
<!--         Champ caché pour stocker l'ID de la personne de contact -->
        <input wire:model="reservation.contactPerson" hidden>
<!--         Bouton de validation -->
        <div class="col-span-full text-center">
            <button class="rounded-full p-2 border-4" type="submit">{{ __('Valider') }}</button>
        </div>
    </div>
 </form>
 @if ( $showUserForm )
    <livewire:new-user-form >
 @endif
</div>
@endif

