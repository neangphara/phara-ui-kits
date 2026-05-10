<?php

namespace Phara\UIKit\Components;

use Illuminate\View\Component;

class Tooltip extends Component
{
    public function __construct(
        public string $text = '',
        public string $position = 'top',
        public string $variant = 'dark',
    ) {}

    public function render()
    {
        return view('ui::components.tooltip');
    }
}
