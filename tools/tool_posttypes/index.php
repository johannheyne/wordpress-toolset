<?php

	function tool_posttypes_register() {

	    if ( count( $GLOBALS['toolset']['inits']['tool_posttypes']['posttypes'] ) > 0 ) {

			foreach ( $GLOBALS['toolset']['inits']['tool_posttypes']['posttypes'] as $name => $args ) {

				register_post_type( $name, $args );
			}
		}

	}

	add_action( 'init', 'tool_posttypes_register' );

?>