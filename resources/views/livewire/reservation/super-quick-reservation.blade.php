<div>
    <div class="p-5 fixed bottom-8 right-8">
        <button class="bg-blue-400 hover:bg-blue-600 rounded-full p-6 hover-discover h-24" wire:click="createSuperQuickReservation">
            <span class="cover-child">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </span>
            <span class="discover-child text-lg text-bold text-white">{{ __("Nouvelle réservation et lien super rapide") }}</span>

        </button>
    </div>
</div>
