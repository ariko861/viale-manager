<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\Visitor;

class VisitorSearch extends Component
{
    public $searchQuery;
    public $visitorSet = false;
    public $visitorsArray = [];
    public $noResult = false;
    public $displayAddVisitorButton = false;

    public function render()
    {
        return view('livewire.visitor-search');
    }

    public function cancelVisitorSelection()
    {
        $this->searchQuery = "";
        $this->visitorSet = false;
    }

    public function setContactPerson($visitor)
    {
        $this->searchQuery = $visitor['full_name'];
        $this->visitorSet = true;
        $this->visitorsArray = [];
        $this->displayAddVisitorButton = false;
        //$this->reservation->contactPerson = $visitor['id']; SET HERE AN EVENT TO SEND VISITOR ID TO PARENT COMPONENT
        $this->emitUp('visitorAdded', $visitor);
    }

    public function searchVisitor()
    {
        //error_log($value);
        $value = $this->searchQuery;
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
}
