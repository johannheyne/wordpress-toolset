<?php

	function tool_posttypes_register() {

		global $current_blog;

		if ( count( $GLOBALS['toolset']['inits']['tool_posttypes']['posttypes'] ) > 0 ) {

			foreach ( $GLOBALS['toolset']['inits']['tool_posttypes']['posttypes'] as $name => $args ) {

				$register = true;

				if ( isset( $args['toolset'] ) ) {

					if ( isset( $args['toolset']['sites'] ) ) {

						if (
							isset( $current_blog )
							AND ! in_array( $current_blog->blog_id, $args['toolset']['sites'] )
						) {

							$register = false;
						}
					}
				}

				if ( $register ) {

					register_post_type( $name, $args );
				}
			}
		}

	}

	add_action( 'init', 'tool_posttypes_register' );
