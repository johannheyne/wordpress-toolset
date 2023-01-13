<?php

	// $GLOBALS['toolset']['inits']

	function tool_acf_translate_string( $string ) {

		if ( empty( $GLOBALS['toolset']['user_locale'] ) ) {

			$GLOBALS['toolset']['user_locale'] = $GLOBALS['toolset']['frontend_locale'];
		}

		if ( ! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $string ][ $GLOBALS['toolset']['user_locale'] ] ) ) {

			$string = $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $string ][ $GLOBALS['toolset']['user_locale'] ];
		}

		return $string;
	}

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
			public $locale_general;
			public $current_screen;

			function __construct() {

				$value = apply_filters( 'tool_acf_translate/disabling', false );

				if ( $value === true ) {

					return;
				}

				// SET LOCALE {

					if ( empty( $GLOBALS['toolset']['user_locale'] ) ) {

						$this->locale = get_user_locale();
					}
					else {

						$this->locale = $GLOBALS['toolset']['user_locale'];
					}

				// }

				// SET GENERAL LOCALE {

					$arr = explode( '_', $this->locale );

					if ( count( $arr ) > 1 ) {

						$this->locale_general = $arr[0];
					}
					else{

						$this->locale_general = $this->locale;
					}

				// }

				// make sure there is a locale like "en_US" for translation
				if ( $this->locale ) {

					// enables calling get_current_screen()
					// this is needed for detecting the field-group settings page
					// this is needed for preventing translating in fieldgroup settings
					add_action( 'current_screen', array( $this, 'current_screen' ) );
				}

				if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

					$this->add_translation_filters();
				}
				else {

					$this->add_options_page_menu_translation_filters();
				}
			}

			function current_screen() {

				$this->current_screen = get_current_screen();

				// prevents translating in fieldgroup setting pages
				if ( $this->current_screen->id !== 'acf-field-group' ) {

					$this->add_translation_filters();
				}
			}

			function add_translation_filters() {

				add_filter( 'acf/prepare_field', array( $this, 'translate' ), 9999 ); // All
				add_filter( 'acf/get_field_groups', array( $this, 'translate' ), 9999 ); // Grouptitles in Optionpages
				//add_filter( 'acf/fields/flexible_content/layout_title', array( $this, 'translate' ), 9999 ); // Grouptitles in FlexContent after Toggle

				add_filter( 'acf/get_valid_field', array( $this, 'translate' ) ); // also Grouptitles in FlexContent after Toggle
				add_filter( 'acf/get_valid_field_group', array( $this, 'translate' ) ); // missed fieldgroup titles at aside postboxes
				//add_filter( 'acf/fields/flexible_content/layout_title', array( $this, 'translate' ) ); // missed fieldgroup titles at option pages
			}

			function add_options_page_menu_translation_filters() {

				add_filter( 'acf/validate_options_page', array( $this, 'translate' ) ); // Option Page
			}

			function get_context_item( $item, $context ) {

				// CHECK IF HAS TRANSLATION {

					$context_sufix = '';

					foreach ( $context as $key => $value ) {

						if (
							0 === strpos( $value, 'parent:group_flex_layouts_' )
						) {

							$value = 'layout_name';
						}

						if ( false !== strpos( $value, 'key:layout_' ) ) {
							$value = 'key:layout';
						}

						if ( ! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $item . '@' . $value  ] ) ) {

							$context_sufix = '@' . $value;
						}
					}

				// }

				return $item . $context_sufix;
			}

			function get_item_translation( $string ) {

				if ( ! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $string ][ $this->locale ] ) ) {

					$string = $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $string ][ $this->locale ];
				}
				elseif ( ! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $string ][ $this->locale_general ] ) ) {

					$string = $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $string ][ $this->locale_general ];
				}
				elseif ( ! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $string ]['default'] ) ) {

					$string = $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $string ]['default'];
				}

				return $string;
			}

			function get_item_bracket_translation( $string ) {

				preg_match_all(
					'|\{\{(.*)\}\}|U',
					$string,
					$matches,
					PREG_SET_ORDER
				);

				if ( ! empty( $matches ) ) {

					foreach ( $matches as $match ) {

						if ( ! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $match[1] ][ $this->locale ] ) ) {

							$string = str_replace( $match[0], $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $match[1] ][ $this->locale ], $string );
						}
						elseif ( ! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $match[1] ][ $this->locale_general ] ) ) {

							$string = str_replace( $match[0], $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $match[1] ][ $this->locale_general ], $string );
						}
						elseif ( ! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $match[1] ]['default'] ) ) {

							$string = str_replace( $match[0], $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $match[1] ]['default'], $string );
						}
					}
				}

				preg_match_all(
					'|\<span class="acf-translate"\>(.*)<\/span\>|U',
					$string,
					$matches,
					PREG_SET_ORDER
				);

				if ( ! empty( $matches ) ) {

					foreach ( $matches as $match ) {

						if ( ! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $match[1] ][ $this->locale ] ) ) {

							$string = str_replace( $match[0], $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $match[1] ][ $this->locale ], $string );
						}
						elseif ( ! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $match[1] ][ $this->locale_general ] ) ) {

							$string = str_replace( $match[0], $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $match[1] ][ $this->locale_general ], $string );
						}
						elseif ( ! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $match[1] ]['default'] ) ) {

							$string = str_replace( $match[0], $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $match[1] ]['default'], $string );
						}
					}
				}

				return $string;
			}

			function translate( $array ) {

				// SETUP CONTEXT {

					$context = array();

					if ( ! empty( $array['menu_slug'] ) ) {

						$context[] = 'menu_slug:' . $array['menu_slug'];
					}

					if ( ! empty( $array['post_id'] ) ) {

						$context[] = 'post_id:' . $array['post_id'];
					}

					if ( ! empty( $array['parent'] ) ) {

						$context[] = 'parent:' . $array['parent'];
					}

					if ( ! empty( $array['key'] ) ) {

						$context[] = 'key:' . $array['key'];
					}

				// }

				// IS SRING

				if ( ! is_array( $array ) ) {

					$array = $this->get_item_translation( $array );
					$array = $this->get_item_bracket_translation( $array );
				}

				// IS ARRAY

				else {

					array_walk( $array, function( &$item, $key ) use ( $context ) {

						// CHOICES {

							if ( $key === 'choices' ) {

								foreach ( $item as $key2 => $value ) {

									if ( ! is_array( $value ) ) {

										$value = $this->get_context_item( $value, $context );
										$value = $this->get_item_translation( $value );

										$item[ $key2 ] = $value;
									}
								}

								return;
							}

						// }

						// LAYOUTS {

							if ( $key === 'layouts' ) {

								array_walk_recursive ( $item , function( &$item, $key2 ) use ( $context ) {

									$keys = array( 'title', 'label', 'description', 'instructions' );

									if ( in_array( $key2, $keys ) ) {

										$item = $this->get_context_item( $item, $context );
										$item = $this->get_item_translation( $item );
										$item = $this->get_item_bracket_translation( $item );
									}
								} );

								return;
							}

						// }

						// IS STRING {

							$keys = array( 'title', 'page_title', 'menu_title', 'label', 'button_label', 'description', 'instructions', 'message', 'default_value', 'append', 'prepend', 'placeholder', 'ui_on_text', 'ui_off_text' );

							if (
								is_string( $item ) AND
								in_array( $key, $keys ) AND
								! empty( $key )
							) {

								$item = $this->get_context_item( $item, $context );
								$item = $this->get_item_translation( $item );
								$item = $this->get_item_bracket_translation( $item );

								return;
							}

						// }

						// IS ARRAY {

							if ( is_array( $item ) ) {

								foreach ( $item as $item2 ) {

									if ( ! empty( $item2['key'] ) ) {

										$context[] = 'key:' . $item2['key'];
									}
								}

								array_walk_recursive( $item, function( &$item, $key2 ) use ( $context ){

									// REMOVES FIELDGROUP LEADING HINTS LIKE "(Clone) Image" {

										if ( $key2 === 'title' ) {

											$item = preg_replace( "/\((.*)\)(.*)/", '$2', $item );
											$item = trim( $item );
										}

									// }

									$keys = array( 'title', 'page_title', 'menu_title', 'label', 'button_label', 'description', 'instructions', 'message', 'default_value', 'append', 'prepend', 'placeholder', 'ui_on_text', 'ui_off_text' );

									if (
										is_string( $item ) AND
										in_array( $key2, $keys ) AND
										! empty( $key2 )
									) {

										$item = $this->get_context_item( $item, $context );
										$item = $this->get_item_translation( $item );
										$item = $this->get_item_bracket_translation( $item );
									}

								} );
							}

						// }

					} );
				}

				return $array;
			}

		}

	}
