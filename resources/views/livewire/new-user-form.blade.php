<div class="absolute top-0 left-0 right-0 bottom-0 bg-slate-600/75">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white shadow-xl sm:rounded-lg p-5 m-10">
        <div>
            <svg xmlns="http://www.w3.org/2000/svg" wire:click="$emit('hideVisitorForm')" class="h-6 w-6 cursor-pointer float-right" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </div>
        <h3 class="text-center uppercase text-lg m-4">{{ __("Ajouter un nouveau visiteur") }}</h3>
        <form wire:submit.prevent="save">
            @csrf
            <div class="w-full px-8 grid grid-cols-3 gap-4">
                <label class="col-span-1">{{ __("Nom de famille") }}</label>
                <input class="col-span-2" type="text" wire:model="newvisitor.name">
                @error('newvisitor.name') <span class="col-span-1"></span><span class="red col-span-2 error">{{ $message }}</span> @enderror
                <label class="col-span-1">{{ __("Prénom") }}</label>
                <input class="col-span-2" type="text" wire:model="newvisitor.surname">
                @error('newvisitor.surname') <span class="col-span-1"></span><span class="red col-span-2 error">{{ $message }}</span> @enderror
                <label class="col-span-1">{{ __("Année de naissance") }}</label>
                <input class="col-span-2" type="number" wire:model="newvisitor.birthyear" min=1900 max=2050 step=1>
                @error('newvisitor.birthyear') <span class="col-span-1"></span><span class="red col-span-2 error">{{ $message }}</span> @enderror
                <label class="col-span-1">{{ __("Email") }}</label>
                <input class="col-span-2" type="email" wire:model="newvisitor.email">
                @error('newvisitor.email') <span class="col-span-1"></span><span class="red col-span-2 error">{{ $message }}</span> @enderror
                <label class="col-span-1">{{ __("Numéro de téléphone") }}</label>
                <input class="col-span-2" type="tel" wire:model="newvisitor.phone">
                @error('newvisitor.phone') <span class="col-span-1"></span><span class="red col-span-2 error">{{ $message }}</span> @enderror
                <label class="col-span-1">{{ __("Remarques ( à usage interne )") }}</label>
                <textarea class="col-span-2" wire:model="newvisitor.remarks"></textarea>
                @error('newvisitor.remarks') <span class="col-span-1"></span><span class="red col-span-2 error">{{ $message }}</span> @enderror

                <div class="col-span-full text-center">
                    <button class="rounded-full p-2 border-4" type="submit">{{ __('Valider') }}</button>
                </div>

            </div>
        </form>
    </div>
</div>
