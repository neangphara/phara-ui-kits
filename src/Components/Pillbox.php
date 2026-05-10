<?php

namespace Phara\UIKit\Components;

use Illuminate\View\Component;

class Pillbox extends Component
{
    public function __construct(
        public string $placeholder = 'Select options...',
        public string $searchPlaceholder = 'Search...',
        public string $size = 'md',
        public bool $searchable = false,
        public bool $disabled = false,
        public bool $invalid = false,
        public ?string $name = null,
        public array $value = [],
    ) {}

    public function render()
    {
        return view('ui::components.pillbox');
    }
}
