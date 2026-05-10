<?php

namespace Phara\UIKit\Components;

use Illuminate\View\Component;

class NavListGroup extends Component
{
    public function __construct(
        public string $label = '',
        public ?string $icon = null,
        public string $iconVariant = 'outline',
        public bool $collapsible = false,
        public bool $open = true,
    ) {}

    public function render()
    {
        return view('ui::components.navlist-group');
    }
}
