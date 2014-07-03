<?php

	// BACKEND STYLES MULTISITE ( Version 2 ) {

		function tool_multisite_switchmenu_sufix() {

			if ( isset( $GLOBALS['toolset']['sites'] ) && is_array( $GLOBALS['toolset']['sites'] ) ) {

				echo '<style type="text/css"> ';

					foreach ( $GLOBALS['toolset']['sites'] as $key => $item ) {

						echo '#wp-admin-bar-blog-' . $item['key'] . ' > a:after { content: " (' . $item['name'] . ')"; }';
					}

					echo '#wp-admin-bar-site-name > a:after {  content: " (' . $GLOBALS['toolset']['sites'][ config_get_curr_site_key() ]['name'] . ')"; }';

				echo '</style>';
			}

		}

		add_action( 'admin_head', 'tool_multisite_switchmenu_sufix' );

	// }

?>