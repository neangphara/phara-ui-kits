<?php

namespace Phara\UIKit\Components;

use Illuminate\View\Component;

class Heading extends Component
{
    public function __construct(
        public string $level = '1',
        public string $size = 'default',
        public string $variant = 'default'
    ) {}

    public function render()
    {
        return view('ui::components.heading');
    }
}
