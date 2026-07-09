<?php

namespace UiGrid;

use Elgg\IntegrationTestCase;

/**
 * Strengthens the c427052 regression: after retargeting the view extension
 * from the dead css/elements/grid view onto elgg.css, the FULL responsive
 * grid must land in the compiled main stylesheet — not just the top-level
 * small-breakpoint rules.
 *
 * ViewExtensionsTest::testCoreGridCssViewExtended already asserts .elgg-small-3
 * (a rule outside any @media block). This test guards the media-query-scoped
 * medium/large rules, which are a distinct regression vector: a partial or
 * mis-ordered merge could ship the small rules while dropping the responsive
 * breakpoints, leaving the page HTTP 200 but non-responsive.
 */
class GridCompiledCssTest extends IntegrationTestCase {

	public function up() {}

	public function down() {}

	/**
	 * @return string
	 */
	public function getPluginID(): string {
		return 'ui_grid';
	}

	/**
	 * @return void
	 */
	public function testCompiledElggCssShipsResponsiveGrid(): void {
		$css = elgg_view('elgg.css');
		$this->assertIsString($css);
		$this->assertNotEmpty($css);

		// The medium and large breakpoints (and their column families) must be
		// present in the compiled stylesheet.
		$this->assertStringContainsString('@media (min-width: 40.063em)', $css);
		$this->assertStringContainsString('@media (min-width: 64.063em)', $css);
		$this->assertStringContainsString('.elgg-medium-6', $css);
		$this->assertStringContainsString('.elgg-large-3', $css);

		// And the gallery block-grid family the plugin is known for.
		$this->assertStringContainsString('.elgg-gallery-small-5', $css);
	}
}
