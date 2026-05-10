<?php

namespace Phara\UIKit\Components;

use Illuminate\View\Component;

class Tab extends Component
{
    public function __construct(
        public string $name,
        public ?string $icon = null,
        public string $iconVariant = 'outline',
        public bool $disabled = false,
    ) {}

    public function render()
    {
        return view('ui::components.tab');
    }
}
