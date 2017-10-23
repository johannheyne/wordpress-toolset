<?php

	// DEVELOPER {

		add_filter( 'allow_dev_auto_core_updates', 'wp_control_dev_auto_updates' );
		function wp_control_dev_auto_updates( $value ) {

			// true zum Aktivieren, false zum Deaktivieren
			return $GLOBALS['toolset']['inits']['tool_wp_auto_updates'][ config_get_site_type() ]['allow_dev_auto_core_updates'];
		}

	// }

	// MINOR {

		add_filter( 'allow_minor_auto_core_updates', 'wp_control_minor_auto_updates' );
		function wp_control_minor_auto_updates( $value ) {

			// true zum Aktivieren, false zum Deaktivieren
			return $GLOBALS['toolset']['inits']['tool_wp_auto_updates'][ config_get_site_type() ]['allow_minor_auto_core_updates'];
		}

	// }

	// MAJOR {

		add_filter( 'allow_major_auto_core_updates', 'wp_control_major_auto_updates' );
		function wp_control_major_auto_updates( $value ) {

			// true zum Aktivieren, false zum Deaktivieren
			return $GLOBALS['toolset']['inits']['tool_wp_auto_updates'][ config_get_site_type() ]['allow_major_auto_core_updates'];
		}

	// }

	// THEMES {

		add_filter( 'auto_update_theme', 'wp_control_theme_auto_updates' );
		function wp_control_theme_auto_updates( $value ) {

			// true zum Aktivieren, false zum Deaktivieren
			return $GLOBALS['toolset']['inits']['tool_wp_auto_updates'][ config_get_site_type() ]['auto_update_theme'];
		}

	// }

	// PLUGINS {

		add_filter( 'auto_update_plugin', 'wp_control_plugin_auto_updates' );
		function wp_control_plugin_auto_updates( $value ) {

			// true zum Aktivieren, false zum Deaktivieren
			return $GLOBALS['toolset']['inits']['tool_wp_auto_updates'][ config_get_site_type() ]['auto_update_plugin'];
		}

	// }

	// TRANSLATIONS {

		add_filter( 'auto_update_translation', 'wp_control_translation_auto_updates' );
		function wp_control_translation_auto_updates( $value ) {

			// true zum Aktivieren, false zum Deaktivieren
			return $GLOBALS['toolset']['inits']['tool_wp_auto_updates'][ config_get_site_type() ]['auto_update_translation'];
		}

	// }
