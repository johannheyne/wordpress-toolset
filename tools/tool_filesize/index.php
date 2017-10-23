<?php

	// GET FILESIZE ( Version 1 ) {

		function tool_get_filesize( $id = false, $p = array( 'format' => true ) ) {
			if ( $id ) {
				$url = wp_get_attachment_url( $id );
				$url = str_replace( get_bloginfo('url'), '', $url );
				$size = filesize( trim( $url, '/' ) );
				if ( $p['format'] ) $size = tool_format_filesize( $size );
				return $size;
			}
		}

	// }

	// FORMAT FILESIZE ( Version 1 ) {

		function tool_format_filesize( $size ) {
			$arr_units = array(
				'<acronym lang="en" xml:lang="en" title="Byte">B</acronym>',
				'<acronym lang="en" xml:lang="en" title="Kilobyte">KB</acronym>',
				'<acronym lang="en" xml:lang="en" title="Megabyte">MB</acronym>',
				'<acronym lang="en" xml:lang="en" title="Gigabyte">GB</acronym>',
				'<acronym lang="en" xml:lang="en" title="Terabyte">TB</acronym>'
			);
			for ( $i = 0; $size > 1024; $i++ ) {
				$size /= 1024;
			}
			return number_format( $size, 2, ',', '' ).' '.$arr_units[ $i ];
		}

	// }
