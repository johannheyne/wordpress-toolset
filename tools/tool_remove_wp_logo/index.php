<?php

	add_action( 'admin_bar_menu', 'tool_remove_wp_logo', 999 );

	function tool_remove_wp_logo( $wp_admin_bar ) {
		
		$wp_admin_bar->remove_node( 'wp-logo' );
	}

?>