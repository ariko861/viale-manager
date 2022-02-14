<?php

namespace App\Http\Livewire\Reservation;

use Carbon\Carbon;
use Livewire\Component;
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
    public $showEmailForm = false;

    protected $listeners = ['visitorSelectedFromEmail', 'emailNotFound' ];

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
            'addedVisitors.*.visitor.name' => 'required|string',
            'addedVisitors.*.visitor.surname' => 'required|string',
            'addedVisitors.*.visitor.birthyear' => 'required|integer|between:1900,2100',
            'addedVisitors.*.visitor.email' => 'email',

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

    public function checkEmptyFields()
    {

        $this->unknown = collect([]);
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
    }

    public function addVisitor($visitor = null)
    {
        if ($this->addedVisitors->count() < $this->link->max_added_visitors)
        {
            if ($visitor === null) $visitor = new Visitor();
            $this->addedVisitors->push(['visitor' => $visitor, 'price' => $this->price, 'showEmailForm' => true]);
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

    public function setMinArrivalDate()
    {
        $carbonArrivalDate = $this->getMinDate($this->reservation->arrivaldate);
        $today = Carbon::now();
        if ( $today->gt( $carbonArrivalDate ) ) {
            $this->minarrivaldate = $today->format('Y-m-d');
        } else {
            $this->minarrivaldate = $carbonArrivalDate->format('Y-m-d');
        }

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

    public function emailNotFound($options)
    {
        if ( $options["visitorKey"] === "contact" ){
            $this->contact_person->name = $this->contact_person->getOriginal('name');
            $this->contact_person->surname = $this->contact_person->getOriginal('surname');
            $this->contact_person->email = $options["email"];
            $this->contact_person->save();
            $this->checkEmptyFields();
            $this->showEmailForm = false;
        } else {
            $this->addedVisitors = $this->addedVisitors->replaceRecursive([
                $options["visitorKey"] => [ 'showEmailForm' => 'notfound', 'visitor' => [ 'email' => $options["email"] ] ]

            ]);

        }
    }

    public function visitorSelectedFromEmail($options)
    {
        $visitor = Visitor::find( $options["visitor_id"] );

        if ($options["visitorKey"] === 'contact') {
            $visitor_id_to_destroy = $this->contact_person->id;
            $this->reservation->visitors()->detach($visitor_id_to_destroy);
            $this->reservation->visitors()->attach($visitor->id, ['contact' => true]);
            $this->reservation->save();
            $this->reservation->refresh();
            $this->contact_person = $this->reservation->contact_person;
            Visitor::destroy($visitor_id_to_destroy);
            $this->showEmailForm = false;
            $this->checkEmptyFields();
            $refresh;
        } else {
            $this->addedVisitors = $this->addedVisitors->replaceRecursive([
                $options["visitorKey"] => [ 'visitor' => $visitor, 'showEmailForm' => 'found' ]
            ]);
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
        $this->contact_person->confirmed = true;
        $this->contact_person->quickLink = false;
        $this->contact_person->normalize();
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
            if ($addedVisitor["showEmailForm"] === "found" && $addedVisitor["visitor"]["id"]) {
                $visitor = Visitor::find($addedVisitor["visitor"]["id"]);
                $visitor->birthyear = $addedVisitor["visitor"]["birthyear"];
            } else {

                $visitor = Visitor::create([
                    'name' => $addedVisitor["visitor"]["name"],
                    'surname' => $addedVisitor["visitor"]["surname"],
                    'birthyear' => $addedVisitor["visitor"]["birthyear"],
                    'email' => ( $addedVisitor["visitor"]["email"] ?? ''),
                    'confirmed' => ( isset($addedVisitor["visitor"]["email"]) ? true : false),
                ]);
            }

            $visitor->normalize();
            $visitor->save();

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
        ]);
        $this->checkEmptyFields();

        if ( $this->reservation->quickLink && $this->unknown->contains('email') ) {
            $this->showEmailForm = true;
        }

        $this->otherVisitorsArray = $this->reservation->getNonContactVisitors()->each(function($item, $key) { $item["price"] = $this->price; });
        $arrival = $this->reservation->arrivaldate;
        $departure = $this->reservation->departuredate;


        $this->maxarrivaldate = $this->getMaxDate($arrival)->format('Y-m-d');
        $this->setMinArrivalDate();

        $this->maxdeparturedate = $this->getMaxDate($departure)->format('Y-m-d');
        $this->setMinDepartureDate();

    }
    public function render()
    {
        return view('livewire.reservation.confirmation-form');
    }
}
