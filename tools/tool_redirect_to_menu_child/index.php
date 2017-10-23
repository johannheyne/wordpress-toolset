<?php

	// REDIRECT TO MENU CHILD ( Version 2 ) {

		function tool_redirect_to_menu_child( $p = array() ) {

			global $post;

			// DEFAULTS {

				$defaults = array(
					'menu_id' => false,
					'bubble' => 1,
				);

				$p = array_replace_recursive( $defaults, $p );

			// }

			// JOB {

				if ( $p['menu_id'] ) {

					// GET ARRAY OF ALL MENU-ITEMS {

						$menu = wp_get_nav_menu_items( $p['menu_id'], array(
								'order' => 'ASC',
								'orderby' => 'menu_order',
								'post_type' => 'nav_menu_item',
								'post_status' => 'publish',
								'output' => ARRAY_A,
								'output_key' => 'menu_order',
								'nopaging' => true,
								'update_post_term_cache' => false,
								'suppress_filters' => false,
						) );

					// }

					// GET THE CURRENT MENU-ITEM {

						$menu_item = false;

						foreach ( $menu as $key => $item ) {

							if ( $item->object_id == $GLOBALS['wp_query']->queried_object_id ) {

								$menu_item = $item;
							}
						}

					// }

					// GET THE CURRENT MENU-ITEM AND HIS FIRST CHILDS {

						$result = tool( array(
							'name' => 'tool_build_recursive_array',
							'param' => array(
								'array' => $menu,
								'id_name' => 'ID',
								'parent_name' => 'menu_item_parent',
								'index_key' => false,
								'parent_id' => $menu_item->ID, //$item_id,
								'limit_items' => 1,
								'limit_levels' => $p['bubble'],
							),
						) );

					// }

					// GET THE CHILD-URL BY BUBBLING DOWN MENU-ITEMS {

						$url = false;

						$url = $result[0]['item']['url'];

						if ( $p['bubble'] === 2 && isset( $result[0]['children'][0]['item']['url'] ) ) {

							$url = $result[0]['children'][0]['item']['url'];
						}

						if ( $p['bubble'] === 3 && isset( $result[0]['children'][0]['children'][0]['item']['url'] ) ) {

							$url = $result[0]['children'][0]['children'][0]['item']['url'];
						}

					// }

					// REDIRECT {

						if ( $url ) {

							wp_redirect( $url, 302 );

							exit;
						}

					// }

				}

			// }

		}

	// }
