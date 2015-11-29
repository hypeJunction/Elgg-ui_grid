<?php

/**
 * AJAX tabs
 *
 * @author Ismayil Khayredinov <info@hypejunction.com>
 * @copyright Copyright (c) 2015, Ismayil Khayredinov
 */
require_once __DIR__ . '/autoloader.php';

elgg_register_event_handler('init', 'system', 'ui_grid_init');

/**
 * Initialize the plugin
 * @return void
 */
function ui_grid_init() {

	elgg_extend_view('css/elements/grid', 'elements/ui/grid.css');

	elgg_extend_view('theme_sandbox/grid', 'theme_sandbox/ui/grid');
	elgg_extend_view('css/theme_sandbox.css', 'theme_sandbox/ui/grid.css');
}
