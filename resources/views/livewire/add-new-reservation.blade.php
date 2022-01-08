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
 <form method="POST" action="/reservations">
    @csrf
    <div class="w-full px-8 grid grid-cols-3 gap-4">
        <label class="col-span-1">{{ __("Date d'arrivée") }}</label>
        <input class="col-span-2" type="date" name="arrivaldate" required min="{{ $today }}" wire:change="setMinDate($event.target.value)">
        <label class="col-span-1">{{ __("Date de départ") }}</label>
        <input class="col-span-2" type="date" name="departuredate" required min="{{ $mindeparturedate }}">
        <label class="col-span-1">{{ __("Personne de contact") }}</label>
        <div class="col-span-2"><input class="w-full static" type="text" name="contactperson" required wire:keyup="searchVisitor($event.target.value)">
            <ul class="col-span-2 relative bg-white left-0 right-0">
            @foreach ($visitorsArray as $visitor)
                <li>{{ $visitor['surname'] }} {{ $visitor['name']}}</li>
            @endforeach
            @if ( $displayAddVisitorButton )
                <li>Pas de resultat !</li>
                <li><button class="rounded-full p-2 border-4" wire:click.prevent="$toggle('showUserForm')">{{ __("Ajouter un nouveau visiteur") }}</button></li>
            @endif
            </ul>
        </div>
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
