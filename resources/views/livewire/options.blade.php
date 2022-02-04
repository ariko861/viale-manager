<div>
    <h2 class="text-xl text-center mt-10 mb-6">{{ __("Options") }}</h2>

    <div class="w-full px-8 grid grid-cols-4 gap-4">
        <label class="col-span-1">{{ __("Changer le mail de réception des reservations") }}</label>
        <input class="col-span-2" type="text" wire:model="email">
        <button class="btn-submit" wire:click="saveMail">{{__("Sauvegarder le changement")}}</button>
        @error('email') <span class="col-span-1"></span><span class="red col-span-3 error">{{ $message }}</span> @enderror
    </div>


</div>
