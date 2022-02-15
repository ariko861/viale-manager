<div>
    <div class="w-full border-4 p-5">
        <button class="w-full h-full rounded-full p-6" wire:click="$emit('newVisitorForm')">{{ __("Ajouter un nouveau visiteur") }}</button>
    </div>

    <livewire:visitor.new-visitor-form >

</div>
