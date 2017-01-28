<?php

	// influencing output off wp_head() ( Version 2 ) {

		function fu_init_wp_head() {

			$defaults = array(
				'feed_links_extra' => array(
					'show' => false,
					'priority' => 3,
				),
				'feed_links' => array(
					'show' => false,
					'priority' => 2,
				),
				'rsd_link' => array(
					'show' => false,
					'priority' => 10,
				),
				'wlwmanifest_link' => array(
					'show' => false,
					'priority' => 10,
				),
				'index_rel_link' => array(
					'show' => false,
					'priority' => 10,
				),
				'wp_shortlink_wp_head' => array(
					'show' => false,
					'priority' => 10,
				),
				'adjacent_posts_rel_link_wp_head' => array(
					'show' => false,
					'priority' => 10,
				),
				'parent_post_rel_link' => array(
					'show' => false,
					'priority' => 10,
				),
				'start_post_rel_link' => array(
					'show' => false,
					'priority' => 10,
				),
				'adjacent_posts_rel_link' => array(
					'show' => false,
					'priority' => 10,
				),
				'wp_generator' => array(
					'show' => false,
					'priority' => 10,
				),
				'rel_canonical' => array(
					'show' => true,
					'priority' => 10,
				),
			);

			if ( is_array( $GLOBALS['toolset']['inits']['tool_wp_head'] ) ) {

				$p = array_replace_recursive( $defaults, $GLOBALS['toolset']['inits']['tool_wp_head'] );
			}
			else {

				$p = $defaults;
			}

			$action = 'wp_head';

			foreach ( $p as $key => $item ) {

				if ( $item['show'] === false ) {

					if ( empty( $item['priority'] ) ) {

						$item['priority'] = 10;
					}

					if ( ! empty( $item['action'] ) ) {

						$action = $item['action'];
					}

					remove_action( $action, $key, $item['priority'] );
				}
			}

		}

		add_action( 'after_setup_theme', 'fu_init_wp_head' );

	// }

?>