<?php

namespace App\Http\Livewire\Visitor;

use App\Models\Visitor;
use Livewire\Component;

class NewVisitorForm extends Component
{
//     public Visitor $newvisitor;

    public $modify = false;
    public $showUserForm = false;

    public function mount()
    {
        $this->newvisitor = new Visitor();
    }

    protected $listeners = ['newVisitorForm', 'visitorChangeForm'];

    protected $rules = [
        'newvisitor.name' => 'required|string',
        'newvisitor.surname' => 'required|string',
        'newvisitor.phone' => '',
        'newvisitor.email' => 'email|nullable',
        'newvisitor.birthyear' => '',
        'newvisitor.remarks' => '',
    ];

    public function newVisitorForm()
    {
        $this->newvisitor = new Visitor();
        $this->showUserForm = true;
        $this->modify = false;
    }
    public function visitorChangeForm($id)
    {
        $this->newvisitor = Visitor::find($id);
        $this->showUserForm = true;
        $this->modify = true;
    }

    public function cancelVisitorForm()
    {
        $this->showUserForm = false;
        $this->newvisitor = new Visitor();
        $this->emit('hideVisitorForm');
    }

     public function save()
    {
        $this->validate();
        $this->newvisitor->save();
        if ( $this->modify )
        {
            $this->emit('visitorModified', $this->newvisitor->id );
        } else {
            $this->emit('newVisitorSaved', $this->newvisitor->id );
        }
        $this->emit('showAlert', [ __("L'utilisateur a bien été enregistré"), "bg-green-500" ] );
        $this->cancelVisitorForm();
    }

    public function render()
    {
        return view('livewire.visitor.new-visitor-form');
    }
}
