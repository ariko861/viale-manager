<div>
    <h2 class="text-xl text-center">{{ __("Profiles") }}</h2>
    <table class="mt-8 w-full table-auto border-collapse border border-gray-400">
    @php
        $thead_class="border-2 border-gray-400 bg-gray-100";
        $tbody_class="border-2 border-gray-400 p-1"
    @endphp
    <thead>
        <tr>
            <td class="{{ $thead_class }}">{{ __("Nom du profil") }}</td>
            <td class="{{ $thead_class }}">{{ __("Prix") }}</td>
            <td class="{{ $thead_class }}">{{ __("Description") }}</td>
            <td class="{{ $thead_class }}">{{ __("Modifications") }}</td>
        </tr>
    </thead>
    <tbody>
        @foreach ( $profiles as $profile )
            <tr>
                <td class="{{ $tbody_class }}">{{ $profile["name"] }}</td>
                <td class="{{ $tbody_class }}">{{ $profile["euro"] }}</td>
                <td class="{{ $tbody_class }}">{{ $profile["remarks"] }}</td>
                <td class="{{ $tbody_class }}">
                @if($confirming===$profile->id)
                <button wire:click="deleteProfile({{ $profile->id }})" class="bg-red-800 text-white w-32 px-4 py-1 hover:bg-red-600 rounded border">{{ __("Confirmer la suppression ?") }}</button>
                @else
                    <svg wire:click="engageProfileChange({{ $profile->id }})" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 float-left cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <svg wire:click="confirmDelete({{ $profile->id }})" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 float-right stroke-red-600 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                @endif
                </td>
            </tr>
        @endforeach
        @if ( $creatingProfile )
            <tr>
                <td class="{{ $tbody_class }}"><input type="text" wire:model="newProfile.name"></td>
                <td class="{{ $tbody_class }}"><input type="number" wire:model="newProfile.price"></td>
                <td class="{{ $tbody_class }}"><textarea wire:model="newProfile.remarks"></textarea></td>
                <td class="{{ $tbody_class }}"><button class="btn" wire:click="saveProfile">{{ __("Enregistrer") }}</button></td>

            </tr>
        @endif

   </tbody>
   </table>
   @if ( ! $creatingProfile )
    <button wire:click="engageCreateProfile">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </button>
    @endif
</div>
