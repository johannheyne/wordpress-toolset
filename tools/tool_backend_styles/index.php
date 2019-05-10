<?php

	// BACKEND STYLES ( Version 3 ) {

		add_action( 'admin_head', function() {

			echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo( 'template_directory' ) . '/css/backend-styles.css?v=' . config_get_theme_version() . '" />';

		} );

	// }
