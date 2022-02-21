<?php

namespace App\Mail;

use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Reservation;
use App\Models\Option;

class RecapitulatifConfirmationReservation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $reservation;
    public $confirmation_messages;
    public $isEmail = true;
    public $showPrice;

    public function __construct(Reservation $reservation, $showPrice = false)
    {
        //
        $this->reservation = $reservation;
        $this->showPrice = $showPrice;
        $this->confirmation_messages = collect([]);
        $confirmation_messages = Option::where('name', 'confirmation_message')->orderBy('id')->get();
        foreach ($confirmation_messages as $message )
        {
            $this->confirmation_messages->push( Str::markdown($message->value) );
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $reply_to = Option::firstOrNew(['name' => 'email'])->value;

        return $this->replyTo($reply_to, env("APP_NAME"))
                    ->subject(__("Récapitulatif de votre réservation à")." ".env("APP_NAME"))
                    ->view('livewire.reservation.success-confirm');
    }
}
