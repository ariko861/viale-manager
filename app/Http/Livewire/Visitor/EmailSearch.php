<?php

namespace App\Http\Livewire\Visitor;

use Livewire\Component;
use App\Models\Visitor;

class EmailSearch extends Component
{
    public $email;
    public $showVisitorList = false;
    public $visitors;
    public $user;

    protected $rules = [
        'email' => 'email|required',
    ];

    protected $messages = [
        'email.required' => "L'adresse email doit être remplie",
        'email.email' => "Il faut une adresse email valide",
    ];

    public function updatedEmail($value){
        $this->email = str_replace( array( '\'', '"', ',' , ';', '<', '>' ), '', $value);
    }

    public function selectVisitor($visitor_id = null)
    {
        $this->showVisitorList = false;
        $this->visitors = collect([]);
        if ($visitor_id) {
            $this->emitUp('visitorSelectedFromEmail', $visitor_id);
        } else {
            $this->emitUp('emailNotFound', $this->email);
        }
        $this->email = "";
    }

    public function submit()
    {
        $this->validate();
        $email = $this->email;
        $this->visitors = Visitor::where('email', $email)->get();
        if ( $this->visitors->count() ) {
            $this->showVisitorList = true;
        } else {
            $this->emitUp('emailNotFound', $email);
            $this->email = "";

        }
    }

    public function render()
    {
        return view('livewire.visitor.email-search');
    }
}
