<?php

namespace App\Http\Livewire;

use Livewire\Component;

class InformationDisplay extends Component
{
    public $displayText = null;
    public $title = "";

    protected $listeners = ['displayInformation'];

    public function displayInformation($info) {
        $this->displayText = $info['text'];
        $this->title = $info['title'];
    }

    public function cancelInformationDisplay() {
        $this->displayText = null;
        $this->title = "";
    }

    public function render()
    {
        return view('livewire.information-display');
    }
}
