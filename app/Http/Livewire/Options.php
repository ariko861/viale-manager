<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use App\Models\Option;
use App\Models\MatrixLink;

class Options extends Component
{

    public $email;
    public $newMatrixLink;

    protected $rules = [
        'email.value' => 'required|email',
        'phone.value' => 'string',
        'address.value' => 'string',
        'confirmation_message.value' => '',
        'confirmation_messages.*.value' => '',
        'matrix_links.*.homeserver' => '',
        'matrix_links.*.roomID' => '',
        'matrix_links.*.filteredUser' => '',
        'matrix_links.*.gallery' => '',
        'matrix_links.*.displayDate' => '',
        'matrix_links.*.displayAddress' => '',

    ];

    public function mount()
    {
        $this->email = Option::firstOrNew(['name' => 'email']);
        $this->phone = Option::firstOrNew(['name' => 'phone']);
        $this->address = Option::firstOrNew(['name' => 'address']);
        $this->confirmation_messages = Option::where('name', 'confirmation_message')->get();
        $this->matrix_links = MatrixLink::all();
    }

    public function addNewMessage()
    {
        $message = new Option(['name' => 'confirmation_message', 'value' => ' ']);
        $message->save();
        $this->confirmation_messages->push($message);
    }

    public function deleteMessage($message_id)
    {
        Option::destroy($message_id);
        $this->confirmation_messages = Option::where('name', 'confirmation_message')->get();
    }
    public function saveMessages()
    {
        foreach ($this->confirmation_messages as $message)
        {
            $message->save();
            $this->emit('showAlert', [ __("L'option a bien été changé"), "bg-green-600"] );
        }
    }

    public function addNewMatrix()
    {
        $this->validate([
            'newMatrixLink' => 'required|string',
        ]);
        $matrix = new MatrixLink();
        $matrix->link = strtolower($this->newMatrixLink);
        $matrix->save();
        $matrix->refresh();
        $this->matrix_links->push($matrix);
        $this->newMatrixLink = "";
    }

    public function deleteMatrix($matrix_id)
    {
        MatrixLink::destroy($matrix_id);
        $this->matrix_links = MatrixLink::all();
    }

    public function saveMatrix()
    {
        foreach ($this->matrix_links as $matrix)
        {
            $matrix->save();
            $this->emit('showAlert', [ __("Les liens Matrix ont bien été changés"), "bg-green-600"] );
        }
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
