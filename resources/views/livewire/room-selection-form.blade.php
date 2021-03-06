<div>
@if ($showRoomSelection)
    <div class="fixed top-0 left-0 right-0 bottom-0 bg-slate-600/75 overflow-auto">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white shadow-xl sm:rounded-lg p-5 m-10">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" wire:click="cancelRoomSelection" class="h-6 w-6 cursor-pointer float-right" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <h2 class="text-lg text-center">{{ __("Choisir une chambre") }}</h2>
                    <table class="mt-8 w-full table-auto border-collapse border border-gray-400">
                        @php
                            $thead_class="border-2 border-gray-400 bg-gray-100";
                            $tbody_class="border-2 border-gray-400 p-1"
                        @endphp

                        <thead>
                            <tr>
                                <td class="{{ $thead_class }}"><i>{{ __("Chambres") }}</i></td>
                                <td class="{{ $thead_class }}"><i>{{ __("Nombre de lits") }}</i></td>
                                <td class="{{ $thead_class }}"><i>{{ __("Occupants durant cette période") }}</i></td>
                            </tr>
                        </thead>
                        <tbody>
                            @if ( isset($visitorInReservation->room_id) )
                            <tr>
                                <td class="{{ $tbody_class }} bg-red-100 text-center hover:bg-red-300 cursor-pointer" colspan=3 wire:click="cancelRoom">{{__("Retirer de sa chambre")}}</td>
                            </tr>
                            @endif
                            @foreach ( $houses as $house )
                                <tr>
                                    <td class="{{ $thead_class }} text-center" colspan="3"><strong>{{ $house->name }}</strong></td>
                                </tr>

                                @foreach ( $house->rooms as $room )
                                    <tr>
                                        <td class="{{ $tbody_class }} hover:bg-slate-400 cursor-pointer" wire:click="selectRoom({{ $room }})">{{ $room->name }}</td>
                                        <td class="{{ $tbody_class }}">{{ $room->beds }}</td>
                                        <td class="{{ $tbody_class }}">
                                            @foreach ( $this->getRoomAvailability($room) as $resa )
                                                <div id="resa{{ $resa->id }}" class="card p-2 {{ $cursor ?? '' }} draggable relative border-l-4 {{ $resa->reservation->confirmed ? 'border-green-400' : 'border-yellow-400' }} {{ $resa->reservation->arrivaldate == $endDay ? 'bg-red-100' : ''}} {{ $resa->reservation->departuredate == $beginDay && ! $resa->reservation->nodeparturedate ? 'bg-yellow-100' : ''}}">
                                                    <div class="float-right text-sm italic">{{ $resa->reservation->arrivaldate == $endDay ? __("Arrive")." ".$lastDay : ''}}{{ $resa->reservation->departuredate == $beginDay && ! $resa->reservation->nodeparturedate ? __("Part")." ".$firstDay : ''}}</div>
                                                    <p>{{ $resa->visitor->full_name }}, {{ $resa->visitor->age}} {{ __("ans")}}</p>
                                                    <p class="text-xs italic">{{ $resa->reservation->arrival}} {{ __("jusqu'au")}} {{ $resa->reservation->departure  }}</p>

                                                </div>
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach

                            @endforeach
                        </tbody>
                    </table>
            </div>
        </div>
    </div>
@endif
</div>
