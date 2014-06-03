<?php

	function tool_meta_description( $p = array() ) {

		// DEFAULTS {

			$defaults = array(
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

			// BY GLOBAL OPTION {

				$v['description'] = get_field( 'opt_site_description_global' . THEME_LANG_SUFIX, 'options' );

			// }

			// BY PAGE {

				$v['description_custom_page'] = get_field( 'meta_seitenbeschreibung' );

				if ( $v['description_custom_page'] ) {

					$v['description'] = $v['description_custom_page'];
				}

			// }
		}

		if ( $v['description'] ) {

			echo '<meta name="description" content="' . $v['description'] . '"/>' . "\n";
		}

	}

?>