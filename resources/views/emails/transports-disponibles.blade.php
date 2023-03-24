<div>
    <h2>{{__("Bonjour")}}</h2>
    <p>{{__("Voici la liste des personnes ayant de la place pour la Viale dans vos dates")}} :</p>
    <ul>
    @foreach ( $listReservations as $reservation )
        <li>
            {{ $reservation->contact_person->full_name }}, 
            @if ($reservation->shareEmail)
                <a href="mailto:{{ $reservation->contact_person->email }}">{{ $reservation->contact_person->email }}</a>
            @endif
            @if ($reservation->sharePhone)
                {{ $reservation->contact_person->phone }}
            @endif
            <br/>
            {{__("Début de son séjour :")}} {{ $reservation->arrival }}
            <br />
            {{__("Propose :n place", [ 'n' => $reservation->numberCarPlaces ])}}

        </li>
    @endforeach
    </ul>

</div>
