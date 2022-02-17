<?php

namespace App\Http\Livewire\Visitor;

use Livewire\Component;
use App\Models\Visitor;

class VisitorsList extends Component
{

    public $advancedSearch = false;
    public $visitorSearch;
    public $onlyConfirmed = true;
    public $visitors;

    public function mount()
    {
        $this->getAllVisitors();
    }

    protected $listeners = ['newVisitorSaved', 'visitorModified', 'deleteAction', 'changeAction'];

    public function newVisitorSaved($id)
    {
        if ($id)
        {
            $visitor = Visitor::find($id);
            $this->visitors->push($visitor);
            $this->visitors = $this->visitors->sortBy('name');
        }
    }

    public function getAllVisitors(){
        $this->visitors = Visitor::getVisitorsList($this->onlyConfirmed);
    }

    public function getVisitorsByName(){
        $this->visitors = Visitor::searchVisitorsByName($this->visitorSearch, $this->onlyConfirmed);
    }

    public function visitorModified($id)
    {
        $this->visitors->find($id)->fresh();
    }

    public function changeAction($options)
    {
        if ($options[1] == 'visitor')
        {
            $this->engageVisitorChange($options[0]);
        }
    }

    public function deleteAction($options)
    {
        if ($options[1] == 'visitor')
        {
            $this->deleteVisitor($options[0]);
        }
    }

    public function engageVisitorChange($id)
    {
        $this->emit('visitorChangeForm', $id);
    }

    public function deleteVisitor($visitor_id)
    {
        $visitor = Visitor::find($visitor_id);
        if ($visitor->reservations->count()) {
            $visitor->confirmed = false;
            $visitor->save();
        }
        else $visitor->delete();

        $this->visitors = $this->visitors->filter(function($item) use ($visitor_id) {
            return $item->id != $visitor_id;
        });

        $this->emit('showAlert', [ __("L'utilisateur a bien été supprimé"), "bg-red-600"] );
    }


    public function render()
    {
        return view('livewire.visitor.visitors-list');
    }
}
