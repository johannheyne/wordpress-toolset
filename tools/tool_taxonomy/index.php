<?php

	// Taxonomy Version ( 1 ) {

		// UNREGISTER TAXONOMIES ( Version 1 ) {
			/*
			function unregister_taxonomies() {

				global $wp_taxonomies;

				// category = entfernt Kategorien der Beiträge
				// post_tag = entfernt Schlagworte der Beiträge

				// WPML Hinweis!
				// 'category' kann nur entfernt werden, wenn im WPML Plugin diese Taxonomie
				// nicht mehr für das Auswahlmenü beim Übersetzen der Taxonomies voreingestellt ist.
				// Siehe: sitepress-multilingual-cms/menu/taxonomy-translation.php Line 8
				// http://wpml.org/forums/topic/deregistering-catergory/

				$taxonomies = array( 'post_tag', 'category' );

				foreach ( $taxonomies as $value ) {

					if ( taxonomy_exists( $value ) ) {

						unset( $wp_taxonomies[ $value ] );
					}
				}
			}
			add_action( 'init', 'unregister_taxonomies' );
			*/
		// }

		// BEISPIEL {
			/*
			register_taxonomy( 'demos_kategorien', array( 'demos' ), array(
				'hierarchical'	  => false,
				'labels'			=> array(
					'name'			  => _x( 'Kategorien', 'taxonomy general name' ),
					'singular_name'	 => _x( 'Kategorie', 'taxonomy singular name' ),
					'search_items'	  => __( 'Suche Kategorien' ),
					'all_items'		 => __( 'Alle Kategorien' ),
					'parent_item'	   => __( 'Übergeordnete Kategorie' ),
					'parent_item_colon' => __( 'Übergeordnete Kategorie:' ),
					'edit_item'		 => __( 'Bearbeite Kategorie' ),
					'update_item'	   => __( 'Aktualisiere Kategorie' ),
					'add_new_item'	  => __( 'Kategorie hinzufügen' ),
					'new_item_name'	 => __( 'Neuer Kategorie Name' ),
					'menu_name'		 => __( 'Kategorien' ),
				   ),
				'show_ui'		   => true,
				'show_admin_column' => true,
				'query_var'		 => true,
				'rewrite'		   => array( 'slug' => 'demos' ),
			) );
			*/
		// }

	// }
