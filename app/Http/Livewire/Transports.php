<?php

namespace App\Http\Livewire;

use App\Models\TransportLink;
use Carbon\Carbon;
use Livewire\Component;

class Transports extends Component
{
    public $transportsLinks = [];
    public $showLinkForm = false;
    public $newlink;

    protected $rules = [
        'newlink.date' => 'required|date',
        'newlink.interval' => 'required|min:1',
        'newlink.type' => 'required|text'
    ];

    public function mount()
    {
        $this->transportsLinks = TransportLink::all();

    }

    public function newLinkForm()
    {
        $this->newlink = new TransportLink();
        $this->newlink->interval = 5;
        $this->newlink->type = "offer_places";
        $this->newlink->date = Carbon::today()->toDateString();
        $this->showLinkForm = true;
    }

    public function save()
    {
        $this->newlink->generateLinkToken();
//        $this->newlink->type = 'offer_places';
        $this->newlink->save();
        $this->showLinkForm = false;
        $this->transportsLinks = TransportLink::all();
    }

    public function delete($id)
    {
        $link = $this->transportsLinks->find($id);
        $link->delete();
        $this->transportsLinks = TransportLink::all();
    }

    public function render()
    {
        return view('livewire.transports');
    }
}
