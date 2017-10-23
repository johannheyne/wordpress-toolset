<?php

	// HELPER ( Version 9 ) {

		function print_o( $obj, $return = false ) {

			$r = '';

			$style = 'min-width: 400px; max-width: 100%; display: inline-block; border-top: 1px solid #fff; position: relative; z-index: 9999; padding: 10px 20px; overflow: auto; color: white; background: rgba(0,0,0,0.8); color: white; font-size: 16px; line-height: 1.2;';

			$r .= '<pre style="' . $style . '">';

			$r .= print_r( $obj, true );

			$r .=   '</pre><br/>';

			if ( $return ) {

				return $r;

			} else {

				echo $r;
			}
		}

		// Link HTTP(S):// Filter {

			function tool_link_http( $string ) {

				if ( $string ) {

					if( strpos( $string, "http://") === false && strpos( $string, "https://") === false ) {
						$string = 'http://' . $string;
					}

					return $string;
				}

				else {
				   return false;
				}
			}

		// }

	// }
