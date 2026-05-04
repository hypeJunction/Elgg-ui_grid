<?php

return [
	'plugin' => [
		'id' => 'ui_grid',
		'name' => 'Grid System',
		'version' => '1.4.0',
		'description' => 'Responsive CSS grid system for Elgg.',
		'author' => 'Ismayil Khayredinov',
		'category' => 'ui',
	],

	'view_extensions' => [
		// Elgg 7.x's elgg.css → core.css no longer renders an
		// elements/grid view, so attach the grid styles directly to
		// the main stylesheet.
		'elgg.css' => [
			'elements/ui/grid.css' => [],
		],
		'theme_sandbox/grid' => [
			'theme_sandbox/ui/grid' => [],
		],
		'css/theme_sandbox.css' => [
			'theme_sandbox/ui/grid.css' => [],
		],
	],
];
