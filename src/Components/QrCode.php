<?php

namespace Phara\UIKit\Components;

use Illuminate\View\Component;

class QrCode extends Component
{
    public function __construct(
        public string $value = '',
        public int $size = 160,
        public string $color = '#000000',
        public string $background = '#ffffff',
        public string $errorCorrection = 'M',
        public ?string $title = null,
        public ?string $description = null,
        public string $state = 'default',
        public bool $copyable = false,
        public bool $downloadable = false,
    ) {}

    public function render()
    {
        return view('ui::components.qr-code');
    }
}
