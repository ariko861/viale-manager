<?php

namespace App\View\Components\Buttons;

use Illuminate\View\Component;

class ArrowChevron extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $up = false;

    public $size;

    public function __construct($up, $size = 6)
    {
        //
        $this->up = $up;
        $this->size = $size;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.buttons.arrow-chevron');
    }
}
