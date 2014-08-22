<?php

	// LIVERELOAD WORDPRESS ( 1 ) {
	
	function tool_livereload_wp() {
		touch( get_template_directory() . '/index.php');
	}
	
	add_action( 'save_post', 'tool_livereload_wp' );
	add_action( 'updated_option', 'tool_livereload_wp' );
	add_action( 'wp_update_nav_menu', 'tool_livereload_wp' );
	add_action( 'edit_attachment', 'tool_livereload_wp' );
	add_action( 'edit_category', 'tool_livereload_wp' );
	add_action( 'check_admin_referer', 'tool_livereload_wp' );

?>