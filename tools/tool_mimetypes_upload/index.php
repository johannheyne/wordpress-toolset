<?php

	// MEDIA-UPLOAD ERLAUBTE DATEITYPEN ( Version 2 ) {

		/* MIME-Typen Übersicht
			http://de.selfhtml.org/diverses/mimetypen.htm */

		/* alternativ Upload-Mime-Typen per Plugin verwalten:
			http://blog.ftwr.co.uk/wordpress/mime-config/ */

		/* Alle Dateitypen zulassen
			Alle Dateitypen kann man mit der Konstante 
			define (‘ALLOW_UNFILTERED_UPLOADS’, true); 
			in der wp-config.php erlauben. */
			

		function tool_mimetypes_upload( $mimes ) {

			if ( $GLOBALS['theme']['inits']['tool_mimetypes_upload']['reset_mimetypes'] ) {

				$mimes = array();
			}
			
			$mimes = array_replace_recursive( $GLOBALS['theme']['inits']['tool_mimetypes_upload']['add_mimetypes'], $mimes );
			
			return $mimes;
		}

		add_filter( 'upload_mimes', 'tool_mimetypes_upload' );

	// }

?>