<?php

namespace Phara\UIKit\Components;

use Illuminate\View\Component;

class NavListItem extends Component
{
    public bool $isActive;

    public function __construct(
        public ?string $href = null,
        public ?string $icon = null,
        public string $iconVariant = 'outline',
        public ?string $badge = null,
        public string $badgeColor = 'zinc',
        public ?bool $current = null,
    ) {
        $this->isActive = $current ?? ($href !== null && request()->url() === url($href));
    }

    public function render()
    {
        return view('ui::components.navlist-item');
    }
}
