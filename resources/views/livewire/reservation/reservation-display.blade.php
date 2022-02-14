<div>
    @if ($reservation)
    <div class="fixed top-0 left-0 right-0 bottom-0 bg-slate-600/75 overflow-auto z-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white shadow-xl sm:rounded-lg p-5 m-10">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" wire:click="hideReservation" class="h-6 w-6 cursor-pointer float-right" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>

            <h1 class="my-6">{{__("Réservation")}} {{$reservation->id}}</h1>
            <p class="mx-2"><strong>{{__("Jour d'arrivée")}} : </strong>{{$reservation->arrival}}</p>
            <p class="mx-2"><strong>{{__("Jour de départ")}} : </strong>{{$reservation->departure}}</p>

            <h3 class="mt-6 mb-2">{{__("Personne de contact")}} :</h3>
            <p><strong>{{__("Nom")}} : </strong>{{$reservation->contact_person->full_name}}</p>
            <p><strong>{{__("Age")}} : </strong>{{$reservation->contact_person->age}}</p>
            <p><strong>{{__("Email")}} : </strong><a href="mailto:{{$reservation->contact_person->email}}">{{$reservation->contact_person->email}}</a></p>
            <p><strong>{{__("Numéro de téléphone")}} : </strong><a href="tel:{{$reservation->contact_person->phone}}">{{$reservation->contact_person->phone}}</a></p>
            @if ($reservation->contact_person->pivot->room)
                <p><strong>{{__("Chambre prévue")}} : </strong>{{$reservation->contact_person->pivot->room->fullName()}}</p>
            @endif
            @can ('visitor-list')
                @if ($reservation->contact_person->remarks)
                    <p><strong>{{__("Remarques")}} : </strong>{{$reservation->contact_person->remarks}}</p>
                @endif
            @endcan

            @if ( $reservation->getNonContactVisitors()->count() )
                <h3 class="mt-6 mb-2">{{__("Autres visiteurs sur cette réservation")}} :</h3>
                @foreach ($reservation->getNonContactVisitors() as $visitor)
                    <div class="card">
                        <p><strong>{{__("Nom")}} : </strong>{{$visitor->full_name}}</p>
                        <p><strong>{{__("Age")}} : </strong>{{$visitor->age}}</p>
                        @if ($visitor->email)
                            <p><strong>{{__("Email")}} : </strong><a href="{{$visitor->email}}">{{$visitor->email}}</a></p>
                        @endif
                        @if ($visitor->phone)
                            <p><strong>{{__("Numéro de téléphone")}} : </strong><a href="tel:{{$visitor->phone}}">{{$visitor->phone}}</a></p>
                        @endif
                        @if ($visitor->pivot->room)
                            <p><strong>{{__("Chambre prévue")}} : </strong>{{$visitor->pivot->room->fullName()}}</p>
                        @endif
                        @can ('visitor-list')
                            @if ($visitor->remarks)
                                <p><strong>{{__("Remarques")}} : </strong>{{$visitor->remarks}}</p>
                            @endif
                        @endcan

                    </div>
                @endforeach
            @endif

            @if ($reservation->remarks)
                <p><strong>{{__("Remarques faites à la confirmation")}} : </strong>{{$reservation->remarks}}</p>
            @endif

            <p class="mt-8"><button wire:click="superUserInfo">Super User Info</button></p>


        </div>
    </div>
    @endif
</div>
