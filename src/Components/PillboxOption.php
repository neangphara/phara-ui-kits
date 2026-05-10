<?php

namespace Phara\UIKit\Components;

use Illuminate\View\Component;

class PillboxOption extends Component
{
    public function __construct(
        public string $value,
        public ?string $icon = null,
        public string $iconVariant = 'outline',
    ) {}

    public function render()
    {
        return view('ui::components.pillbox-option');
    }
}
