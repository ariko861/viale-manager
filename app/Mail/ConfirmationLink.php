<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\ReservationLink;
use App\Models\Option;

class ConfirmationLink extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $link;
    public $personalMessage = "";

    public function __construct(ReservationLink $link, $personalMessage = "")
    {
        //
        $this->link = $link;
        $this->personalMessage = $personalMessage;
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
                    ->subject(__("Confirmation de votre réservation à")." ".env("APP_NAME"))
                    ->view('emails.confirmation-link');
    }
}
