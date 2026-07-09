<?php

namespace UiGrid;

use PHPUnit\Framework\TestCase;

/**
 * Structural contract for the grid stylesheet (views/default/elements/ui/grid.css).
 *
 * Static (no Elgg boot) — reads the raw .css view off disk. These assertions
 * pin the actual grid maths and the responsive breakpoint layout, so a bad
 * regeneration of the stylesheet (wrong widths, dropped breakpoint, missing
 * gallery/offset families) fails loudly rather than shipping a subtly broken
 * grid behind an HTTP 200.
 */
final class GridCssStructureTest extends TestCase {

	private function gridCss(): string {
		$path = dirname(__DIR__, 4) . '/views/default/elements/ui/grid.css';
		$this->assertFileExists($path, 'grid stylesheet must exist');
		$css = (string) file_get_contents($path);
		$this->assertNotEmpty($css);

		return $css;
	}

	/**
	 * The 12-column system resolves to the correct fractional widths.
	 *
	 * @return void
	 */
	public function testTwelveColumnWidths(): void {
		$css = $this->gridCss();
		$this->assertStringContainsString('.elgg-small-12 { width: 100%; }', $css);
		$this->assertStringContainsString('.elgg-small-6 { width: 50%; }', $css);
		$this->assertStringContainsString('.elgg-small-3 { width: 25%; }', $css);
		$this->assertStringContainsString('.elgg-small-4 { width: 33.33333%; }', $css);
	}

	/**
	 * Each column family lives inside its own breakpoint media query:
	 * small @ 0em, medium @ 40.063em, large @ 64.063em, and in that order.
	 *
	 * @return void
	 */
	public function testResponsiveBreakpointsWrapTheirColumnFamilies(): void {
		$css = $this->gridCss();

		$small = strpos($css, '@media (min-width: 0em)');
		$medium = strpos($css, '@media (min-width: 40.063em)');
		$large = strpos($css, '@media (min-width: 64.063em)');

		$this->assertNotFalse($small, 'missing small (0em) breakpoint');
		$this->assertNotFalse($medium, 'missing medium (40.063em) breakpoint');
		$this->assertNotFalse($large, 'missing large (64.063em) breakpoint');

		// Breakpoints appear in ascending order.
		$this->assertLessThan($medium, $small);
		$this->assertLessThan($large, $medium);

		// .elgg-medium-* rules fall inside the medium query (after 40.063em,
		// before the large query); .elgg-large-* after the large query.
		$mediumRule = strpos($css, '.elgg-medium-6 { width: 50%; }');
		$largeRule = strpos($css, '.elgg-large-6 { width: 50%; }');
		$this->assertNotFalse($mediumRule);
		$this->assertNotFalse($largeRule);
		$this->assertGreaterThan($medium, $mediumRule);
		$this->assertLessThan($large, $mediumRule);
		$this->assertGreaterThan($large, $largeRule);
	}

	/**
	 * Block-grid gallery families and offset helpers are present across
	 * breakpoints.
	 *
	 * @return void
	 */
	public function testGalleryAndOffsetFamiliesPresent(): void {
		$css = $this->gridCss();
		$this->assertStringContainsString('.elgg-gallery-small-5', $css);
		$this->assertStringContainsString('.elgg-gallery-medium-4', $css);
		$this->assertStringContainsString('.elgg-gallery-large-6', $css);
		$this->assertStringContainsString('.elgg-offset-small-2', $css);
		$this->assertStringContainsString('.elgg-offset-small-1', $css);
	}

	/**
	 * Legacy float-layout scaffolding: the row clearfix and the spacer utility.
	 *
	 * @return void
	 */
	public function testSpacerAndRowClearfix(): void {
		$css = $this->gridCss();

		// .elgg-row:after clears floated columns.
		$this->assertMatchesRegularExpression(
			'/\.elgg-row:after\s*\{[^}]*clear:\s*both/',
			$css,
			'.elgg-row:after must clear both floats'
		);

		// .elgg-spacer is a full-width block.
		$this->assertMatchesRegularExpression(
			'/\.elgg-spacer\s*\{[^}]*display:\s*block/',
			$css
		);
		$this->assertMatchesRegularExpression(
			'/\.elgg-spacer\s*\{[^}]*width:\s*100%/',
			$css
		);
	}
}
