<?php

	// BACKEND JAVASCRIPT ( Version 1 ) {

		function customBackendScript() {

			echo '<script src="' . get_bloginfo('template_directory') . '/js/wp-backend.js" type="text/javascript"></script>';
		}

		add_action('admin_head', 'customBackendScript');

	// }

?>