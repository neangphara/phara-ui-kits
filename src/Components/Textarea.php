<?php

namespace Phara\UIKit\Components;

use Illuminate\View\Component;

class Textarea extends Component
{
    public function __construct(
        public ?string $name = null,
        public ?string $label = null,
        public string $size = 'md',
        public string $variant = 'default',
        public int $rows = 4,
        public bool $autoResize = false,
        public ?int $maxLength = null,
        public bool $showCount = false,
    ) {}

    public function render()
    {
        return view('ui::components.textarea');
    }
}
