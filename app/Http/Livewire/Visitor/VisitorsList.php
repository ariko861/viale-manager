<?php

namespace App\Http\Livewire\Visitor;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use App\Models\Visitor;
use App\Models\Tag;

class VisitorsList extends Component
{
    public $tags;
    public $selectedTags;
    public $advancedSearch = false;
    public $visitorSearch;
    public $onlyConfirmed = true;
    public $visitors;

    public function mount()
    {
        $this->getAllVisitors();
        $this->tags = Tag::all();
        $this->selectedTags = collect([]);
    }

    protected $listeners = ['newVisitorSaved', 'visitorModified', 'deleteAction', 'changeAction', 'tagChosen'];

    public function newVisitorSaved($id)
    {
        if ($id)
        {
            $visitor = Visitor::find($id);
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
            $this->visitors = Visitor::getVisitorsList($this->onlyConfirmed)->get()->sortBy('name');
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


    public function render()
    {
        return view('livewire.visitor.visitors-list');
    }
}
