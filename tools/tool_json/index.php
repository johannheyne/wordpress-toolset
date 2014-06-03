<?php

	// JSON ENCODE ( Version 2 ) {

		function tool_json_encode( $p = array() ) {

			$p += array(
				'source' => false,
				'keys' => false,
				'print' => false
			);

			$ret = false;

			if ( $p['source'] ) {

				foreach ( $p['source'] as $key => $item ) {

					foreach ( $item as $i => $value ) {

						if ( !$p['keys'] OR $p['keys'] && in_array( $i, $p['keys'] ) ) $ret[$key][$i] = $value;
					}
				}
			}

			if ( $p['print'] ) {

				print_o( $ret );
			}

			return htmlentities( json_encode( $ret ), ENT_QUOTES);
		}

	// }

?>