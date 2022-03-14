<div>
    @if ($reservation)
    <div class="fixed top-0 left-0 right-0 bottom-0 bg-slate-600/75 overflow-auto z-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white shadow-xl sm:rounded-lg p-5 m-10">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" wire:click="hideReservation" class="h-6 w-6 cursor-pointer float-right" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>

            <h1 class="my-6">{{__("Réservation")}} {{$reservation->id}}</h1>
            <p class="mx-2"><strong>{{__("Jour d'arrivée")}} : </strong>{{$reservation->arrival}}</p>
            <p class="mx-2"><strong>{{__("Jour de départ")}} : </strong>{{$reservation->departure}}</p>

            <h3 class="mt-6 mb-2">{{__("Créer une période d'absence pour la ou les personnes présentes dans cette réservation")}} :</h3>
            <p class="my-4">{{__("Absent·e·s du")}} <input type="date" min="{{ $reservation->arrivaldate }}" max="{{ $reservation->nodeparturedate ? '' : $reservation->departuredate }}" wire:change="startChanged" wire:model="splitStart"/> {{__("au")}} <input type="date" wire:model="splitEnd" min="{{$splitStart}}" max="{{ $reservation->nodeparturedate ? '' : $reservation->departuredate }}"/></p>

            <button class="my-6 px-4 btn-submit" wire:click="submit">{{__("Valider")}}</button>

        </div>
    </div>
    @endif
</div>
