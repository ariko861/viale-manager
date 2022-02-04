<?php

namespace App\Http\Livewire\Reservation;

use Carbon\Carbon;
use Livewire\Component;
// use Illuminate\Support\Collection;
use App\Models\Profile;
use App\Models\Visitor;

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
    public $birthyear;
    public $profiles;
    public $otherVisitorsArray;
    public $addedVisitors;
    public $forbidAddingVisitors;
    public $current_year;
    public $remark;
    public $phoneNumber;

    public $devMessage;

        protected $rules = [
            'phoneNumber' => 'required',
            'birthyear' => 'required|integer|between:1900,2100',
            'arrivaldate' => 'required|date',
            'departuredate' => 'required|date',
            'remark' => '',
            'addedVisitors.*.name' => 'required|string',
            'addedVisitors.*.surname' => 'required|string',
            'addedVisitors.*.birthyear' => 'required|integer|between:1900,2100',

        ];


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

    public function addVisitor()
    {
        if ($this->addedVisitors->count() < $this->link->max_added_visitors)
        {
            $visitor = new Visitor();
            $this->addedVisitors->push(['visitor' => $visitor, 'price' => $this->price]);
            if ($this->addedVisitors->count() == $this->link->max_added_visitors)
            {
                $this->forbidAddingVisitors = true;
            }
        }
    }

    public function removeAddedVisitor($key)
    {
        $this->addedVisitors->pull($key);
        $this->forbidAddingVisitors = false;
    }

    public function checkContactPersonChange($input, $attribute)
    {
        if ($input != $this->link->reservation->contact_person[$attribute])
        {
            $this->link->reservation->contact_person[$attribute] = $input;
        }
    }

    public function checkReservationChange($input, $attribute)
    {
        if ($input != $this->link->reservation[$attribute])
        {
            $this->link->reservation[$attribute] = $input;
        }
    }

    public function setMinDepartureDate()
    {
        $departure = $this->link->reservation->departuredate;
        $carbonArrivalDate = new Carbon($this->arrivaldate);

        if ( $carbonArrivalDate->gt($this->getMinDate($departure) ) )
        {
            $this->mindeparturedate = $carbonArrivalDate->format('Y-m-d');

        } else {
            $this->mindeparturedate = $this->getMinDate($departure)->format('Y-m-d');
        }
    }

    public function save()
    {
        $this->validate();
        // Check if any changes for the contact Person
        $this->checkContactPersonChange($this->phoneNumber, 'phone');
        $this->checkContactPersonChange($this->birthyear, 'birthyear');
        // Update Price for the contact Person
        $this->link->reservation->visitors()->updateExistingPivot($this->link->reservation->contact_person->id, [
            'price' => $this->price,
        ]);
        $this->link->reservation->contact_person->save();

        // Update prices for pre-register other visitors
        foreach ($this->otherVisitorsArray as $otherVisitor)
        {
            $this->link->reservation->visitors()->updateExistingPivot($otherVisitor["id"], [
                'price' => $otherVisitor["price"],
            ]);
        }

        // update dates
        $this->checkReservationChange($this->arrivaldate, 'arrivaldate');
        $this->checkReservationChange($this->departuredate, 'departuredate');
        $this->link->reservation->remarks = $this->remark;

        // save added visitors
        foreach ($this->addedVisitors as $addedVisitor)
        {
            $visitor = Visitor::create([
                'name' => $addedVisitor["name"],
                'surname' => $addedVisitor["surname"],
                'birthyear' => $addedVisitor["birthyear"],
                'confirmed' => false,
            ]);

            $this->link->reservation->visitors()->attach($visitor, [ 'price' => $addedVisitor["price"], 'contact' => false ]);
        }
        $this->link->reservation->confirmed = true;

        $this->link->reservation->save();

        $this->emit('showRecapReservation', $this->link->reservation->id);

        $this->link->delete();
        $this->link = false;
    }



    public function mount()
    {
        $arrival = $this->link->reservation->arrivaldate;
        $departure = $this->link->reservation->departuredate;
        $carbonArrivalDate = new Carbon($arrival);

        $this->fill([
            'addedVisitors' => collect([]),
            'current_year' => Carbon::now()->year,
            'arrivaldate' => $arrival,
            'departuredate' => $departure,
            'phoneNumber' => $this->link->reservation->contact_person->phone,
            'profiles' => Profile::all(),
            'price' => Profile::firstWhere('is_default', true)->price ?? Profile::first()->price,
            'birthyear' => $this->link->reservation->contact_person->birthyear,
        ]);

        $this->otherVisitorsArray = $this->link->reservation->getNonContactVisitors()->each(function($item, $key) { $item["price"] = $this->price; });

        $this->maxarrivaldate = $this->getMaxDate($arrival)->format('Y-m-d');
        $this->minarrivaldate = $this->getMinDate($arrival)->format('Y-m-d');

        $this->maxdeparturedate = $this->getMaxDate($departure)->format('Y-m-d');
        $this->setMinDepartureDate();

    }
    public function render()
    {
        return view('livewire.reservation.confirmation-form');
    }
}
