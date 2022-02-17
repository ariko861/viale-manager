<div class="my-4 w-full card px-12 md:w-2/3 {{ $visitorInReservation->contact ? 'border-4 border-red-200' : 'border-2' }}">
    @if ( $visitorInReservation && $visitorInReservation->contact )
        <p class="text-lg"><strong>{{ __("Personne de contact") }} :</strong> </p>
    @endif
    <div class="float-right text-right">
        @if ( $editing )

                <input type="number" min=0 wire:model="visitorInReservation.price" wire:change.debounce.1000ms="updatePivot"/>€ {{__("par nuit")}}
                <br>
                <div class="mt-6">
                    @unless ($visitorInReservation->contact)
                        @if ( $waitingForMove )
                            <p class="error">{{__("Vous êtes sur le point de déplacer ce visiteur dans une nouvelle réservation, êtes vous sur ?")}}</p>
                            <button wire:click="moveToNewReservation" class="mx-4 my-2 btn-warning">{{__("Déplacer")}}</button><button wire:click="$toggle('waitingForMove')" class="mx-4 my-2">{{__("Annuler")}}</button>
                        @else
                            <button wire:click="$toggle('waitingForMove')">{{__("Déplacer dans une nouvelle réservation")}}</button>
                        @endif
                    @endunless
                </div>
        @else
            @can ('read-statistics')
                    <span>{{number_format($visitorInReservation->price, 2,'€',' ')}} {{__("par nuit")}}
            @endcan
        @endif
        <div class="p-4 border-4 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="inline h-8 w-8 m-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <livewire:buttons.edit-buttons :wire:key="'visitor-'.$visitor->id.'-in-reservation-'.$reservation->id.'-edit'" model="visitorInReservation" :modelId="$reservation->id.'-'.$visitor->id" editRights="visitor-edit" deleteRights="reservation-edit" messageDelete="du visiteur de cette réservation" :deleteCondition="!$visitorInReservation->contact">
            <div class="clear-both"></div>
        </div>
    </div>

    <p><strong>{{ __("Nom") }} :</strong> <span>{{ $visitor->full_name }}, {{ $visitor->age}} {{ __("ans") }}</span></p>
    <p><strong>{{ __("Email") }} :</strong> <span><a class="text-blue-600" href="mailto:{{ $visitor->email }}">{{ $visitor->email }}</a></span></p>
    <p><strong>{{ __("Numéro de téléphone") }} :</strong> <span>{{ $visitor->phone }}</span></p>
    <p><strong>{{ __("Chambre") }} :</strong>
        <span>

        @if ( $visitorInReservation->room_id )
            {{ $visitorInReservation->room->fullName() }}
        @else
            {{ __("Pas de chambre prévue") }}
        @endif

        </span>
    </p>
    @can('room-choose')
        <p>
        @if ( $visitorInReservation->room_id )
            <button class="btn-sm" wire:click="selectRoom">{{ __("Changer la chambre") }}</button>
        @else
            <button class="btn" wire:click="selectRoom">{{ __("Choisir la chambre") }}</button>
        @endif
        </p>
    @endcan
    <div class="clear-right"></div>

</div>
