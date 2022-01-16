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
                    <p><button class="btn" wire:click="selectRoom({{ $visitor }}, {{ $reservation }})">{{ __("Choisir la chambre") }}</button></p>

                </div>
            @endforeach
        </div>
    @endforeach
    @if ( $showRoomSelection )
        <livewire:room-selection-form :visitor="$visitorSelectedForRoom" :reservation="$reservationSelectedForRoom">
    @endif
</div>
