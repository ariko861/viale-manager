<div>
    @unless ( $showQuickReservationForm )
    <div class="w-full border-4 p-5 border-blue-400 my-4">
        <button class="w-full h-full bg-blue-400 hover:bg-blue-600 rounded-full p-6" wire:click="$toggle('showQuickReservationForm')">{{ __("Nouvelle réservation et lien rapide") }}</button>
    </div>
    @endunless

    @if ( $showQuickReservationForm )
        <div class="p-8 border-4 border-blue-400 my-32">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" wire:click="$set('showQuickReservationForm', false)" class="h-6 w-6 cursor-pointer float-right" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>

            <livewire:visitor.email-search >
        <div>
    @endif

</div>

