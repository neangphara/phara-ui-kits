<?php

namespace Phara\UIKit\Components;

use Illuminate\View\Component;

class ListItem extends Component
{
    public function __construct() {}

    public function render()
    {
        return view('ui::components.list-item');
    }
}
