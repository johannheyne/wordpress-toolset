<?php

	// LIVERELOAD WORDPRESS ( 1 ) {
	
	function livereload() {
		touch( get_template_directory() . '/style.css');
	}
	
	add_action( 'save_post', 'livereload' );
	add_action( 'updated_option', 'livereload' );
	add_action( 'wp_update_nav_menu', 'livereload' );
	add_action( 'edit_attachment', 'livereload' );
	add_action( 'edit_category', 'livereload' );
	add_action( 'check_admin_referer', 'livereload' );

?>