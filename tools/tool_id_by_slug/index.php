<?php

	// ID BY SLUG {

		function tool_id_by_slug( $slug ) {
			
			global $wpdb;
			
			return $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_name = '" . $slug . "'" );
		}

	// }

	

?>