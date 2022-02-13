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
    public $contact_person;
    public $minarrivaldate;
    public $maxarrivaldate;
    public $mindeparturedate;
    public $maxdeparturedate;
    public $price;
    public $unknown;
    public $profiles;
    public $otherVisitorsArray;
    public $addedVisitors;
    public $forbidAddingVisitors;
    public $current_year;

        protected $rules = [
            'contact_person.name' => 'required|string',
            'contact_person.surname' => 'required|string',
            'contact_person.phone' => 'required',
            'contact_person.email' => 'required|string',
            'contact_person.birthyear' => 'required|integer|between:1900,2100',
            'price' => '',
            'reservation.arrivaldate' => 'required|date',
            'reservation.departuredate' => 'required|date',
            'reservation.remarks' => '',
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


    public function setMinDepartureDate()
    {
        $departure = $this->reservation->departuredate;
        $carbonArrivalDate = new Carbon($this->reservation->arrivaldate);

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
        // Update Price for the contact Person
        $this->reservation->visitors()->updateExistingPivot($this->contact_person->id, [
            'price' => $this->price,
        ]);
        $this->contact_person->save();

        // Update prices for pre-register other visitors
        foreach ($this->otherVisitorsArray as $otherVisitor)
        {
            $this->reservation->visitors()->updateExistingPivot($otherVisitor["id"], [
                'price' => $otherVisitor["price"],
            ]);
        }

        // save added visitors
        foreach ($this->addedVisitors as $addedVisitor)
        {
            $visitor = Visitor::create([
                'name' => $addedVisitor["name"],
                'surname' => $addedVisitor["surname"],
                'birthyear' => $addedVisitor["birthyear"],
                'confirmed' => false,
            ]);

            $this->reservation->visitors()->attach($visitor, [ 'price' => $addedVisitor["price"], 'contact' => false ]);
        }
        $this->reservation->confirmed = true;
        $this->reservation->quickLink = false;

        $this->reservation->save();

        $this->emit('showRecapReservation', $this->reservation->id);

        $this->link->delete();
        $this->link = false;
    }



    public function mount()
    {
        $this->fill([
            'addedVisitors' => collect([]),
            'reservation' => $this->link->reservation,
            'contact_person' => $this->link->reservation->contact_person,
            'current_year' => Carbon::now()->year,
            'profiles' => Profile::all(),
            'price' => Profile::firstWhere('is_default', true)->price ?? Profile::first()->price,
            'unknown' => collect([]),
        ]);

        if ( $this->contact_person->name == 'x-inconnu' ){ //check if name hasn't been filled yet
            $this->unknown->push('name');
            $this->contact_person->name = "";
        }
        if ( $this->contact_person->surname == 'x-inconnu' ){ //check if surname hasn't been filled yet
            $this->unknown->push('surname');
            $this->contact_person->surname = "";
        }
        if ( $this->contact_person->email == '' ){ //check if email hasn't been filled yet
            $this->unknown->push('email');
        }


        $this->otherVisitorsArray = $this->reservation->getNonContactVisitors()->each(function($item, $key) { $item["price"] = $this->price; });
        $arrival = $this->reservation->arrivaldate;
        $departure = $this->reservation->departuredate;


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
