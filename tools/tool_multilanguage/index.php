<?php

	function tool_multilanguage_require_lang( $p = array() ) {

		/*
			This function loads the required language into the
			$GLOBALS['toolset']['multilanguage_langs'] array.
			This can be a general or localized language.
			general: $GLOBALS['toolset']['multilanguage_langs']['en']
			localized: $GLOBALS['toolset']['multilanguage_langs']['en_US']

			// Source: https://github.com/petercoles/Multilingual-Language-List/blob/master/data/de_DE.php

		*/

		$plugin_path = plugin_dir_path( __FILE__ );

		if ( ! isset( $GLOBALS['toolset']['multilanguage_langs'] ) ) {

			$GLOBALS['toolset']['multilanguage_langs'] = array();
		}

		// DEFAULTS {

			$defaults = array(
				'lang' => false,
				'country' => false,
				'locale' => 'en', // alternative to lang and country
			);

			$p = array_replace_recursive( $defaults, $p );

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
				$p['country'] ) {

				$p['locale'] .= '_' . $p['country'];
			}

		// }

		// LOAD LANGUAGE FROM SOURCE {

			$path = $plugin_path . 'data-languages/' . $p['locale'] . '.php';

			if (
				empty( $GLOBALS['toolset']['multilanguage_langs'][ $p['locale'] ] ) AND
				file_exists( $path )
			) {

				$GLOBALS['toolset']['multilanguage_langs'][ $p['locale'] ] = require_once( $path );
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
				$p['country'] ) {

				$p['locale'] .= '_' . $p['country'];
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
				$p['country'] ) {

				$p['locale'] .= '_' . $p['country'];
			}

		// }

		tool_multilanguage_require_lang( array(
			'locale' => $p['locale'],
		) );


		return $GLOBALS['toolset']['multilanguage_langs'][ $p['locale'] ][ $p['langcode'] ];
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

		// }


		tool_multilanguage_require_countries( array(
			'locale' => $p['locale'],
		) );


		return $GLOBALS['toolset']['multilanguage_countries'][ $p['locale'] ][ strtoupper( $p['countrycode'] ) ];
	}
