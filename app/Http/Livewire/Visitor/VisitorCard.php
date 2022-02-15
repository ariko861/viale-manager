<?php

namespace App\Http\Livewire\Visitor;

use Livewire\Component;

class VisitorCard extends Component
{
    public $visitor;
    public $vKey;
    public $reservation;
    public $editing;

    public function render()
    {
        return view('livewire.visitor.visitor-card');
    }
}
