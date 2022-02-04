<?php

namespace App\Http\Livewire;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Livewire\Component;
use Carbon\Carbon;
use App\Models\Visitor;
use App\Models\Reservation;
use App\Models\Profile;

class AddNewReservation extends Component
{

    public $message;
    public $showReservationForm = false;
    public $mindeparturedate;
    public $showUserForm = false;
    public $otherVisitorsArray;
    public $contactPerson;


    protected $listeners = ['hideVisitorForm', 'showUserForm', 'visitorAdded', 'contactPersonAdded', 'contactPersonRemoved'];

    public function mount()
    {
        $this->reservation = new Reservation();
        $this->otherVisitorsArray = collect([]);
    }

    protected function rules()
    {
        if ($this->reservation->nodeparturedate){
            $departuredaterequirements = '';
        } else {
            $departuredaterequirements = 'required|date';
        }
        $result = [
            'reservation.arrivaldate' => 'required|date',
            'reservation.departuredate' => $departuredaterequirements,
            'reservation.nodeparturedate' => '',
            'reservation.otherVisitorsAuthorized' => '',
            'contactPerson' => 'required',

        ];

        return $result;
    }

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

    public function showUserForm()
    {
        $this->showUserForm = true;
    }
    public function hideVisitorForm()
    {
        $this->showUserForm = false;
    }

    public function contactPersonAdded($visitor)
    {
        $this->contactPerson = $visitor["id"];
    }

    public function contactPersonRemoved()
    {
        $this->contactPerson = "";
    }

    public function visitorAdded($result)
    {
        $this->otherVisitorsArray->put($result[1], $result[0]["id"]);
    }

    public function addNewOtherVisitor()
    {
        $this->otherVisitorsArray->push("");

    }
    public function removeOtherVisitor($key)
    {
        $this->otherVisitorsArray->pull($key);
    }

    public function save()
    {
        $this->validate();
        $this->reservation->save();
        $this->reservation->visitors()->attach($this->contactPerson, ['contact' => true ]);
        $this->reservation->visitors()->attach($this->otherVisitorsArray, ['contact' => false ]);
        $this->emit('showAlert', [ __("La réservation a bien été enregistré"), "bg-green-500" ] );

        $this->showReservationForm = false;

        $this->emit('displayReservation', $this->reservation->id);

        $this->reservation = new Reservation();
        $this->otherVisitorsArray = collect([]);
        $this->contactPerson = "";

    }

}
