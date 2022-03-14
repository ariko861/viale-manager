<div>
    @if ($showResaRecap)
    <div class="fixed top-0 left-0 right-0 bottom-0 bg-slate-600/75 overflow-auto z-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white shadow-xl sm:rounded-lg p-5 m-10">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" wire:click="hideReservationRecap" class="h-6 w-6 cursor-pointer float-right" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>

            <h1 class="my-6">{{__("Afficher le récapitulatif des arrivées et départs pour la période")}} :</h1>

            <p class="my-4">{{__("Du")}} <input type="date" wire:model="beginDate"/> {{__("au")}} <input type="date" wire:model="endDate" min="{{ $beginDate }}"/></p>
            <p class="my-4"><button class="mx-4" wire:click="prevWeek">{{__("Semaine précédente")}}</button><button class="mx-4" wire:click="thisWeek">{{__("Semaine actuelle")}}</button><button class="mx-4" wire:click="nextWeek">{{__("Semaine suivante")}}</button></p>
            <a href="/recapitulatif/reservations/{{$beginDate}}/{{$endDate}}" target="_blank"><button class="my-6 px-4 btn-submit" >{{__("Valider")}}</button></a>

        </div>
    </div>
    @endif
</div>
