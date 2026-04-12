<?php

namespace UiGrid;

use Elgg\IntegrationTestCase;

/**
 * Basic smoke test: plugin is active and its elgg-plugin.php returns the
 * expected configuration structure.
 */
class PluginActiveTest extends IntegrationTestCase {

    public function up() {}

    public function down() {}

    public function getPluginID(): string {
        return 'ui_grid';
    }

    public function testPluginIsActive(): void {
        $plugin = elgg_get_plugin_from_id('ui_grid');
        $this->assertNotNull($plugin);
        $this->assertTrue($plugin->isActive());
    }

    public function testElggPluginConfigStructure(): void {
        $config = include dirname(__DIR__, 4) . '/elgg-plugin.php';
        $this->assertIsArray($config);
        $this->assertArrayHasKey('view_extensions', $config);
        $this->assertArrayHasKey('css/elements/grid', $config['view_extensions']);
        $this->assertArrayHasKey('theme_sandbox/grid', $config['view_extensions']);
        $this->assertArrayHasKey('css/theme_sandbox.css', $config['view_extensions']);
    }
}
