<?php

namespace App\Http\Livewire\Buttons;

use Livewire\Component;

class EditButtons extends Component
{

    public $modelId;
    public $editRights;
    public $deleteRights;
    public $confirmingDeletion;
    public $model;
    public $options;
    public $messageDelete;
    public $deleteCondition = true;

    public function mount()
    {
        $this->options = collect([ $this->modelId, $this->model ]);
    }

    public function render()
    {
        return view('livewire.buttons.edit-buttons');
    }
}
