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

				// DNS-PREFECH
				// Source: https://developer.mozilla.org/en-US/docs/Web/HTTP/Controlling_DNS_prefetching
				'wp_resource_hints' => array(
					'show' => false,
					'priority' => 2,
				),
				'emoji_svg_url' => array(
					'show' => false,
					'filter' => '__return_false',
				),
			);

			if ( is_array( $GLOBALS['toolset']['inits']['tool_wp_head'] ) ) {

				$p = array_replace_recursive( $defaults, $GLOBALS['toolset']['inits']['tool_wp_head'] );
			}
			else {

				$p = $defaults;
			}

			$action = 'wp_head';
			$accepted_args = 1;
			$priority = 10;

			foreach ( $p as $key => $item ) {

				if ( $item['show'] === false ) {

					if ( ! empty( $item['priority'] ) ) {

						$priority = $item['priority'];
					}

					if ( ! empty( $item['action'] ) ) {

						$action = $item['action'];
					}

					if ( ! empty( $item['accepted_args'] ) ) {

						$accepted_args = $item['accepted_args'];
					}

					if ( empty( $item['filter'] ) ) {

						if ( is_array( $action ) ) {

							foreach ( $action as $item2 ) {

								if ( empty( $item2['priority'] ) ) {

									$item2['priority'] = $priority;
								}

								remove_action( $item2['name'], $key, $item2['priority'] );
							}
						}
						else {

							remove_action( $action, $key, $priority );
						}
					}
					else {

						add_filter( $key, $item['filter'], $priority, $accepted_args );
					}
				}
			}
		}

		add_action( 'after_setup_theme', 'fu_init_wp_head' );

	// }
