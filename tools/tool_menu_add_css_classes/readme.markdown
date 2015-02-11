[back to overview](../../README.markdown#initial-functionality)

Tool tool_menu_add_css_classes
===============================

This tool adds CSS classe to a menu item by rules. You can filter by posttype and / or functions like `is_single()`.

````php
	$GLOBALS['toolset'] = array(
		'inits' => array(
			'tool_menu_add_css_classes' => array(
				array(
					'menu_item_id' => {id of menu item},
					'is_posttype' => '{posttype name}',
					'rules' => array(
						array( 'is_single', 'not_attachment' ),
						array( 'is_category' ),
						// is equal to: if ( ( is_single() && ! is_attachment() ) || ( is_category() ) ) {
					),
					'class' => 'current-menu-item',
				),
				array(
					// another rule
				),
			),
		),
	);
````

[back to overview](../../README.markdown#initial-functionality)
