<?php

	// ADMINBAR {

		function tool_remove_wp_footprint_adminbar( $wp_admin_bar ) {

			$wp_admin_bar->remove_node( 'wp-logo' );
		}

		add_action( 'admin_bar_menu', 'tool_remove_wp_footprint_adminbar', 999 );

	// }

	// LOGINPAGE {

		function tool_remove_wp_footprint_loginpage() {
			echo '<style type="text/css">
				h1 {
					margin-bottom: 20px !important;
				}
				h1 a { 
					display: none !important;
				}
				h1:after {
					content: "' . get_bloginfo( 'name' ) . ' Login";
				}
			</style>';
		}

		add_action( 'login_head', 'tool_remove_wp_footprint_loginpage' );

	// }

	// FOOTERTEXT AND VERSION {

		function tool_remove_wp_footprint_footertexte() {
		    add_filter( 'admin_footer_text',    '__return_false', 11 );
		    add_filter( 'update_footer',        '__return_false', 11 );
		}

		add_action( 'admin_init', 'tool_remove_wp_footprint_footertexte' );

	// }

?>