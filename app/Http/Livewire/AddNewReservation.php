<?php

namespace App\Http\Livewire;

use Illuminate\Support\Str;
use Livewire\Component;
use Carbon\Carbon;
use App\Models\Visitor;
use App\Models\Reservation;

class AddNewReservation extends Component
{

    public $message;
    public $showReservationForm = false;
    public $mindeparturedate;
    public $showUserForm = false;
    public $otherVisitorsArray = [];

    protected $listeners = ['hideVisitorForm' => 'hideUserForm', 'visitorAdded'];

    public function mount()
    {
        $this->reservation = new Reservation();
    }

    protected $rules = [
        'reservation.arrivaldate' => 'required|date',
        'reservation.departuredate' => 'required|date',
        'reservation.nodeparturedate' => 'boolean',
        'reservation.contactPerson' => '',
        'reservation.otherVisitorsAuthorized' => 'boolean',
        'reservation.otherVisitors' => 'required|array',

    ];

    public function render()
    {
        return view('livewire.add-new-reservation', [
            'today' => Carbon::now()->format('Y-m-d'),
        ]);
    }

    public function setMinDate($value)
    {
        $this->mindeparturedate = $value;
    }

    public function hideUserForm()
    {
        $this->showUserForm = false;
        $this->fullname = "";
        $this->noResult = false;
    }

    public function visitorAdded($visitor)
    {

    }

    public function addNewOtherVisitor()
    {
        $newVisitor = new Visitor();
//         dd($this->reservation->otherVisitors);
//         dd($newVisitor);
        array_push($this->otherVisitorsArray, $newVisitor);
    }

}
