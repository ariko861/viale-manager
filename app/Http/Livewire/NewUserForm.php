<?php

namespace App\Http\Livewire;

use App\Models\Visitor;
use Livewire\Component;

class NewUserForm extends Component
{
//     public Visitor $newvisitor;

    public $modify = false;

    public function mount()
    {
        $this->newvisitor = new Visitor();
    }

    protected $listeners = ['visitorChange'];

    protected $rules = [
        'newvisitor.name' => 'required|string',
        'newvisitor.surname' => 'required|string',
        'newvisitor.phone' => '',
        'newvisitor.email' => 'required|email',
        'newvisitor.birthyear' => '',
        'newvisitor.remarks' => '',
    ];

    public function visitorChange($id)
    {
        $this->newvisitor = Visitor::find($id);
        $this->modify = true;
    }

    public function cancelVisitorForm()
    {
        $this->emit('hideVisitorForm');
        $this->newvisitor = new Visitor();
    }

    public function render()
    {
        return view('livewire.new-user-form');
    }
     public function save()
    {
        $this->validate();
        $this->newvisitor->save();
        $this->emit('hideVisitorForm');
        if ( $this->modify )
        {
            $this->emit('visitorModified', $this->newvisitor->id );
        } else {
            $this->emit('newVisitorSaved', $this->newvisitor->id );
        }
        $this->emit('showAlert', [ __("L'utilisateur a bien été enregistré"), "bg-green-500" ] );
    }
}
