<?php

	// TOOL META ROBOTS ( Version 1 ) {

		function tool_meta_robots( $p = array() ) {

			$fields = get_lang_field( 'page_meta_searchengines' );

			$values = array();

			// INDEX {

				if ( ! empty( $fields['index'] ) ) {

					array_push( $values, 'index' );
				}
				else {

					array_push( $values, 'noindex' );
				}

			// }

			// FOLLOW {

				if ( ! empty( $fields['follow'] ) ) {

					array_push( $values, 'follow' );
				}
				else {

					array_push( $values, 'nofollow' );
				}

			// }

			echo '<meta name="robots" content="'. implode( ', ', $values ) . '">' . "\n";
		}

	// }
