<?php

	// MENU WORDPRESS ( Version 1 ) {

		// ENTFERNEN {

			// MAIN {
				/*
				function tool_remove_menu_page() {

					//global $menu; print_o( $menu );

					// Beiträge: edit.php

					remove_menu_page('link-manager.php');
					remove_menu_page('edit-comments.php');
				}

				add_action( 'admin_menu', 'tool_remove_menu_page' );
				*/
			// }

			// SUB {
				/*
				function tool_remove_submenu_page() {

					//global $submenu; print_o( $submenu );

					$page = remove_submenu_page( 'edit.php', 'edit-tags.php?taxonomy=post_tag' );
					$page = remove_submenu_page( 'edit.php', 'edit-tags.php?taxonomy=category' );
				}

				add_action( 'admin_menu', 'tool_remove_submenu_page', 999 );
				*/
			// }

		// }

		// UMBENENNEN {
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

		// SORTIEREN {
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