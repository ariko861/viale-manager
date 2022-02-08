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

    protected $listeners = ['hideVisitorForm'];

    public function render()
    {
        return view('livewire.visitor-search');

    }

    public function hideVisitorForm()
    {
        $this->noResult = false;
        $this->searchQuery = "";
        $this->displayAddVisitorButton = false;
    }

    public function cancelVisitorSelection()
    {
        $this->searchQuery = "";
        $this->visitorSet = false;
        $this->emitUp('contactPersonRemoved');
    }

    public function setContactPerson($visitor)
    {
        if ($visitor["email"]){
            $this->searchQuery = $visitor['full_name']." <".$visitor["email"].">";
            $this->visitorSet = true;
            $this->visitorsArray = [];
            $this->displayAddVisitorButton = false;
            switch($this->visitorType) {
                case('contactPerson'):
                    $this->emitUp('contactPersonAdded', $visitor);
                    break;
                case('otherVisitor'):
                    $result = [$visitor, $this->visitorKey];
                    $this->emitUp('visitorAdded', $result);
                    break;
            }
        } else {
            $this->emit('showAlert', [ __("La personne de contact doit avoir un email enregistré"), "bg-red-500" ] );
            $this->searchQuery="";
            $this->visitorSet = false;
            $this->visitorsArray = [];
            $this->displayAddVisitorButton = false;

        }
    }

    public function searchVisitor()
    {
        //error_log($value);
        $value = $this->searchQuery;
        if ( Str::length($value) >= 3 )
        {

            $this->visitorsArray = Visitor::where('name', 'ilike', '%'.$value.'%')
                ->orWhere('surname', 'ilike', '%'.$value.'%')
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
