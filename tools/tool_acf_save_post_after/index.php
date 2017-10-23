<?php

	// ACF SAVE POST ( Version 1 ) {

		// SOURCE: http://www.advancedcustomfields.com/resources/acfsave_post/

		if ( isset( $GLOBALS['toolset']['inits']['tool_acf_save_post_after'] ) ) {

			add_action( 'acf/save_post', function( $post_id ) {

				$param = array(
					'post_id' => $post_id,
					'fields' => false,
				);

				// ACF4
				if ( isset( $_POST['fields'] ) ) {

					$param['fields'] = $_POST['fields'];
				}

				// ACF5
				if ( isset( $_POST['acf'] ) ) {

					$param['fields'] = $_POST['acf'];
				}

				$GLOBALS['toolset']['inits']['tool_acf_save_post_after']['function']( $param );

			}, 20 );

		}

	// }
