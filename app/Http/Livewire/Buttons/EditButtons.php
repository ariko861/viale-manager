<?php

namespace App\Http\Livewire\Buttons;

use Livewire\Component;

class EditButtons extends Component
{

    public $modelId;
    public $editRights;
    public $deleteRights;
    public $confirmingDeletion;


    public function render()
    {
        return view('livewire.buttons.edit-buttons');
    }
}
