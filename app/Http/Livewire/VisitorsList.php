<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Visitor;

class VisitorsList extends Component
{

//
    public $confirming;
    public function mount()
    {
        $this->visitors = Visitor::all()->sortBy('name');
    }

    protected $listeners = ['newVisitorSaved', 'visitorModified'];

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

    public function confirmDelete($id)
    {
        $this->confirming = $id;
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
