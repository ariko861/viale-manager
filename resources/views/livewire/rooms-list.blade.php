<div>
    <h2 class="text-xl text-center">{{ __("Chambres") }}</h2>
    <table class="mt-8 w-full table-auto border-collapse border border-gray-400">
    @php
        $thead_class="border-2 border-gray-400 bg-gray-100";
        $tbody_class="border-2 border-gray-400 p-1"
    @endphp

    <tbody>
        @foreach ( $houses as $key => $house )
            <tr>
                <td class="{{ $thead_class }} text-center" colspan="3">
                    <button wire:click="engageCreateRoom({{ $house->id }})" class="float-right mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>
                    <strong>{{ $house->name }}</strong>
                    <input class="ml-2" type="checkbox" wire:change="updateCommunityRule()" wire:model="houses.{{ $key }}.community"><label class="ml-1 text-sm">{{__("Disponible pour maisonnées")}}</label>
                </td>
            </tr>
            <tr>
                <td class="{{ $thead_class }}"><i>{{ __("Chambres") }}</i></td>
                <td class="{{ $thead_class }}"><i>{{ __("Nombre de lits") }}</i></td>
                <td class="{{ $thead_class }}"><i>{{ __("Actions") }}</i></td>

            </tr>
            @foreach ( $house->rooms as $roomKey => $room )
                <tr>
                    <td class="{{ $tbody_class }}">{{ $room->name }}</td>
                    <td class="{{ $tbody_class }}">{{ $room->beds }}</td>
                    <td class="{{ $tbody_class }}"><livewire:buttons.edit-buttons :wire:key="$room->id" model="room" :modelId="$room->id" editRights="config-manage" deleteRights="config-manage"></td>
                </tr>
            @endforeach

        @endforeach

        @if ( $creatingHouse )
            <tr>
                <td class="{{ $tbody_class }}"><input type="text" wire:model="newHouse.name"><button class="btn ml-8" wire:click="saveHouse">{{ __("Enregistrer") }}</button></td>
                <td class="{{ $tbody_class }}"></td>
            </tr>
        @endif

   </tbody>
   </table>
   @if ( ! $creatingHouse )
        <button wire:click="engageCreateHouse" class="float-left mr-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </button>
    @endif

   @if ( $creatingRoom )
        <div class="absolute top-0 left-0 right-0 bottom-0 bg-slate-600/75">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white shadow-xl sm:rounded-lg p-5 m-10">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" wire:click="cancelRoomForm" class="h-6 w-6 cursor-pointer float-right" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <h2 class="text-lg text-center">{{ __("Ajouter une nouvelle chambre") }}</h2>
                    <div class="w-full px-8 grid grid-cols-3 gap-4">
                        <label class="col-span-1">{{ __("Nom de la chambre") }}</label>
                        <input class="col-span-2" type="text" wire:model="newRoom.name">
                        <label class="col-span-1">{{ __("Nombre de lits") }}</label>
                        <input class="col-span-2" type="number" min=0 wire:model="newRoom.beds">
                        <div class="col-span-full text-center">
                            <button class="btn ml-8" wire:click="saveRoom">{{ __("Enregistrer") }}</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
