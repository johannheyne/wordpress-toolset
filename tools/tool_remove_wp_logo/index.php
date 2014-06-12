<?php

	// ADMINBAR {

		function tool_remove_wp_logo_adminbar( $wp_admin_bar ) {

			$wp_admin_bar->remove_node( 'wp-logo' );
		}

		add_action( 'admin_bar_menu', 'tool_remove_wp_logo_adminbar', 999 );

	// }

	// LOGINPAGE {

		function tool_remove_wp_logo_loginpage() {
			echo '<style type="text/css">
				h1 a { 
					display: none !important;
				}
				h1:after {
					content: "' . get_bloginfo( 'name' ) . '";
				}
			</style>';
		}

		add_action( 'login_head', 'tool_remove_wp_logo_loginpage' );

	// }

?>