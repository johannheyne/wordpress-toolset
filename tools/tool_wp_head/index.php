<?php

	// influencing output off wp_head() ( Version 1 ) {

		function fu_init_wp_head() {

			$defaults = array(
				'feed_links_extra' => false,
				'feed_links' => false,
				'rsd_link' => false,
				'wlwmanifest_link' => false,
				'index_rel_link' => false,
				'wp_shortlink_wp_head' => false,
				'adjacent_posts_rel_link_wp_head' => false,
				'parent_post_rel_link' => false,
				'start_post_rel_link' => false,
				'adjacent_posts_rel_link' => false,
				'wp_generator' => false,
			);

			if ( is_array( $GLOBALS['theme']['inits']['tool_wp_head'] ) ) {

				$p = array_replace_recursive( $defaults, $GLOBALS['theme']['inits']['wp_head'] );
			}
			else {

				$p = $defaults;
			}

			foreach ( $p as $key => $value ) {

				if ( $value === false ) {

					remove_action( 'wp_head', $key );
				}
			}

		}

		add_action( 'init', 'fu_init_wp_head' );

	// }

?>