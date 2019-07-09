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
				add_filter( 'acf/fields/flexible_content/layout_title', array( $this, 'translate' ), 9999 ); // Grouptitles in FlexContent after Toggle

				//add_filter( 'acf/get_valid_field', array( $this, 'translate' ) ); // Fields
				//add_filter( 'acf/get_valid_field_group', array( $this, 'translate' ) ); // missed fieldgroup titles at option pages
				//add_filter( 'acf/fields/flexible_content/layout_title', array( $this, 'translate' ) ); // missed fieldgroup titles at option pages
			}

			function add_options_page_menu_translation_filters() {

				add_filter( 'acf/validate_options_page', array( $this, 'translate' ) ); // Option Page
			}

			function translate( $array ) {

				if ( ! is_array( $array ) ) {

					if (
						! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $array ][ $this->locale ] )
					) {

						$array = $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $array ][ $this->locale ];
					}
					elseif (
						! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $array ][ $this->locale_general ] )
					) {

						$array = $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $array ][ $this->locale_general ];
					}
					elseif (
						! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $array ]['default'] )
					) {

						$array = $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $array ]['default'];
					}

					preg_match_all(
						'|\<span class="acf-translate"\>(.*)<\/span\>|U',
						$array,
						$matches,
						PREG_SET_ORDER
					);

					if ( ! empty( $matches ) ) {

						foreach ( $matches as $match ) {

							if ( ! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $match[1] ][ $this->locale ] ) ) {

								$array = str_replace( $match[0], $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $match[1] ][ $this->locale ], $array );
							}
							elseif ( ! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $match[1] ][ $this->locale_general ] ) ) {

								$array = str_replace( $match[0], $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $match[1] ][ $this->locale_general ], $array );
							}
							elseif ( ! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $match[1] ]['default'] ) ) {

								$array = str_replace( $match[0], $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $match[1] ]['default'], $array );
							}
						}
					}

				}
				else {

					array_walk( $array, function( &$item, $key ) {

						// STRINGS {

							$keys = array( 'title', 'page_title', 'menu_title', 'label', 'button_label', 'description', 'instructions', 'message', 'default_value', 'append', 'prepend', 'placeholder' );

							if (
								is_string( $item ) AND
								in_array( $key, $keys ) AND
								! empty( $key )
							) {

								if ( ! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $item ][ $this->locale ] ) ) {

									$item = $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $item ][ $this->locale ];
								}
								elseif ( ! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $item ][ $this->locale_general ] ) ) {

									$item = $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $item ][ $this->locale_general ];
								}
								elseif ( ! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $item ]['default'] ) ) {

									$item = $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $item ]['default'];
								}
							}

							if (
								is_string( $item ) AND
								in_array( $key, $keys ) AND
								! empty( $key )
							) {

								preg_match_all(
									'|\{\{(.*)\}\}|U',
									$item,
									$matches,
									PREG_SET_ORDER
								);

								if ( ! empty( $matches ) ) {

									foreach ( $matches as $match ) {

										if ( ! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $match[1] ][ $this->locale ] ) ) {

											$item = str_replace( $match[0], $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $match[1] ][ $this->locale ], $item );
										}
										elseif ( ! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $match[1] ][ $this->locale_general ] ) ) {

											$item = str_replace( $match[0], $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $match[1] ][ $this->locale_general ], $item );
										}
										elseif ( ! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $match[1] ]['default'] ) ) {

											$item = str_replace( $match[0], $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $match[1] ]['default'], $item );
										}
										else {

											$item = str_replace( '{{' . $match[1] . '}}', $match[1], $item );
										}
									}
								}
								else {

								}
							}

						// }

						// ARRAYS {

							if ( is_array( $item ) ) {

								array_walk_recursive( $item, function( &$item, $key ) {

									// REMOVES FIELDGROUP LEADING HINTS LIKE "(Clone) Image" {

										if ( $key === 'title' ) {

											$item = preg_replace( "/\((.*)\)(.*)/", '$2', $item );
											$item = trim( $item );
										}

									// }

									$keys = array( 'title', 'page_title', 'menu_title', 'label', 'button_label', 'description', 'instructions', 'message', 'default_value', 'append', 'prepend', 'placeholder' );

									if (
										is_string( $item ) AND
										in_array( $key, $keys ) AND
										! empty( $key )
									) {

										if ( ! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $item ][ $this->locale ] ) ) {

											$item = $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $item ][ $this->locale ];
										}
										elseif ( ! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $item ][ $this->locale_general ] ) ) {

											$item = $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $item ][ $this->locale_general ];
										}
										elseif ( ! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $item ]['default'] ) ) {

											$item = $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $item ]['default'];
										}
									}

									if (
										is_string( $item ) AND
										in_array( $key, $keys ) AND
										! empty( $key )
									) {

										preg_match_all(
											'|\{\{(.*)\}\}|U',
											$item,
											$matches,
											PREG_SET_ORDER
										);

										if ( ! empty( $matches ) ) {

											foreach ( $matches as $match ) {

												if ( ! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $match[1] ][ $this->locale ] ) ) {

													$item = str_replace( $match[0], $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $match[1] ][ $this->locale ], $item );
												}
												elseif ( ! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $match[1] ][ $this->locale_general ] ) ) {

													$item = str_replace( $match[0], $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $match[1] ][ $this->locale_general ], $item );
												}
												elseif ( ! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $match[1] ]['default'] ) ) {

													$item = str_replace( $match[0], $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $match[1] ]['default'], $item );
												}
												else {

													$item = str_replace( '{{' . $match[1] . '}}', $match[1], $item );
												}
											}
										}

									}

								} );

							}

						// }

						// CHOICES {

							if ( $key === 'choices' ) {

								foreach ( $item as $key => $value ) {

									if ( ! is_array( $value ) ) {

										if ( ! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $value ][ $this->locale ] ) ) {

											$item[ $key ] = $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $value ][ $this->locale ];
										}
										elseif ( ! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $value ][ $this->locale_general ] ) ) {

											$item[ $key ] = $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $value ][ $this->locale_general ];
										}
										elseif ( ! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $value ]['default'] ) ) {

											$item[ $key ] = $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $value ]['default'];
										}
									}
								}
							}

						// }

						// LAYOUTS {

							if ( $key === 'layouts' ) {

								array_walk_recursive ( $item , function( &$item, $key ) {

									$keys = array( 'title', 'label', 'description', 'instructions' );

									if ( in_array( $keys, $keys ) ) {

										if ( ! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $item ][ $this->locale ] ) ) {

											$item = $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $item ][ $this->locale ];
										}
										elseif ( ! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $item ][ $this->locale_general ] ) ) {

											$item = $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $item ][ $this->locale_general ];
										}
										elseif ( ! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $item ]['default'] ) ) {

											$item = $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $item ]['default'];
										}
									}

									if (
										in_array( $key, $keys )
									) {

										preg_match_all(
											'|\{\{(.*)\}\}|U',
											$item,
											$matches,
											PREG_SET_ORDER
										);

										if ( ! empty( $matches ) ) {

											foreach ( $matches as $match ) {

												if ( ! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $match[1] ][ $this->locale ] ) ) {

													$item = str_replace( $match[0], $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $match[1] ][ $this->locale ], $item );
												}
												elseif ( ! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $match[1] ][ $this->locale_general ] ) ) {

													$item = str_replace( $match[0], $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $match[1] ][ $this->locale_general ], $item );
												}
												elseif ( ! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $match[1] ]['default'] ) ) {

													$item = str_replace( $match[0], $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $match[1] ]['default'], $item );
												}
												else {

													$item = str_replace( '{{' . $match[1] . '}}', $match[1], $item );
												}
											}
										}
									}
								} );
							}

						// }

					} );

					/*array_walk_recursive( $array, function( &$item, $key ) {

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
								$key !== 'position' AND // prevents translation of fieldgroup box positioning
								! empty( $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $item ][ $this->locale ] )
							) {

								$item = $GLOBALS['toolset']['inits']['tool_acf_translate']['strings'][ $item ][ $this->locale ];
							}

						// }

					} );*/
				}

				//error_log( print_r( $array, true) );
				return $array;
			}

		}

	}
