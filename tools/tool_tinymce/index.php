<?php

	// TinyMCE EDITOR ( Version 3 ) {

		function tool_tiny_mce_before_init( $init ) {

			// Add block format elements you want to show in dropdown
			$init['theme_advanced_blockformats'] = $GLOBALS['theme']['inits']['tool_tinymce']['blockformats'];

			if ( isset( $GLOBALS['theme']['inits']['tool_tinymce']['styleformats'] ) && count( $GLOBALS['theme']['inits']['tool_tinymce']['styleformats'] ) > 0 ) {

				$init['style_formats'] = json_encode( $GLOBALS['theme']['inits']['tool_tinymce']['styleformats'] );
			    $init['style_formats_merge'] = false;
			}

			return $init;
		}

		add_filter( 'tiny_mce_before_init', 'tool_tiny_mce_before_init' );

		function tool_mce_buttons( $buttons ) {

			// The settings are returned in this array. Customize to suite your needs.
			return $GLOBALS['theme']['inits']['tool_tinymce']['toolbar-line-1'];
		}

		add_filter( 'mce_buttons', 'tool_mce_buttons', 0 );

		function tool_mce_buttons_2( $buttons ) {

			// The settings are returned in this array. Customize to suite your needs. An empty array is used here because I remove the second row of icons.
			return $GLOBALS['theme']['inits']['tool_tinymce']['toolbar-line-2'];
		}

		add_filter( 'mce_buttons_2', 'tool_mce_buttons_2', 0 );

	// }

?>