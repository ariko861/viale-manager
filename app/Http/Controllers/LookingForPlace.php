<?php

namespace App\Http\Controllers;

use App\Mail\TransportsAvailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\Option;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class LookingForPlace extends Controller
{
    //
    public function lookForPlace(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email'
        ]);

        // obtenir la liste des futures réservations correspondant au mail
        $reservations = Reservation::whereHas('contactVisitor', function (Builder $query) use ($validated) {
            $query->where('email', '=', $validated["email"] );
        })->where('arrivaldate', '>', Carbon::now())->where('confirmed', true)->get();

        // $reservations = Reservation::all();

        if ($reservations && $reservations->count() > 0){
            $email = $validated["email"];
            Mail::to($email)->send(new TransportsAvailable($reservations));
        } else {
            $email = 'error';
        }

        $options = Option::all();
        return view('welcome', ['options' => $options, 'email' => $email]);
    }
}
