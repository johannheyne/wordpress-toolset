<?php

	function tool_admin_bar_remove( ) {

	    // DEFAULTS {

			$defaults = array(
				'wp-logo' => false,
				'about' => false,
				'wporg' => false,
				'documentation' => false,
				'support-forums' => false,
				'feedback' => false,
				'site-name' => false,
				'view-site' => false,
				'updates' => false,
				'comments' => false,
				'new-content' => false,
				'w3tc' => false,
				'my-account' => false,
			);
			$p = array_replace_recursive( $defaults, $GLOBALS['toolset']['inits']['tool_admin_bar']['remove'] );

	    // }
	    
		global $wp_admin_bar;
		
		foreach ( $p as $key => $value ) {
		    
			if ( $value ) {
		    	
				$wp_admin_bar->remove_menu( $key );
		
			}
		}
	}
	
	if ( isset( $GLOBALS['toolset']['inits']['tool_admin_bar']['remove'] ) ) {
	    
		add_action( 'wp_before_admin_bar_render', 'tool_admin_bar_remove' );
	}

?>