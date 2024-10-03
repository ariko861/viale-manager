<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReservationLink;

class ConfirmationController extends Controller
{
    //
    public function showConfirmationForm(Request $request)
    {
        $link_token = $request->get('link_token');
        $link = ReservationLink::where('link_token', $link_token)->firstOrFail();
        $reservation = $link->reservation;

        return view('confirmation', ['reservation' => $reservation, 'link' => $link]);
    }

    public function newShowConfirmationForm(string $link_token)
    {
        $link = ReservationLink::where('link_token', $link_token)->firstOrFail();
        $reservation = $link->reservation;

        return view('confirmation', ['reservation' => $reservation, 'link' => $link]);
    }

}
