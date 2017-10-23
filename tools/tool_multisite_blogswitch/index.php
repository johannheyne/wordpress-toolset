<?php

	// WP-MULTISITE BLOGSWITCH ( Version 3 ) {

		function tool_switch_to_blog( $p = array() ) {

			// DEFAULTS {

				$defaults = array(
					'id' => false,
				);

				$p = array_replace_recursive( $defaults, $p );

			// }

			global $switched;
			global $current_blog;

			if ( $p['id'] AND ! is_int( $p['id'] ) AND function_exists( 'config_get_site_key_by_id' ) ) {

				$id = config_get_site_key_by_id( $p['id'] );
			}

			//if ( $current_blog->site_id !== $id ) {

				switch_to_blog( $p['id'] );
			//}

		}

		function tool_restore_blog() {

			global $switched;
			restore_current_blog();
		}

	// }
