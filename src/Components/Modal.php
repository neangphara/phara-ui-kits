<?php

namespace Phara\UIKit\Components;

use Illuminate\View\Component;

class Modal extends Component
{
    public function __construct(
        public ?string $name = null,
        public string $variant = 'default',
        public string $position = 'center',
        public bool $dismissible = true,
        public bool $closable = true,
        public ?string $maxWidth = 'md',
    ) {}

    public function render()
    {
        return view('ui::components.modal');
    }
}
