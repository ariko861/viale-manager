<div class="my-12 w-full card border-l-4 border-b-4 {{ $reservation->confirmed ? 'border-green-400' : 'border-yellow-400'}}">
    <div class="p-1 float-right border-4 text-center sm:p-2 md:p-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="inline h-6 w-6 m-2 md:h-10 md:w-10 md:m-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <livewire:buttons.edit-buttons :wire:key="$reservation->id" model="reservation" :modelId="$reservation->id" editRights="reservation-edit" deleteRights="reservation-delete" messageDelete="de la réservation">
    </div>
    <p class="italic">{{ $reservation->person_number }}</p>
    <p><strong>{{ __("Date d'arrivée") }} :</strong>
        @if ( $editing )
            <input wire:model="reservation.arrivaldate" type="date" wire:change="updateReservation">
            @error('reservation.arrivaldate')<p class="error-message">{{ $message }}</p>@enderror
        @else
            <span>{{ $reservation->arrival }}</span>
        @endif
    </p>
    <p><strong>{{ __("Date de départ") }} :</strong>
        @if ( $editing || $setDepartureDate )
            <input wire:model="reservation.departuredate" min="{{ $reservation->arrivaldate }}" type="date" wire:change="updateReservation('departure')">
            @error('reservation.departuredate')<p class="error-message">{{ $message }}</p>@enderror
        @else
            <span>{{ $reservation->departure }}</span>
            @if ( $reservation->nodeparturedate )
                <button class="btn-sm" wire:click="$set('setDepartureDate', true)">{{__("Choisir une date de départ")}}</button>
            @endif
        @endif
    </p>
    @if ($editing)
        <p class="my-2"><button wire:click="$emit('engageSplitReservation', {{$reservation->id}})">{{__("Programmer une absence")}}</button><p>
    @endif
    <p><strong>{{ __("Nombre de nuits") }} :</strong> <span>{{ $reservation->nights }}</span></p>
    @can('statistics-remove')
        <p><label><strong>{{__("Retirer des statistiques")}} : </strong></label><input type="checkbox" wire:change="updateReservation" wire:model="reservation.removeFromStats"></p>
    @endcan
    @can('read-statistics')
        @if ($reservation->confirmed)
            <p><strong>{{ __("Prix total de la réservation") }} :</strong> <span>{{ $reservation->getTotalPriceEuroAttribute() }}</span></p>
        @endif
    @endcan
    @if ( $editing )
        <br>
        <p><label><strong>{{__("Ne connait pas sa date de départ")}} : </strong></label><input type="checkbox" wire:model="reservation.nodeparturedate" wire:change="updateReservation"></p>
        <br>
        <p><label><strong>{{__("Réservation confirmée")}} : </strong></label><input type="checkbox" wire:model="reservation.confirmed" wire:change="updateReservation"></p>
    @endif
    @if ($reservation->remarks)
        <p><strong>{{ __("Remarques") }} :</strong> <span>{{ $reservation->remarks }}</span></p>
    @endif
    @if ($editing)
        <p><label><strong>{{ __("Remarques") }} :</strong></label> <textarea wire:model="reservation.remarks"></textarea></p>
        <p class="my-4"><button class="btn-warning" wire:click="$set('editing', '')">{{__("Arrêter les changements")}}</button></p>
    @endif

    @canany(['reservation-create', 'reservation-edit'])
        @if ( $reservation->links->count() )
            <h4>{{__("Liens de confirmation")}} :</h4>
            @foreach ( $reservation->links as $link)
                <p>
                    @if ( $editing )
                    <svg wire:click="deleteLink({{ $link->id }})" xmlns="http://www.w3.org/2000/svg" class="inline h-6 w-6 mr-2 stroke-red-600 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    @endif
                    <a href="{{ $link->getLink() }}" onclick="return false;">{{ $link->getLink() }}</a>
                </p>
            @endforeach
        @endif
        <div class="p-4">
            <svg xmlns="http://www.w3.org/2000/svg" wire:click="sendConfirmationMail" class="h-8 w-8 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </div>
    @endcanany

    @if ( $reservation->visitors && $reservation->visitors->count() )
    @foreach ( $reservation->visitors->sortByDesc('pivot.contact') as $key => $visitor )
        <livewire:visitor.visitor-card wire:key="{{ 'reservation-'.$reservation->id.'-visitor-'.$visitor->id }}" :reservation="$reservation" :visitorInReservation="$visitor->pivot" :visitor="$visitor" :vKey="$key">
    @endforeach
    @endif

    @if ( $editing )
        <div class="card">
            @unless ( $newVisitorInReservation )
                <div class="w-full">
                    <button class="btn w-full" wire:click="$toggle('newVisitorInReservation')">{{ __("Ajouter un autre visiteur") }}</button>
                </div>
            @endunless
            @if ($newVisitorInReservation)
                <livewire:visitor.visitor-search :wire:key="'add-visitor-in-reservation-'.$reservation->id" :visitorKey="$reservation->id" visitorType="otherVisitor">
            @endif
        </div>
    @endif

<!--             Footer for each reservation -->


</div>
