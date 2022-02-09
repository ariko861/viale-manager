<div>
    <h2>{{__("Bonjour")}},</h2>
    <p>{{$reservation->contact_person->full_name}} {{__("a bien confirmé sa réservation à")}} {{env("APP_NAME")}}</p>
    <p>{{__("Vous pouvez consulter les détails en suivant ce lien")}} :</p>
    <p><a href="{{ $link }}">{{ $link }}</a></p>

</div>
