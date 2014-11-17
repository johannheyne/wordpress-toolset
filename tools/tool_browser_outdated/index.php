<?php

	// BROWSER OUTDATED {

		// IE 8 Support by Microsoft ends January 12, 2016
		// http://blogs.msdn.com/b/ie/archive/2014/08/07/stay-up-to-date-with-internet-explorer.aspx

		function tool_browser_outdated( $p = array() ) {

			// DEFAULTS {

				$defaults = array(
					'version' => 7,
					'message' => array(
						'en' => 'You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a>.',
						'de' => 'Dieser Browser ist <strong>veraltet!</strong> <a href="http://browsehappy.com/">Aktualisiere Deinen Browser oder wähle einen anderen Browser</a>.',
					),
				);

				$p = array_replace_recursive( $defaults, $p );

			// }

			$browseroutdated = $p['message'][ config_get_curr_site_id() ];

			echo '<!--[if lt IE ' . ( $p['version'] + 1 ) . ']>';
				echo '<p class="browseroutdated font">' . $browseroutdated . '</p>';
			echo '<![endif]-->';
		}

	// }
	

?>