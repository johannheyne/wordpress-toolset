<?php

	// ID BY SLUG {

		function tool_id_by_slug( $slug ) {
			
			global $wpdb;

			$sql = "SELECT ID FROM $wpdb->posts WHERE post_name = %s";
			$query = $wpdb->prepare( $sql, $slug );
			
			return $wpdb->get_var( $query );
		}

	// }

	

?>