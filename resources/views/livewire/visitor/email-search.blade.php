<div>
    <input type="email" wire:model="email" placeholder="{{__('Saisissez ici un email valide')}}">
    <button wire:click.prevent="submit">{{__("Rechercher l'adresse email")}}</button>
    @error('email')<p class="error">{{ $message }}</p>@enderror
    @if ($showVisitorList)
    <div class="fixed top-0 left-0 right-0 bottom-0 bg-slate-600/75 overflow-auto">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white shadow-xl sm:rounded-lg p-5 m-10">
            <svg xmlns="http://www.w3.org/2000/svg" wire:click="cancelSearch" class="h-6 w-6 cursor-pointer float-right" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>

            <h2 class="text-lg text-center">{{ __("Voici la liste des visiteurs enregistrés avec cette adresse email") }}</h2>
            <table class="mt-8 w-full table-auto border-separate border border-gray-400 border-spacing">
                @php
                    $thead_class="border-2 border-gray-400 bg-gray-100";
                    $tbody_class="border-2 border-gray-400 p-1"
                @endphp

                <thead>
                    <tr>
                        <td class="{{ $thead_class }}"><i>{{ __("Nom") }}</i></td>
                        <td class="{{ $thead_class }}"><i>{{ __("Age") }}</i></td>
                        <td class="{{ $thead_class }}"><i>{{ __("Téléphone") }}</i></td>
                    </tr>
                </thead>
                <tbody>
                    @if ( $visitors->count() )
                        @foreach($visitors as $visitor)
                        <tr  wire:click.prevent="selectVisitor({{$visitor->id}})" class="{{$selectedVisitor == $visitor->id ? 'bg-blue-400' : 'cursor-pointer hover:bg-blue-400'}}">
                            <td class="{{ $tbody_class }} text-center {{ $selectedVisitor == $visitor->id ? 'border-4 border-red-400' : '' }}">{{ $visitor->full_name }}</td>
                            <td class="{{ $tbody_class }} text-center {{ $selectedVisitor == $visitor->id ? 'border-4 border-red-400' : '' }}">{{ $visitor->age }}</td>
                            <td class="{{ $tbody_class }} text-center {{ $selectedVisitor == $visitor->id ? 'border-4 border-red-400' : '' }}">{{ $visitor->phone }}</td>
                        </tr>
                        @endforeach
                    @else
                        <tr><td colspan=3>{{ __("La liste est vide, cet email n'a jamais été enregistré") }}</td></tr>
                    @endif
                    <tr class="{{ $selectedVisitor == 'none' ? 'bg-lime-400' : ' cursor-pointer bg-lime-100 hover:bg-lime-400' }}" wire:click="selectVisitor">
                        <td colspan=3 class="{{ $tbody_class }} text-center {{ $selectedVisitor == 'none' ? 'border-4 border-red-400' : '' }}">{{ __("Enregistrer un nouveau visiteur avec l'adresse") }} {{ $email }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="text-center mt-4">
                <button wire:click="cancelSearch" class="btn-warning px-8">{{__("Annuler et saisir un autre email")}}</button>
                @if ( $selectedVisitor )
                    <button class="btn-submit px-16" wire:click="submitMail">{{__("Valider")}}</button>
                @endif
            </div>
        </div>
    </div>

    @endif
</div>
