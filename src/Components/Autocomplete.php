<?php

namespace Phara\UIKit\Components;

use Illuminate\View\Component;

class Autocomplete extends Component
{
    public function __construct(
        public array $items = [],
        public string $placeholder = 'Search...'
    ) {}

    public function render()
    {
        return view('ui::components.autocomplete');
    }
}