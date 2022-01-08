<?php

namespace App\Http\Livewire;

use App\Models\Visitor;
use Livewire\Component;

class NewUserForm extends Component
{
//     public Visitor $newvisitor;
    public function mount()
    {
        $this->newvisitor = new Visitor();
    }

    protected $rules = [
        'newvisitor.name' => 'required|string',
        'newvisitor.surname' => 'required|string',
        'newvisitor.phone' => 'required|string',
        'newvisitor.email' => 'required|email',
        'newvisitor.birthyear' => 'required',
        'newvisitor.remarks' => '',
    ];
    public function render()
    {
        return view('livewire.new-user-form');
    }
     public function save()
    {
        $this->validate();
        $this->newvisitor->save();
        $this->emit('hideVisitorForm');
        $this->emit('showAlert', __("L'utilisateur a bien été enregistré") );
    }
}
