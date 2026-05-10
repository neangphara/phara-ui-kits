# UI Kit Customization

## Primary Color Customization

All components in this UI Kit support customizable primary colors. By default, blue is used as the primary color, but you can override this by defining custom CSS variables in your root styles.

### How It Works

The components use CSS custom properties (CSS variables) that look for `--main-*` variables, falling back to blue if not defined:

```css
--color-primary-600: var(--main-600, #2563eb);
```

### Customize the Primary Color

To use your own primary color, define the `--main-*` variables in your CSS:

**Option 1: In your `app.css` (recommended)**

Add your custom color values to the `:root` selector or `@theme` block:

```css
@theme {
    /* Your custom primary color (example: purple) */
    --main-50: #faf5ff;
    --main-100: #f3e8ff;
    --main-200: #e9d5ff;
    --main-300: #d8b4fe;
    --main-400: #c084fc;
    --main-500: #a855f7;
    --main-600: #9333ea;
    --main-700: #7e22ce;
    --main-800: #6b21a8;
    --main-900: #581c87;
    --main-950: #3b0764;
}
```

**Option 2: Using inline styles on the root element**

```html
<html style="--main-600: #9333ea; --main-700: #7e22ce; --main-500: #a855f7; --main-400: #c084fc;">
```

### Color Scale

You should define the following shades for best results:

- `--main-50` - Lightest shade
- `--main-100`
- `--main-200`
- `--main-300`
- `--main-400` - Used in dark mode for text
- `--main-500` - Used for focus rings
- `--main-600` - Primary background color
- `--main-700` - Hover state
- `--main-800`
- `--main-900`
- `--main-950` - Darkest shade

### Components Using Primary Color

The following components use the primary color system:

- **Button** - `variant="primary"`
- **Input** - Focus ring color
- **Heading** - `variant="primary"`
- **Text** - `variant="primary"`
- **ThemeToggle** - Focus ring and moon icon color

### Example: Using Green as Primary

```css
@theme {
    --main-400: #4ade80;
    --main-500: #22c55e;
    --main-600: #16a34a;
    --main-700: #15803d;
}
```

### Quick Color Generator

You can use tools like [Tailwind Color Generator](https://uicolors.app) to generate a complete color scale for your brand color.
