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
    public $visitorType;
    public $visitorKey;

    protected $listeners = ['cancelVisitorSelection', 'newVisitorSaved'];

    public function render()
    {
        return view('livewire.visitor-search');

    }

    public function newVisitorSaved($id){
        if ($this->visitorSet == false && $this->displayAddVisitorButton){
            $visitor = Visitor::find($id);
            $this->setContactPerson($visitor);
        }
    }



    public function cancelVisitorSelection()
    {
        $this->searchQuery = "";
        $this->visitorSet = false;
        $this->emitUp('contactPersonRemoved');
    }

    public function setContactPerson($visitor)
    {
            $this->searchQuery = $visitor['full_name']." <".( $visitor["email"] ?? "" ).">";
            $this->visitorSet = true;
            $this->visitorsArray = [];
            $this->displayAddVisitorButton = false;
            $this->noResult = false;
            switch($this->visitorType) {
                case('contactPerson'):
                    $this->emitUp('contactPersonAdded', $visitor);
                    break;
                case('otherVisitor'):
                    $result = [$visitor, $this->visitorKey];
                    $this->emitUp('visitorAdded', $result);
                    break;
            }
    }

    public function searchVisitor()
    {
        $value = $this->searchQuery;
        if ( Str::length($value) >= 3 )
        {
            $this->visitorsArray = Visitor::where('quickLink', false)->where(function($query) use ($value) {
                $query->where('name', 'ilike', '%'.$value.'%')
                ->orWhere('surname', 'ilike', '%'.$value.'%')
                ->orWhere('email', 'ilike','%'.$value.'%')
                ->orderBy('updated_at', 'desc');
            })->get();



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
