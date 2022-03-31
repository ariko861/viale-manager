<x-guest-layout>
    <div class="pt-4 bg-gray-100">
        <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
            <div class="w-full mt-6 p-6 bg-white shadow-md overflow-hidden sm:rounded-lg">
                @foreach ($recap as $dayRecap)
                    @if ($dayRecap["departures"]->count() || $dayRecap["arrivals"]->count() )
                    <div>
                        <h3 class="border-4 p-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $dayRecap["date"] }}
                            <span class="italic text-sm">
                                {{ $dayRecap["presences"]->getTotalAmountOfVisitors() }} {{__("personnes restant sur place")}}
                                @if ($dayRecap["departures"]->count())
                                    {{__("et")}} {{ $dayRecap["departures"]->getTotalAmountOfVisitors() }} {{__("départs")}}
                                @endif
                                @if ($dayRecap["arrivals"]->count())
                                    {{__("et")}} {{ $dayRecap["arrivals"]->getTotalAmountOfVisitors() }} {{__("arrivées")}}
                                @endif

                            </span>

                        </h3>

                        @if ( $dayRecap["departures"]->count() )
                        <div class="p-4 border-2">
                            <h4 class="underline">{{ $dayRecap["departures"]->getTotalAmountOfVisitors() }} {{ __("Départs")}} :</h4>
                            @foreach ($dayRecap["departures"] as $departure)
                                @foreach ($departure->visitors as $visitor)
                                <p>
                                    <span class="font-semibold">{{ $visitor["full_name"] }}</span>
                                    @if ($visitor->pivot->room_id)
                                        <span class="italic text-sm">{{ $visitor->pivot->room->fullName() }}</span>
                                    @endif

                                </p>
                                @endforeach
                            @endforeach
                            </div>
                        @endif
                        @if ( $dayRecap["arrivals"]->count() )
                        <div class="p-4 border-2">
                            <h4 class="underline">{{ $dayRecap["arrivals"]->getTotalAmountOfVisitors() }} {{__("Arrivées")}} :</h4>
                            @foreach ($dayRecap["arrivals"] as $arrival)
                                @foreach ($arrival->visitors as $visitor)
                                <p>
                                    <span class="font-semibold">{{ $visitor["full_name"] }}</span>
                                    @if ($visitor->pivot->room_id)
                                        <span class="italic text-sm">{{ $visitor->pivot->room->fullName() }}</span>
                                    @endif

                                </p>
                                @endforeach
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</x-guest-layout>

