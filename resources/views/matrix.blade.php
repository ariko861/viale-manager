<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Matrix') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="{{ $matrix->gallery ? 'max-w-full' : 'max-w-7xl' }} mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-5">
                <div id="matrix-body" data-gallery="{{ $matrix->gallery ? 'true' : 'false' }}" data-roomID="{{ $matrix->roomID }}" data-homeserver="{{ $matrix->homeserver }}" data-userFilteredOut="{{ $matrix->filteredUser }}" data-giveRoomAddress="{{ $matrix->displayAddress ? 'true' : 'false'}}" data-displayDate="{{ $matrix->displayDate ? 'true' : 'false' }}"></div>
            </div>
        </div>
    </div>

</x-guest-layout>
