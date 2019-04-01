<?php

	/**off/

	$result = array();
	$path = 'data';

	$dir = scandir( $path );
	foreach ( $dir as $key => $value ) {

		if (
			$value != '.' AND
			$value != '..' AND
			is_dir( $path . '/' . $value )
		)  {

			$dir2 = scandir( $path . '/' . $value );

			foreach ( $dir2 as $key2 => $value2 ) {

				$path2 = dirname( __FILE__ );

				if (
					is_file( $path2 . '/' . $path . '/' . $value . '/' . $value2 ) AND
					strpos( $value2, '.php' ) == 0 AND
					strpos( $value2, '.json' ) == 0
				) {
					//error_log( print_r( $value2, true) );
					//unlink( $path2 . '/' . $path . '/' . $value . '/' . $value2 );
					//error_log( print_r( $value_new, true) );
					//copy( $path2 . '/' . $path . '/' . $value . '/' . $value2, $path2 . '/' . $value2 );
				}
			}

		}
	}

	/**/
