<?php

	// HTML CLASSES ( VERSION 1 ) {

		function tool_html_classes( $p ) {

			$p += array(
				'classes' => array(),
				'echo' => true,
			);

			$return = '';

			if ( count( $p['classes'] ) > 0 ) {

				foreach ( $p['classes'] as $value ) {

					$return .= ' ' . trim( $value );
					$return = trim( $return );
				}
			}

			if ( $p['echo'] ) {

				echo ' ' . $return;
			}
			else {

				return $return;
			}
		}

	// }
