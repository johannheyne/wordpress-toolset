<?php

	// MAY GET AN POST ID ( Version 1 ) {

		function tool_may_get_the_id() {

			global $post;

			$return = false;

			if ( $post ) {

				$return = get_the_ID();
			}

			return $return;
		}

	// }

	// GET AN POST ID {

		function tool_get_an_id( $p = array() ) {

			/** ABOUT

				this function returns the current post id if possible
				if not, it will find the next post id by bubbling up the ancestors using the uri slugs
				if no post id was found, it returns the post id of the home page

			**/

			global $post;

			$p += array(
				'level' => false,
				'include_current' => true,
				'page_on_front_fallback' => true,
			);

			$return = false;

			if ( !$return && $p['include_current'] && $post ) {

				$return = get_the_ID();
			}

			if ( !$return ) {

				$uri_array = explode( '/', trim( $_SERVER['REQUEST_URI'], '/' ) );

				if ( $p['level'] !== false && isset( $uri_array[ $p['level'] ] ) ) {

					$return = tool_id_by_slug( $uri_array[ $p['level'] ] );

				} else {

					foreach ( $uri_array as $key => $item ) {

						if ( !$return ) {

							$return = tool_id_by_slug( $uri_array[ $key ] );
						}
					}
				}
			}

			if ( $p['page_on_front_fallback'] && !$return ) {

			   $return = get_option('page_on_front');
			}

			return $return;
		}

	// }
