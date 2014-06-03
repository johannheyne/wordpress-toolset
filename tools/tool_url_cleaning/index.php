<?php

	// URL CLEANING ( Version 1 ) {

		function tool_sanitize_urlstring( $string = '' ) {

			  $string = str_replace( 'Ä', 'Ae', $string );
			  $string = str_replace( 'ä', 'ae', $string );
			  $string = str_replace( 'Ö', 'Oe', $string );
			  $string = str_replace( 'ö', 'oe', $string );
			  $string = str_replace( 'Ü', 'Ue', $string );
			  $string = str_replace( 'ü', 'ue', $string );
			  $string = str_replace( 'ẞ', 'ss', $string );
			  $string = str_replace( 'ß', 'ss', $string );
			  $string = str_replace( ' ', '-',  $string );

			  $string = trim( $string, '-' );
			  $string = trim( $string, '_' );
			  $string = trim( $string, ' ' );

			  $string = preg_replace( '/[^A-Za-z0-9\.\-_]*/', '', $string );

			  return $string;
		}

		// SLUG {

			add_filter( 'sanitize_title', 'filter_slug', 5, 3 );
			function filter_slug( $title, $raw_title = NULL, $context = 'query' ) {

				if ( $raw_title != NULL ) {
					$title = $raw_title;
				}

				$title = tool_sanitize_urlstring( $title );

				if ( $context == 'save' ) {
					$title = remove_accents( $title );
				}

				return $title;
			}

		// }

		// FILENAME {

			add_filter( 'wp_handle_upload_prefilter', 'custom_upload_name' );   
			function custom_upload_name( $file ) {

				$file['name'] = tool_sanitize_urlstring( $file['name'] );

				return $file;
			}

		// }

	// }

?>