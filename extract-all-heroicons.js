#!/usr/bin/env node

/**
 * Extract ALL Heroicons from node_modules and generate PHP array code
 */

import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const outlineDir = path.join(__dirname, '..', '..', '..', 'node_modules', '@heroicons', 'vue', '24', 'outline');
const solidDir = path.join(__dirname, '..', '..', '..', 'node_modules', '@heroicons', 'vue', '24', 'solid');

// Convert PascalCase to kebab-case
function toKebabCase(str) {
    return str
        .replace(/Icon$/, '') // Remove 'Icon' suffix
        .replace(/([a-z0-9])([A-Z])/g, '$1-$2')
        .replace(/([A-Z])([A-Z][a-z])/g, '$1-$2')
        .toLowerCase();
}

// Extract SVG path from icon file
function extractPath(filePath) {
    try {
        const content = fs.readFileSync(filePath, 'utf8');
        const pathMatch = content.match(/d:\s*"([^"]+)"/);
        return pathMatch ? pathMatch[1] : null;
    } catch (error) {
        return null;
    }
}

// Get all icon files
const outlineFiles = fs.readdirSync(outlineDir)
    .filter(file => file.endsWith('Icon.js'))
    .sort();

console.log(`Found ${outlineFiles.length} icons\n`);
console.log('Generating PHP array code...\n');

const icons = {};

outlineFiles.forEach(file => {
    const iconName = file.replace('.js', '');
    const kebabName = toKebabCase(iconName);

    const outlinePath = extractPath(path.join(outlineDir, file));
    const solidPath = extractPath(path.join(solidDir, file));

    if (outlinePath || solidPath) {
        icons[kebabName] = {
            outline: outlinePath,
            solid: solidPath
        };
    }
});

// Generate PHP array code
console.log("return [");

Object.keys(icons).sort().forEach(iconName => {
    const icon = icons[iconName];
    console.log(`    '${iconName}' => [`);

    if (icon.outline) {
        // Escape single quotes in the path
        const escapedOutline = icon.outline.replace(/'/g, "\\'");
        console.log(`        'outline' => '${escapedOutline}',`);
    }

    if (icon.solid) {
        // Escape single quotes in the path
        const escapedSolid = icon.solid.replace(/'/g, "\\'");
        console.log(`        'solid' => '${escapedSolid}',`);
    }

    console.log(`    ],`);
});

console.log("];");

console.error(`\n✓ Generated ${Object.keys(icons).length} icons`);
console.error('\nCopy the output above and replace the return statement in:');
console.error('packages/phara/ui-kit/src/Components/Icon.php -> getIconMap() method');
