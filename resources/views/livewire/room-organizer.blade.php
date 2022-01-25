<div class="grid grid-cols-4 gap-4 w-full">
    <div class="col-span-1">
        <ul>
            <h2 class="text-lg font-bold mb-4">{{__("Futurs arrivants")}}</h2>
            @foreach ( $resas as $resa )
                <li id="resa{{ $resa->id }}" class="card p-2 cursor-move draggable relative">
                    <p>{{ $resa->visitor->full_name }}, {{ $resa->visitor->age}} {{ __("ans")}}</p>
                    <p class="text-xs italic">{{ $resa->reservation->arrival}} {{ __("jusqu'au")}} {{ $resa->reservation->departure }}</p>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="col-span-3">

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
                            <td class="{{ $tbody_class }} hover:bg-slate-400 cursor-pointer">{{ $room->name }}</td>
                            <td class="{{ $tbody_class }}">{{ $room->beds }}</td>
                            <td id="room{{ $room->id }}"class="{{ $tbody_class }} dropzone ui-widget-header">
                                @foreach ( $this->getRoomAvailability($room) as $resa )
                                    <div id="resa{{ $resa->id }}" class="card p-2 cursor-move draggable relative">
                                        <p>{{ $resa->visitor->full_name }}, {{ $resa->visitor->age}} {{ __("ans")}}</p>
                                        <p class="text-xs italic">{{ $resa->reservation->arrival}} {{ __("jusqu'au")}} {{ $resa->reservation->departure }}</p>

                                    </div>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach

                @endforeach
            </tbody>
        </table>
    </div>


      <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
    <script>

  $( function() {

      function letDrag() {
//         $(".draggable").off();
        $( ".draggable" ).draggable({
            revert: "invalid",
            cursor: "move",
            start: function( event, ui ){
                Livewire.emit('movingVisitor', ui.helper[0].id);
            }
        });
      }

      letDrag();

    $( ".dropzone" ).droppable({
//       classes: {
//         "ui-droppable-active": "ui-state-active",
//         "ui-droppable-hover": "ui-state-hover"
//       },
      drop: function( event, ui ) {

        Livewire.emit('roomChanged', { room: this.id, resa: ui.draggable[0].id });
        //letDrag();

      }
    });
  } );

    </script>
</div>