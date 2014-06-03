<?php

	// WP-AJAX ( Version 2 ) {

		function tool_ajax_return( $return = 'Hello World!', $newline = '<br/>' ) {

			$return = str_replace( "\n", $newline, $return );
			$return = str_replace( '"', '\"', $return );

			die('"' . $return . '"');  
		}

		// FUNCTION TEMPLATE {
/*

			add_action( 'wp_ajax_myajaxtest', 'myajaxtest' );
			add_action( 'wp_ajax_nopriv_myajaxtest', 'myajaxtest' );

			function myajaxtest() {

				// there is a hidden input field with a global nonce
				if ( !wp_verify_nonce( $_REQUEST['nonce'], 'nonce') ) {

					exit ( 'No naughty business please' );
				}   

				$return = 'Hello World!';

				// new lines \n must be replaced before output by tool_ajax_return( $return, 'newline replacecement' )
				// using ACF WYSIWYG-Field, the \n should replaced by ''
				tool_ajax_return( $return, '' );
			}
*/
		// }

	// }

?>