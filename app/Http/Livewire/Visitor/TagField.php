<?php

namespace App\Http\Livewire\Visitor;

use Livewire\Component;
use App\Models\Tag;
use App\Models\Visitor;


class TagField extends Component
{

    public $tagSearchQuery;
    public $tags;
    public $colors;
    public $visitor_id;
    public $showTagForm = false;
    public $visitor_tags;

    public function searchTag() {
        $this->tags = Tag::where('name', 'ilike', $this->tagSearchQuery.'%')->get();
    }
    public function mount() {
        $this->colors = collect(['#F87171', '#FB923C', '#FACC15', '#A3E635', '#60A5FA', '#C084FC']);
    }

    public function refreshVisitorTags() {
        $this->visitor_tags = Visitor::find($this->visitor_id)->tags;
    }

    public function stopTagSearch() {
        $this->tags = null;
        $this->tagSearchQuery = "";
        $this->showTagForm = false;
    }

    public function setTag($tag_id) {
        $tag = Tag::find($tag_id);
        $tag->visitors()->attach($this->visitor_id);
        $this->stopTagSearch();
        $this->refreshVisitorTags();
    }

    public function removeTag($tag_id) {
        $tag = Tag::find($tag_id);
        $tag->visitors()->detach($this->visitor_id);
        $this->stopTagSearch();
        $this->refreshVisitorTags();
    }

    public function createTag($color = "#000000"){
        $tag = new Tag();
        $tag->name = $this->tagSearchQuery;
        $tag->color = $color;
        $tag->normalize();
        $tag->save();
        $tag->visitors()->attach($this->visitor_id);
        $this->refreshVisitorTags();
        $this->stopTagSearch();
    }

    public function render()
    {
        return view('livewire.visitor.tag-field');
    }
}
