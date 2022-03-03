<div>
    @if ($displayText)
        <div class="fixed top-0 left-0 right-0 bottom-0 bg-slate-600/75 overflow-auto z-50">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white shadow-xl sm:rounded-lg p-5 m-10">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" wire:click="cancelInformationDisplay" class="h-6 w-6 cursor-pointer float-right" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <h2 class="my-4">{{ $title }}</h2>
                <p>{{ $displayText }}</p>
            </div>
        </div>
    @endif
</div>
