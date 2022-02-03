<div>
    <h1>{{ __("Merci de confirmer votre réservation à ")}}{{env('APP_NAME')}}</h1>
    <br>
    <form wire:submit.prevent="save" autocomplete="off">
        @csrf
        <div class="w-full px-8 grid grid-cols-3 gap-4">
            <span class="col-span-full">{{ $devMessage }}</span>

            <label class="col-span-1">{{ __("Nom de la personne de contact") }}</label>
            <span class="col-span-2">{{ $link->reservation->contact_person->full_name }}</span>

            <label class="col-span-1">{{ __("Email") }}</label>
            <span class="col-span-2">{{ $link->reservation->contact_person->email }}</span>

            <label class="col-span-1">{{ __("Numéro de téléphone") }}</label>
            <input type="tel" class="col-span-2" wire:model="phoneNumber">
            @error('phoneNumber') <span class="col-span-1"></span><span class="text-red-600 col-span-2">{{ $message }}</span> @enderror

            <label class="col-span-1">{{ __("Année de naissance") }}</label>
            <input type="number" class="col-span-2" min=1900 max=2100 wire:model="birthyear">
            @error('birthyear') <span class="col-span-1"></span><span class="text-red-600 col-span-2">{{ $message }}</span> @enderror


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
                <input class="col-span-2" wire:model="arrivaldate" wire:change="setMinDepartureDate" max="{{ $maxarrivaldate }}" min="{{ $minarrivaldate }}" type="date" />
                @error('arrivaldate') <span class="col-span-1"></span><span class="text-red-600 col-span-2">{{ $message }}</span> @enderror

                <label class="col-span-1">{{ __("Date de départ") }}</label>
                <input class="col-span-2" wire:model="departuredate" max="{{ $maxdeparturedate }}" min="{{ $mindeparturedate }}" type="date" />
                @error('departuredate') <span class="col-span-1"></span><span class="text-red-600 col-span-2">{{ $message }}</span> @enderror


            @endif

            @if ($otherVisitorsArray)
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
                <br>
            @endif

            @if ( $link->max_added_visitors )
                @unless ( $forbidAddingVisitors )
                    <div class="col-span-full">
                        <button class="btn w-full" wire:click.prevent="addVisitor">{{ __("Ajouter un autre visiteur") }}</button>
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

                        <label class="col-span-1">{{ __("Nom de famille") }}</label>
                        <input type="text" class="col-span-2" wire:model="addedVisitors.{{ $key }}.name">
                        @error('addedVisitors.'.$key.'.name') <span class="col-span-1"></span><span class="text-red-600 col-span-2">{{ $message }}</span> @enderror

                        <label class="col-span-1">{{ __("Prénom") }}</label>
                        <input type="text" class="col-span-2" wire:model="addedVisitors.{{ $key }}.surname">
                        @error('addedVisitors.'.$key.'.surname') <span class="col-span-1"></span><span class="text-red-600 col-span-2">{{ $message }}</span> @enderror

                        <label class="col-span-1">{{ __("Année de naissance") }}</label>
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
            <textarea class="col-span-2 mt-6" wire:model="remark"></textarea>

            <div class="col-span-full text-center">
                <button type="submit">{{ __('Valider') }}</button>
            </div>
        </div>
    </form>

</div>
