<?php

namespace Phara\UIKit\Components;

use Illuminate\View\Component;

class DropdownItem extends Component
{
    public function __construct(
        public ?string $href = null,
        public ?string $icon = null
    ) {}

    public function render()
    {
        return view('ui::components.dropdown-item');
    }
}
