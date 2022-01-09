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
    public $visitorsArray = [];
    public $noResult = false;
    public $displayAddVisitorButton = false;
    public $showUserForm = false;
    public $fullname = "";

    protected $listeners = ['hideVisitorForm' => 'hideUserForm'];

    public function mount()
    {
        $this->newReservation = new Reservation();
    }

    protected $rules = [
        'newReservation.arrivaldate' => 'required|date',
        'newReservation.departuredate' => 'required|date',
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

    public function searchVisitor($value)
    {
        //error_log($value);
        if ( Str::length($value) >= 3 )
        {

            $this->visitorsArray = Visitor::where('name', 'like', '%'.$value.'%')
                ->orWhere('surname', 'like', '%'.$value.'%')
                ->orWhere('full_name', 'like', '%'.$value.'%')
                ->orderBy('updated_at', 'desc')
                ->get();



            $this->visitorsArray->whenEmpty(function() {
                $this->noResult = true;
            });
            $this->visitorsArray->whenNotEmpty(function() {
                $this->noResult = false;
            });

            $this->displayAddVisitorButton = true;

        }
        else {
            $this->visitorsArray = [];
            $this->displayAddVisitorButton = false;
            $this->noResult = false;
        }
    }
    public function setContactPerson($visitor)
    {
        $this->fullname = $visitor['full_name'];
        $this->visitorsArray = [];
        $this->displayAddVisitorButton = false;
    }

}
