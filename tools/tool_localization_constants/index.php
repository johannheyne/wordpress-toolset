<?php

	// THEME LOCALIZATION CONSTANTS ( Version 3 ) {

		// DEFINES THEME_LANG_SUFIX AND THEME_LANG_ARRAY BY WPML {

			if ( defined( 'ICL_LANGUAGE_CODE' ) ) {

				if ( ! isset( $GLOBALS['toolset']['inits']['tool_localization_constants']['THEME_LANG_SUFIX'] ) ) {

					define( 'THEME_LANG_SUFIX', '_' . ICL_LANGUAGE_CODE );
				}

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

				if ( ! isset( $GLOBALS['toolset']['inits']['tool_localization_constants']['THEME_LANG_ARRAY'] ) ) {

					$data = icl_get_languages();
					define( 'THEME_LANG_ARRAY', serialize( $data ) );
				}
			}

		// }

		// DEFAULT {

			// requires:
			// $GLOBALS['toolset']['langcode']
			// $GLOBALS['toolset']['countrycode']

			// DEFINES THEME_LANG_SUFIX BY THE FIRST DEFINED LANGCODE {

				$langcode = reset( $GLOBALS['toolset']['langcode'] );

				if (
					! defined( 'THEME_LANG_SUFIX' ) AND
					! isset( $GLOBALS['toolset']['inits']['tool_localization_constants']['THEME_LANG_SUFIX'] )
				) {

					define( 'THEME_LANG_SUFIX', '_' . $langcode );
				}

			// }

			// DEFINES THEME_LANG_ARRAY {

				if (
					! defined( 'THEME_LANG_ARRAY' ) AND
					! isset( $GLOBALS['toolset']['inits']['tool_localization_constants']['THEME_LANG_ARRAY'] )
				) {

					$lang_array = array();

					if ( !empty( $GLOBALS['toolset']['langarray'] ) ) {

						$lang_array = $GLOBALS['toolset']['langarray'];
					}
					else {

						foreach( $GLOBALS['toolset']['langcode'] as $langcode ) {

							$lang_array[ $langcode ] = array(
								'id' => 1,
								'active' => 1,
								'encode_url' => 0,
								'tag' => $GLOBALS['toolset']['countrycode'][ $langcode ],
								'native_name' => '',
								'language_code' => $langcode,
								'translated_name' => $langcode,
								'url' => '',
								'country_flag_url' => '',
							);
						}
					}

					define( 'THEME_LANG_ARRAY', serialize( $lang_array ) );
				}

			// }

		// }

	// }
