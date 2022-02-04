<div>
    <h2 class="text-center mt-8 text-lg">{{ $listTitle }}</h2>
    <br>
    <div class="option-field">
        <p><strong>{{__("Afficher les")}} <input type="number" step=1 wire:model="amountDisplayedReservation"> {{__("prochaines arrivées")}} : </strong><button wire:click="getReservationsComing">{{__("Chercher")}}</button></p>
        <br>
        <p><strong>{{__("Ou sélectionner par date d'arrivée")}} :</strong> {{__("Du")}} <input type="date" wire:model="beginDate"> {{__("Au")}} <input wire:change="getReservationsInBetween" type="date" wire:model="endDate" min="{{$beginDate}}"></p>
    </div>
    <br>

    @foreach ( $reservations as $reservation )
        <div @class(['mt-4', 'w-full', 'card', 'border-l-4', 'border-yellow-400' => ! $reservation->confirmed, 'border-green-400' => $reservation->confirmed])>
                <p><strong>{{ __("Date d'arrivée") }} :</strong>
                    @if ( $editing === $reservation->id )
                        <input wire:model="newArrivalDate" type="date">
                    @else
                        <span>{{ $reservation->arrival }}</span>
                    @endif
                </p>
                <p><strong>{{ __("Date de départ") }} :</strong>
                    @if ( $editing === $reservation->id )
                        <input wire:model="newDepartureDate" type="date">
                    @else
                        <span>{{ $reservation->departure }}</span>
                    @endif
                </p>
                @if ( $editing === $reservation->id )
                    <br>
                    <p><label><strong>{{__("Ne connait pas sa date de départ")}} : </strong></label><input type="checkbox" wire:model="noDepartureDate"></p>
                    <br>
                    <p><label><strong>{{__("Réservation confirmée")}} : </strong></label><input type="checkbox" wire:model="reservationConfirmed"></p>
                    <br>
                    <p><button class="btn-warning" wire:click="$set('editing', '')">{{__("Annuler les changements")}}</button><button class="btn-submit" wire:click="saveEdit({{$reservation->id}})">{{__("Sauvegarder les changements")}}</button></p>
                @endif
                @if ($reservation->remarks)
                    <p><strong>{{ __("Remarques") }} :</strong> <span>{{ $reservation->remarks }}</span></p>
                @endif
                @if ( $reservation->links->count() )
                    <h4>{{__("Liens de confirmation")}}</h4>
                    @foreach ( $reservation->links as $link)
                        <p><a href="{{ $link->getLink() }}" onclick="return false;">{{ $link->getLink() }}</a></p>
                    @endforeach
                @endif

            @foreach ( $reservation->visitors as $visitor )
                <div @class(['mt-2', 'w-full', 'card'])>
                    @if ( $visitor->pivot->contact )
                        <p class="text-lg"><strong>{{ __("Personne de contact") }} :</strong> </p>
                    @endif
                    <p><strong>{{ __("Nom") }} :</strong> <span>{{ $visitor->full_name }} {{ $visitor->age}}, {{ __("ans") }}</span></p>
                    <p><strong>{{ __("Email") }} :</strong> <span><a class="text-blue-600" href="mailto:{{ $visitor->email }}">{{ $visitor->email }}</a></span></p>
                    <p><strong>{{ __("Numéro de téléphone") }} :</strong> <span>{{ $visitor->phone }}</span></p>
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
            <div class="p-4 float-left">
                <svg xmlns="http://www.w3.org/2000/svg" wire:click="sendConfirmationMail({{ $reservation->id }})" class="h-8 w-8 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <div class="p-4 text-right">

                <livewire:buttons.edit-buttons :wire:key="$reservation->id" model="reservation" :modelId="$reservation->id" editRights="reservation-edit" deleteRights="reservation-delete">

            </div>
        </div>
    @endforeach
    @if ( $showRoomSelection )
        <livewire:room-selection-form :visitor="$visitorSelectedForRoom" :reservation="$reservationSelectedForRoom">
    @endif
    @if ($showSendLinkForm )
        <livewire:reservation.create-link >
    @endif
</div>
