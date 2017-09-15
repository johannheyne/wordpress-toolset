<?php

	function tool_get_locale_by_url( $p = array() ) {

		// DEFAULTS {

			$defaults = array(
				'default_locale' => 'en',
				'locales' => array( 'en' ),
				'url' => $_REQUEST['REQUEST_URI'],
			);

			$p = array_replace_recursive( $defaults, $p );

		// }

		$locale = explode( '/', trim( $_SERVER['REQUEST_URI'], '/' ) )[0];

		if (
			empty( $locale ) OR
			! in_array( $locale, $p['locales'] )
		) {

			$locale = $p['default_locale'];
		}

		return $locale;
	}
