<div @class(['mt-2', 'w-full', 'card'])>
    @if ( $visitorInReservation && $visitorInReservation->contact )
        <p class="text-lg"><strong>{{ __("Personne de contact") }} :</strong> </p>
    @endif
    @if ( $editing )
        <div class="float-right">
            <input type="number" min=0 wire:model="visitorInReservation.price" wire:change.debounce.1000ms="updatePivot"/>€ {{__("par nuit")}}
        </div>
    @else
        @can ('read-statistics')
            <div class="float-right">
                <span>{{number_format($visitorInReservation->price, 2,'€',' ')}} {{__("par nuit")}}
            </div>
        @endcan
    @endif
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
    <div class="p-4 text-right">
        <livewire:buttons.edit-buttons :wire:key="'visitor-'.$visitor->id.'-in-reservation-'.$reservation->id.'-edit'" model="visitorInReservation" :modelId="$reservation->id.'-'.$visitor->id" editRights="visitor-edit" deleteRights="reservation-edit">
    </div>
</div>
