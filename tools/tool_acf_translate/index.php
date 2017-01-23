<?php

	// $GLOBALS['toolset']['inits']

	function tool_acf_translate( $p = array() ) {

		// DEFAULTS {

			$defaults = array(
				'strings' => false,
				'locale' => false,
			);

			// DEFINE LOCALE {

				if ( empty( $GLOBALS['toolset']['admin_locale'] ) ) {

					$defaults['locale'] = get_locale();
				}
				else {

					$defaults['locale'] = $GLOBALS['toolset']['admin_locale'];
				}

			// }

			$p = array_replace_recursive( $defaults, $p );

		// }

		new ToolACFTranslate( $p );
	}

	if ( ! class_exists( 'ToolACFTranslate' ) ) {

		class ToolACFTranslate {

			public $strings;
			public $current_screen;

		    function __construct( $p ) {

				$defaults = array(
					'strings' => false,
					'locale' => false,
				);

		        $p = array_replace_recursive( $defaults, $p );

				if ( $p['strings'] AND $p['locale'] ) {

					$this->strings = $p['strings'];
					$this->locale = $p['locale'];

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

					if ( ! empty( $this->strings[ $item ][ $this->locale ] ) ) {

						$item = $this->strings[ $item ][ $this->locale ];
					}

				} );

				return $array;
		    }

		}

	}

?>