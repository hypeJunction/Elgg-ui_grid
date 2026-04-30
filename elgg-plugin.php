<?php

return [
	'plugin' => [
		'id' => 'ui_grid',
		'name' => 'Grid System',
		'version' => '1.3.0',
		'description' => 'Responsive CSS grid system for Elgg.',
		'author' => 'Ismayil Khayredinov',
		'category' => 'ui',
	],

	'view_extensions' => [
		'css/elements/grid' => [
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
