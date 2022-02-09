<div>
    <div class="w-full border-4 p-5">
        <button class="w-full h-full rounded-full p-6" wire:click="$toggle('showUserForm')">{{ __("Ajouter un nouveau visiteur") }}</button>
    </div>

    @if ($showUserForm )
        <livewire:new-user-form >
    @endif
</div>
