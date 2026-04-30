# Changelog

## [1.2.0] — Elgg 5.x

- Bumped `elgg/elgg` requirement from `^4.0` to `^5.0`
- Bumped PHP requirement from `>=7.4` to `>=8.2`
- No PHP logic changes required (pure CSS/views plugin)
- Updated Docker test stack to Elgg 5.x (PHP 8.1, MySQL 8.0)

## [1.1.0] — Elgg 4.x

- Removed `manifest.xml`; `composer.json` is now the sole metadata source
- Added `'plugin'` key to `elgg-plugin.php`
- Bumped `composer/installers` from `require-dev ~1.0` to `require ^2.0`

## [1.0.0] — Initial release

- Responsive CSS grid system for Elgg
- View extensions for `css/elements/grid` and theme sandbox integration
