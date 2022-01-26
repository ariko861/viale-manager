<div class="fixed top-0 left-0 right-0 bottom-0 bg-slate-600/75">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white shadow-xl sm:rounded-lg p-5 m-10">
        <div>
            <svg xmlns="http://www.w3.org/2000/svg" wire:click="cancelRoleForm" class="h-6 w-6 cursor-pointer float-right" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </div>
        @if ($modify )
            <h3 class="text-center uppercase text-lg m-4">{{ __("Modifier un role existant") }}</h3>
        @else
            <h3 class="text-center uppercase text-lg m-4">{{ __("Ajouter un nouveau role") }}</h3>
        @endif

        <form wire:submit.prevent="save" autocomplete="off">
            @csrf
            <div class="w-full px-8 grid grid-cols-3 gap-4">
                <label class="col-span-1">{{ __("Nom du role") }}</label>
                <input class="col-span-2" type="text" wire:model="role.name">
                @error('role.name') <span class="col-span-1"></span><span class="red col-span-2 error">{{ $message }}</span> @enderror

                @foreach ( $permissions as $permission )
                    <label class="col-span-1">{{ $permission->name }}</label>
                    <input class="col-span-2" type="checkbox" wire:model="permissionsInRole.{{ $permission->id }}"/>

                @endforeach


                <div class="col-span-full text-center">
                    <button type="submit">{{ __('Valider') }}</button>
                </div>
            </div>
        </form>

    </div>
</div>
