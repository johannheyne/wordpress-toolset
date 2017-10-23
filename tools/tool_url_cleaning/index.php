<?php

	// URL CLEANING ( Version 2 ) {

		function tool_url_cleaning( $string = '' ) {

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

			add_filter( 'sanitize_title', function( $title, $raw_title = NULL, $context = 'query' ) {

				if ( $raw_title != NULL ) {
					$title = $raw_title;
				}

				$title = tool_url_cleaning( $title );

				if ( $context == 'save' ) {
					$title = remove_accents( $title );
				}

				return $title;
			}, 5, 3 );


		// }

		// FILENAME {

			add_filter( 'wp_handle_upload_prefilter', function( $file ) {

				$file['name'] = tool_url_cleaning( $file['name'] );

				return $file;
			} );


		// }

	// }
