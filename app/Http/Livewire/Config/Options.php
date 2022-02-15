<?php

namespace App\Http\Livewire\Config;

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
        'phone.value' => 'string|nullable',
        'address.value' => 'string|nullable',
        'confirmation_messages.*.value' => 'string|nullable',
        'reservation_link_messages.*.value' => 'string|nullable',
        'matrix_links.*.homeserver' => 'string|nullable',
        'matrix_links.*.roomID' => 'string|nullable',
        'matrix_links.*.filteredUser' => 'string|nullable',
        'matrix_links.*.gallery' => 'boolean',
        'matrix_links.*.displayDate' => 'boolean',
        'matrix_links.*.displayAddress' => 'boolean',

    ];

    public function mount()
    {
        $this->email = Option::firstOrNew(['name' => 'email']);
        $this->phone = Option::firstOrNew(['name' => 'phone']);
        $this->address = Option::firstOrNew(['name' => 'address']);
        $this->confirmation_messages = Option::where('name', 'confirmation_message')->get();
        $this->reservation_link_messages = Option::where('name', 'reservation_link_message')->get();
        $this->matrix_links = MatrixLink::all();
    }

    public function addNewMessage()
    {
        $message = new Option(['name' => 'confirmation_message', 'value' => ' ']);
        $message->save();
        $this->confirmation_messages->push($message);
    }

    public function addNewReservationLinkMessage()
    {
        $message = new Option(['name' => 'reservation_link_message', 'value' => ' ']);
        $message->save();
        $this->reservation_link_messages->push($message);
    }

    public function deleteMessage($message_id)
    {
        Option::destroy($message_id);
        $this->confirmation_messages = Option::where('name', 'confirmation_message')->get();
        $this->reservation_link_messages = Option::where('name', 'reservation_link_message')->get();
    }
    public function saveMessages()
    {
        foreach ($this->confirmation_messages as $message)
        {
            $message->save();
            $this->emit('showAlert', [ __("L'option a bien été changé"), "bg-green-600"] );
        }
    }

    public function saveReservationLinkMessages()
    {
        foreach ($this->reservation_link_messages as $message)
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
            $this->validate();
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
        return view('livewire.config.options');
    }
}
