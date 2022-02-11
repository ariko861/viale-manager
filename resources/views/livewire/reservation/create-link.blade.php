<div class="fixed top-0 left-0 right-0 bottom-0 bg-slate-600/75 overflow-auto">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white shadow-xl sm:rounded-lg p-5 m-10">
        <div>
            <svg xmlns="http://www.w3.org/2000/svg" wire:click="$emitUp('cancelLinkForm')" class="h-8 w-8 cursor-pointer float-right" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </div>
            <h3 class="text-center text-lg">{{__("Envoyer un lien de confirmation à")}} {{ $reservation->contact_person->full_name ?? "" }}</h3>
            <br>
            <label>{{__("Autoriser à décaler les dates de")}} </label><input type="number" min=0 wire:model="maxDays"><span> {{__("jours")}}</span>
            <br><br>
            <label>{{__("Autoriser à ajouter un maximum de")}} </label><input type="number" min=0 wire:model="maxVisitors"><span> {{__("visiteurs")}}</span>
            <br><br>
            @unless ($linkCreated)
                <button class="btn-submit" wire:click="send(false)">{{__("Générer un lien de confirmation")}}</button>
                @if ($reservation->contact_person && $reservation->contact_person->email)
                    <br><p>{{__("Ou")}}</p>
                    <button class="btn-submit btn-sm" wire:click="send(true)">{{__("Envoyer un email de confirmation à")}} {{ $reservation->contact_person->email ?? "" }}</button>
                    <br>
                    <p wire:loading>{{__("Email en cours d'envoi")}}</p>
                @endif

            @endunless
            @if ($linkCreated)
                <p>Un nouveau lien <a href="{{$linkCreated}}" onclick="return false;">{{$linkCreated}}</a> a été créé,
                    @if ($emailSent)
                        <span><strong>{{__("ce lien vient d'être envoyé à la personne de contact")}}</strong></span>
                    @else
                        <span>vous pouvez l'envoyer à la personne concernée</span>
                    @endif
                </p>
            @endif


    </div>
</div>
