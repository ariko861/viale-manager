<div>
    {{-- Do your work, then step back. --}}
    <div class="w-full border-4 p-5">
        <button class="w-full h-full rounded-full p-6" wire:click="newLinkForm">{{ __("Créer un nouveau lien de transports") }}</button>
    </div>

    <h3 class="mt-8">Liens créés pour l'affichage des transports</h3>
    <table class="mt-8 w-full table-auto border-collapse border border-gray-400">
        <thead>
            <td class="border-2 border-gray-400 bg-gray-100">Informations affichées</td>
            <td class="border-2 border-gray-400 bg-gray-100">Lien</td>
            <td class="border-2 border-gray-400 bg-gray-100"></td>
        </thead>
    @foreach($transportsLinks as $link)
        <tr>
            <td class="border-2 border-gray-400 p-1">{{ $link->link_type }}</td>
            <td class="border-2 border-gray-400 p-1"><a href="{{ $link->getLink() }}" target="__blank">{{ $link->getLink() }}</a></td>
            <td class="border-2 border-gray-400 p-1">
                <svg wire:click="delete({{$link->id}})" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 float-right mr-4 stroke-red-600 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
{{--                <a wire:click="delete({{$link->id}})">Supprimer</a>--}}
            </td>
        </tr>
    @endforeach
    </table>

    @if ( $showLinkForm )
        <div class="fixed top-0 left-0 right-0 bottom-0 bg-slate-600/75 overflow-auto z-50">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white shadow-xl sm:rounded-lg p-5 m-10">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" wire:click="$set('showLinkForm', false)" class="h-6 w-6 cursor-pointer float-right" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <h3 class="text-center uppercase text-lg m-4">{{ __("Ajouter un nouveau lien d'accès aux transports") }}</h3>
                <form wire:submit.prevent="save" autocomplete="off">
                    @csrf
                    <div class="w-full px-8 grid grid-cols-3 gap-4">
                        <label class="col-span-1">{{ __("Date") }}</label>
                        <input class="col-span-2" type="date" wire:model="newlink.date">
                        @error('newvisitor.date') <span class="col-span-1"></span><span class="red col-span-2 error">{{ $message }}</span> @enderror
                        <label class="col-span-1">{{ __("Intervale de jours") }}</label>
                        <input class="col-span-2" type="number" min="0" wire:model="newlink.interval">
                        @error('newvisitor.interval') <span class="col-span-1"></span><span class="red col-span-2 error">{{ $message }}</span> @enderror

                        <div class="col-span-full text-center">
                            <button class="rounded-full p-2 border-4" type="submit">{{ __('Valider') }}</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
