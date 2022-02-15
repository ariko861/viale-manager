<?php

namespace App\Http\Livewire\Visitor;

use Livewire\Component;
use App\Models\Visitor;

class VisitorsList extends Component
{

//
    public function mount()
    {
        $this->visitors = Visitor::where('confirmed', true)->get()->sortBy('name');
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
        Visitor::destroy($visitor_id);

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
