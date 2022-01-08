<?php

namespace App\Http\Livewire;

use Illuminate\Support\Str;
use Livewire\Component;
use Carbon\Carbon;
use App\Models\Visitor;

class AddNewReservation extends Component
{

    public $message;
    public $showReservationForm = false;
    public $mindeparturedate;
    public $visitorsArray = [];
    public $displayAddVisitorButton = false;
    public $showUserForm = false;
    public $fullname = "";

    protected $listeners = ['hideVisitorForm' => 'hideUserForm'];

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
        $this->displayAddVisitorButton = false;
    }

    public function searchVisitor($value)
    {
        //error_log($value);
        if ( Str::length($value) >= 3 )
        {

            $this->visitorsArray = Visitor::where('name', 'like', '%'.$value.'%')
                ->orWhere('surname', 'like', '%'.$value.'%')
                ->orderBy('updated_at', 'desc')
                ->get();

            $this->visitorsArray->whenEmpty(function() {
                $this->displayAddVisitorButton = true;
            });

        }
        else {
            $this->displayAddVisitorButton = false;
        }
    }
    public function setContactPerson($visitor)
    {
        $this->fullname = $visitor['full_name'];
        $this->visitorsArray = [];
    }

}
