<div>
    <h2 class="text-xl text-center mt-10 mb-6">{{ __("Options") }}</h2>
    @php
        $li_class="my-2";
    @endphp
<!--     Pour régler l'adresse email -->
    <div class="w-full px-8 grid grid-cols-4 gap-4 my-2">
        <label class="col-span-1">{{ __("Changer le mail de réception des reservations") }}</label>
        <input class="col-span-2" type="text" wire:model="email.value">
        <button class="btn-submit" wire:click="save('email')">{{__("Sauvegarder le changement")}}</button>
        @error('email.value') <span class="col-span-1"></span><span class="red col-span-3 error">{{ $message }}</span> @enderror
    </div>

    <!--     Pour régler le numéro de téléphone -->
    <div class="w-full px-8 grid grid-cols-4 gap-4 my-2">
        <label class="col-span-1">{{ __("Changer le téléphone affiché") }}</label>
        <input class="col-span-2" type="text" wire:model="phone.value">
        <button class="btn-submit" wire:click="save('phone')">{{__("Sauvegarder le changement")}}</button>
        @error('phone.value') <span class="col-span-1"></span><span class="red col-span-3 error">{{ $message }}</span> @enderror
    </div>

        <!--     Pour régler l'adresse -->
    <div class="w-full px-8 grid grid-cols-4 gap-4 my-2">
        <label class="col-span-1">{{ __("Changer l'adresse affiché") }}</label>
        <input class="col-span-2" type="text" wire:model="address.value">
        <button class="btn-submit" wire:click="save('address')">{{__("Sauvegarder le changement")}}</button>
        @error('address.value') <span class="col-span-1"></span><span class="red col-span-3 error">{{ $message }}</span> @enderror
    </div>

    @if ($email->value)
        <div class="w-full px-8 my-6">
            <button class="bg-lime-500 hover:bg-lime-800" wire:click="testEmail">{{__("Tester l'adresse email")}}</button>
        </div>
    @endif


    <!--     Messages après confirmation -->
    <h3 class="mt-8 mb-2 text-center">{{__("Messages de confirmations")}}</h3>
    <p class="text-center">{{__("Messages reçus par les visiteurs après confirmation de leur réservation")}}</p>
    @foreach ($confirmation_messages as $key => $message)
    <div class="w-full px-8 grid grid-cols-6 gap-4 mt-6">
        <label class="col-span-2">{{ __("Message envoyé à la confirmation de la réservation") }}</label>
        <textarea class="col-span-2" wire:model="confirmation_messages.{{ $key }}.value"></textarea>
        <button class="btn-submit col-span-1" wire:click="saveMessages">{{__("Sauvegarder le changement")}}</button>
        <button class="btn-warning col-span-1" wire:click="deleteMessage({{$message->id}})">{{__("Supprimer le message")}}</button>
        @error('confirmation_messages.{{$key}}.value') <span class="col-span-1"></span><span class="red col-span-3 error">{{ $message }}</span> @enderror
    </div>
    @endforeach
    <button class="w-full rounded-full p-2 mt-4" wire:click="addNewMessage"> Ajouter un nouveau message de confimation</button>

    <!--     Liens vers salons Matrix -->
    <h3 class="mt-8 mb-2 text-center">{{__("Liens vers salons Matrix")}}</h3>
    <p class="text-center">{{__("Liens conduisant vers des fils de discussion sur Matrix")}}</p>
    @foreach ($matrix_links as $key => $matrix)
    <div class="card">
        <ul>
            <li class="{{$li_class}}"><strong>{{ __("Lien") }} :</strong> <a href="{{ $matrix->getLink() }}" target="_blank">{{ $matrix->getLink() }}</a></li>
            <li class="{{$li_class}}"><strong>{{__("Serveur")}} :</strong> <input class="w-full" type="text" wire:model="matrix_links.{{ $key }}.homeserver"></li>
            <li class="{{$li_class}}"><strong>{{__("Room ID")}} :</strong> <input class="w-full" type="text" wire:model="matrix_links.{{ $key }}.roomID"></li>
            <li class="{{$li_class}}"><strong>{{__("Utilisateur filtré")}} :</strong> <input class="w-full" type="text" wire:model="matrix_links.{{ $key }}.filteredUser"></li>
            <li class="{{$li_class}}"><strong>{{__("Gallerie photo")}} :</strong> <input type="checkbox" wire:model="matrix_links.{{ $key }}.gallery"></li>
            <li class="{{$li_class}}"><strong>{{__("Afficher les dates")}} :</strong> <input type="checkbox" wire:model="matrix_links.{{ $key }}.displayDate"></li>
            <li class="{{$li_class}}"><strong>{{__("Afficher l'adresse Matrix du salon")}} :</strong> <input type="checkbox" wire:model="matrix_links.{{ $key }}.displayAddress"></li>
        </ul>
        <div class="float-right">
            <button class="btn-submit col-span-1" wire:click="saveMatrix">{{__("Sauvegarder les changements")}}</button>
            <button class="btn-warning col-span-1" wire:click="deleteMatrix({{$matrix->id}})">{{__("Supprimer le lien Matrix")}}</button>
        </div>
        <div class="w-full clear-right h-10"></div>
    </div>
    @endforeach
    <input type="text" wire:model="newMatrixLink">
    <button class="rounded-full p-2 mt-4" wire:click="addNewMatrix"> Ajouter un nouveau lien Matrix</button>




</div>
