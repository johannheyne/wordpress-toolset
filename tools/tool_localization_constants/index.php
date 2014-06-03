<?php

	// THEME LOCALIZATION CONSTANTS ( Version 1 ) {

		// WPML {

			if ( defined( 'ICL_LANGUAGE_CODE' ) ) {

				define( 'THEME_LANG_SUFIX', '_' . ICL_LANGUAGE_CODE );

				/* die Konstante THEME_LANG_ARRAY sollte ein Array nach folgendem Muster sein:
					array( 
						'de' => array(
							'id' => 1,
							'active' => 1,
							'encode_url' => 0,
							'tag' => 'de-DE',
							'native_name' => 'Deutsch',
							'language_code' => 'de',
							'translated_name' => 'Deutsch',
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

			if ( !defined( 'THEME_LANG_SUFIX' ) ) {

				define( 'THEME_LANG_SUFIX', '_de' );
			}

			if ( !defined( 'THEME_LANG_ARRAY' ) ) {

				define( 'THEME_LANG_ARRAY', serialize( 
					array( 
						'de' => array(
							'id' => 1,
							'active' => 1,
							'encode_url' => 0,
							'tag' => 'de-DE',
							'native_name' => 'Deutsch',
							'language_code' => 'de',
							'translated_name' => 'Deutsch',
							'url' => '',
							'country_flag_url' => '',
						),
					)
				) );
			}

		// }

	// }

?>