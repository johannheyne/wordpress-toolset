<?php

	// GET FILESIZE ( Version 1 ) {

		function tool_get_filesize( $id = false, $p = array( 'format' => true ) ) {

			if ( $id ) {

				$path = get_attached_file( $id );
				$size = filesize( $path );

				if ( $p['format'] ) {

					$size = tool_format_filesize( $size );
				}

				return $size;
			}
		}

	// }

	// FORMAT FILESIZE ( Version 1 ) {

		function tool_format_filesize( $size, $decimals = array() ) {

			$arr_units = array(
				'<acronym lang="en" xml:lang="en" title="Byte">B</acronym>',
				'<acronym lang="en" xml:lang="en" title="Kilobyte">KB</acronym>',
				'<acronym lang="en" xml:lang="en" title="Megabyte">MB</acronym>',
				'<acronym lang="en" xml:lang="en" title="Gigabyte">GB</acronym>',
				'<acronym lang="en" xml:lang="en" title="Terabyte">TB</acronym>'
			);

			// GETS SIZE AND UNIT INDEX {

				for ( $i = 0; $size >= 1000; $i++ ) {

					$size /= 1000;
				}

			// }

			// DECIMALS BY METHODE PARAM {

				if (
					is_array( $decimals ) AND
					isset( $decimals[$i] )
				) {

					$decimal = $decimals[$i];
				}

			// }

			// GET DECIMALS WHETER IS BY PARAM OR FILTERED DEFAULT {

				if ( ! isset( $decimal ) ) {

					$decimals = apply_filters( 'tool_format_filesize/decimals', 2, $i );
				}
				else {

					$decimals = $decimal;
				}

			// }

			$dec_point = apply_filters( 'tool_format_filesize/dec_point', ',' );
			$thousands_sep = apply_filters( 'tool_format_filesize/thousands_sep', '.' );

			// ROUNDS SIZE UP ACCORDING TO DECIMALS {

				$multiplier = 1;

				for ( $l = 0; $l < $decimals; $l++ ) {

					if ( $multiplier == 1 ) {

						$multiplier = 10;
					}
					else {

						$multiplier = $multiplier * 10;
					}
				}

				$size = ceil( $size * $multiplier ) / $multiplier;

			// }

			return number_format( $size, $decimals, $dec_point, $thousands_sep ) . ' ' . $arr_units[ $i ];
		}

	// }

	// GET DIRECTORY SIZE {

		function tool_get_directory_size( $path, $p = array( 'format' => true ) ) {

			$bytestotal = 0;

			$path = realpath( $path );

			if(
				$path !== false &&
				$path != '' &&
				file_exists( $path )
			) {

				foreach( new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $path, FilesystemIterator::SKIP_DOTS )) as $object ) {

					$bytestotal += $object->getSize();
				}
			}

			if ( $p['format'] ) {

				$bytestotal = tool_format_filesize( $bytestotal );
			}

			return $bytestotal;
		}

	// }
