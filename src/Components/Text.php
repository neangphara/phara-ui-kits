<?php

namespace Phara\UIKit\Components;

use Illuminate\View\Component;

class Text extends Component
{
    public function __construct(
        public string $size = 'base',
        public string $variant = 'default',
        public string $tag = 'p'
    ) {}

    public function render()
    {
        return view('ui::components.text');
    }
}
