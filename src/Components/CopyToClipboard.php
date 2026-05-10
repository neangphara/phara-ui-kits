<?php

namespace Phara\UIKit\Components;

use Illuminate\View\Component;

class CopyToClipboard extends Component
{
    public function __construct(
        public string $text = '',
        public string $size = 'md',
        public string $variant = 'default',
        public string $style = 'button',
        public ?string $successMessage = 'Copied!'
    ) {}

    public function render()
    {
        return view('ui::components.copy-to-clipboard');
    }
}
