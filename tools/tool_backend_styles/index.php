<?php

	// BACKEND STYLES ( Version 2 ) {

		function customStyles() {

			echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('template_directory') . '/css/backend-styles.css" />';
		}

		add_action( 'admin_head', 'customStyles' );

	// }

?>