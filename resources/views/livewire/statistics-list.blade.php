<div>
    <h2 class="text-xl text-center my-6">{{ __("Statistiques") }}</h2>

    <div class="option-field my-4">
        <p><strong>{{__("Sélectionner par date de départ")}} :</strong> {{__("Du")}} <input type="date" wire:model="beginDate"> {{__("Au")}} <input wire:change="getReservationsInBetween" type="date" wire:model="endDate" min="{{$beginDate}}"></p>
        <br>
        <p><strong>{{__("Ou rechercher par nom des visiteurs")}} :</strong> <input type="text" wire:keyup="getReservationsByVisitorName" wire:model="visitorSearch"></p>
    </div>

    <div class="w-full grid grid-cols-2 gap-4 px-8">
        <div class="col-span-full">{{ $listTitle }}</div>
        <div class="text-center">
            <span><strong>{{__("Total des nuitées")}} : </strong>{{ $totalNights }}<span>
        </div>
        <div class="text-center">
            <span><strong>{{__("Total des participations")}} : </strong>{{ $totalIncome }}<span>
        </div>
    </div>

    <table class="mt-8 w-full table-auto border-collapse border border-gray-400">
    @php
        $thead_class="border-2 border-gray-400 bg-gray-100";
        $tbody_class="border-2 border-gray-400 p-1"
    @endphp
    <thead>
        <tr>
            <td class="{{ $thead_class }}">{{ __("Reservation ID") }}</td>
            <td class="{{ $thead_class }}">{{ __("Visiteurs") }}</td>
            <td class="{{ $thead_class }}">{{ __("Date de départ") }}</td>
            <td class="{{ $thead_class }}">{{ __("Nuitées") }}</td>
            <td class="{{ $thead_class }}">{{ __("Participation par nuitée") }}</td>
            <td class="{{ $thead_class }}">{{ __("Participation sur le séjour") }}</td>

        </tr>
    </thead>
    <tbody>
        @foreach ( $reservations as $reservation )
            <tr>
                <td class="{{ $tbody_class }}">{{ $reservation->id }}</td>
                <td class="{{ $tbody_class }}">
                    @foreach ( $reservation->visitors as $visitor )
                        <p>{{ $visitor->full_name }}</p>
                    @endforeach
                </td>
                <td class="{{ $tbody_class }}">{{ $reservation->departure }}</td>
                <td class="{{ $tbody_class }}">{{ $reservation->nights*$reservation->visitors->count() ?? $reservation->nights }}</td>
                <td class="{{ $tbody_class }}">{{ $reservation->per_night_euro }}</td>
                <td class="{{ $tbody_class }}">{{ $reservation->total_price_euro }}</td>
            </tr>
        @endforeach

   </tbody>
   </table>
</div>
