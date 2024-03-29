<?php

	add_filter( 'wp_handle_upload_prefilter', function( $file ) {

		if ( 'image/svg+xml' === $file['type'] ) {

			$cont = file_get_contents( $file['tmp_name'] );

			if ( false === strpos( $cont, '<?xml' ) ) {

				$cont = '<?xml version="1.0" encoding="utf-8"?>' . $cont;
				file_put_contents( $file['tmp_name'], $cont );
			};
		}

		return $file;

	} );
