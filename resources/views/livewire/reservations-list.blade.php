<div>
    <h2 class="text-center mt-8">{{ __("Prochaines arrivées") }}</h2>
    @foreach ( $reservations as $reservation )
        <div @class(['mt-4', 'w-full', 'card', 'border-l-4', 'border-yellow-400' => ! $reservation->confirmed, 'border-red-400' => $reservation->confirmed])>
            @foreach ( $reservation->visitors as $visitor )
                <div @class(['mt-2', 'w-full', 'card'])>
                    @if ( $visitor->pivot->contact )
                        <p class="text-lg"><strong>{{ __("Personne de contact") }} :</strong> </p>
                    @endif
                    <p><strong>{{ __("Nom") }} :</strong> <span>{{ $visitor->full_name }} {{ $visitor->age}}, {{ __("ans") }}</span></p>
                    <p><strong>{{ __("Email") }} :</strong> <span><a class="text-blue-600" href="mailto:{{ $visitor->email }}">{{ $visitor->email }}</a></span></p>
                    <p><strong>{{ __("Numéro de téléphone") }} :</strong> <span>{{ $visitor->phone }}</span></p>
                    <p><strong>{{ __("Date d'arrivée") }} :</strong> <span>{{ $reservation->arrival }}</span></p>
                    <p><strong>{{ __("Date de départ") }} :</strong> <span>{{ $reservation->departure }}</span></p>
                    <p><strong>{{ __("Chambre") }} :</strong>
                        <span>

                        @if ( $visitor->pivot->room_id )
                            {{ $visitor->pivot->room->name }}
                        @else
                            {{ __("Pas de chambre prévue") }}
                        @endif

                        </span>
                    </p>
                    @can('room-choose')
                        <p>
                        @if ( $visitor->pivot->room_id )
                            <button class="btn-sm" wire:click="selectRoom({{ $visitor }}, {{ $reservation }})">{{ __("Changer la chambre") }}</button>
                        @else
                            <button class="btn" wire:click="selectRoom({{ $visitor }}, {{ $reservation }})">{{ __("Choisir la chambre") }}</button>
                        @endif
                        </p>
                    @endcan
                </div>
            @endforeach

<!--             Footer for each reservation -->
            <div class="p-4 text-right">
                @if($confirmingDeletion===$reservation->id)
                    @can('reservation-delete')
                        <button wire:click="deleteReservation({{ $reservation->id }})" class="bg-red-800 text-white px-4 py-1 hover:bg-red-600 rounded-lg border">{{ __("Confirmer la suppression ?") }}</button>
                    @endcan
                @else
                    @can('reservation-edit')
                        <svg wire:click="engageReservationChange({{ $reservation->id }})" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 float-right cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    @endcan
                    @can('reservation-delete')
                        <svg wire:click="confirmDelete({{ $reservation->id }})" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 float-right mr-4 stroke-red-600 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    @endcan

                @endif

            </div>
        </div>
    @endforeach
    @if ( $showRoomSelection )
        <livewire:room-selection-form :visitor="$visitorSelectedForRoom" :reservation="$reservationSelectedForRoom">
    @endif
</div>
