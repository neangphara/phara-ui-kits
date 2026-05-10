<?php

namespace Phara\UIKit\Components;

use Illuminate\View\Component;

class Dropdown extends Component
{
    public function __construct(
        public string $align = 'left',
        public string $width = 'md'
    ) {}

    public function render()
    {
        return view('ui::components.dropdown');
    }
}
