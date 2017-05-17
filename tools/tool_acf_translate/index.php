<?php

	// $GLOBALS['toolset']['inits']

	function tool_acf_translate( $p = array() ) {

		if ( empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'] ) ) {

			$GLOBALS['toolset']['inits']['tool_acf_translate']['strings'] = array();
		}

		// DEFAULTS {

			$defaults = array(
				'strings' => array(),
			);

			$GLOBALS['toolset']['inits']['tool_acf_translate']['strings'] = array_replace_recursive( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'], $p['strings'] );

		// }

		if ( empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['class'] ) ) {

			$GLOBALS['toolset']['inits']['tool_acf_translate']['class'] = new ToolACFTranslate();
		}
	}

	if ( ! class_exists( 'ToolACFTranslate' ) ) {

		class ToolACFTranslate {

			public $locale;
			public $current_screen;

			function __construct() {

				if ( empty( $GLOBALS['toolset']['admin_locale'] ) ) {

					$this->locale = get_locale();
				}
				else {

					$this->locale = $GLOBALS['toolset']['admin_locale'];
				}

				if ( $this->locale ) {

					add_action( 'current_screen', array( $this, 'current_screen' ) );
				}
			}

			function current_screen() {

				$this->current_screen = get_current_screen();

				if ( $this->current_screen->id != 'acf-field-group' ) {

					add_filter( 'acf/get_valid_field', array( $this, 'translate' ) );
					add_filter( 'acf/get_valid_field_group', array( $this, 'translate' ) );
				}
			}

			function translate( $array ) {

				array_walk_recursive( $array, function( &$item, $key ) {

					// REMOVES FIELDGROUP LEADING HINTS LIKE "(Clone) Image" {

						if ( $key === 'title' ) {

							$item = preg_replace( "/\((.*)\)(.*)/", '$2', $item );
						}

					// }

					// REPLACE STRINGS {

						if ( ! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $item ][ $this->locale ] ) ) {

							$item = $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $item ][ $this->locale ];
						}

					// }

				} );

				return $array;
			}

		}

	}

?>
