#!/usr/bin/env node

/**
 * Helper script to extract Heroicon SVG paths for use in Blade components
 *
 * Usage:
 *   node extract-heroicons.js user
 *   node extract-heroicons.js user,home,heart
 */

import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const icons = process.argv[2]?.split(',') || [];

if (icons.length === 0) {
    console.log('Usage: node extract-heroicons.js <icon-name>[,<icon-name>,...]');
    console.log('Example: node extract-heroicons.js user,home,heart');
    console.log('\nAvailable variants: outline, solid, mini (20px), micro (16px)');
    process.exit(1);
}

function extractPath(iconName, variant, size = '24') {
    try {
        const iconPath = path.join(
            __dirname,
            '..',
            '..',
            '..',
            'node_modules',
            '@heroicons',
            'vue',
            size,
            variant,
            `${iconName.charAt(0).toUpperCase() + iconName.slice(1)}Icon.js`
        );

        const content = fs.readFileSync(iconPath, 'utf8');

        // Extract d attribute from path
        const pathMatch = content.match(/d:\s*"([^"]+)"/);

        if (pathMatch) {
            return pathMatch[1];
        }

        return null;
    } catch (error) {
        return null;
    }
}

function toCamelCase(str) {
    return str
        .split('-')
        .map((word, index) =>
            index === 0 ? word : word.charAt(0).toUpperCase() + word.slice(1)
        )
        .join('');
}

console.log('// Add these to your Icon.php getIconMap() method:\n');

icons.forEach(iconName => {
    const name = iconName.trim();
    const camelName = toCamelCase(name);

    const outlinePath = extractPath(camelName, 'outline');
    const solidPath = extractPath(camelName, 'solid');

    if (outlinePath || solidPath) {
        console.log(`'${name}' => [`);
        if (outlinePath) {
            console.log(`    'outline' => '${outlinePath}',`);
        }
        if (solidPath) {
            console.log(`    'solid' => '${solidPath}',`);
        }
        console.log('],');
    } else {
        console.log(`// Icon '${name}' not found`);
    }
});

console.log('\n// To see available icons, run:');
console.log('// ls node_modules/@heroicons/vue/24/outline/');
