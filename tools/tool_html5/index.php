<?php

	if ( ! empty( $GLOBALS['toolset']['inits']['tool_html5'] ) ) {

		add_action( 'after_setup_theme', function() {

			add_theme_support( 'html5', array( 'script', 'style' ) );
		} );
	}
