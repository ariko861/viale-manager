<div>
    <h2>{{__("Bonjour")}} {{$link->reservation->contact_person->surname}},</h2>
    <p>{{__("Vous avez fait une demande de réservation auprès de")}} {{env("APP_NAME")}}</p>
    <p>{{__("Veuillez confirmer votre réservation en suivant ce lien")}} :</p>
    <p><a href="{{ $link->getLink() }}">{{ $link->getLink() }}</a></p>

</div>
