<?php

namespace Phara\UIKit\Components;

use Illuminate\View\Component;

class Profile extends Component
{
    public function __construct(
        public string $name = 'User',
        public ?string $subtitle = null,
        public ?string $avatar = null,
        public string $size = 'md',
        public string $shape = 'circle',
        public ?string $status = null,
        public string $variant = 'inline',
        public ?string $href = null,
    ) {}

    public function render()
    {
        return view('ui::components.profile');
    }
}
