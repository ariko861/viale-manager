<?php

namespace App\View\Components\Logo;

use Illuminate\View\Component;

class VialeManager extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $height;
    public $width;

    public function __construct($height, $width)
    {
        //
        $this->height = $height;
        $this->width = $width;

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.logo.viale-manager');
    }
}
