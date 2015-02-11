[back to overview](../../README.markdown#initial-functionality)

Tool tool_widgets_unregister_defaults
===============================

This tool unregister WordPress default widgets. You can exclude widget.

````php
	$GLOBALS['toolset'] = array(
		'inits' => array(
			'tool_widgets_unregister_defaults' => array(, // false, true, array
				'exclude' => array(  ),
				/* Exclude Array Widget Keys
					'pages',
					'calendar',
					'archives',
					'links',
					'meta',
					'search',
					'text',
					'categories',
					'recent_posts',
					'recent_comments',
					'rss',
					'tag_cloud',
					'nav_menu',
				*/
			),
		),
	);
````

[back to overview](../../README.markdown#initial-functionality)
