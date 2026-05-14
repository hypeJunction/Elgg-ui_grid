<?php

namespace UiGrid;

use Elgg\IntegrationTestCase;

/**
 * Tests that the ui_grid plugin registers its view extensions correctly
 * and that the grid CSS and theme_sandbox views render without errors.
 */
class ViewExtensionsTest extends IntegrationTestCase {

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
    public function testGridCssViewExists(): void {
        $this->assertTrue(
            elgg_view_exists('elements/ui/grid.css'),
            'Expected elements/ui/grid.css view to exist'
        );
    }

    /**
     * @return void
     */
    public function testGridCssViewRenders(): void {
        $output = elgg_view('elements/ui/grid.css');
        $this->assertIsString($output);
        $this->assertNotEmpty($output);
    }

    /**
     * @return void
     */
    public function testThemeSandboxGridPageViewExists(): void {
        $this->assertTrue(
            elgg_view_exists('theme_sandbox/ui/grid'),
            'Expected theme_sandbox/ui/grid view to exist'
        );
    }

    /**
     * @return void
     */
    public function testThemeSandboxGridPageRenders(): void {
        $output = elgg_view('theme_sandbox/ui/grid');
        $this->assertIsString($output);
        $this->assertNotEmpty($output);
        // Sanity check: output should contain at least one span class.
        $this->assertStringContainsString('elgg-small', $output);
    }

    /**
     * @return void
     */
    public function testThemeSandboxGridCssViewExists(): void {
        $this->assertTrue(
            elgg_view_exists('theme_sandbox/ui/grid.css'),
            'Expected theme_sandbox/ui/grid.css view to exist'
        );
    }

    /**
     * @return void
     */
    public function testThemeSandboxGridCssViewRenders(): void {
        $output = elgg_view('theme_sandbox/ui/grid.css');
        $this->assertIsString($output);
        $this->assertNotEmpty($output);
    }

    /**
     * @return void
     */
    public function testCoreGridCssViewExtended(): void {
        // ui_grid extends elgg.css with elements/ui/grid.css so the
        // grid rules ship in the main stylesheet on Elgg 7.x.
        $this->assertTrue(elgg_view_exists('elgg.css'));
        $output = elgg_view('elgg.css');
        $this->assertIsString($output);
        $this->assertNotEmpty($output);
        $this->assertStringContainsString('.elgg-small-3', $output);
        $this->assertStringContainsString('.elgg-row', $output);
    }

    /**
     * @return void
     */
    public function testThemeSandboxExtendedWithGridEntry(): void {
        // ui_grid extends theme_sandbox/grid with theme_sandbox/ui/grid.
        $this->assertTrue(elgg_view_exists('theme_sandbox/grid'));
        $output = elgg_view('theme_sandbox/grid');
        $this->assertIsString($output);
        $this->assertNotEmpty($output);
        $this->assertStringContainsString('elgg-small', $output);
    }

    /**
     * @return void
     */
    public function testThemeSandboxCssExtendedWithGridCss(): void {
        // ui_grid extends css/theme_sandbox.css with theme_sandbox/ui/grid.css.
        $this->assertTrue(elgg_view_exists('css/theme_sandbox.css'));
        $output = elgg_view('css/theme_sandbox.css');
        $this->assertIsString($output);
        $this->assertNotEmpty($output);
    }
}
