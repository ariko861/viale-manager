<div>
    @if ($link)
        @if ($link->times_used > 0)
            <h1>{{ __('Modification de votre réservation à') }} {{ env('APP_NAME') }}</h1>
        @else    
        <h1 class="my-4">{{ __('Merci de confirmer votre réservation à') }} {{ env('APP_NAME') }}</h1>
        @endif
        
        <br>
        @if ($reservation_link_messages)
            <div class="w-full p-4 border-4 mt-2 mb-6">
                @foreach ($reservation_link_messages as $message)
                    <p class="my-2">{!! $message !!}
                    <p>
                @endforeach
            </div>
        @endif

        @if ($showEmailForm)
            <p class="mb-4">
                {{ __('Veuillez saisir une adresse email pour voir si vous nous retrouvons dans nos précédentes inscriptions') }}
                :</p>
            <livewire:visitor.email-search user="visitor" visitorKey="contact">
        @endif

        @unless($showEmailForm)
            <div>
                @csrf
                <div class="w-full px-8 grid grid-cols-3 gap-4">

                    <h3 class="col-span-full mt-6 mb-4">{{ __('Personne de contact') }}</h3>

                    <label class="col-span-1">{{ __('Nom') }}</label>
                    @if ($unknown->contains('name'))
                        <input type="text" class="col-span-2" wire:model="contact_person.name">
                        @error('contact_person.name')
                            <span class="col-span-1"></span><span class="text-red-600 col-span-2">{{ $message }}</span>
                        @enderror
                    @else
                        <span class="col-span-2">{{ $contact_person->name }}</span>
                    @endif

                    <label class="col-span-1">{{ __('Prénom') }}</label>
                    @if ($unknown->contains('surname'))
                        <input type="text" class="col-span-2" wire:model="contact_person.surname">
                        @error('contact_person.surname')
                            <span class="col-span-1"></span><span class="text-red-600 col-span-2">{{ $message }}</span>
                        @enderror
                    @else
                        <span class="col-span-2">{{ $contact_person->surname }}</span>
                    @endif

                    <label class="col-span-1">{{ __('Email') }}</label>
                    @if ($unknown->contains('email'))
                        <input type="email" class="col-span-2" wire:model="contact_person.email">
                        @error('contact_person.email')
                            <span class="col-span-1"></span><span class="text-red-600 col-span-2">{{ $message }}</span>
                        @enderror
                    @else
                        <span class="col-span-2">{{ $contact_person->email }}</span>
                    @endif

                    <label class="col-span-1">{{ __('Numéro de téléphone') }}</label>
                    <input type="tel" class="col-span-2" wire:model="contact_person.phone">
                    @error('contact_person.phone')
                        <span class="col-span-1"></span><span class="text-red-600 col-span-2">{{ $message }}</span>
                    @enderror

                    <label class="col-span-1">{{ __('Année de naissance') }}</label>
                    <input type="number" class="col-span-2" min=1900 max=2100 wire:model="contact_person.birthyear">
                    @error('contact_person.birthyear')
                        <span class="col-span-1"></span><span class="text-red-600 col-span-2">{{ $message }}</span>
                    @enderror


                    <h3 class="col-span-full">{{ __('Votre participation aux frais') }} :</h3>

                    @foreach ($profiles as $profile)
                        <label class="col-span-1"><span class="font-semibold">{{ $profile->name }} :</span>
                            {{ $profile->remarks }}</label>
                        <div class="col-span-2">
                            <input type="radio" wire:model="price"
                                value="{{ $profile->price }}"><label>{{ $profile->euro }}</label>
                        </div>
                    @endforeach

                    @if ($link->max_days_change > 0)
                        <h3 class="col-span-full">{{ __('Modifier vos dates') }} : </h3>

                        <label class="col-span-1">{{ __("Date d'arrivée") }}</label>
                        <input id="resa-arrivaldate" class="col-span-2 datepick" wire:model="reservation.arrivaldate"
                            wire:change="setMinDepartureDate" max="{{ $maxarrivaldate }}" min="{{ $minarrivaldate }}"
                            type="date" />
                        @error('reservation.arrivaldate')
                            <span class="col-span-1"></span><span class="text-red-600 col-span-2">{{ $message }}</span>
                        @enderror

                        <label class="col-span-1">{{ __('Date de départ') }}</label>
                        <input id="resa-departuredate" class="col-span-2 datepick" wire:model="reservation.departuredate"
                            max="{{ $maxdeparturedate }}" min="{{ $mindeparturedate }}" type="date" />
                        @error('reservation.departuredate')
                            <span class="col-span-1"></span><span class="text-red-600 col-span-2">{{ $message }}</span>
                        @enderror
                    @endif

                    @if ($otherVisitorsArray->count())
                        <h3 class="col-span-full">{{ __('Autres visiteurs') }} :</h3>
                        <ul class="col-span-full list-disc list-inside">
                            @foreach ($otherVisitorsArray as $key => $otherVisitor)
                                <li>{{ $otherVisitor['full_name'] }}, {{ $otherVisitor['age'] }} {{ __('Ans') }}
                                    <br>
                                    <span class="font-bold">{{ __('Participation aux frais') }} :</span>
                                    @foreach ($profiles as $profile)
                                        <label class="ml-4"><span class="font-semibold">{{ $profile->name }}
                                                :</span></label>
                                        <input type="radio" wire:model="otherVisitorsArray.{{ $key }}.price"
                                            value="{{ $profile->price }}"><label
                                            class="mr-4">{{ $profile->euro }}</label>
                                    @endforeach
                                    <br>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    @if ($link->max_added_visitors)
                        <br>
                        <h3 class="col-span-full mt-6 mb-4">
                            {{ __('Vous êtes accompagné·e ? Veuillez remplir les informations de votre/vos invité·e·s') }}
                            :</h3>

                        <!--    Pour chaque visiteur ajouté à la réservation -->
                        @if ($addedVisitors)
                            @foreach ($addedVisitors as $key => $addedVisitor)
                                <div class="card col-span-full">
                                    <div class="w-full grid grid-cols-3 gap-4">
                                        <label class="col-span-1">
                                            <svg wire:click="removeAddedVisitor({{ $key }})"
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-6 w-6 float-left stroke-red-600 cursor-pointer" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>

                                        </label>
                                        <div class="col-span-2">
                                            <span>{{ $addedVisitor['visitor']['name'] ?? '' }}
                                                {{ $addedVisitor['visitor']['surname'] ?? '' }}
                                                @if (isset($addedVisitor['visitor']['birthyear']))
                                                    , {{ $current_year - $addedVisitor['visitor']['birthyear'] }}
                                                    {{ __('Ans') }}
                                                @endif

                                            </span>
                                        </div>
                                    </div>

                                    <div class="my-6 w-full border-2 text-center p-6">
                                        @if ($addedVisitor['showEmailForm'] === true)
                                            <p>{{ __('Cette personne est déjà venue ? Retrouvez là par son adresse email') }}
                                                :</p>
                                            <livewire:visitor.email-search :visitorKey="$key"
                                                :wire:key="'addedVisitor-'.$key" />
                                        @elseif ($addedVisitor['showEmailForm'] === 'found')
                                            <p>{{ __('Cet email existe bien dans notre historique, nous avons rempli les informations pour vous') }}
                                            </p>
                                        @elseif ($addedVisitor['showEmailForm'] === 'notfound')
                                            <p>{{ __("Cet email n'existe pas dans notre historique, vous pouvez remplir les informations à la main") }}
                                            </p>
                                        @endif
                                    </div>

                                    <div class="my-6 w-full grid grid-cols-3 gap-4">
                                        <label class="col-span-1">{{ __('Nom de famille') }} *</label>
                                        @if ($addedVisitor['showEmailForm'] === 'found' && $addedVisitor['visitor']['name'])
                                            <p class="col-span-2">{{ $addedVisitor['visitor']['name'] }}</p>
                                        @else
                                            <input type="text" class="col-span-2"
                                                wire:model="addedVisitors.{{ $key }}.visitor.name">
                                            @error('addedVisitors.' . $key . '.name')
                                                <span class="col-span-1"></span><span
                                                    class="text-red-600 col-span-2">{{ $message }}</span>
                                            @enderror
                                        @endif
                                        <label class="col-span-1">{{ __('Prénom') }} *</label>
                                        @if ($addedVisitor['showEmailForm'] === 'found' && $addedVisitor['visitor']['surname'])
                                            <p class="col-span-2">{{ $addedVisitor['visitor']['surname'] }}</p>
                                        @else
                                            <input type="text" class="col-span-2"
                                                wire:model="addedVisitors.{{ $key }}.visitor.surname">
                                            @error('addedVisitors.' . $key . '.surname')
                                                <span class="col-span-1"></span><span
                                                    class="text-red-600 col-span-2">{{ $message }}</span>
                                            @enderror
                                        @endif

                                        @if ($addedVisitor['showEmailForm'] !== true && $addedVisitor['visitor']['email'])
                                            <label class="col-span-1">{{ __('Email') }}</label>
                                            <p class="col-span-2">{{ $addedVisitor['visitor']['email'] ?? '' }}</p>
                                        @endif
                                        <label class="col-span-1">{{ __('Année de naissance') }} *</label>
                                        <input type="number" class="col-span-2" min=1900 max=2100
                                            wire:model.debounce.1000ms="addedVisitors.{{ $key }}.visitor.birthyear">
                                        @error('addedVisitors.' . $key . '.birthyear')
                                            <span class="col-span-1"></span><span
                                                class="text-red-600 col-span-2">{{ $message }}</span>
                                        @enderror

                                        <div class="col-span-full">
                                            <span class="font-bold">{{ __('Participation aux frais') }} :</span>
                                            @foreach ($profiles as $profile)
                                                <label class="ml-4"><span class="font-semibold">{{ $profile->name }}
                                                        :</span></label>
                                                <input type="radio"
                                                    wire:model="addedVisitors.{{ $key }}.price"
                                                    value="{{ $profile->price }}"><label
                                                    class="mr-4">{{ $profile->euro }}</label>
                                            @endforeach
                                            <br>
                                        </div>
                                    </div>


                                </div>
                            @endforeach
                        @endif
                        @unless($forbidAddingVisitors)
                            <div class="col-span-full">
                                <button class="btn bg-blue-400 w-full"
                                    wire:click.prevent="addVisitor">{{ __('Ajouter un autre visiteur') }}</button>
                            </div>
                        @endunless
                    @endif

                    <h3 class="col-span-full">{{ __('Vous cherchez/proposez un transport ?') }} :</h3>

                    <label class="col-span-1">{{ __('Vous cherchez une·des place·s de voiture') }}</label>
                    <input type="checkbox" class="col-span-2" wire:model="reservation.lookForCarPlaces"  wire:click="$set('reservation.hasCarPlaces', false)">

                    <label class="col-span-1">{{ __('Vous proposez une·des places de voiture') }}</label>
                    <input type="checkbox" class="col-span-2" wire:model="reservation.hasCarPlaces" wire:click="$set('reservation.lookForCarPlaces', false)">

                    @if ($reservation->hasCarPlaces || $reservation->lookForCarPlaces)
                        <label class="col-span-1">{{ __('Combien de places proposez vous/cherchez vous ?') }}</label>
                        <input type="number" class="col-span-2" min=0 max=10 wire:model="reservation.numberCarPlaces">

                        <label class="col-span-1">{{ __('Au départ de ?') }}</label>
                        <input type="text" class="col-span-2" min=0 max=10 wire:model="reservation.coming_from">
                        
                        <div class="col-span-3"><p>{{ __('Vous acceptez de transmettre vos coordonnées aux personnes ayant un réservation à la Viale à des dates proches') }} :</p>
                        </div>
                            <label class="col-span-1">{{ __('Votre email') }}</label>
                        <input type="checkbox" class="col-span-2" wire:model="reservation.shareEmail">

                        <label class="col-span-1">{{ __('Votre téléphone') }}</label>
                        <input type="checkbox" class="col-span-2" wire:model="reservation.sharePhone">
                    @endif

                    <label class="col-span-1 mt-6">{{ __("Nous faire part d'une remarque") }}</label>
                    <textarea class="col-span-2 mt-6" wire:model="reservation.remarks"></textarea>

                    <div class="col-span-full text-center">
                        <button wire:click="save">{{ __('Confirmer la réservation') }}</button>
                    </div>
                </div>
            </div>
        @endunless
    @endif

</div>
