<?php

	// FILTER ( Version 4 ) {

		// FUNCTION {

			function tool_filter( $arg, $string ) {

				foreach ( $arg as $value ) {

					if ( $value === 'placeholder' ) {

						$string = strtr( $string, $GLOBALS['toolset']['inits']['tool_content_filter']['placeholder'] );
					}

					if ( $value === 'textile' ) {

						if ( function_exists('textile_this') ) {

							$string = textile_this( $string );
						}
					}

					if ( $value === 'remove-p' ) {

						$string = strtr( $string, array (
							'<p>' => '',
							'</p>' => ''
						) );
					}
				}

				if ( 'typofilter' ) {

					$array = array (
						'&#8220;' => '&bdquo;',
						'&#8221;' => '&ldquo;',
						'&#8216;' => '&sbquo;',
						'&#8217;' => '&lsquo;'
					);

					$string = strtr( $string, $array );
				}

				return $string;
			}

		// }

		// ACTION {

			function the_content_placeholder_data( $content ) {

				return tool_filter( $GLOBALS['toolset']['inits']['tool_content_filter'], $content );
			}

			if ( $GLOBALS['toolset']['inits']['tool_content_filter'] ) {

				add_filter( 'the_content', 'the_content_placeholder_data' );
			}

		// }

	// }
