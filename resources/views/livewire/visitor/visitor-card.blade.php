<div @class(['mt-2', 'w-full', 'card'])>
    @if ( $visitor && $visitor->pivot && $visitor->pivot->contact )
        <p class="text-lg"><strong>{{ __("Personne de contact") }} :</strong> </p>
    @endif
    @if ( $editing === $reservation->id )
        <div class="float-right">
            <input type="number" min=0 wire:model="visitor.pivot.price"/>€ {{__("par nuit")}}
        </div>
    @else
        @can ('read-statistics')
            <div class="float-right">
                <span>{{number_format($visitor->pivot->price, 2,'€',' ')}} {{__("par nuit")}}
            </div>
        @endcan
    @endif
    <p><strong>{{ __("Nom") }} :</strong> <span>{{ $visitor->full_name }}, {{ $visitor->age}} {{ __("ans") }}</span></p>
    <p><strong>{{ __("Email") }} :</strong> <span><a class="text-blue-600" href="mailto:{{ $visitor->email }}">{{ $visitor->email }}</a></span></p>
    <p><strong>{{ __("Numéro de téléphone") }} :</strong> <span>{{ $visitor->phone }}</span></p>
    <p><strong>{{ __("Chambre") }} :</strong>
        <span>

        @if ( $visitor->pivot->room_id )
            {{ $visitor->pivot->room->fullName() }}
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
    @if ( $editing === $reservation->id )
    <div class="p-4 text-right">
        <livewire:buttons.edit-buttons :wire:key="'visitor-'.$visitor->id.'-in-reservation-'.$reservation->id" model="visitorInReservation" :modelId="$reservation->id.'-'.$visitor->id" editRights="visitor-edit" deleteRights="reservation-edit">
    </div>
    @endif
</div>
