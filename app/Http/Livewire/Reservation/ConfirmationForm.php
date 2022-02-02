<?php

namespace App\Http\Livewire\Reservation;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Profile;

class ConfirmationForm extends Component
{
    public $reservation;
    public $link;
    public $arrivaldate;
    public $departuredate;
    public $minarrivaldate;
    public $maxarrivaldate;
    public $mindeparturedate;
    public $maxdeparturedate;
    public $price;


    public function getMaxDate($date)
    {
        $carbonDate = new Carbon($date);
        return $carbonDate->addDays($this->link->max_days_change);
    }

    public function getMinDate($date)
    {
        $carbonDate = new Carbon($date);
        return $carbonDate->subDays($this->link->max_days_change);
    }



    public function mount()
    {
        $arrival = $this->link->reservation->arrivaldate;
        $departure = $this->link->reservation->departuredate;
        $carbonArrivalDate = new Carbon($arrival);
        $this->maxarrivaldate = $this->getMaxDate($arrival)->format('Y-m-d');
        $this->minarrivaldate = $this->getMinDate($arrival)->format('Y-m-d');
        $this->arrivaldate = $arrival;

        $this->maxdeparturedate = $this->getMaxDate($departure)->format('Y-m-d');
        if ( $carbonArrivalDate->gt($this->getMinDate($departure) ) )
        {
            $this->mindeparturedate = $carbonArrivalDate->format('Y-m-d');

        } else {
            $this->mindeparturedate = $this->getMinDate($departure)->format('Y-m-d');
        }
        $this->departuredate = $departure;
        $this->phoneNumber = $this->link->reservation->contact_person->phone;

        $this->profiles = Profile::all();
        $this->price = Profile::firstWhere('is_default');
//         $this->price = 12;
        $this->otherVisitorsArray = $this->link->reservation->getNonContactVisitors();
        $this->birthyear = $this->link->reservation->contact_person->birthyear;
    }
    public function render()
    {
        return view('livewire.reservation.confirmation-form');
    }
}
