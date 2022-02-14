<div>
    <input type="email" wire:model="email" placeholder="{{__('Saisissez ici un email valide')}}">
    <button wire:click="submit">{{__("Rechercher l'adresse email")}}</button>
    @error('email')<p class="error">{{ $message }}</p>@enderror

    @if ($showVisitorList)
    <div class="fixed top-0 left-0 right-0 bottom-0 bg-slate-600/75 overflow-auto">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white shadow-xl sm:rounded-lg p-5 m-10">
            <svg xmlns="http://www.w3.org/2000/svg" wire:click="$set('showVisitorList', false)" class="h-6 w-6 cursor-pointer float-right" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>

            <h2 class="text-lg text-center">{{ __("Voici la liste des visiteurs enregistrés avec cette adresse email") }}</h2>
            <p>{{__("Séléctionnez le nom correspondant en cliquant sur la liste")}}</p>
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
                    @foreach($visitors as $visitor)
                    <tr  wire:click="selectVisitor({{$visitor->id}})" class="hover:bg-blue-300">
                        <td class="{{ $tbody_class }} text-center cursor-pointer">{{ $visitor->full_name }}</td>
                        <td class="{{ $tbody_class }} text-center cursor-pointer">{{ $visitor->age }}</td>
                        <td class="{{ $tbody_class }} text-center cursor-pointer">{{ $visitor->phone }}</td>
                    </tr>
                    @endforeach
                    <tr class=" bg-red-100 hover:bg-red-400" wire:click="selectVisitor">
                        <td colspan=3 class="{{ $tbody_class }} text-center cursor-pointer">{{ $user == 'visitor' ? __("Vous n'êtes pas dans cette liste") : __("Le visiteur recherché n'est pas dans cette liste") }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    @endif
<div>
