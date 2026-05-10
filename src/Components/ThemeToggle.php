<?php

namespace Phara\UIKit\Components;

use Illuminate\View\Component;

class ThemeToggle extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $variant = 'dropdown',
        public string $size = 'md',
        public string $align = 'right'
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('ui::components.theme-toggle');
    }
}
