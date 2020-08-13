<?php

	// ADDS BACKENDSTYLES {

		if ( is_admin() ) {

			add_action( 'admin_enqueue_scripts', function() {

				wp_enqueue_style( 'admin-acf-styles', plugin_dir_url( __FILE__ ) . 'styles-admin.css' );
			});
		}

	// }

	// ACF FIELDS ( Version 5 ) {

		// REGISTER CUSTOM FIELDTYPES {

			function tool_register_acf_field( $p = array() ) {

				// DEFAULTS {

					$defaults = array(
						'fields' => false,
					);

					$p = array_replace_recursive( $defaults, $p );

				// }

				if ( ! isset( $GLOBALS['temp_tool_register_acf_field_action'] ) ) {

					$GLOBALS['temp_tool_register_acf_field_action'] = array();
				}

				$GLOBALS['temp_tool_register_acf_field_action'] = array_replace_recursive( $p, $GLOBALS['temp_tool_register_acf_field_action'] );

				add_action( 'acf/register_fields', 'tool_register_acf_field_action_call' );

			}

			function tool_register_acf_field_action_call() {

				if ( is_array( $GLOBALS['temp_tool_register_acf_field_action']['fields'] ) ) {

					foreach ( $GLOBALS['temp_tool_register_acf_field_action']['fields'] as $key => $item ) {

						include_once( get_template_directory() . '/' . $p['path'] );
					}
				}

				unset( $GLOBALS['temp_tool_register_acf_field_action'] );
			}

		// }

		// LOAD FIELDS ( Version 2 ) {

			$template_directory = get_template_directory();

			$path_1 = $template_directory  . '/config/acf-fields.php';
			$path_2 = $template_directory  . '/config/acf/acf-init.php';

			if ( file_exists( $path_1 ) ) {

				include( $path_1 );
			}
			elseif ( file_exists( $path_2 ) ) {

				include( $path_2 );
			}

		// }

		// FlexibleField Helper get_sub_fields ( Version 2 ) {

			function tool_acf_get_sub_fields( $p = array() ) {

				$return = false;

				if ( count( $p ) > 0 ) {

					foreach ( $p as $key ) {

						$return[ $key ] = get_sub_field( $key );
					}
				}

				return $return;
			}

		// }

		// GET FIELDS ( Version 5 ) {

			function tool_acf_get_post_custom_keys( $p = array() ) {

				$p += array(
					'post_id' => false,
					'filter' => '_'
				);

				$ret = false;

				if ( $p['post_id'] ) {

					$ret = get_post_custom_keys( $p['post_id'] );

					if ( count( $ret ) > 0 ) {

						foreach ( $ret as $key => $item ) {

							if ( $p['filter'] == '_' && $item[0] === '_' ) unset( $ret[ $key ] );
						}
					}
				}

				return $ret;
			}

			function tool_acf_print_post_custom_keys( $p = array() ) {

				$p += array(
					 'post_id' => false,
					 'filter' => '_'
				);

				$ret = tool_acf_get_post_custom_keys( $p );

				if ( $ret === false ) $ret = 'Keine Keys gefunden!';

				 print_o( $ret );
			}

			function tool_acf_get_fields( $p = array() ) {

				/** ABOUT

					USE {

						$result = tool_acf_get_fields( array(
							'keys' => false, // simple array( 'one', 'two' )
							'post_id' => tool_may_get_the_id(),
							'print_keys' => false,
							'print' => false,
							'permalink' => true,
						) );

					}

				**/

				$p += array(
					'keys' => false, // simple array( 'one', 'two' )
					'post_id' => tool_get_an_id(),
					'print_keys' => false,
					'print' => false,
					'permalink' => true
				);

				$ret = false;

				if ( $p['keys'] === false ) {

					$p['keys'] = tool_acf_get_post_custom_keys( $p );
				}

				if ( $p['keys'] ) {

					foreach ( $p['keys'] as $key => $value ) {

						if ( $p['post_id'] ) $ret[ $value ] = get_field( $value, $p['post_id'] );
						if ( !$p['post_id'] ) $ret[ $value ] = get_field( $value );
					}

					if ( $p['permalink'] === true ) $ret['permalink'] = get_permalink( $p['post_id'] );

					if ( $p['print_keys'] ) {

						$temp = false;
						$keys = tool_acf_get_post_custom_keys( $p );

						foreach ( $keys as $key => $value ) {

							$temp .= $value . "\n";
						}
						print_o( trim( $temp ) );
					}

					if ( $p['print'] ) {

						print_o( $ret );
					}
				}

				return $ret;
			}

		// }

	// }
