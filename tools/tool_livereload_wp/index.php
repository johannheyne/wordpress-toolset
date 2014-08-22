<?php

	// LIVERELOAD WORDPRESS ( 1 ) {
	
		/* Ever wanted to refresh your Wordpress page when you hit save or update? 
		Just use livereload http://livereload.com
		Using the PHP touch function in the following actions makes Livereload
		think the index.php file has changed.
		*/
		
		function tool_livereload_wp() {
			touch( get_template_directory() . '/index.php');
		}
	
		add_action( 'updated_option', 'tool_livereload_wp' );
		add_action( 'check_admin_referer', 'tool_livereload_wp' );

	// }
	
?>