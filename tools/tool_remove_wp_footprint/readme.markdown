[back to overview](../README.markdown#initial-functionality)

WordPress Remove Footprint
===============

This hides serveral WordPress brand elements.

```php
$GLOBALS['toolset'] = array(
	'inits' => array(
		'tool_remove_wp_footprint' => true, // true, array
	),
);

// or 

$GLOBALS['toolset'] = array(
	'inits' => array(
		'tool_remove_wp_footprint' => array(
			'remove_admin_bar_logo' => true,
			'remove_loginpage_logo' => true,
			'show_title_on_loginpage' => true, // boolean ( true = blogname ), string
			'remove_footer_text' => true,
		),
	),
);
`````

[back to overview](../README.markdown#initial-functionality)
