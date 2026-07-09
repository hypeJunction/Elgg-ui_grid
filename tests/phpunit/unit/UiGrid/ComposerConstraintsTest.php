<?php

namespace UiGrid;

use PHPUnit\Framework\TestCase;

/**
 * Migration regression guards for the composer constraint bumps that make
 * ui_grid installable on an Elgg 7.x tree.
 *
 * Static (no Elgg boot) — reads composer.json off disk.
 *
 *  - 7e4224e: php_constraint_too_low — require.php must demand >= 8.3 so the
 *    plugin cannot be resolved onto a runtime Elgg 7.x rejects.
 *  - 64e7993: elgg/elgg bumped ^6.0 -> ^7.0.
 */
final class ComposerConstraintsTest extends TestCase {

	/**
	 * @return array<string, mixed>
	 */
	private function composer(): array {
		$path = dirname(__DIR__, 4) . '/composer.json';
		$this->assertFileExists($path, 'composer.json must exist at plugin root');
		$data = json_decode((string) file_get_contents($path), true);
		$this->assertIsArray($data, 'composer.json must be valid JSON');
		$this->assertArrayHasKey('require', $data);

		return $data;
	}

	/**
	 * Regression for 7e4224e: an Elgg 7.x plugin must not permit PHP < 8.3.
	 *
	 * @return void
	 */
	public function testRequiresPhp83OrHigher(): void {
		$require = $this->composer()['require'];
		$this->assertArrayHasKey('php', $require);

		$constraint = (string) $require['php'];
		// Elgg 7.x needs PHP 8.3+. The old constraint (>=8.1 / >=8.2) would
		// silently allow composer to resolve onto an unsupported interpreter.
		$this->assertSame(
			'>=8.3',
			$constraint,
			"composer require.php must be '>=8.3' for Elgg 7.x, got '{$constraint}'"
		);
	}

	/**
	 * Regression for 64e7993: the plugin targets the Elgg 7.x major only.
	 *
	 * @return void
	 */
	public function testRequiresElgg7Major(): void {
		$require = $this->composer()['require'];
		$this->assertArrayHasKey('elgg/elgg', $require);

		$constraint = (string) $require['elgg/elgg'];
		$this->assertSame(
			'^7.0',
			$constraint,
			"composer require.elgg/elgg must be '^7.0', got '{$constraint}'"
		);
		// Guard against a stale lower-major constraint slipping back in.
		$this->assertStringNotContainsString('6.0', $constraint);
		$this->assertStringNotContainsString('^6', $constraint);
	}
}
