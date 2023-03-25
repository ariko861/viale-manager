<div>
    @if ($reservation)

        <h1 class="my-4">{{__("Votre réservation a été confirmée")}}</h1>
        <h2 class="my-4">{{__("Récapitulatif de votre réservation")}} :</h2>

        <p><strong>{{__("Votre date d'arrivée")}} : </strong>{{$reservation->arrival}}</p>
        <p><strong>{{__("Votre date de départ")}} : </strong>{{$reservation->departure}}</p>
        <p><strong>{{__("Vos remarques")}} : </strong>{{$reservation->remarks}}</p>

        @if ($reservation->links && $reservation->links->count() > 0)
            @foreach ( $reservation->links as $link )
            <p>{{__("Vous pouvez encore utiliser ce lien de réservation :n fois pour modifier votre réservation", ['n' => $link->times_left ])}}</p>
            <a href="{{$link->getLink()}}">{{$link->getLink()}}</a>
            @endforeach
        @endif

        <h2 class="mt-4 mb-2">{{__("Personnes présentes")}} :</h2>

        @foreach ( $reservation->visitors as $visitor )
            <div class="card">
                <p><strong>{{__("Nom")}} : </strong>{{$visitor->full_name}}</p>
                <p><strong>{{__("Age")}} : </strong>{{$visitor->age}}</p>
                @if ( $visitor->phone )
                    <p><strong>{{__("Numéro de téléphone")}} : </strong>{{$visitor->phone}}</p>
                @endif
                @if ( $visitor->email )
                    <p><strong>{{__("Email")}} : </strong>{{$visitor->email}}</p>
                @endif
                <p><strong>{{__("Participation aux frais par nuitée")}} : </strong>{{$visitor->pivot->price}} €</p>

            </div>
        @endforeach
        

        @unless ($showPrice)
            @unless ($isEmail)
                <button class="mt-4" wire:click="$toggle('showPrice')">{{__("Afficher la participation totale pour le séjour")}}</button>
            @endunless
        @endunless
        @if ($showPrice)
            <p class="mt-4"><strong>{{__("Participation total pour le séjour")}} : </strong>{{$reservation->total_price_euro}}</p>
        @endif

        @unless ($isEmail)
            @unless ($emailSent)
                <button class="my-4" wire:click="sendRecapReservation">{{__("Recevoir le récapitulatif de la réservation par email")}}</button>
            @endunless
            @if ($emailSent)
                <p class="my-4">{{__("Email envoyé !")}}</p>
            @endif
        @endunless

        @if ( $confirmation_messages )
        <div class="bg-red-100 w-full p-6 border-4 border-red-600 mt-4">
            <h2>{{__("N'oubliez pas")}} :</h2>
            @foreach ( $confirmation_messages as $message )
                <p class="mt-4">{!! $message !!}<p>
            @endforeach
        </div>
        @endif

    @endif
</div>
