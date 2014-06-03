<?php

	// REGISTER SIDEBARS ( Version 1 ) {

		if ( isset( $GLOBALS['theme']['inits']['tool_sidebars_register']['sidebars'] ) && count( $GLOBALS['theme']['inits']['tool_sidebars_register']['sidebars'] ) > 0 ) {

			foreach ( $GLOBALS['theme']['inits']['tool_sidebars_register']['sidebars'] as $item ) {

				register_sidebar( $item['parameter'] );
			}
		}

	// }

?>