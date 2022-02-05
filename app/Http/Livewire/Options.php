<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Option;

class Options extends Component
{

    public $email;

    protected $rules = [
        'email.value' => 'required|email',
        'phone.value' => 'string',
        'address.value' => 'string',
    ];

    public function mount()
    {
        $this->email = Option::firstOrNew(['name' => 'email']);;
        $this->phone = Option::firstOrNew(['name' => 'phone']);
        $this->address = Option::firstOrNew(['name' => 'address']);

//         dd($this->email);
    }

    public function save($property)
    {
        $this->validate();
        $this->$property->name = $property;
        $this->$property->save();
        $this->emit('showAlert', [ __("L'option a bien été changé"), "bg-green-600"] );
    }

    public function render()
    {
        return view('livewire.options');
    }
}
