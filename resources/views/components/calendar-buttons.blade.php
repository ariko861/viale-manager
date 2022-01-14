<div class="grid grid-cols-3">
    <button class="btn" wire:click="goToPreviousMonth">{{ __("Mois précédent") }}</button>

    <button class="btn" wire:click="goToCurrentMonth">{{ __("Revenir au mois présent") }}</button>

    <button class="btn" wire:click="goToNextMonth">{{ __("Mois suivant") }}</button>
</div>
