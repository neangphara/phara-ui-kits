<?php

namespace Phara\UIKit\Components;

use Illuminate\View\Component;

class SwitchInput extends Component
{
    public function __construct(
        public ?string $name = null,
        public string $value = '1',
        public bool $checked = false,
        public ?string $label = null,
        public ?string $description = null,
        public bool $disabled = false,
        public string $size = 'md',
        public string $color = 'blue',
    ) {}

    public function render()
    {
        return view('ui::components.switch');
    }
}
