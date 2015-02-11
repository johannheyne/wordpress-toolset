<?php

	// THEME LOCALIZATION CONSTANTS ( Version 2 ) {

		// WPML {

			if ( defined( 'ICL_LANGUAGE_CODE' ) ) {

				define( 'THEME_LANG_SUFIX', '_' . ICL_LANGUAGE_CODE );

				/* the variable THEME_LANG_ARRAY should be an array with the following pattern:
					array( 
						'en' => array(
							'id' => 1,
							'active' => 1,
							'encode_url' => 0,
							'tag' => 'en-US',
							'native_name' => 'English',
							'language_code' => 'en',
							'translated_name' => 'Englisch',
							'url' => '',
							'country_flag_url' => '',
						),
					)
				*/

				$data = icl_get_languages();
				define( 'THEME_LANG_ARRAY', serialize( $data ) );
			}

		// }

		// DEFAULT {

			// requires: $GLOBALS['toolset']['langcode'], $GLOBALS['toolset']['countrycode']

			$languages = $GLOBALS['toolset']['langcode'];
			$languages = array_values( $languages );
			$langcode = $languages[0];

			$countrycode = $GLOBALS['toolset']['countrycode'][ $langcode ];

			if ( !defined( 'THEME_LANG_SUFIX' ) ) {

				define( 'THEME_LANG_SUFIX', '_' . $langkey );
			}

			if ( !defined( 'THEME_LANG_ARRAY' ) ) {

				define( 'THEME_LANG_ARRAY', serialize( 
					array( 
						$langcode => array(
							'id' => 1,
							'active' => 1,
							'encode_url' => 0,
							'tag' => $countrycode,
							'native_name' => '',
							'language_code' => $langcode,
							'translated_name' => '',
							'url' => '',
							'country_flag_url' => '',
						),
					)
				) );
			}

		// }

	// }

?>