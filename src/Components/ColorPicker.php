<?php

namespace Phara\UIKit\Components;

use Illuminate\View\Component;

class ColorPicker extends Component
{
    public function __construct(
       
    ) {}

    public function render()
    {
        return view('ui::components.color-picker');
    }
}