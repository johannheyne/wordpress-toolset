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

				if ( empty( $GLOBALS['toolset']['user_locale'] ) ) {

					$this->locale = get_user_locale();
				}
				else {

					$this->locale = $GLOBALS['toolset']['user_locale'];
				}

				if ( $this->locale ) {

					add_action( 'current_screen', array( $this, 'current_screen' ) );
				}
			}

			function current_screen() {

				$this->current_screen = get_current_screen();

				if ( $this->current_screen->id != 'acf-field-group' ) {

					add_filter( 'acf/get_valid_field', array( $this, 'translate' ) ); // Fields
					add_filter( 'acf/get_field_groups', array( $this, 'translate' ) ); // Grouptitles in Optionpages
					add_filter( 'acf/fields/flexible_content/layout_title', array( $this, 'translate' ) ); // Grouptitles in FlexContent

					//add_filter( 'acf/get_valid_field_group', array( $this, 'translate' ) ); // missed fieldgroup titles at option pages
					//add_filter( 'acf/fields/flexible_content/layout_title', array( $this, 'translate' ) ); // missed fieldgroup titles at option pages
				}
			}

			function translate( $array ) {

				if ( ! is_array( $array ) ) {

					if (
						! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $array ][ $this->locale ] )
					) {

						$array = $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $array ][ $this->locale ];
					}

				}
				else {

					array_walk_recursive( $array, function( &$item, $key ) {

						// REMOVES FIELDGROUP LEADING HINTS LIKE "(Clone) Image" {

							if ( $key === 'title' ) {

								$item = preg_replace( "/\((.*)\)(.*)/", '$2', $item );
								$item = trim( $item );
							}

						// }

						// REPLACE STRINGS {

							if (
								is_string( $key ) AND
								$key !== 'value' AND // prevents translation of conditional logig values
								! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $item ][ $this->locale ] )
							) {

								$item = $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $item ][ $this->locale ];
							}

						// }

					} );
				}



				return $array;
			}

		}

	}

?>
