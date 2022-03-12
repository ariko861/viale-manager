<div class="text-center mt-4">

    <p class="my-6 text-lg font-bold">{{ $displayDate->translatedFormat('l d F Y') }}</p>
    <p class="my-6 text-lg font-bold">
        <svg xmlns="http://www.w3.org/2000/svg" wire:click="previousDay" class="inline h-6 w-6 mx-4 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z" />
        </svg>
        <input type="date" wire:model="formattedDisplayDate" wire:change="updateDate">
        <svg xmlns="http://www.w3.org/2000/svg" wire:click="nextDay" class="inline h-6 w-6 mx-4 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </p>
    @if ( $today )
        <p class="my-4"><button wire:click="backToToday">{{__("Revenir à aujourd'hui")}}</button></p>
    @endif
    @if ( $arrivals->count() )
        <button class="mx-4" wire:click="displayDayEvents('arrivals')">{{ $arrivals->getTotalAmountOfVisitors() }} {{__("arrivées ce jour")}}</button>
    @else
        <span class="mx-4"><nobr>{{__("Pas d'arrivée ce jour")}}</nobr></span>
    @endif

    @if ( $presences->count() )
        <button class="mx-4" wire:click="displayDayEvents('presences')">{{ $presences->getTotalAmountOfVisitors() }} {{__("personnes présentes ce jour")}}</button>
    @endif
    @if ( $departures->count() )
        <button class="mx-4" wire:click="displayDayEvents('departures')">{{ $departures->getTotalAmountOfVisitors() }} {{__("départs ce jour")}}</button>
    @else
        <span class="mx-4"><nobr>{{ __("Pas de départ ce jour") }}</nobr></span>
    @endif

</div>
