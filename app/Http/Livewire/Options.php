<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Option;

class Options extends Component
{

    public $email;

    protected $rules = [
        'email' => 'required|email',
    ];

    public function mount()
    {
        $email = Option::firstOrCreate(['name' => 'email']);
        $this->email = $email->value;
    }

    public function saveMail()
    {
        $this->validate();
        $email = Option::firstWhere('name', 'email');
        $email->value = $this->email;
        $email->save();
        $this->emit('showAlert', [ __("L'email a bien été changé"), "bg-green-600"] );
    }

    public function render()
    {
        return view('livewire.options');
    }
}
