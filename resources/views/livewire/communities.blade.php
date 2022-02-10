<div class="grid grid-cols-4 gap-4 w-full">

    @can('community-choose')
        <div class="col-span-full">
            <p class="text-center text-lg">{{__("Afficher les arrivants entre le")}} {{ $beginDay }} <input type="date" wire:model="beginDate" > {{__("et le")}} {{ $endDay }} <input type="date" min="{{ $beginDate }}" wire:change="getResas" wire:model="endDate"></p>
        </div>
        <div class="col-span-full my-4 text-center">
            <button wire:click="prevWeek">{{__("Semaine précédente")}}</button><button wire:click="nextWeek">{{__("Semaine suivante")}}</button>
        </div>
        <div class="col-span-full my-4 text-center">
            <button wire:click="emptyCommunities" class="btn-warning">{{__("Vider les communautés")}}</button>
        </div>
        <div class="col-span-1">
            <ul>
                <h2 class="text-lg font-bold mb-1">{{__("Futurs arrivants non placés")}}</h2>
                <p class="mb-4">{{ $resas->count() }} {{__("Personnes prévues pour cette période")}}</p>
                @foreach ( $resas as $resa )
                    @if ($resa->reservation)
                    <li id="resa{{ $resa->id }}" class="card p-2 cursor-move draggable relative border-l-4 {{ $resa->reservation->confirmed ? 'border-green-400' : 'border-yellow-400' }}">
                        <p>{{ $resa->visitor->full_name }}, {{ $resa->visitor->age}} {{ __("ans")}}</p>
                        <p class="text-xs italic">{{ $resa->reservation->arrival}} {{ __("jusqu'au")}} {{ $resa->reservation->departure }}</p>
                        <div class="hidden-remarks">
                            <p>{{ $resa->reservation->remarks }}</p>
                            <p>{{ $resa->visitor->remarks }}</p>
                        </div>
                    </li>
                    @endif
                @endforeach
            </ul>
        </div>
    @endcan

    @can('community-choose')
        <div class="col-span-3">
        @php
            $cursor="cursor-move";
        @endphp
    @else
        <div class="col-span-4">
    @endcan


    <table class="mt-8 w-full table-fixed border-collapse border border-gray-400">
        @php
            $thead_class="border-2 border-gray-400 bg-gray-100 text-center";
            $tbody_class="border-2 border-gray-400 p-1"
        @endphp
        <thead>
            <tr>
                @foreach ( $communities as $community )
                    <td class="{{ $thead_class }}">{{ $community->name }}</td>
                @endforeach
            </tr>
            <tr>
                @foreach ( $communities as $community )
                    <td class="{{ $thead_class }} italic">{{ $community->reservationVisitors->count() }} {{__("Occupants")}}</td>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
            @foreach ( $communities as $community )
                    <td id="comm{{ $community->id }}" class="{{ $tbody_class }} dropzone ui-widget-header"><br>
                        @foreach ( $community->reservationVisitors as $resa )
                            <div id="resa{{ $resa->id }}" class="card p-2 {{ $cursor ?? '' }} draggable relative border-l-4 {{ $resa->reservation->confirmed ? 'border-green-400' : 'border-yellow-400' }}">
                                <p>{{ $resa->visitor->full_name }}, {{ $resa->visitor->age}} {{ __("ans")}}</p>
                                <p class="text-xs italic">{{ $resa->reservation->arrival}} {{ __("jusqu'au")}} {{ $resa->reservation->departure }}</p>
                                <div class="hidden-remarks">
                                    <p>{{ $resa->reservation->remarks }}</p>
                                    <p>{{ $resa->visitor->remarks }}</p>
                                    <p><button wire:click="getOutOfCommunity({{ $resa->id }})" class="btn-warning">{{__("Retirer")}}</button></p>
                                </div>
                            </div>
                        @endforeach
                    </td>
            @endforeach
            </tr>
        </tbody>
    </table>

    @can('room-choose')
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
    <script>

  $( function() {

      function letDrag() {
        setTimeout(function(){
            $( ".draggable" ).draggable({
                revert: 'invalid',
                cursor: "move",
//                 start: function( event, ui ){
//                     Livewire.emit('movingVisitor', ui.helper[0].id);
//                 }
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

        Livewire.emit('communityChanged', { house: this.id, resa: ui.draggable[0].id });
        letDrag();

      }
    });
  } );

    </script>
    @endcan
</div>
