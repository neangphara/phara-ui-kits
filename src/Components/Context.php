<?php

namespace Phara\UIKit\Components;

use Illuminate\View\Component;

class Context extends Component
{
    public function __construct() {}

    public function render()
    {
        return view('ui::components.context');
    }
}
