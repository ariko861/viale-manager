<div>
    @if($confirmingDeletion == $modelId)
        @can($deleteRights)
            <button wire:click="$emitUp('deleteAction', {{ $options }} )" class="bg-red-800 text-white hover:bg-red-600 rounded-lg border">{{ __("Confirmer la suppression") }} {{ $messageDelete }} ?</button>
            <button wire:click="$set('confirmingDeletion','')">{{ __("Annuler la suppression")}}</button>
        @endcan
    @else
        @can($editRights)
            <svg wire:click="$emitUp('changeAction', {{ $options }} )" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 float-right cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
        @endcan
        @if ($deleteCondition)
            @can($deleteRights)
                <svg wire:click="$set('confirmingDeletion', '{{ $modelId }}' )" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 float-right mr-4 stroke-red-600 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            @endcan
        @endif

    @endif
</div>
