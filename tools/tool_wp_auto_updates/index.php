<?php

	// DEVELOPER {

		add_filter( 'allow_dev_auto_core_updates', function( $value ) {

			if ( isset( $GLOBALS['toolset']['inits']['tool_wp_auto_updates'][ config_get_site_type() ]['allow_dev_auto_core_updates'] ) ) {

				// true zum Aktivieren, false zum Deaktivieren
				$value = $GLOBALS['toolset']['inits']['tool_wp_auto_updates'][ config_get_site_type() ]['allow_dev_auto_core_updates'];
			}

			return $value;
		} );

	// }

	// MINOR {

		add_filter( 'allow_minor_auto_core_updates', function( $value ) {

			if ( isset( $GLOBALS['toolset']['inits']['tool_wp_auto_updates'][ config_get_site_type() ]['allow_minor_auto_core_updates'] ) ) {

				// true zum Aktivieren, false zum Deaktivieren
				$value = $GLOBALS['toolset']['inits']['tool_wp_auto_updates'][ config_get_site_type() ]['allow_minor_auto_core_updates'];
			}

			return $value;
		} );

	// }

	// MAJOR {

		add_filter( 'allow_major_auto_core_updates', function( $value ) {

			if ( isset( $GLOBALS['toolset']['inits']['tool_wp_auto_updates'][ config_get_site_type() ]['allow_major_auto_core_updates'] ) ) {

				// true zum Aktivieren, false zum Deaktivieren
				$value = $GLOBALS['toolset']['inits']['tool_wp_auto_updates'][ config_get_site_type() ]['allow_major_auto_core_updates'];
			}

			return $value;
		} );

	// }

	// THEMES {

		add_filter( 'auto_update_theme', function( $value ) {

			if ( isset( $GLOBALS['toolset']['inits']['tool_wp_auto_updates'][ config_get_site_type() ]['auto_update_theme'] ) ) {

				// true zum Aktivieren, false zum Deaktivieren
				$value = $GLOBALS['toolset']['inits']['tool_wp_auto_updates'][ config_get_site_type() ]['auto_update_theme'];
			}

			return $value;
		} );

	// }

	// PLUGINS {

		add_filter( 'auto_update_plugin', function( $value ) {

			if ( isset( $GLOBALS['toolset']['inits']['tool_wp_auto_updates'][ config_get_site_type() ]['auto_update_plugin'] ) ) {

				// true zum Aktivieren, false zum Deaktivieren
				$value = $GLOBALS['toolset']['inits']['tool_wp_auto_updates'][ config_get_site_type() ]['auto_update_plugin'];
			}

			return $value;
		} );

	// }

	// TRANSLATIONS {

		add_filter( 'auto_update_translation', function( $value ) {

			if ( isset( $GLOBALS['toolset']['inits']['tool_wp_auto_updates'][ config_get_site_type() ]['auto_update_translation'] ) ) {

				// true zum Aktivieren, false zum Deaktivieren
				$value = $GLOBALS['toolset']['inits']['tool_wp_auto_updates'][ config_get_site_type() ]['auto_update_translation'];
			}

			return $value;
		} );


	// }
