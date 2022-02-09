<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use App\Models\Option;

class Options extends Component
{

    public $email;

    protected $rules = [
        'email.value' => 'required|email',
        'phone.value' => 'string',
        'address.value' => 'string',
        'confirmation_message.value' => '',
    ];

    public function mount()
    {
        $this->email = Option::firstOrNew(['name' => 'email']);
        $this->phone = Option::firstOrNew(['name' => 'phone']);
        $this->address = Option::firstOrNew(['name' => 'address']);
        $this->confirmation_message = Option::firstOrNew(['name' => 'confirmation_message']);


//         dd($this->email);
    }

    public function testEmail()
    {
        $this->validate([
               'email.value' => 'required|email',
            ]);
        Mail::to($this->email->value)->send(new TestMail());
        $this->emit('showAlert', [ __("Le mail a bien été envoyé"), "bg-green-600"] );

    }

    public function save($property)
    {
        if ( $property == 'email' )
        {
            $this->validate([
                $property.'.value' => 'email'
            ]);
        } else {
            $this->validate([
                $property.'.value' => 'string'
            ]);
        }
        $this->$property->name = $property;
        $this->$property->save();
        $this->emit('showAlert', [ __("L'option a bien été changé"), "bg-green-600"] );
    }

    public function render()
    {
        return view('livewire.options');
    }
}
