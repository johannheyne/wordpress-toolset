<?php

	// MENU WORDPRESS ( Version 2 ) {

		// REMOVE {

			// PAGES {

				function _tool_remove_menu_pages() {

					//global $menu; print_o( $menu );

					// Beiträge: edit.php

					foreach ( $GLOBALS['toolset']['inits']['tool_wp_menu']['tool_remove_menu_pages'] as $item ) {

						remove_menu_page( $item['page'] );
					}
				}

				if (
					isset( $GLOBALS['toolset']['inits']['tool_wp_menu']['tool_remove_menu_pages'] )
					&& count( $GLOBALS['toolset']['inits']['tool_wp_menu']['tool_remove_menu_pages'] ) > 0
				) {

					add_action( 'admin_menu', '_tool_remove_menu_pages' );
				}

			// }

			// SUBPAGES {

				function _tool_remove_submenu_page() {

					//global $submenu; print_o( $submenu );

					foreach ( $GLOBALS['toolset']['inits']['tool_wp_menu']['tool_remove_submenu_pages'] as $item ) {

						remove_submenu_page( $item['page'], $item['subpage'] );
					}
				}

				if (
					isset( $GLOBALS['toolset']['inits']['tool_wp_menu']['tool_remove_submenu_pages'] )
					&& count( $GLOBALS['toolset']['inits']['tool_wp_menu']['tool_remove_submenu_pages'] ) > 0
				) {

					add_action( 'admin_menu', '_tool_remove_submenu_page', 999 );
				}
			// }

		// }

		// RENAME {
			/*
			function change_post_menu_label() {
				global $menu;
				global $submenu;
				$menu[5][0] = 'Contacts';
				$submenu['edit.php'][5][0] = 'Contacts';
				$submenu['edit.php'][10][0] = 'Add Contacts';
				$submenu['edit.php'][15][0] = 'Status'; // Change name for categories
				$submenu['edit.php'][16][0] = 'Labels'; // Change name for tags
				echo '';
			}

			add_action( 'admin_menu', 'change_post_menu_label' );
			*/  
		// }

		// SORT {
			/*
			function change_post_object_label() {
				global $wp_post_types;
				$labels = &$wp_post_types['post']->labels;
				$labels->name = 'Contacts';
				$labels->singular_name = 'Contact';
				$labels->add_new = 'Add Contact';
				$labels->add_new_item = 'Add Contact';
				$labels->edit_item = 'Edit Contacts';
				$labels->new_item = 'Contact';
				$labels->view_item = 'View Contact';
				$labels->search_items = 'Search Contacts';
				$labels->not_found = 'No Contacts found';
				$labels->not_found_in_trash = 'No Contacts found in Trash';
			}
			add_action( 'init', 'change_post_object_label' );
			*/
		// }

	// }

?>