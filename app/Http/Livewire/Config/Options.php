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
    public $reservationLinksMaxUse;

    protected $rules = [
        'email.value' => 'required|email|max:255',
        'phone.value' => 'string|nullable|max:255',
        'address.value' => 'string|nullable|max:255',
        'confirmation_messages.*.value' => 'string|nullable|max:255',
        'reservation_link_messages.*.value' => 'string|nullable|max:255',
        'matrix_links.*.homeserver' => 'string|nullable|max:255',
        'matrix_links.*.roomID' => 'string|nullable|max:255',
        'matrix_links.*.filteredUser' => 'string|nullable|max:255',
        'matrix_links.*.gallery' => 'boolean',
        'matrix_links.*.displayDate' => 'boolean',
        'matrix_links.*.displayAddress' => 'boolean',
        'reservationLinksMaxUse.value' => 'required|integer|min:1|max:10',

    ];

    protected $messages = [

        'reservation_link_messages.*.value.max' => 'Le message ne peut pas être plus long que 255 caractères.',
        'email.email' => 'The Email Address format is not valid.',

    ];
    public function mount()
    {
        $this->email = Option::firstOrNew(['name' => 'email']);
        $this->phone = Option::firstOrNew(['name' => 'phone']);
        $this->address = Option::firstOrNew(['name' => 'address']);
        $this->confirmation_messages = Option::where('name', 'confirmation_message')->orderBy('id')->get();
        $this->reservation_link_messages = Option::where('name', 'reservation_link_message')->orderBy('id')->get();
        $this->matrix_links = MatrixLink::all();
        
        $this->reservationLinksMaxUse = Option::firstOrNew(['name' => 'reservationLinksMaxUse']);
        
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
        $this->validate();
        foreach ($this->confirmation_messages as $message)
        {
            $message->save();
            $this->emit('showAlert', [ __("L'option a bien été changé"), "bg-green-600"] );
        }
    }

    public function saveReservationLinkMessages()
    {
        $this->validate();
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
        $this->validate();
        foreach ($this->matrix_links as $matrix)
        {
            $matrix->save();
            $this->emit('showAlert', [ __("Les liens Matrix ont bien été changés"), "bg-green-600"] );
        }
    }

    public function testEmail()
    {
        $this->validate([
               'email.value' => 'required|email|max:255',
            ]);
        Mail::to($this->email->value)->send(new TestMail());
        $this->emit('showAlert', [ __("Le mail a bien été envoyé"), "bg-green-600"] );

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
        return view('livewire.config.options');
    }
}
