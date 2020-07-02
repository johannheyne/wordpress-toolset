<?php

	function tool_multilanguage_require_lang( $p = array() ) {

		/* Info
			This function loads the required language into the
			$GLOBALS['toolset']['multilanguage_langs'] array.
			This can be a general or localized language.
			general: $GLOBALS['toolset']['multilanguage_langs']['en']
			localized: $GLOBALS['toolset']['multilanguage_langs']['en_US']

			// Source: https://github.com/petercoles/Multilingual-Language-List/blob/master/data/de_DE.php

		*/

		//$plugin_path = plugin_dir_path( __FILE__ );

		if ( ! isset( $GLOBALS['toolset']['multilanguage_langs'] ) ) {

			$GLOBALS['toolset']['multilanguage_langs'] = array();
		}

		// DEFAULTS {

			$defaults = array(
				'locale' => 'en_GB', // alternative to lang and country
				'lang' => false,
				'country' => false,
				'only_locales_with_region' => true,
			);

			$p = array_replace_recursive( $defaults, $p );

		// }

		// BUILD LOCALE FROM LANG AND COUNTRY {

			if (
				! $p['locale'] AND
				$p['lang'] AND
				$p['country']
			) {

				$p['locale'] = $p['lang'] . '_' . strtoupper( $p['country'] );
			}

		// }

		// BUILD LANGUAGE ARRAY {

			if (
				empty( $GLOBALS['toolset']['multilanguage_langs'][ $p['locale'] ] )
			) {

				$locales = resourcebundle_locales( '' );

				foreach ( $locales as $key => $item ) {

					if (
						$GLOBALS['toolset']['multisite'] OR // all langs, also without countrycode
						strpos( $item, '_' )
					) {

						$GLOBALS['toolset']['multilanguage_langs'][ $p['locale'] ][ $item ] = Locale::getDisplayName( $item, $GLOBALS['toolset']['user_locale'] );
					}
				}
			}

		// }
	}

	function tool_multilanguage_get_lang_list( $p = array() ) {

		/*
			Returns the list of all languages with labels in
			the required language and countrycode.
		*/

		// DEFAULTS {

			$defaults = array(
				'locale' => 'en_GB',
				'lang' => false,
				'country' => false,
			);

			$p = array_replace_recursive( $defaults, $p );

			$p['country'] = strtoupper( $p['country'] );

		// }

		// BUILD LOCALE FROM LANG AND COUNTRY {

			if (
				! $p['locale'] AND
				$p['lang'] AND
				$p['country']
			) {

				$p['locale'] = $p['lang'] . '_' . strtoupper( $p['country'] );
			}

			// }

		tool_multilanguage_require_lang( array(
			'locale' => $p['locale'],
		) );

		if ( ! empty( $GLOBALS['toolset']['multilanguage_langs'][ $p['locale'] ] ) ) {

			return $GLOBALS['toolset']['multilanguage_langs'][ $p['locale'] ];
		}
		else {

			return array();
		}
	}

	function tool_multilanguage_get_lang_label( $p = array() ) {

		/*
			Returns the language $label of an given langcode translated by $lang and $country codes.
		*/

		// DEFAULTS {

			$defaults = array(
				'langcode' => 'en',
				'locale' => 'en',
				'lang' => false,
				'country' => false,
			);

			$p = array_replace_recursive( $defaults, $p );

			$p['country'] = strtoupper( $p['country'] );

		// }

		// BUILD LOCALE FROM LANG AND COUNTRY {

			if (
				! $p['locale'] AND
				$p['lang']
			) {

				$p['locale'] = $p['lang'];
			}

			if (
				! $p['locale'] AND
				$p['country']
			) {

				$p['locale'] .= '_' . $p['country'];
			}

		// }

		return Locale::getDisplayLanguage( $p['langcode'], $p['locale'] );
	}


	function tool_multilanguage_require_countries( $p = array() ) {

		/*
			This function loads the required countries into the
			$GLOBALS['toolset']['multilanguage_countries'] array.
			This can be a general or localized language.
			general: $GLOBALS['toolset']['multilanguage_langs']['en']
			localized: $GLOBALS['toolset']['multilanguage_langs']['en_US']
		*/

		$plugin_path = plugin_dir_path( __FILE__ );

		if ( ! isset( $GLOBALS['toolset']['multilanguage_countries'] ) ) {

			$GLOBALS['toolset']['multilanguage_countries'] = array();
		}

		// DEFAULTS {

			$defaults = array(
				'locale' => 'en', // alternative to lang and country
			);

			$p = array_replace_recursive( $defaults, $p );

		// }


		// LOAD COUNTRY FROM SOURCE {

			$path = $plugin_path . 'data-countries/' . $p['locale'] . '/country.php';

			if (
				empty( $GLOBALS['toolset']['multilanguage_countries'][ $p['locale'] ] ) AND
				file_exists( $path )
			) {

				$GLOBALS['toolset']['multilanguage_countries'][ $p['locale'] ] = require_once( $path );
			}

		// }

	}

	function tool_multilanguage_get_country_label( $p = array() ) {

		/*
			Returns the county $label of an given langcode.
		*/

		// DEFAULTS {

			$defaults = array(
				'countrycode' => 'DE',
				'locale' => 'en',
			);

			$p = array_replace_recursive( $defaults, $p );

			if ( $p['countrycode'] == 'INT' ) {

				return 'International';
			}

			if ( $p['countrycode'] == '001' ) {

				return 'International';
			}

			if ( $p['countrycode'] == '150' ) {

				return 'Europe';
			}

		// }

		tool_multilanguage_require_countries( array(
			'locale' => $p['locale'],
		) );

		return $GLOBALS['toolset']['multilanguage_countries'][ $p['locale'] ][ strtoupper( $p['countrycode'] ) ];
	}


	function tool_multilanguage_get_locale_label( $p = array() ) {

		/*
			Returns the locale $label
		*/

		// DEFAULTS {

			$defaults = array(
				'langcode' => 'en_GB',
				'locale' => 'en_GB',
			);

			$p = array_replace_recursive( $defaults, $p );

		// }


		return Locale::getDisplayName( $p['langcode'], $p['locale'] );
	}


	function tool_multilanguage_require_regions( $p = array() ) {

		/*
			This function loads the required regions into the
			$GLOBALS['toolset']['multilanguage_regions'] array.
			general: $GLOBALS['toolset']['multilanguage_regions']['en']

			// Source: https://github.com/petercoles/Multilingual-Language-List/blob/master/data/de_DE.php

		*/

		$plugin_path = plugin_dir_path( __FILE__ );

		if ( ! isset( $GLOBALS['toolset']['multilanguage_regions'] ) ) {

			$GLOBALS['toolset']['multilanguage_regions'] = array();
		}

		// DEFAULTS {

			$defaults = array(
				'locale' => 'en', // alternative to lang and country
			);

			$p = array_replace_recursive( $defaults, $p );

		// }


		// LOAD LANGUAGE FROM SOURCE {

			$path = $plugin_path . 'data-regions/' . $p['locale'] . '.php';

			if (
				empty( $GLOBALS['toolset']['multilanguage_regions'][ $p['locale'] ] ) AND
				file_exists( $path )
			) {

				$GLOBALS['toolset']['multilanguage_regions'][ $p['locale'] ] = require_once( $path );
			}

		// }

	}

	function tool_multilanguage_require_regions_countries( $p = array() ) {

		/*
			This function loads the required regions countries relation into the
			$GLOBALS['toolset']['multilanguage_regions_countries'] array.
		*/

		$plugin_path = plugin_dir_path( __FILE__ );

		if ( ! isset( $GLOBALS['toolset']['multilanguage_regions_countries'] ) ) {

			$GLOBALS['toolset']['multilanguage_regions_countries'] = array();
		}


		// LOAD LANGUAGE FROM SOURCE {

			$path = $plugin_path . 'regions-countries.php';

			if (
				empty( $GLOBALS['toolset']['multilanguage_regions_countries'] ) AND
				file_exists( $path )
			) {

				$GLOBALS['toolset']['multilanguage_regions_countries'] = require_once( $path );
			}

		// }
	}

	function tool_multilanguage_get_regions( $p = array() ) {

		/*
			This function returns the regions list by an locale
			general: $GLOBALS['toolset']['multilanguage_langs']['en']
		*/

		// DEFAULTS {

			$defaults = array(
				'locale' => 'en', // alternative to lang and country
			);

			$p = array_replace_recursive( $defaults, $p );

		// }

		tool_multilanguage_require_regions( array(
			'locale' => $p['locale'],
		) );

		if ( ! empty( $GLOBALS['toolset']['multilanguage_regions'][ $p['locale'] ] ) ) {

			return $GLOBALS['toolset']['multilanguage_regions'][ $p['locale'] ];
		}
		else {

			return false;
		}
	}

	function tool_multilanguage_country_in_region( $p = array() ) {

		/*
			This function checks if an country code is related to an region code.
		*/

		// DEFAULTS {

			$defaults = array(
				'country_code' => 'gb',
				'region_code' => 'eu',
			);

			$p = array_replace_recursive( $defaults, $p );

		// }

		tool_multilanguage_require_regions_countries();

		if ( ! empty( $GLOBALS['toolset']['multilanguage_regions_countries'][ strtolower( $p['region_code'] ) ][ strtoupper( $p['country_code'] ) ] ) ) {

			return true;
		}
		else {

			return false;
		}
	}


	function tool_multilanguage_region_lang_data( $p = array() ) {

		// DEFAULTS {

			$defaults = array(
				'url' => 'home_url',
			);

			$p = array_replace_recursive( $defaults, $p );

			$data = array();

		// }

		// GET WORLD REGIONS AND WALK TRUE {

			$regions = tool( array(
				'name' => 'tool_multilanguage_get_regions',
				'param' => array(
					'locale' => 'en', // no other translations aviable
				),
			));

			foreach ( $regions as $region_code => $region_label ) {

				$has_region = false;

				// MULTISITE LOOP REGIONS > SITES > LANGS {

					if ( $GLOBALS['toolset']['multisite'] ) {

						// GET HREFLANG ENTRIES {

							$post_id = get_the_ID();
							$alternate_posts = array();

							// GET ALTERNATE POSTS {

								foreach ( $GLOBALS['toolset']['sites'] as $blog_id => $site ) {

									$hreflang_post_id = $GLOBALS['toolset']['classes']['ToolHreflang']->get_post_meta_hreflang( $post_id, $blog_id );

									if ( ! empty( $hreflang_post_id ) ) {

										$alternate_posts[ $blog_id ] = array(
											'post_id' => $hreflang_post_id
										);
									}
								}

							// }

							// GET POST DATA {

								if ( ! empty( $alternate_posts ) ) {

									foreach ( $alternate_posts as $blog_id => $item ) {

										// GET SITE DEFAULT LOCALE {

											$default_locale = $GLOBALS['toolset']['sites'][ $blog_id ]['default_language'];

											if ( ! strpos( $default_locale, '_' )  ) {

												if ( $GLOBALS['toolset']['sites'][ $blog_id ]['country_code'] ) {

													$default_locale .= '_' . $GLOBALS['toolset']['sites'][ $blog_id ]['country_code'];
												}
												else {
													$default_locale .= '_int';
												}
											}

											$default_locale = decode_lang_slug( $default_locale );

										// }

										// GET REGION CODE {

											$country_code = Locale::getRegion( $default_locale );

										// }

										// CHECK IF COUNTRY IN CURRENT REGION {

											$is_country_in_region = tool( array(
												'name' => 'tool_multilanguage_country_in_region',
												'param' => array(
													'country_code' => $country_code,
													'region_code' => $region_code,
												),
											));

											// IS WORLD {

												if (
													$region_code == '001' AND
													$country_code == '001'
												) {

													$is_country_in_region = true;
												}

											// }

											// IS EUROPE {

												if (
													$region_code == '150' AND
													$country_code == '150'
												) {

													$is_country_in_region = true;
												}

											// }

											if ( $is_country_in_region ) {

												// OUTPUT REGION LABEL {

													if ( ! $has_region ) {

														$has_region = true;

														$data[ $region_code ] = array();
														$data[ $region_code ]['lable'] = $region_label;
														$data[ $region_code ]['items'] = array();
													}

												// }

												// GET LANG CODE {

													$lang_code = Locale::getPrimaryLanguage( $default_locale );

												// }

												// GET COUNTRY LABEL {

													$country_label = tool( array(
														'name' => 'tool_multilanguage_get_country_label',
														'param' => array(
															'countrycode' => $country_code,
															'locale' => $lang_code,
														),
													));

												// }

												// GET LANG LABEL {

													$lang_label = tool( array(
														'name' => 'tool_multilanguage_get_lang_label',
														'param' => array(
															'langcode' => $lang_code,
															'locale' => Locale::getPrimaryLanguage( $lang_code ),
														),
													));

												// }

												// SETUP ALTERNATE ARRAY {

													$data[ $region_code ]['items'][ $lang_code . '_' . $country_code ] = array();
													$data[ $region_code ]['items'][ $lang_code . '_' . $country_code ]['label'] = array();
													$data[ $region_code ]['items'][ $lang_code . '_' . $country_code ]['label']['country'] = $country_label;
													$data[ $region_code ]['items'][ $lang_code . '_' . $country_code ]['label']['language'] = $lang_label;

													if ( $GLOBALS['toolset']['blog_details']->blog_id != $blog_id ) {

														switch_to_blog( $blog_id );
													}

														$data[ $region_code ]['items'][ $lang_code . '_' . $country_code ]['url'] = get_permalink( $item['post_id'] ); // , $GLOBALS['toolset']['sites'][ $blog_id ]['default_language'];

														if ( $GLOBALS['toolset']['blog_details']->blog_id != $blog_id ) {

														restore_current_blog();
													}

												// }

											}

										// }

									}
								}

							// }

						// }

						/*foreach ( $GLOBALS['toolset']['sites'] as $site_id => $site_item ) {

							if (  $site_id === 1 ) {

								continue;
							}

							if ( empty( $site_item['languages'] ) ) {

								continue;
							}

							$country_code = $site_item['country_code'];

							$has_multiple_langs = count( $site_item['languages'] );

							foreach ( $site_item['languages'] as $language_key => $language_item ) {

								$lang_code = $language_item['language'];

								// EXTRACT COUNTRY CODE {

									if ( strstr( $lang_code, '_' ) ) {

										$country_code = explode( '_', $lang_code )[1];
									}

								// }

								// CHECK IF COUNTRY IN CURRENT REGION {

									$is_country_in_region = tool( array(
										'name' => 'tool_multilanguage_country_in_region',
										'param' => array(
											'country_code' => $country_code,
											'region_code' => $region_code,
										),
									));

									// IS WORLD {

										if (
											$region_code == '001' AND
											$country_code == '001'
										) {

											$is_country_in_region = true;
										}

									// }

									// IS EUROPE {

										if (
											$region_code == '150' AND
											$country_code == '150'
										) {

											$is_country_in_region = true;
										}

									// }

									if ( $is_country_in_region ) {

										// OUTPUT REGION LABEL {

											if ( ! $has_region ) {

												$has_region = true;

												$data[ $region_code ] = array();
												$data[ $region_code ]['lable'] = $region_label;
												$data[ $region_code ]['items'] = array();
											}

										// }

										// GET COUNTRY LABEL {

											$country_label = tool( array(
												'name' => 'tool_multilanguage_get_country_label',
												'param' => array(
													'countrycode' => $country_code,
													'locale' => $lang_code,
												),
											));

										// }

										// GET LANG LABEL {

											$lang_label = tool( array(
												'name' => 'tool_multilanguage_get_lang_label',
												'param' => array(
													'langcode' => $lang_code,
													'locale' => Locale::getPrimaryLanguage( $lang_code ),
												),
											));

										// }

										// COMBINE LABELS {

											$label = $country_label;
											$label .= ' (' . $lang_label . ')';

										// }

										// GET URL {

											$url = tool( array(
												'name' => 'tool_multilanguage_add_locale_to_url',
												'param' => array(
													'lang' => $lang_code,
													'url' => $p['url'],
												),
											));

										// }

										$data[ $region_code ]['items'][ $lang_code ] = array();
										$data[ $region_code ]['items'][ $lang_code ]['url'] = $url;
										$data[ $region_code ]['items'][ $lang_code ]['label'] = array();
										$data[ $region_code ]['items'][ $lang_code ]['label']['country'] = $country_label;
										$data[ $region_code ]['items'][ $lang_code ]['label']['language'] = $lang_label;
									}

								// }
							}
						}*/
					}

				// }

				// SINGLE SITE LOOP REGIONS > LANGS {

					else {

						foreach ( $GLOBALS['toolset']['language_array'] as $language_key => $language_item ) {

							$lang_code = $language_key;

							// EXTRACT COUNTRY CODE {

								if ( strstr( $lang_code, '_' ) ) {

									$country_code = explode( '_', $lang_code )[1];
								}
								else {

									$country_code = '001';
								}

							// }

							// CHECK IF COUNTRY IN CURRENT REGION {

								$is_country_in_region = tool( array(
									'name' => 'tool_multilanguage_country_in_region',
									'param' => array(
										'country_code' => $country_code,
										'region_code' => $region_code,
									),
								));

								// IS WORLD {

									if (
										$region_code == '001' AND
										$country_code == '001'
									) {

										$is_country_in_region = true;
									}

								// }

								// IS EUROPE {

									if (
										$region_code == '150' AND
										$country_code == '150'
									) {

										$is_country_in_region = true;
									}

								// }

								if ( $is_country_in_region ) {

									// OUTPUT REGION LABEL {

										if ( ! $has_region ) {

											$has_region = true;

											$data[ $region_code ] = array();
											$data[ $region_code ]['lable'] = $region_label;
											$data[ $region_code ]['items'] = array();
										}

									// }

									// GET COUNTRY LABEL {

										$country_label = tool( array(
											'name' => 'tool_multilanguage_get_country_label',
											'param' => array(
												'countrycode' => $country_code,
												'locale' => $lang_code,
											),
										));

									// }

									// GET LANG LABEL {

										$lang_label = tool( array(
											'name' => 'tool_multilanguage_get_lang_label',
											'param' => array(
												'langcode' => $lang_code,
												'locale' => Locale::getPrimaryLanguage( $lang_code ),
											),
										));

									// }

									// VALIDATES LABEL {

										$lang_label = trim( preg_replace( "/\((.*)\)(.*)/", '$2', $lang_label ) );

									// }

									$url = tool( array(
										'name' => 'tool_multilanguage_add_locale_to_url',
										'param' => array(
											'lang' => $lang_code,
											'url' => $p['url'],
										),
									));

									$data[ $region_code ]['items'][ $lang_code ] = array();
									$data[ $region_code ]['items'][ $lang_code ]['url'] = $url;
									$data[ $region_code ]['items'][ $lang_code ]['label'] = array();
									$data[ $region_code ]['items'][ $lang_code ]['label']['country'] = $country_label;
									$data[ $region_code ]['items'][ $lang_code ]['label']['language'] = $lang_label;
								}

							// }
						}
					}

				// }
			}

		// }


		return $data;
	}

	function tool_multilanguage_region_select( $p = array() ) {

		// DEFAULTS {

			$defaults = array(
				'return' => 'html_list', // html_list, data
				'country_prepend' => '',
				'country_append' => '',
				'url' => 'home_url',
			);

			$p = array_replace_recursive( $defaults, $p );

			$html_list = '';

			$data = array();

		// }

		// GET WORLD REGIONS AND WALK TRUE {

			$regions = tool( array(
				'name' => 'tool_multilanguage_get_regions',
				'param' => array(
					'locale' => 'en',
				),
			));

			$html_list .= '<ul class="region-select-list-regions">';

				foreach ( $regions as $region_code => $region_label ) {

					$has_region = false;

					// MULTISITE LOOP SITES > LANGS {

						if ( $GLOBALS['toolset']['multisite'] ) {

							foreach ( $GLOBALS['toolset']['sites'] as $site_id => $site_item ) {

								if (  $site_id === 1 ) {

									continue;
								}

								if ( empty( $site_item['languages'] ) ) {

									continue;
								}

								$country_code = $site_item['country_code'];

								$has_multiple_langs = count( $site_item['languages'] );

								foreach ( $site_item['languages'] as $language_key => $language_item ) {

									$lang_code = $language_item['language'];

									// EXTRACT COUNTRY CODE {

										if ( strstr( $lang_code, '_' ) ) {

											$country_code = explode( '_', $lang_code )[1];
										}

									// }

									// CHECK IF COUNTRY IN CURRENT REGION {

										$is_country_in_region = tool( array(
											'name' => 'tool_multilanguage_country_in_region',
											'param' => array(
												'country_code' => $country_code,
												'region_code' => $region_code,
											),
										));

										if ( $is_country_in_region ) {

											// OUTPUT REGION LABEL {

												if ( ! $has_region ) {

													$has_region = true;

													$data[ $region_code ] = array();
													$data[ $region_code ]['lable'] = $region_label;
													$data[ $region_code ]['items'] = array();

													$html_list .=  '<li><span class="region-select-region-label">' . $region_label . '</span>';
													$html_list .=  '<ul class="region-select-list-sites">';
												}

											// }

											// GET COUNTRY LABEL {

												$country_label = tool( array(
													'name' => 'tool_multilanguage_get_country_label',
													'param' => array(
														'countrycode' => $country_code,
														'locale' => $lang_code,
													),
												));

											// }

											// GET LANG LABEL {

												$lang_label = tool( array(
													'name' => 'tool_multilanguage_get_lang_label',
													'param' => array(
														'langcode' => $lang_code,
														'locale' => Locale::getPrimaryLanguage( $lang_code ),
													),
												));

											// }

											// COMBINE LABELS {

												$label = $country_label;
												$label .= ' (' . $lang_label . ')';

											// }

											// GET URL {

												/*$url = tool( array(
													'name' => 'tool_multilanguage_add_locale_to_url',
													'param' => array(
														'lang' => $lang_code,
														'url' => $p['url'],
													),
												));*/

												$url = $GLOBALS['toolset']['home_url'] . $site_item['slug'];

											// }

											$data[ $region_code ]['items'][ $lang_code ] = array();
											$data[ $region_code ]['items'][ $lang_code ]['url'] = $url;
											$data[ $region_code ]['items'][ $lang_code ]['label'] = array();
											$data[ $region_code ]['items'][ $lang_code ]['label']['country'] = $country_label;
											$data[ $region_code ]['items'][ $lang_code ]['label']['language'] = $lang_label;

											$html_list .=  '<li class="region-select-item"><a class="region-select-link" href="' . $url. '">' . $p['country_prepend'] . $label . $p['country_append'] . '</a></li>';
										}

									// }
								}
							}
						}

					// }

					if ( $has_region ) {

						$html_list .= '</ul></li>';
					}
				}

			$html_list .= '</ul>';

		// }

		if ( $p['return'] === 'html_list' ) {

			return $html_list;
		}

		if ( $p['return'] === 'data' ) {

			return $data;
		}

		return $html_list;
	}

	function tool_multilanguage_add_locale_to_url( $p = array() ) {

		// DEFAULTS {

			$defaults = array(
				'lang' => $GLOBALS['toolset']['default_lang'],
				'url' => 'curr_url', // home_url, curr_url
			);

			$p = array_replace_recursive( $defaults, $p );

		// }

		// DEFINE URL {

			$url = $GLOBALS['toolset']['curr_url'];

			if ( $p['url'] === 'home_url' ) {

				$url = $GLOBALS['toolset']['home_url'];
			}

		// }

		// removes current lang slug from item url
		$url = str_replace( $GLOBALS['toolset']['home_url'] . '/' . encode_lang_slug( $GLOBALS['toolset']['frontend_locale'] ), $GLOBALS['toolset']['home_url'], $GLOBALS['toolset']['curr_url'] );

		// adds item lang slug to item url
		if ( $p['lang'] !== $GLOBALS['toolset']['default_lang'] ) {

			$url = str_replace( $GLOBALS['toolset']['home_url'], $GLOBALS['toolset']['home_url'] . '/' . encode_lang_slug( $p['lang'] ), $url );
		}

		return $url;
	}

	function tool_get_lang_value_from_array( $array ) {

		$locale = $GLOBALS['toolset']['frontend_locale'];

		// GETS BY LOCALE {

			if ( ! empty( $array[ $locale ] ) ) {

				return $array[ $locale ];
			}

		// }

		// GETS BY LANG {

			$lang = explode( '_', $locale )[0];

			if ( ! empty( $array[ $lang ] ) ) {

				return $array[ $lang ];
			}

		// }

		// GETS BY DEFAULT {

			if ( ! empty( $array['default'] ) ) {

				return $array['default'];
			}

		// }

		return false;
	}
