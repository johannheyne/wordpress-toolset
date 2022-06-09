<?php

	// Version 2

	function tool_remove_wp_footprint( $p = array() ) {

		// DEFAULTS {

			$defaults = array(
				'remove_admin_bar_logo' => false,
				'remove_loginpage_logo' => false,
				'show_title_on_loginpage' => false, // bolean, string
				'remove_footer_text' => false,
				'remove_gutenberg_frontend_styles' => true,
			);

			// ALL TRUE {

				if ( $GLOBALS['toolset']['inits']['tool_remove_wp_footprint'] === true ) {

					$GLOBALS['toolset']['inits']['tool_remove_wp_footprint'] = array(
						'remove_admin_bar_logo' => true,
						'remove_loginpage_logo' => true,
						'show_title_on_loginpage' => true,
						'remove_footer_text' => true,
					);
				}

			// }

			$p = array_replace_recursive( $defaults, $GLOBALS['toolset']['inits']['tool_remove_wp_footprint'] );

		// }

		// ADMINBAR {

			if ( $p['remove_admin_bar_logo'] ) {

				add_action( 'admin_bar_menu', 'tool_remove_wp_footprint_adminbar', 999 );
			}

		// }

		// LOGINPAGE {

			if ( $p['remove_loginpage_logo'] ) {

				add_action( 'login_head', 'tool_remove_wp_loginpage_logo' );
			}

			if ( $p['show_title_on_loginpage'] ) {

				add_action( 'login_head', 'show_title_on_loginpage' );
			}

		// }

		// FOOTERTEXT AND VERSION {

			if ( $p['remove_footer_text'] ) {

				add_action( 'admin_init', 'tool_remove_wp_footprint_footertexte' );
			}
		// }

		// GUTENBERG STYLES IN FRONTEND {

			if ( $p['remove_gutenberg_frontend_styles'] ) {

				add_action( 'wp_enqueue_scripts', function() {
					wp_dequeue_style( 'wp-block-library' );
					wp_dequeue_style( 'wp-block-library-theme' );
					wp_dequeue_style( 'wc-block-style' ); // REMOVE WOOCOMMERCE BLOCK CSS
					wp_dequeue_style( 'global-styles' ); // REMOVE THEME.JSON
				} );
			}

		// }

	}

	tool_remove_wp_footprint();

	// ADMINBAR {

		// useless since WP 4.0

		function tool_remove_wp_footprint_adminbar( $wp_admin_bar ) {

			$wp_admin_bar->remove_node( 'wp-logo' );
		}

	// }

	// LOGINPAGE {

		function tool_remove_wp_loginpage_logo() {

			echo '<style type="text/css">
				h1 a {
					display: none !important;
				}
			</style>';
		}

		function show_title_on_loginpage() {

			// IN {

				$p = array();

				if ( is_string( $GLOBALS['toolset']['inits']['tool_remove_wp_footprint']['show_title_on_loginpage'] ) ) {

					$p['text'] = $GLOBALS['toolset']['inits']['tool_remove_wp_footprint']['show_title_on_loginpage'];
				}

			// }

			// DEFAULTS {

				$defaults = array(
					'text' => get_bloginfo( 'name' ),
				);

				$p = array_replace_recursive( $defaults, $p );

			// }

			echo '<style type="text/css">
				h1 {
					margin-bottom: 20px !important;
					line-height: 1.1;
				}
				h1:after {
					content: "' . $p['text'] . ' Login";
				}
			</style>';
		}

	// }

	// FOOTERTEXT AND VERSION {

		function tool_remove_wp_footprint_footertexte() {

			add_filter( 'admin_footer_text', '__return_false', 11 );
			add_filter( 'update_footer', '__return_false', 11 );
		}

	// }
