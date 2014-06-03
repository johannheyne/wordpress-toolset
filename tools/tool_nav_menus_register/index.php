<?php

	// MENUS SITE ( Version 2 ) {

		function tool_nav_menus_register() {

			if ( isset( $GLOBALS['theme']['inits']['tool_nav_menus_register'] ) ) {
				
				$array = false;
				
				foreach ( $GLOBALS['theme']['inits']['tool_nav_menus_register'] as $item ) {

					$array[ $item['slug'] ] = $item['name'];
				}
				
				if ( $array ) {
				    
					register_nav_menus( $array );
				}
			}
		}

		if ( isset( $GLOBALS['theme']['inits']['tool_nav_menus_register'] ) ) {

			add_action( 'init', 'tool_nav_menus_register' );
		}

	// }

?>