<?php

	// FALLBACK array_replace_recursive() {

		if ( !function_exists( 'array_replace_recursive' ) ) {

			function array_replace_recursive( $a, $b )  { 

				$r = $a;

				foreach ( $b as $key => $value ) {

					if ( is_array( $value ) ) {

						$r[ $key ] = array_replace_recursive( $r[ $key ], $value );
					}
					else {

						$r[ $key ] = $value;
					}
				}

				return $r;
			}
		}

	// }

	if ( file_exists( get_template_directory() . '/config/config.php' ) ) {

		require_once( get_template_directory() . '/config/config.php' );
	}

	require_once( 'classes.php' );

	// CORE {

	    // GLOBALS['toolglobal'] ( Version 1 ) {

			/** ABOUT

				global storage of requests made inside of tool_ functions
				prevents multiple requests within these functions

				INDEX {

					$GLOBALS['toolglobal']['menus'][ {menu-id, number} ] = object;
						USED IN {
							tool_get_menu_ancestors();
							tool_has_menu_ancestors();
						}
				}
			**/

			$GLOBALS['toolglobal'] = array();

		// }

		// FUNCTIONS { 

			// AUTOLOAD ( Version 1 ) {

				function toolset_autoload( $p ) {

					// SETUP {

					    $d = array(
							'files' => false, // array( '','' )
						);

						$p = array_replace_recursive( $d, $p );

					// }

					foreach ( $p['files'] as $file ) {

						// SANITIZE
						$file = str_replace( '../', '', $file );

						// IF NOT LOADET JET
						if ( ! isset( $GLOBALS['toolset']['autoloaded'][ $file ] ) ) {

							include( 'tools/' . $file );
							$GLOBALS['toolset']['autoloaded'][ $file ] = true;
						}
					}

				}

			// }

			// CALL THEMEFUNC  ( Version 1 ) {

				// use this functione for calling a function, it will autoload from folder "functions"

				function tool( $p ) {

					// SETUP {

					    // DEFAULTS
						$d = array(
							'name' => false,
							'param' => array(),
						);

						// EXTEND PARAMETER
					    $p = array_replace_recursive( $d, $p );

						// VARIABLES
						$v = array(
							'funame' => false,
							'file' => false,
						);

						// RETURN
						$r = false;

					// }

					if ( $p['name'] ) {

						if ( ! isset( $GLOBALS['tool']['functions'] ) ) {

							include( 'tools/index.php' );
						}

						$v['funame'] = $GLOBALS['tool']['functions'][ $p['name'] ]['funame'];
						$v['file'] = $GLOBALS['tool']['functions'][ $p['name'] ]['dir'];

						if ( ! function_exists( $v['funame'] ) ) {

							toolset_autoload( array(
								'files' => array( $v['file'] . '/index.php' ),
							) );
						}

						if ( function_exists( $v['funame'] ) ) {

							$r = $v['funame']( $p['param'] );
						}
						else {

							error_log( print_r( 'WordPress Plugin ToolSet: ' . "\n" . 'Function name "' . $p['name']  . '" does not exists in toolset/tools/index.php!', true ) );
						}
					}

					return $r;
				}

			// }

		// }

		// PROZESS {

			// LOAD INITS {

				if ( isset( $GLOBALS['toolset']['inits'] ) ) {

					foreach ( $GLOBALS['toolset']['inits'] as $key => $value ) {

						if ( $value ) {

							toolset_autoload( array(
								'files' => array( $key . '/index.php' ),
							) );
						}
					}
				}

			// }

			// AUTOLOAD PHP CLASSES ( Version 3 ) {

				/* Info: http://php.net/manual/de/language.oop5.autoload.php */

				spl_autoload_register( function ( $class_name ) {

					if ( isset( $GLOBALS['toolset']['autoload_php_classes_plugin'] ) ) {

						foreach ( $GLOBALS['toolset']['autoload_php_classes_plugin'] as $key => $value ) {

							if ( $class_name == $key ) {

								require_once( $value );
							}

						}
					}

					if ( isset( $GLOBALS['toolset']['autoload_php_classes'] ) ) {

						foreach ( $GLOBALS['toolset']['autoload_php_classes'] as $key => $value ) {

							if ( $class_name == $key ) {

								require_once( get_template_directory() . $value );
							}

						}
					}

				} );

			// }

		// }

	// }

?>