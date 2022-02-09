<div>
    <h2 class="text-xl text-center mt-10 mb-6">{{ __("Options") }}</h2>

<!--     Pour régler l'adresse email -->
    <div class="w-full px-8 grid grid-cols-4 gap-4 my-2">
        <label class="col-span-1">{{ __("Changer le mail de réception des reservations") }}</label>
        <input class="col-span-2" type="text" wire:model="email.value">
        <button class="btn-submit" wire:click="save('email')">{{__("Sauvegarder le changement")}}</button>
        @error('email.value') <span class="col-span-1"></span><span class="red col-span-3 error">{{ $message }}</span> @enderror
    </div>

    <!--     Pour régler le numéro de téléphone -->
    <div class="w-full px-8 grid grid-cols-4 gap-4 my-2">
        <label class="col-span-1">{{ __("Changer le téléphone affiché") }}</label>
        <input class="col-span-2" type="text" wire:model="phone.value">
        <button class="btn-submit" wire:click="save('phone')">{{__("Sauvegarder le changement")}}</button>
        @error('phone.value') <span class="col-span-1"></span><span class="red col-span-3 error">{{ $message }}</span> @enderror
    </div>

        <!--     Pour régler l'adresse -->
    <div class="w-full px-8 grid grid-cols-4 gap-4 my-2">
        <label class="col-span-1">{{ __("Changer l'adresse affiché") }}</label>
        <input class="col-span-2" type="text" wire:model="address.value">
        <button class="btn-submit" wire:click="save('address')">{{__("Sauvegarder le changement")}}</button>
        @error('address.value') <span class="col-span-1"></span><span class="red col-span-3 error">{{ $message }}</span> @enderror
    </div>

        <!--     Message après confirmation -->
    <div class="w-full px-8 grid grid-cols-4 gap-4 my-2">
        <label class="col-span-1">{{ __("Message envoyé à la confirmation de la réservation") }}</label>
        <textarea class="col-span-2" type="text" wire:model="confirmation_message.value"></textarea>
        <button class="btn-submit" wire:click="save('confirmation_message')">{{__("Sauvegarder le changement")}}</button>
        @error('confirmation_message.value') <span class="col-span-1"></span><span class="red col-span-3 error">{{ $message }}</span> @enderror
    </div>

    @if ($email->value)
        <div class="w-full px-8 my-2">
            <button class="bg-lime-500 hover:bg-lime-800" wire:click="testEmail">{{__("Tester l'adresse email")}}</button>
        </div>
    @endif




</div>
