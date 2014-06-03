<?php

	// GET MENU ANCESTORS ( Version 2 ) {

		function tool_get_menu_ancestors( $p = array() ) {

			/** ABOUT

				USE {

					$result = tool_get_menu_ancestors( array(
						'menu_id' => false,
						'post_id' => tool_get_an_id(),
						'level' => false
					) );

				}
				REQUIRED BY {
					tool_has_menu_ancestors()
				}
				REQUIRES {
					tool_id_by_slug()
					tool_get_an_id()
				}
				RETURN {
					an array of the menu ancestors objects
				}
			**/

			$p += array(
				'menu_id' => false,
				'post_id' => tool_get_an_id(),
				'level' => false
			);

			$return = false;

			if ( $p['menu_id'] && $p['post_id'] ) {

				$menu_id = $p['menu_id'];

				if ( isset( $GLOBALS['toolglobal']['menus'][ $p['menu_id'] ] ) ) {

					$menu_items = $GLOBALS['toolglobal']['menus'][ $p['menu_id'] ];

				} else {

					$menu_items = wp_get_nav_menu_items( $menu_id );
				}

				$GLOBALS['toolglobal']['menus'][ $p['menu_id'] ] = $menu_items;

				$menu_parent_item = array();

				foreach ( $menu_items as $key => $item ) {

					if ( $item->object_id == $p['post_id'] ) {
						$menu_id = $item->ID;
					}
				}

				$check = false;
				$i = 0;

				while ( $check === false ) {

					$i++;

					if ( $i === 99 ) {

						$check = true;
					}

					foreach ( $menu_items as $menu_item ) {

						if( $menu_item->ID == $menu_id ) {

							$menu_id = $menu_item->menu_item_parent;
							$return[] = $menu_item;

							if( $menu_item->menu_item_parent == 0 ) {

								$check = true;
							}
						}
					}
				}

				$return = array_reverse( $return );

				if ( $p['level'] !== false && isset( $return[ $p['level'] ] ) ) {

					$return = $return[ $p['level'] ];
				}
			}

			return $return;  
		}

	// }
	
	// HAS MENU ANCESTORS ( Version 1 ) {

		function tool_has_menu_ancestors( $p = array() ) {

			/** ABOUT

				USE {

					$result = tool_has_menu_ancestors( array(
						'menu_id' => false,
						'post_id' => tool_get_an_id()
					) );

				}
				REQUIRE {
					tool_get_menu_ancestors()
				}
				RETURN {
					the number of anchestors from 0 on
				}
			**/

			$p += array(
				'menu_id' => false,
				'post_id' => tool_get_an_id()
			);

			$return = tool_get_menu_ancestors( array(
				'menu_id' => $p['menu_id'],
				'post_id' => $p['post_id'],
			) );

			$count = count( $return );

			if ( $count ) {

				$return = $count - 1;
			}

			return $return;  
		}

	// }
	
?>