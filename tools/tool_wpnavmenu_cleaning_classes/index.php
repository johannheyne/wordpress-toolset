<?php

	// WP_NAV_MENU KLASSEN REDUZIEREN ( Version 1 ) {

		/* Die Klassen "current-menu-item", "current-menu-parent", "current-menu-ancestor"
		   werden für SubmenuWalker() benötigt */

		add_filter( 'nav_menu_css_class', function( $classes, $item ) {

			// DEFAULTS {

				$defaults = array(
					'allow_classes' => array(
						'current-menu-item',
						'current-menu-parent',
						'current-menu-ancestor',
						//'menu-item',
						//'dropdown',
						//'menu-item-has-children',
					),
				);

				$p = array();

				if ( isset( $GLOBALS['toolset']['inits']['tool_wpnavmenu_cleaning_classes'] ) ) {

					$p = $GLOBALS['toolset']['inits']['tool_wpnavmenu_cleaning_classes'];
				}

				$p = array_replace_recursive( $defaults, $p );

			// }

			$filter = $p['allow_classes'];

			$r = array_intersect( $classes , $filter );

			return $r;

		}, 10, 2 );

	// }

?>