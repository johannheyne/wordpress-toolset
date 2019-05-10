<?php

	// BACKEND JAVASCRIPT ( Version 1 ) {

		/*function customBackendScript() {

			echo '<script src="' . get_bloginfo('template_directory') . '/js/wp-backend.js" type="text/javascript"></script>';
		}

		add_action('admin_head', 'customBackendScript');
		*/

		add_action( 'admin_enqueue_scripts', function( $hook ) {

			wp_enqueue_script( 'theme_backend_script', get_bloginfo('template_directory') . '/js/wp-backend.js?v=' . config_get_theme_version() );
		});

	// }
