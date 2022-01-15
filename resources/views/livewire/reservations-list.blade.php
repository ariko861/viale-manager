<div>
    <h2 class="text-center mt-8">{{ __("Prochaines arrivées") }}</h2>
    @foreach ( $reservations as $reservation )
    <div @class(['mt-4', 'w-full', 'card', 'border-l-4', 'border-yellow-400' => ! $reservation->confirmed, 'border-red-400' => $reservation->confirmed])>
        <p><strong>{{ __("Personne de contact") }} :</strong> <span>{{ $reservation->contact_person->full_name }} {{ $reservation->contact_person->age}}, {{ __("ans") }}</span></p>
        <p><strong>{{ __("Email") }} :</strong> <span><a class="text-blue-600" href="mailto:{{ $reservation->contact_person->email }}">{{ $reservation->contact_person->email }}</a></span></p>
        <p><strong>{{ __("Numéro de téléphone") }} :</strong> <span>{{ $reservation->contact_person->phone }}</span></p>
        <p><strong>{{ __("Date d'arrivée") }} :</strong> <span>{{ $reservation->arrival }}</span></p>
        <p><strong>{{ __("Date de départ") }} :</strong> <span>{{ $reservation->departure }}</span></p>
        <p><strong>{{ __("Personnes accompagnant") }} :</strong></p>
        <ul class="list-disc list-inside">
        @foreach ( $reservation->visitors as $visitor )
            @unless ( $visitor->pivot->contact )
                <li>{{ $visitor->full_name }}, {{ $visitor->age}} {{ __("ans") }}</li>
            @endunless
        @endforeach
        </ul>

    </div>
    @endforeach
</div>
