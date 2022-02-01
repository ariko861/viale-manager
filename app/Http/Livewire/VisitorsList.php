<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Visitor;

class VisitorsList extends Component
{

//
    public function mount()
    {
        $this->visitors = Visitor::where('confirmed', true)->get()->sortBy('name');
    }

    protected $listeners = ['newVisitorSaved', 'visitorModified', 'deleteAction' => 'deleteVisitor', 'changeAction' => 'engageVisitorChange'];

    public function newVisitorSaved($id)
    {
        if ($id)
        {
            $newvisitor = Visitor::find($id);
            $this->visitors->push($newvisitor);
            $this->visitors = $this->visitors->sortBy('name');
        }
    }

    public function visitorModified($id)
    {
        $this->visitors->find($id)->fresh();
    }

    public function engageVisitorChange($id)
    {
        $this->emit('showVisitorForm');
        $this->emit('visitorChange', $id);
        //$this->emit('hideVisitorForm');
    }

    public function deleteVisitor($visitor_id)
    {
        Visitor::destroy($visitor_id);

        $this->visitors = $this->visitors->filter(function($item) use ($visitor_id) {
            return $item->id != $visitor_id;
        });

        $this->emit('showAlert', [ __("L'utilisateur a bien été supprimé"), "bg-red-600"] );
    }


    public function render()
    {
        return view('livewire.visitors-list');
    }
}
