<?php

namespace App\Http\Livewire\Visitor;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use App\Models\Visitor;
use App\Models\Tag;
use Carbon\Carbon;

class VisitorsList extends Component
{
    public $tags;
    public $selectedTags;
    public $advancedSearch = false;
    public $visitorSearch;
    public $onlyConfirmed = true;
    public $visitors;
    public $presenceDateBegin;
    public $presenceDateEnd;

    public function mount()
    {
        $this->visitors = collect([]);
        $this->tags = Tag::all();
        $this->selectedTags = collect([]);
        $today = Carbon::now()->format('Y-m-d');
        $this->presenceDateBegin = $today;
        $this->presenceDateEnd = $today;

    }

    protected $listeners = ['newVisitorSaved', 'visitorModified', 'deleteAction', 'changeAction', 'tagChosen'];

    protected $rules = [
        'presenceDateBegin' => 'required|date',
        'presenceDateEnd' => 'required|date|after_or_equal:presenceDateBegin',

    ];

    public function searchPresences() {
        $this->validate();
        $this->visitors = Visitor::searchByPresenceDate($this->presenceDateBegin, $this->presenceDateEnd);
    }

    public function newVisitorSaved($visitor_id)
    {
        if ($visitor_id)
        {
            $visitor = Visitor::find($visitor_id);
            $this->visitors->push($visitor);
            $this->visitors = $this->visitors->sortBy('name');
        }
    }

    public function getAllVisitors(){
        $this->visitors = Visitor::getVisitorsList($this->onlyConfirmed)->get()->sortBy('name');
    }

    public function getVisitorsByName(){
        $this->visitors = Visitor::searchVisitorsByName($this->visitorSearch, $this->onlyConfirmed);
    }

    public function visitorModified($id)
    {
        $this->visitors->find($id)->fresh();
    }

    public function changeAction($options)
    {
        if ($options[1] == 'visitor')
        {
            $this->engageVisitorChange($options[0]);
        }
    }

    public function deleteAction($options)
    {
        if ($options[1] == 'visitor')
        {
            $this->deleteVisitor($options[0]);
        }
    }

    public function engageVisitorChange($id)
    {
        $this->emit('visitorChangeForm', $id);
    }

    public function deleteVisitor($visitor_id)
    {
        $visitor = Visitor::find($visitor_id);
        if ($visitor->reservations->count()) {
            $visitor->confirmed = false;
            $visitor->save();
        }
        else $visitor->delete();

        $this->visitors = $this->visitors->filter(function($item) use ($visitor_id) {
            return $item->id != $visitor_id;
        });

        $this->emit('showAlert', [ __("L'utilisateur a bien été supprimé"), "bg-red-600"] );
    }

    public function filterByTag($tag_id) {

        if ($this->selectedTags->contains($tag_id) ) {
            $this->selectedTags = $this->selectedTags->reject(function($value, $key) use ($tag_id){
                return $value == $tag_id;
            });
        } else $this->selectedTags->push($tag_id);

        if ( $this->selectedTags->isEmpty() ) {
            $this->visitors = collect([]);
        } else {
            $this->visitors = Visitor::getVisitorsList($this->onlyConfirmed)->whereHas('tags', function (Builder $query) {
                $query->whereIn('id', $this->selectedTags);
            })->get();
        }
    }

    public function isTagSelected($tag_id) {
        $tag = $this->tags->find($tag_id);
        if ($this->selectedTags->contains($tag_id)) return true;
        else return false;
    }

    public function getEmailList() {
        $mailList = "";
        foreach ( $this->visitors as $visitor ) {
            if ($visitor->email) $mailList = $mailList.$visitor->email.", ";
        }
        $info = [
            'title' => __("Liste des emails des visiteurs séléctionnés"),
            'text' => $mailList,
        ];
        $this->emit('displayInformation', $info);

    }


    public function render()
    {
        return view('livewire.visitor.visitors-list');
    }
}
