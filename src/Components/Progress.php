<?php

namespace Phara\UIKit\Components;

use Illuminate\View\Component;

class Progress extends Component
{
    public function __construct(
        public float $value = 0,
        public float $max = 100,
        public string $size = 'md',
        public string $color = 'blue',
        public ?string $label = null,
        public bool $showValue = false,
        public bool $striped = false,
        public bool $animated = false,
        public bool $indeterminate = false,
    ) {}

    public function render()
    {
        return view('ui::components.progress');
    }
}
