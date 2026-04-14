# ui_grid — Architecture (Elgg 4.x)

## Summary

ui_grid provides a responsive CSS grid system for Elgg. It contains no PHP
logic — its sole purpose is registering CSS view extensions that inject grid
styles into Elgg's asset pipeline.

## Directory Structure

```
ui_grid/
├── views/default/
│   ├── elements/ui/grid.css       — Core grid CSS rules
│   ├── theme_sandbox/ui/grid      — Theme sandbox grid demo view
│   └── theme_sandbox/ui/grid.css  — Theme sandbox grid CSS
├── sass/                          — SASS source for grid.css
├── tests/
│   ├── phpunit/integration/…/BootstrapTest.php
│   ├── bootstrap.php
│   └── phpunit.xml
├── composer.json
└── elgg-plugin.php
```

## Registered View Extensions

| Extends | With |
|---------|------|
| `css/elements/grid` | `elements/ui/grid.css` |
| `theme_sandbox/grid` | `theme_sandbox/ui/grid` |
| `css/theme_sandbox.css` | `theme_sandbox/ui/grid.css` |

## Dependencies

None — leaf plugin with no sibling plugin dependencies.

## Migration Notes (3.x → 4.x)

- `manifest.xml` removed; `composer.json` is now the sole metadata source.
- `elgg-plugin.php` received the `'plugin'` key.
- `composer/installers` moved from `require-dev` to `require` and bumped to `^2.0`.
- No PHP code — no security findings, no exception renames needed.
