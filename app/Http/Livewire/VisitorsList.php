<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Visitor;

class VisitorsList extends Component
{

//     public $visitors = Visitor::all();
    public function mount()
    {
        $this->visitors = Visitor::all();
    }

    protected $listeners = ['hideVisitorForm' => '$refresh']; // corriger en ajoutant le visiteur nouvellement ajouté à la collection $visitors


    public function render()
    {
        return view('livewire.visitors-list');
    }
}
