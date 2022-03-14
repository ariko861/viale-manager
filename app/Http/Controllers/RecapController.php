<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\CarbonPeriod;
use App\Models\Reservation;

class RecapController extends Controller
{
    //
    public function printReservationRecap($beginDate, $endDate) {

        $period = CarbonPeriod::create($beginDate, $endDate);
        $recap = collect([]);
        foreach ($period as $day) {
            $dayRecap = [
                'date' => $day->translatedFormat('l d F Y'),
                'arrivals' => Reservation::where('confirmed', true)->whereDate('arrivaldate', $day)->get(),
                'departures' => Reservation::where('confirmed', true)->where('nodeparturedate', false)->whereDate('departuredate', $day)->get(),
            ];
            $recap->push($dayRecap);
        }
        return view('recap-reservations', ['recap' => $recap]);
    }
}
