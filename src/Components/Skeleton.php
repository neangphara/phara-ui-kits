<?php

namespace Phara\UIKit\Components;

use Illuminate\View\Component;

class Skeleton extends Component
{
    public function __construct(
        public string $variant = 'pulse',
    ) {}

    public function render()
    {
        return view('ui::components.skeleton');
    }
}
