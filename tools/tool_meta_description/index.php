<?php

	function tool_meta_description( $p = array() ) {

		// DEFAULTS {

			$defaults = array(
				'acf_field_name_page_decription' => 'meta_page_description'
			);

			$p = array_replace_recursive( $defaults, $p );

		// }

		// VARIABLES {

			$v = array(
				'description' => false,
				'description_custom_page' => false,
			);

		// }

		if ( function_exists( 'get_field' ) ) {

			if ( empty( $GLOBALS['toolset']['multilanguage'] ) ) {

				// BY GLOBAL OPTION {

					$v['description'] = get_field( 'opt_site_description_global' . THEME_LANG_SUFIX, 'options' );

				// }

				// BY PAGE {

					$v['description_custom_page'] = get_field( $p['acf_field_name_page_decription'] );

					if ( $v['description_custom_page'] ) {

						$v['description'] = $v['description_custom_page'];
					}

				// }
			}
			else {

				// BY GLOBAL OPTION {

					$v['description'] = '';

					$metas = get_lang_field( 'option_meta_searchengines', 'options' );

					if ( ! empty( $metas['description'] ) ) {

						$v['description'] = $metas['description'];
					}

				// }

				// BY PAGE {

					$v['description_custom_page'] = '';

					$metas = get_lang_field( 'page_meta_searchengines' );

					if ( ! empty( $metas['description'] ) ) {

						$v['description_custom_page'] = $metas['description'];
					}

					if ( $v['description_custom_page'] ) {

						$v['description'] = $v['description_custom_page'];
					}

				// }
			}

		}

		if ( $v['description'] ) {

			echo '<meta name="description" content="' . $v['description'] . '"/>' . "\n";
		}

	}
