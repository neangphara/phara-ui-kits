<?php

namespace Phara\UIKit\Components;

use Illuminate\View\Component;

class Toast extends Component
{
    public function __construct(
        public string $position = 'bottom-right',
        public int $duration = 5000,
    ) {}

    public function render()
    {
        return view('ui::components.toast');
    }
}
