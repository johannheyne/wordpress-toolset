<?php

	// WP_NAV_MENU KLASSEN REDUZIEREN ( Version 1 ) {

		/* Die Klassen "current-menu-item", "current-menu-parent", "current-menu-ancestor"
		   werden für SubmenuWalker() benötigt */

		function cssklassen_menu_classes( $classes, $item ) {

			$classes = array_filter( 
				$classes, 
				create_function( '$class', 'return in_array( $class, array( "current-menu-item", "current-menu-parent", "current-menu-ancestor" ) );' )
			);
			return array_merge( $classes, (array)get_post_meta( $item->ID, '_menu_item_classes', true ) );
		}

		add_filter( 'nav_menu_css_class', 'cssklassen_menu_classes', 10, 2 );

	// }

?>