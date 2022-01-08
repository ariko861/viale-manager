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

            error_log($this->visitorsArray);
        }
        else {
            $this->displayAddVisitorButton = false;
        }
    }

}
