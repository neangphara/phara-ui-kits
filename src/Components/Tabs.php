<?php

namespace Phara\UIKit\Components;

use Illuminate\View\Component;

class Tabs extends Component
{
    public function __construct(
        public string $default = '',
        public string $variant = 'underline',
        public string $size = 'md',
    ) {}

    public function render()
    {
        return view('ui::components.tabs');
    }
}
