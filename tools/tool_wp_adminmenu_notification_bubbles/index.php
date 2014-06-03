<?php

	// NOTIFICATION BUBBLES ( Version 1 ) {

		/* USEAGE

			function tool_wp_adminmenu_notification_bubbles_setup() {

				global $wpdb, $menu;
				// print_o( $menu );

				$return = array(
					'notifications' => array(
						'1' => array(
							'post_type' => 'tests',
							'menu' => 'menu-posts-tests', // $menu[n][5] = '$',
							'post_status_from' => array( 'draft', 'pending' ),
							'post_status_to' => 'publish',
							'query' => "SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'tests' AND post_status IN ( 'draft', 'pending' );",
							'note' => '',
							'note-before' => '',
							'note-after' => '',
						),
					),
				);

				return $return;
			}

		*/

		// ADDS THE BUBBLES {

			function tool_wp_adminmenu_notification_bubbles() {

				global $menu, $wpdb;

				$p = tool_wp_adminmenu_notification_bubbles_setup();

				// DEFAULTS {

					$defaults = array(
						'notifications' => false,
					);

					$p = array_replace_recursive( $defaults, $p );

				// }

				if ( $p['notifications'] && count( $p['notifications'] ) > 0 ) {

					// NOTIFICATIONS {

						foreach ( $p['notifications'] as $key => $notification ) {

							// DEFAULTS {

								$defaults = array(
									'query' => false,
									'note' => false,
									'note-before' => false,
									'note-after' => false,
								);

								$notification = array_replace_recursive( $defaults, $notification );

							// }

							// VARS {

								$vars['note'] = '';

							// }

							// BUILD NOTE {

								if ( $notification['note-before'] ) {

									$vars['note'] .= $notification['note-before'];
								}

								if ( $notification['query'] ) {

									$vars['note'] .= $wpdb->get_var( $wpdb->prepare( $notification['query'], '' ) );
								}

								if ( $notification['note'] ) {

									$vars['note'] .= $notification['note'];
								}

								if ( $notification['note-after'] ) {

									$vars['note'] .= $notification['note-after'];
								}

							// }

							// ADD NOTE TO MENU {

								foreach ( $menu as $key => $menuitem ) {

									if ( isset( $menuitem[5] ) && $menuitem[5] == $notification['menu'] ) {

										$class = '';

										if ( is_numeric( $vars['note'] ) ) {

											$class = ' count-' . $vars['note'];
										}

										$menu[ $key ][0] .= $vars['note'] ? '<span class="update-plugins' . $class . '"><span>' . $vars['note'] . '</span></span>' : '';
									}

								}

							// }

						}

					// }

				}
			}

			add_action( 'admin_menu', 'tool_wp_adminmenu_notification_bubbles' );

		// }

		// SET POSTS post_status FROM PENDING TO publish WHEN OPEN {

			function tool_wp_adminmenu_notification_bubbles_action( $post_type, $post ) {

				$p = tool_wp_adminmenu_notification_bubbles_setup();

				if ( count( $p['notifications'] ) > 0 ) {

					foreach ( $p['notifications'] as $key => $notification ) {
						
						$defaults = array(
							'post_status_from' => false,
							'post_status_to' => false,
						);

						$notification = array_replace_recursive( $defaults, $notification );
						
						if ( $notification['post_status_from'] && $notification['post_status_to'] ) {
							
							if ( $notification['post_type'] == $post_type && in_array( $post->post_status, $notification['post_status_from'] ) ) {

								wp_update_post( array(
									'ID' => $post->ID,
									'post_status' => $notification['post_status_to'],
								) );
							}
						}
						
					}
				}

			}

			add_action( 'add_meta_boxes', 'tool_wp_adminmenu_notification_bubbles_action', 10, 2 );

		// }

	// }

?>