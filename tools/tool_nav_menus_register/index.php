<?php

	// MENUS SITE ( Version 3 ) {

		function tool_nav_menus_register() {

			if ( isset( $GLOBALS['toolset']['inits']['tool_nav_menus_register'] ) ) {

				$array = false;

				foreach ( $GLOBALS['toolset']['inits']['tool_nav_menus_register'] as $item ) {

					if ( ! isset( $item['location'] ) ) {

						$item['location'] = $item['slug'];
					}

					$array[ $item['location'] ] = $item['name'];
				}

				if ( $array ) {

					register_nav_menus( $array );
				}
			}
		}

		function tool_nav_menu_register( $p = array() ) {

			// DEFAULTS {

				$defaults = array(
					'location' => false,
					'name' => false,
				);

				$p = array_replace_recursive( $defaults, $p );

			// }


			if (
				$p['location'] AND
				$p['name']
			) {
				$array[ $p['location'] ] = $p['name'];
				register_nav_menus( $array );

			}

		}

		if ( isset( $GLOBALS['toolset']['inits']['tool_nav_menus_register'] ) ) {

			add_action( 'init', 'tool_nav_menus_register' );
		}

	// }
