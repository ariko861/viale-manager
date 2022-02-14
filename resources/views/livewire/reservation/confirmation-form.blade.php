<div>
    @if ($link)
    <h1 class="mt-4 mb-8">{{ __("Merci de confirmer votre réservation à ")}}{{env('APP_NAME')}}</h1>
    <br>

    @if ($showEmailForm)
        <p class="mb-4">{{__("Veuillez saisir une adresse email pour voir si vous nous retrouvons dans nos précédentes inscriptions")}} :</p>
        <livewire:visitor.email-search user="visitor">
    @endif

    @unless ($showEmailForm)
    <form wire:submit.prevent="save" autocomplete="off">
        @csrf
        <div class="w-full px-8 grid grid-cols-3 gap-4">

            <h3 class="col-span-full mt-6 mb-4">{{ __("Personne de contact") }}</h3>

            <label class="col-span-1">{{ __("Nom") }}</label>
            @if ( $unknown->contains('name') )
                <input type="text" class="col-span-2" wire:model="contact_person.name">
                @error('contact_person.name') <span class="col-span-1"></span><span class="text-red-600 col-span-2">{{ $message }}</span> @enderror
            @else
                <span class="col-span-2">{{ $contact_person->name }}</span>
            @endif

            <label class="col-span-1">{{ __("Prénom") }}</label>
            @if ( $unknown->contains('surname') )
                <input type="text" class="col-span-2" wire:model="contact_person.surname">
                @error('contact_person.surname') <span class="col-span-1"></span><span class="text-red-600 col-span-2">{{ $message }}</span> @enderror
            @else
                <span class="col-span-2">{{ $contact_person->surname }}</span>
            @endif

            <label class="col-span-1">{{ __("Email") }}</label>
            @if ( $unknown->contains('email') )
                <input type="email" class="col-span-2" wire:model="contact_person.email">
                @error('contact_person.email') <span class="col-span-1"></span><span class="text-red-600 col-span-2">{{ $message }}</span> @enderror
            @else
                <span class="col-span-2">{{ $contact_person->email }}</span>
            @endif

            <label class="col-span-1">{{ __("Numéro de téléphone") }}</label>
            <input type="tel" class="col-span-2" wire:model="contact_person.phone">
            @error('contact_person.phone') <span class="col-span-1"></span><span class="text-red-600 col-span-2">{{ $message }}</span> @enderror

            <label class="col-span-1">{{ __("Année de naissance") }}</label>
            <input type="number" class="col-span-2" min=1900 max=2100 wire:model="contact_person.birthyear">
            @error('contact_person.birthyear') <span class="col-span-1"></span><span class="text-red-600 col-span-2">{{ $message }}</span> @enderror


            <h3 class="col-span-full">{{__("Votre participation aux frais")}} :</h3>

            @foreach ($profiles as $profile)
                <label class="col-span-1"><span class="font-semibold">{{ $profile->name }} :</span> {{ $profile->remarks }}</label>
                <div class="col-span-2">
                    <input type="radio" wire:model="price" value="{{ $profile->price }}"><label>{{ $profile->euro }}</label>
                </div>
            @endforeach

            @if ($link->max_days_change > 0)
                <h3 class="col-span-full">{{__("Modifier vos dates")}} : </h3>

                <label class="col-span-1">{{ __("Date d'arrivée") }}</label>
                <input class="col-span-2" wire:model="reservation.arrivaldate" wire:change="setMinDepartureDate" max="{{ $maxarrivaldate }}" min="{{ $minarrivaldate }}" type="date" />
                @error('reservation.arrivaldate') <span class="col-span-1"></span><span class="text-red-600 col-span-2">{{ $message }}</span> @enderror

                <label class="col-span-1">{{ __("Date de départ") }}</label>
                <input class="col-span-2" wire:model="reservation.departuredate" max="{{ $maxdeparturedate }}" min="{{ $mindeparturedate }}" type="date" />
                @error('reservation.departuredate') <span class="col-span-1"></span><span class="text-red-600 col-span-2">{{ $message }}</span> @enderror

            @endif

            @if ($otherVisitorsArray->count())
                <h3 class="col-span-full">{{__("Autres visiteurs")}} :</h3>
                <ul class="col-span-full list-disc list-inside">
                    @foreach ( $otherVisitorsArray as $key =>$otherVisitor )
                        <li>{{ $otherVisitor["full_name"]}}, {{$otherVisitor["age"]}} {{__("Ans")}}
                            <br>
                            <span class="font-bold">{{__("Participation aux frais")}} :</span>
                            @foreach ($profiles as $profile)
                                <label class="ml-4"><span class="font-semibold">{{ $profile->name }} :</span></label>
                                <input type="radio" wire:model="otherVisitorsArray.{{ $key }}.price" value="{{ $profile->price }}"><label class="mr-4">{{ $profile->euro }}</label>
                            @endforeach
                            <br>
                        </li>
                    @endforeach
                </ul>

            @endif

            @if ( $link->max_added_visitors )
                <br>
                <h3 class="col-span-full mt-6 mb-4">{{__("Vous êtes accompagné ? Vous pouvez ajouter d'autres visiteurs à la réservation")}} :</h3>
                @unless ( $forbidAddingVisitors )
                    <div class="col-span-full">
                        <button class="btn bg-blue-400 w-full" wire:click.prevent="addVisitor">{{ __("Ajouter un autre visiteur") }}</button>
                    </div>
                @endunless
        <!--    Pour chaque visiteur ajouté à la réservation -->
                @if ( $addedVisitors )
                @foreach ( $addedVisitors as $key => $addedVisitor )
                    <div class="card col-span-full grid grid-cols-3 gap-4">
                        <label class="col-span-1">
                            <svg wire:click="removeAddedVisitor({{$key}})" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 float-left stroke-red-600 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>

                        </label>
                        <div class="col-span-2">
                            <span>{{ $addedVisitor["name"] ?? ""}} {{ $addedVisitor["surname"] ?? "" }}
                            @if ( isset($addedVisitor["birthyear"]))
                                , {{ $current_year - $addedVisitor["birthyear"] }} {{__("Ans")}}
                            @endif

                            </span>
                        </div>

                        <label class="col-span-1">{{ __("Nom de famille") }} *</label>
                        <input type="text" class="col-span-2" wire:model="addedVisitors.{{ $key }}.name">
                        @error('addedVisitors.'.$key.'.name') <span class="col-span-1"></span><span class="text-red-600 col-span-2">{{ $message }}</span> @enderror

                        <label class="col-span-1">{{ __("Prénom") }} *</label>
                        <input type="text" class="col-span-2" wire:model="addedVisitors.{{ $key }}.surname">
                        @error('addedVisitors.'.$key.'.surname') <span class="col-span-1"></span><span class="text-red-600 col-span-2">{{ $message }}</span> @enderror

                        <label class="col-span-1">{{ __("Année de naissance") }} *</label>
                        <input type="number" class="col-span-2" min=1900 max=2100 wire:model.debounce.1000ms="addedVisitors.{{ $key }}.birthyear">
                        @error('addedVisitors.'.$key.'.birthyear') <span class="col-span-1"></span><span class="text-red-600 col-span-2">{{ $message }}</span> @enderror

                        <div class="col-span-full">
                            <span class="font-bold">{{__("Participation aux frais")}} :</span>
                            @foreach ($profiles as $profile)
                                <label class="ml-4"><span class="font-semibold">{{ $profile->name }} :</span></label>
                                <input type="radio" wire:model="addedVisitors.{{$key}}.price" value="{{ $profile->price }}"><label class="mr-4">{{ $profile->euro }}</label>
                            @endforeach
                            <br>
                        </div>


                    </div>
                @endforeach
                @endif
            @endif
            <label class="col-span-1 mt-6">{{ __("Nous faire part d'une remarque") }}</label>
            <textarea class="col-span-2 mt-6" wire:model="reservation.remarks"></textarea>

            <div class="col-span-full text-center">
                <button type="submit">{{ __('Confirmer la réservation') }}</button>
            </div>
        </div>
    </form>
    @endunless
    @endif

</div>
