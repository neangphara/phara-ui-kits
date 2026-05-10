# Phara UI Kit

A Laravel Livewire UI component library with Heroicons integration, dark mode support, and Alpine.js-powered interactivity.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/phara/ui-kit.svg)](https://packagist.org/packages/phara/ui-kit)
[![License](https://img.shields.io/packagist/l/phara/ui-kit.svg)](https://packagist.org/packages/phara/ui-kit)

## Requirements

- PHP 8.1+
- Laravel 10+
- Livewire 4+
- Alpine.js 3+

## Installation

Install the package via Composer:

```bash
composer require phara/ui-kit
```

The service provider is auto-discovered by Laravel — no manual registration needed.

### Alpine.js Setup

If Alpine.js is not already installed:

```bash
npm install alpinejs
```

Then in your `resources/js/app.js`:

```js
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
```

## Usage

All components are available under the `ui::` namespace:

```blade
<x-ui::button>Click me</x-ui::button>
<x-ui::icon name="heart" />
```

---

## Components

### Button

```blade
<x-ui::button variant="primary">Click me</x-ui::button>

<!-- With icon -->
<x-ui::button variant="primary" icon="check">Save</x-ui::button>

<!-- Icon on the right -->
<x-ui::button variant="primary" icon="arrow-right" iconPosition="right">Next</x-ui::button>

<!-- As a link -->
<x-ui::button href="/dashboard" variant="secondary" icon="home">Dashboard</x-ui::button>
```

| Prop | Values | Default |
|------|--------|---------|
| `variant` | `primary`, `secondary`, `danger`, `outline`, `ghost` | `primary` |
| `size` | `sm`, `md`, `lg` | `md` |
| `icon` | Any Heroicon name | — |
| `iconPosition` | `left`, `right` | `left` |
| `href` | URL string | — |

---

### Icon

All 324 Heroicons are included out of the box.

```blade
<x-ui::icon name="user" />
<x-ui::icon name="heart" variant="solid" size="lg" />
<x-ui::icon name="cog" variant="outline" size="sm" class="text-blue-500" />
```

| Prop | Values | Default |
|------|--------|---------|
| `name` | Any [Heroicon](https://heroicons.com) name | required |
| `variant` | `outline`, `solid` | `outline` |
| `size` | `xs`, `sm`, `md`, `lg`, `xl` | `md` |

---

### Input

```blade
<x-ui::input name="email" type="email" label="Email Address" placeholder="you@example.com" />

<!-- With icon -->
<x-ui::input name="search" icon="magnifying-glass" placeholder="Search..." />

<!-- Password with show/hide toggle -->
<x-ui::input name="password" type="password" placeholder="Password" />

<!-- Readonly with copy button -->
<x-ui::input name="token" readonly value="your-api-token-here" />
```

| Prop | Values | Default |
|------|--------|---------|
| `name` | string | required |
| `label` | string | — |
| `type` | `text`, `email`, `password`, `search`, etc. | `text` |
| `size` | `sm`, `md`, `lg` | `md` |
| `variant` | `default`, `filled` | `default` |
| `icon` | Any Heroicon name | — |

**Smart behaviors:**
- `type="search"` — shows a clear (×) button when the field has a value
- `type="password"` — shows an eye icon to toggle visibility
- `readonly` — shows a copy-to-clipboard button automatically

---

### Copy to Clipboard

```blade
<!-- Button style -->
<x-ui::copy-to-clipboard text="composer require phara/ui-kit">
    Copy Install Command
</x-ui::copy-to-clipboard>

<!-- Icon-only style -->
<x-ui::copy-to-clipboard text="your-api-key" style="icon" />

<!-- Custom success message -->
<x-ui::copy-to-clipboard text="secret-key" successMessage="Key copied!">
    Copy Key
</x-ui::copy-to-clipboard>
```

| Prop | Values | Default |
|------|--------|---------|
| `text` | string | — |
| `style` | `button`, `icon` | `button` |
| `variant` | `default`, `primary`, `ghost`, `outline` | `default` |
| `size` | `sm`, `md`, `lg` | `md` |
| `successMessage` | string | `Copied!` |
| `icon` | Heroicon name | `clipboard-document` |
| `successIcon` | Heroicon name | `check` |

---

### Theme Toggle

```blade
<!-- Button style -->
<x-ui::theme-toggle />

<!-- Toggle switch style -->
<x-ui::theme-toggle style="toggle" size="lg" />
```

| Prop | Values | Default |
|------|--------|---------|
| `style` | `button`, `toggle` | `button` |
| `size` | `sm`, `md`, `lg` | `md` |

Automatically detects system preference on first load and persists the user's choice in `localStorage`.

---

## Dark Mode

All components support dark mode out of the box. No extra configuration needed.

## Icons Reference

Browse all available icons at [heroicons.com](https://heroicons.com). Use the icon name directly in any `icon` prop:

```blade
<x-ui::icon name="magnifying-glass" />
<x-ui::icon name="arrow-right" variant="solid" />
```

## License

MIT
