<div class="grid grid-cols-4 gap-4 w-full">
    @can('room-choose')
    <div class="col-span-1">
        <ul id="newComersList" class="">
            <h2 class="text-lg font-bold mb-4">{{__("Futurs arrivants non placés")}}</h2>
            <li class="card p-2 h-16 border-l-4 relative {{ $onMoving && ! $resas->find($onMoving)? 'movable-receiver' : 'hidden' }}" wire:click="takeOutOfRoom">
                <p>{{__("Retirer de la chambre")}}</p>
            </li>
            @foreach ( $resas->sortBy(function($item, $key) { return $item->reservation->arrivaldate; }) as $resa )
                @if ($resa->reservation)
                <li id="resa{{ $resa->id }}" class="card p-2 cursor-pointer movable relative border-l-4 {{ $resa->reservation->confirmed ? 'border-green-400' : 'border-yellow-400' }} {{ $onMoving === $resa->id ? 'moving' : ''}}" wire:click="movingVisitor({{ $resa->id }})">
                    <p>{{ $resa->visitor->full_name }}, {{ $resa->visitor->age}} {{ __("ans")}}</p>
                    <p>{{ $resa->room ?? ''}}</p>
                    <p class="text-xs italic">{{ $resa->reservation->arrival}} {{ __("jusqu'au")}} {{ $resa->reservation->departure }}</p>
                    <div class="{{ $onMoving === $resa->id ? '' : 'hidden' }}">
                        <p>{{ $resa->reservation->remarks }}</p>
                        <p>{{ $resa->visitor->remarks }}</p>
                    </div>
                </li>
                @endif
            @endforeach
        </ul>
    </div>
    @endcan

    @can('room-choose')
        <div class="col-span-3">
        @php
            $cursor="cursor-pointer";
        @endphp
    @else
        <div class="col-span-4">
    @endcan

        <h2 class="text-lg text-center">{{ __("Occupation des chambres") }}</h2>

        <div class="text-center">
            {{ __("Du")}} <input type="date" wire:model="beginDay" /> {{ __("au")}} <input type="date" wire:model="endDay" min="{{ $beginDay }}"/>

        </div>

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
                @foreach ( $houses as $house )
                    <tr>
                        <td class="{{ $thead_class }} text-center" colspan="3"><strong>{{ $house->name }}</strong></td>
                    </tr>

                    @foreach ( $house->rooms as $room )
                        <tr>
                            <td class="{{ $tbody_class }} {{ $onMoving ? 'movable-receiver' : ''}}" wire:click="roomChanged({{$room->id}})">{{ $room->name }}</td>
                            <td class="{{ $tbody_class }}">{{ $room->beds }}</td>
                            <td id="room{{ $room->id }}" class="{{ $tbody_class }}">
                                @foreach ( $this->getRoomAvailability($room) as $resa )
                                    <div id="resa{{ $resa->id }}" class="card p-2 {{ $cursor ?? '' }} movable relative border-l-4 {{ $resa->reservation->confirmed ? 'border-green-400' : 'border-yellow-400' }} {{ $resa->reservation->arrivaldate == $endDay ? 'bg-red-100' : ''}} {{ $resa->reservation->departuredate == $beginDay && ! $resa->reservation->nodeparturedate ? 'bg-yellow-100' : ''}} {{ $onMoving === $resa->id ? 'moving' : ''}}" wire:click="movingVisitor({{ $resa->id }}, {{ $room->id }})">
                                        <div class="float-right text-sm italic">{{ $resa->reservation->arrivaldate == $endDay ? __("Arrive")." ".$lastDay : ''}} {{ $resa->reservation->departuredate == $beginDay && ! $resa->reservation->nodeparturedate ? __("Part")." ".$firstDay : ''}}</div>
                                        <p>{{ $resa->visitor->full_name }}, {{ $resa->visitor->age}} {{ __("ans")}}</p>
                                        <p class="text-xs italic">{{ $resa->reservation->arrival}} {{ __("jusqu'au")}} {{ $resa->reservation->departure }}</p>
                                        <div class="{{ $onMoving === $resa->id ? '' : 'hidden' }}">
                                            <p>{{ $resa->reservation->remarks }}</p>
                                            <p>{{ $resa->visitor->remarks }}</p>
                                        </div>

                                    </div>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach

                @endforeach
            </tbody>
        </table>
    </div>

    @can('room-choose')
    <script>

    $(document).ready(function(){
        const list = $("#newComersList");
        const fromTopInit = list.offset().top;
        $(window).scroll(function() {
            var fromTop = $(window).scrollTop();
            console.log(fromTop);
            if ( fromTop >= fromTopInit ) {
                list.css({ 'position': 'fixed', 'top': 0});
            } else {
                list.css('position', 'initial');
            }
        });
    });
    </script>

<!--  $( function() {

      function letDrag() {
        setTimeout(function(){
            $( ".draggable" ).draggable({
                revert: function(is_valid){
                    if (!is_valid) {
                        Livewire.emit('restoreDays');
                        return true;
                    }
                },
                cursor: "move",
                start: function( event, ui ){
                    Livewire.emit('movingVisitor', ui.helper[0].id);
                }
            });
        }, 500);
        $(".draggable").click( function(){
          $(this).children(".hidden-remarks").fadeToggle();
      });
      }


      letDrag();

    //To check when a date is changed
    Livewire.on('dateChanged', () => {
        letDrag();
    });

    $( ".dropzone" ).droppable({
      classes: {
        "ui-droppable-active": "ui-state-active",
        "ui-droppable-hover": "ui-state-hover"
      },
      drop: function( event, ui ) {

        Livewire.emit('roomChanged', { room: this.id, resa: ui.draggable[0].id });
        letDrag();

      }
    });
  } );

    </script>-->
    @endcan
</div>
