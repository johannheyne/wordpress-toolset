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

		if ( isset( $GLOBALS['toolset']['inits']['tool_nav_menus_register'] ) ) {

			add_action( 'init', 'tool_nav_menus_register' );
		}

	// }
