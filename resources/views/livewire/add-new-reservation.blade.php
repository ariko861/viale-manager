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
 <form wire:submit.prevent="save" autocomplete="off">
    @csrf
    <div class="w-full px-8 grid grid-cols-3 gap-4">
        <label class="col-span-1">{{ __("Date d'arrivée") }}</label>
        <input class="col-span-2" type="date" wire:model="reservation.arrivaldate" wire:change="setMinDate($event.target.value)">
        @error('reservation.arrivaldate') <span class="col-span-1"></span><span class="text-red-600 col-span-2">{{ $message }}</span> @enderror
        <label class="col-span-1">{{ __("Date de départ") }}</label>
        <input class="col-span-2 disabled:opacity-50" type="date" wire:model="reservation.departuredate" min="{{ $mindeparturedate }}" @if ( $reservation['nodeparturedate'] ) disabled @endif>
        @error('reservation.departuredate') <span class="col-span-1"></span><span class="text-red-600 col-span-2">{{ $message }}</span> @enderror
        <label class="col-span-1">{{ __("Ne connais pas sa date de départ") }}</label>
        <input class="col-span-2" type="checkbox" wire:model="reservation.nodeparturedate">
        @error('reservation.nodeparturedate') <span class="col-span-1"></span><span class="text-red-600 col-span-2">{{ $message }}</span> @enderror
        <label class="col-span-1">{{ __("Personne de contact") }}</label>
        <div class="col-span-2">
            <livewire:visitor-search visitorType="contactPerson">
        </div>
        @error('contactPerson') <span class="col-span-1"></span><span class="text-red-600 col-span-2">{{ $message }}</span> @enderror

        <!-- Bouton pour ajouter des visiteurs -->
        <div class="col-span-full">
            <button class="btn w-full" wire:click.prevent="addNewOtherVisitor">{{ __("Ajouter un autre visiteur") }}</button>
        </div>
<!--    Pour chaque visiteur ajouté à la réservation -->
        @foreach ( $otherVisitorsArray as $key => $otherVisitor )
            <div class="card col-span-full grid grid-cols-3 gap-4">
                <label class="col-span-1">
                    <svg wire:click="removeOtherVisitor({{$key}})" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 float-left stroke-red-600 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    {{ __("Visiteur") }}
                </label>
                <div class="col-span-2">
                    <livewire:visitor-search :key="$key" :visitorKey="$key" visitorType="otherVisitor" >
                </div>
            </div>
        @endforeach

<!--         Bouton de validation -->
        <div class="col-span-full text-center">
            <button class="btn btn-submit" type="submit">{{ __('Valider') }}</button>
        </div>
    </div>
 </form>
 @if ( $showUserForm )
    <livewire:new-user-form >
 @endif
</div>
@endif

