<div class="text-center mt-4">
    @if ( $arrivals->count() )
        <button class="mx-4" wire:click="$emit('displayToday', 'arrivals')">{{ $arrivals->getTotalAmountOfVisitors() }} {{__("arrivées aujourd'hui")}}</button>
    @else
        <span class="mx-4"><nobr>{{__("Pas d'arrivée aujourd'hui")}}</nobr></span>
    @endif

    @if ( $departures->count() )
        <button class="mx-4" wire:click="$emit('displayToday', 'departures')">{{ $departures->getTotalAmountOfVisitors() }} {{__("départs aujourd'hui")}}</button>
    @else
        <span class="mx-4"><nobr>{{ __("Pas de départ aujourd'hui") }}</nobr></span>
    @endif

</div>
