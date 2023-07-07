<x-guest-layout>
    <div class="pt-4 bg-gray-100">
        <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
            <div>
                <x-logo.viale-manager height=60 width=78 class="block h-9 w-auto" />
            </div>

            <div class="w-full sm:max-w-2xl mt-6 p-6 bg-white shadow-md overflow-hidden sm:rounded-lg prose">
                <p>{{ $reservations->count() }} personnes {{ $link->type == 'offer_places' ? 'proposent' : 'recherchent' }} des places de voiture autour du {{ $link->date_carbon->isoFormat('dddd Do MMMM YYYY') }} </p>

                @foreach($reservations as $reservation)
                    <div class="card">
                        <h3>{{ $reservation->contact_person->full_name }}</h3>
                        <span>Débute son séjour à la Viale le <b>{{$reservation->arrival}}</b></span>
                        <br><span>{{ $link->type == 'offer_places' ? 'Propose' : 'Recherche' }} {{__(":n place de voiture", [ 'n' => $reservation->numberCarPlaces ])}}</span>
                        @if ($reservation->coming_from)
                            <br><span>{{__("En provenance de :p", [ 'p' => $reservation->coming_from ])}}</span>
                        @endif
                        @if ($reservation->shareEmail)
                            <br><a href="mailto:{{ $reservation->contact_person->email }}">{{ $reservation->contact_person->email }}</a>
                        @endif
                        @if ($reservation->sharePhone)
                            <br><a href="tel:{{ $reservation->contact_person->phone }}">{{ $reservation->contact_person->phone }}</a>
                        @endif
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</x-guest-layout>
