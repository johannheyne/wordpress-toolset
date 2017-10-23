<?php

	// SEND OUTPUT TO ERROR_LOG ( Version 2 ) {

		function tool_debug ( $log )  {

			if ( is_array( $log ) || is_object( $log ) ) {

				error_log( print_r( $log, true ) );

			} else {

				error_log( $log );
			}
		}

	// }
