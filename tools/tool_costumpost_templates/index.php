<?php

	// COSTUMPOST TEMPLATES ( Version 1 ) {

		add_action( 'after_setup_theme', 'tool_custompost_templates_init' );

		function tool_custompost_templates_init() {
			
			foreach ( $GLOBALS['theme']['inits']['tool_costumpost_templates'] as $key => $value ) {
			    
				$$value = new Tool_Single_CostumPost_Template( $value );
			}
		}

	// }

?>