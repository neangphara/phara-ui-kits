<?php

namespace Phara\UIKit\Components;

use Illuminate\View\Component;

class Button extends Component
{
    public function __construct(
        public string $variant = 'primary',
        public string $size = 'md'
    ) {}

    public function render()
    {
        return view('ui::components.button');
    }
}