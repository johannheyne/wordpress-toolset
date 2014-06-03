<?php

	// WP-MULTISITE BLOGSWITCH ( Version 2 ) {

		function tool_switch_to_blog( $id ) {

			global $switched;
			global $current_blog;

			if ( !is_int( $id ) ) {

				$id = config_get_site_key_by_id( $id );
			}

			switch_to_blog( $id );
		}

		function tool_restore_blog() {

			global $switched;
			restore_current_blog();
		}

	// }

?>