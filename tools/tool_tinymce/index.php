<?php

	// TinyMCE EDITOR ( Version 2 ) {

		// TinyMCE: block format elements
		function customformatTinyMCE( $init ) {
		
			// Add block format elements you want to show in dropdown
			$init['theme_advanced_blockformats'] = $GLOBALS['theme']['inits']['tool_tinymce']['blockformats'];
			
			return $init;
		}
		
		add_filter( 'tiny_mce_before_init', 'customformatTinyMCE' );

		// TinyMCE: First line toolbar customizations
		if( ! function_exists( 'base_extended_editor_mce_buttons' ) ) {
			
			function base_extended_editor_mce_buttons( $buttons ) {
			
				// The settings are returned in this array. Customize to suite your needs.
				return $GLOBALS['theme']['inits']['tool_tinymce']['toolbar-line-1'];
			}
			
			add_filter( 'mce_buttons', 'base_extended_editor_mce_buttons', 0 );
		}

		// TinyMCE: Second line toolbar customizations
		if( !function_exists( 'base_extended_editor_mce_buttons_2' ) ) {
		
			function base_extended_editor_mce_buttons_2($buttons) {
				
				// The settings are returned in this array. Customize to suite your needs. An empty array is used here because I remove the second row of icons.
				return $GLOBALS['theme']['inits']['tool_tinymce']['toolbar-line-2'];
			}
			
			add_filter( 'mce_buttons_2', 'base_extended_editor_mce_buttons_2', 0 );
		}


	// }

?>