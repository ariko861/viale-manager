<div class="text-center">
    @if ( $arrivals->count() )
        <button class="mx-4" wire:click="$emit('displayToday', 'arrivals')">{{ $arrivals->getTotalAmountOfVisitors() }} {{__("arrivées aujourd'hui")}}</button>
    @else
        <span class="mx-4">{{__("Pas d'arrivée aujourd'hui")}}</span>
    @endif

    @if ( $departures->count() )
        <button class="mx-4" wire:click="$emit('displayToday', 'departures')">{{ $departures->getTotalAmountOfVisitors() }} {{__("départs aujourd'hui")}}</button>
    @else
        <span class="mx-4">{{ __("Pas de départ aujourd'hui") }}</span>
    @endif

</div>
