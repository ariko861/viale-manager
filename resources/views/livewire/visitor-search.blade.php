<div>
    <div class="w-full relative">
        <input class="w-full disabled:opacity-50" type="text" wire:model="searchQuery" wire:keyup="searchVisitor" @if ( $visitorSet ) disabled @endif>
        @if ( $visitorSet )
            <svg wire:click="cancelVisitorSelection" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 absolute top-2 right-1 stroke-red-600 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        @endif
    </div>
    <ul class="col-span-2 bg-white left-0 right-0">
    @foreach ($visitorsArray as $visitor)
        <li class="p-2 border-2 cursor-pointer" wire:click="setContactPerson({{$visitor}})">{{ $visitor['full_name'] }}</li>
    @endforeach
    @if ( $noResult )
        <li class="p-2 border-2">{{ __("Pas de resultat !") }}</li>
    @endif
    @if ( $displayAddVisitorButton )
        <li class="p-2 border-2 cursor-pointer"><a href="#" wire:click.prevent="$emit('newVisitorForm')">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 float-left" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ __("Ajouter un nouveau visiteur") }}</span>
        </a></li>
    @endif

    </ul>
</div>
