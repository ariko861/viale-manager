<?php

namespace App\Http\Livewire;

use Livewire\Component;

class AddNewUser extends Component
{
    public $showUserForm = false;

    protected $listeners = ['hideVisitorForm' => 'hideUserForm', 'showVisitorForm', 'visitorChange'];

    public function hideUserForm()
    {
        $this->showUserForm = false;

    }
    public function showVisitorForm()
    {
        $this->showUserForm = true;

    }
    public function visitorChange($id){
        $this->emitTo('new-user-form', 'visitorChange', $id);
    }

    public function render()
    {
        return view('livewire.add-new-user');
    }
}
