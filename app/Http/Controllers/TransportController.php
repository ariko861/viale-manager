<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\TransportLink;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransportController extends Controller
{
    //
    public function showTransports(Request $request)
    {
        $link_token = $request->get('link_token');
        $link = TransportLink::where('link_token', $link_token)->firstOrFail();

        return view('transports-available', ['reservations' => $link->getReservations(), 'link' => $link]);

    }
}
