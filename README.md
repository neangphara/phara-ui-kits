# Phara UI Kit

A Laravel Livewire UI component package with Heroicons integration.

## Requirements

- PHP 8.1+
- Laravel 10+
- Livewire 4+
- Alpine.js 3+ (for ThemeToggle component)

## Installation

If you haven't already, install Alpine.js:

```bash
npm install alpinejs
```

In your `resources/js/app.js`:

```js
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
```

## Components

### Button

```blade
<!-- Basic buttons -->
<x-ui::button variant="primary">
    Click me
</x-ui::button>

<!-- With icon (simplified syntax) -->
<x-ui::button variant="primary" icon="check">
    Save Changes
</x-ui::button>

<x-ui::button variant="secondary" icon="cog">
    Settings
</x-ui::button>

<x-ui::button variant="outline" icon="download">
    Download
</x-ui::button>

<x-ui::button variant="ghost" icon="eye">
    View
</x-ui::button>

<!-- Icon on the right -->
<x-ui::button variant="primary" icon="arrow-right" iconPosition="right">
    Next
</x-ui::button>

<!-- As link -->
<x-ui::button href="/dashboard" variant="secondary" icon="home">
    Dashboard
</x-ui::button>
```

**Props:**
- `variant` - `primary`, `secondary`, `danger`, `outline`, `ghost` (default: `primary`)
- `size` - `sm`, `md`, `lg` (default: `md`)
- `icon` - Optional icon name from Heroicons (e.g., `check`, `heart`, `cog`)
- `iconPosition` - `left`, `right` (default: `left`)
- `href` - Optional URL to render as link instead of button

**Dark Mode:**
All button variants automatically adapt to dark mode with optimized colors for better contrast and readability.

### Theme Toggle

```blade
<!-- Button style (default) -->
<x-ui::theme-toggle />

<!-- With custom size -->
<x-ui::theme-toggle size="lg" />

<!-- Toggle switch style -->
<x-ui::theme-toggle style="toggle" />

<!-- Toggle switch with size -->
<x-ui::theme-toggle style="toggle" size="lg" />
```

**Props:**
- `size` - `sm`, `md`, `lg` (default: `md`)
- `style` - `button`, `toggle` (default: `button`)

**Features:**
- Automatically detects system theme preference on first load
- Saves user preference to localStorage
- Smooth animated transitions between light/dark modes
- Supports two visual styles: button and toggle switch
- Uses Alpine.js for reactivity
- Dispatches `theme-changed` custom event for integration with other components

### Icon

```blade
<x-ui::icon name="user" />

<x-ui::icon name="heart" variant="solid" size="lg" />

<x-ui::icon name="cog" variant="outline" size="sm" class="text-blue-500" />
```

**Props:**
- `name` - Icon name (required): `user`, `heart`, `home`, `cog`, `bell`
- `variant` - `outline`, `solid` (default: `outline`)
- `size` - `xs`, `sm`, `md`, `lg`, `xl` (default: `md`)

### Input

```blade
<!-- Basic input -->
<x-ui::input name="email" type="email" placeholder="Enter email" />

<!-- With icon -->
<x-ui::input name="search" icon="magnifying-glass" placeholder="Search..." />

<!-- With label -->
<x-ui::input name="email" type="email" label="Email Address" placeholder="you@example.com" />

<!-- Different sizes -->
<x-ui::input name="small" size="sm" placeholder="Small input" />
<x-ui::input name="medium" size="md" placeholder="Medium input" />
<x-ui::input name="large" size="lg" placeholder="Large input" />

<!-- Search input with clear button -->
<x-ui::input name="search" type="search" icon="magnifying-glass" placeholder="Search..." />

<!-- Password input with show/hide toggle -->
<x-ui::input name="password" type="password" icon="lock-closed" placeholder="Password" />

<!-- Readonly input with copy button -->
<x-ui::input name="api-token" readonly value="your-api-token-here" />

<!-- Filled variant (great for readonly) -->
<x-ui::input name="readonly-field" variant="filled" readonly value="Read-only value" />

<!-- Disabled input (automatically grayed out) -->
<x-ui::input name="disabled-field" disabled value="Disabled input" />

<!-- With icon and size -->
<x-ui::input name="email" type="email" icon="envelope" size="lg" placeholder="your@email.com" />
```

**Props:**
- `name` - Input name (required)
- `label` - Optional label text to display above the input
- `type` - Input type: `text`, `email`, `password`, `search`, `number`, etc. (default: `text`)
- `size` - `sm`, `md`, `lg` (default: `md`)
- `variant` - `default`, `filled` (default: `default`)
- `icon` - Optional left icon name from Heroicons (e.g., `magnifying-glass`, `envelope`, `lock-closed`)

**Features:**
- **Interactive Action Buttons**:
  - `type="search"` - Shows clear button (X) when input has value
  - `type="password"` - Shows eye icon to toggle password visibility
  - `readonly` - Shows copy button to copy value to clipboard
- **Error States**: Red border and red focus ring when Laravel validation errors exist for the input
- **Automatic Behaviors**:
  - Automatically applies filled variant to readonly inputs
  - Disabled inputs are automatically grayed out with reduced opacity
  - Icons automatically scale with input size
  - Action buttons only appear when relevant (not shown on disabled inputs)
- **Full dark mode support**
- **Focus states with ring effects**
- **Alpine.js powered** for interactive features

### Copy to Clipboard

```blade
<!-- Icon-only style (minimal) -->
<x-ui::copy-to-clipboard text="Text to copy" style="icon" />

<!-- Icon with different variants -->
<x-ui::copy-to-clipboard text="Text to copy" style="icon" variant="primary" />
<x-ui::copy-to-clipboard text="Text to copy" style="icon" variant="ghost" />

<!-- Button style with text -->
<x-ui::copy-to-clipboard text="npm install phara/ui-kit" />

<x-ui::copy-to-clipboard text="composer require phara/ui-kit">
    Install Package
</x-ui::copy-to-clipboard>

<!-- Different sizes -->
<x-ui::copy-to-clipboard text="Small text" size="sm">Copy</x-ui::copy-to-clipboard>
<x-ui::copy-to-clipboard text="Medium text" size="md">Copy</x-ui::copy-to-clipboard>
<x-ui::copy-to-clipboard text="Large text" size="lg">Copy</x-ui::copy-to-clipboard>

<!-- Different variants -->
<x-ui::copy-to-clipboard text="Copy this" variant="primary">Copy</x-ui::copy-to-clipboard>
<x-ui::copy-to-clipboard text="Copy this" variant="default">Copy</x-ui::copy-to-clipboard>
<x-ui::copy-to-clipboard text="Copy this" variant="ghost">Copy</x-ui::copy-to-clipboard>
<x-ui::copy-to-clipboard text="Copy this" variant="outline">Copy</x-ui::copy-to-clipboard>

<!-- Custom success message -->
<x-ui::copy-to-clipboard text="API Key: abc123" successMessage="Copied to clipboard!">
    Copy API Key
</x-ui::copy-to-clipboard>

<!-- Copy from slot content if no text prop provided -->
<x-ui::copy-to-clipboard>
    <span data-copy-text>This text will be copied</span>
</x-ui::copy-to-clipboard>

<!-- Custom icons -->
<x-ui::copy-to-clipboard
    text="Text to copy"
    icon="document-duplicate"
    successIcon="check-circle"
>
    Copy Document
</x-ui::copy-to-clipboard>
```

**Props:**
- `text` - Text to copy to clipboard (optional if using slot content)
- `size` - `sm`, `md`, `lg` (default: `md`)
- `variant` - `default`, `primary`, `ghost`, `outline` (default: `default`)
- `style` - `button`, `icon` (default: `button`)
- `successMessage` - Message to show when copied (default: `'Copied!'`)
- `icon` - Icon to show before copying (default: `'clipboard-document'`)
- `successIcon` - Icon to show after copying (default: `'check'`)

**Features:**
- **Visual Feedback**: Icon and text change to show success state for 2 seconds
- **Automatic Detection**: If no `text` prop provided, copies content from slot
- **Two Styles**:
  - `button` - Full button with text and icon
  - `icon` - Minimal icon-only button
- **Async Clipboard API**: Uses modern navigator.clipboard API
- **Error Handling**: Gracefully handles copy failures
- **Color Customization**: Uses the primary color system
- **Full dark mode support**
- **Alpine.js powered** for smooth transitions

## Adding More Heroicons

### All icons are already included!

All 324 Heroicons are pre-installed. You can use any icon from [heroicons.com](https://heroicons.com) immediately.

If you need to update icons when Heroicons releases new ones:

```bash
node packages/phara/ui-kit/extract-all-heroicons.js > icon-paths.txt
```

Then copy the output into `src/Components/Icon.php` replacing the `return [...]` statement in `getIconMap()`.

### Method 2: Manual addition

1. Find your icon at [heroicons.com](https://heroicons.com)

2. Copy the SVG path `d` attribute

3. Add to `src/Components/Icon.php`:

```php
'star' => [
    'outline' => 'M11.48 3.499a.562.562 0 0 1 1.04 0l2.125...',
    'solid' => 'M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082...',
],
```

## Available Icons

**All 324 Heroicons are included!**

Every icon from [heroicons.com](https://heroicons.com) is available in both `outline` and `solid` variants.

Popular icons include:
- `user`, `user-circle`, `user-group` - User icons
- `heart`, `star`, `trophy` - Rating/favorite icons
- `home`, `building-office`, `map` - Location icons
- `cog`, `wrench`, `adjustments-horizontal` - Settings icons
- `bell`, `chat-bubble-left`, `envelope` - Communication icons
- `arrow-right`, `arrow-left`, `chevron-down` - Navigation icons
- `check`, `x-mark`, `exclamation-circle` - Status icons
- `shopping-cart`, `credit-card`, `currency-dollar` - Commerce icons
- `document`, `folder`, `archive-box` - File management icons
- And 300+ more!

View the complete list at [heroicons.com](https://heroicons.com) or visit `/icons` in your browser to see them all.

## Development

The package structure:
```
packages/phara/ui-kit/
├── src/
│   ├── Components/
│   │   ├── Button.php
│   │   └── Icon.php
│   └── UIKitServiceProvider.php
├── resources/
│   └── views/
│       └── components/
│           ├── button.blade.php
│           └── icon.blade.php
├── config/
│   └── ui.php
└── composer.json
```

## Usage in Main App

Components are automatically registered with the `ui::` prefix:

```blade
<x-ui::button>Click me</x-ui::button>
<x-ui::icon name="heart" />
```
