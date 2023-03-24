<?php

namespace App\Mail;

use App\Models\Option;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransportsAvailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $reservations;

    public function __construct($reservations)
    {
        //
        $this->reservations = $reservations;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $reply_to = Option::firstOrNew(['name' => 'email'])->value;
        
        $reservationsWithPlaces = collect([]);

        foreach($this->reservations as $reservation){
            $dateBegin = new Carbon($reservation->arrivaldate);
            $dateEnd = new Carbon($reservation->arrivaldate);
            $dateBegin->subDays(5);
            $dateEnd->addDays(5);
            $reswithPlaces = Reservation::where('confirmed', true)
                ->where('hasCarPlaces', true)
                ->whereDate('arrivaldate', '>=', $dateBegin)
                ->whereDate('arrivaldate', '<=', $dateEnd)
                ->orderBy('arrivaldate')
                ->get();
            // dd($reswithPlaces);
            foreach ($reswithPlaces as $res){
                $reservationsWithPlaces->push($res);
            }
        }

        $listReservations = $reservationsWithPlaces->unique();
        
        return $this->replyTo($reply_to, env("APP_NAME"))
            ->subject(__("véhicules pour")." ".env("APP_NAME"))
            ->view('emails.transports-disponibles', ['reservations' => $listReservations]);
    }
}
