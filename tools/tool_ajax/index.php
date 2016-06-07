<?php

	// WP-AJAX ( Version 3 ) {

		function tool_ajax_return( $return = 'Hello World!', $newline = '<br/>' ) {

			if ( ! is_array( $return ) ) {

				$return = str_replace( "\n", $newline, $return );
				$return = '"' . str_replace( '"', '\"', $return ) . '"';
			}
			else {

				$return = json_encode( $return );
			}

			die( $return );
		}

		// REGISTER AJAX VARS IN SCRIPT {

			/*
				add_action( 'wp_enqueue_scripts', function() {

					wp_register_script( 'myajaxtest_script', get_stylesheet_directory_uri() . '/js/myajaxtest.js', '1.0.0', true );
					wp_localize_script( 'myajaxtest_script', 'wpAjax_myajaxtest', array( 
						'ajaxurl' => admin_url( 'admin-ajax.php' ),
						'ajax_nonce' => wp_create_nonce( 'unique-nonce-name' ),
					) );
					wp_enqueue_script( 'myajaxtest_script' );
				} );

			*/

		// }

		// TEMPLATE {

			/*
				add_action( 'wp_ajax_myajaxtest', 'myajaxtest' );
				add_action( 'wp_ajax_nopriv_myajaxtest', 'myajaxtest' );

				function myajaxtest() {

					// there is a hidden input field with a global nonce
					if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'unique-nonce-name') ) {

						exit ( 'No naughty business please' );
					}   

					$return = 'Hello World!';

					// new lines \n must be replaced before output by tool_ajax_return( $return, 'newline replacecement' )
					// using ACF WYSIWYG-Field, the \n should replaced by ''
					tool_ajax_return( $return, '' );
				}
			*/

		// }

		// JQUERY SCRIPT {

			/*
			$.ajax({
				url: wpAjax_myajaxtest.ajaxurl,
				data: {
					nonce: wpAjax_myajaxtest.ajax_nonce,
					action: 'myajaxtest',
				},
				success:function( data ) {

					// if result is json
					// data = $.parseJSON( data );

					console.log( data );
				},
				error: function( errorThrown ) {

				}
			});
			*/

		// }

	// }

?>