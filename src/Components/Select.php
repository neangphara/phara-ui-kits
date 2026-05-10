<?php

namespace Phara\UIKit\Components;

use Illuminate\View\Component;

class Select extends Component
{
    public function __construct()
    {
    }

    public function render()
    {
        return view('ui::components.select');
    }
}
