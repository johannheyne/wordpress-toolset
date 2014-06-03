<?php

	// BROWSER OUTDATED {

		function tool_browser_outdated() {

			$browseroutdated = 'Dieser Browser ist <strong>veraltet!</strong> <a href="http://browsehappy.com/">Aktualisiere Deinen Browser oder wähle einen anderen Browser</a>.';

			if ( config_get_curr_site_id() == 'en' ) {

				$browseroutdated = 'You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a>.';
			}

			echo '<!--[if lt IE 8]>';
				echo '<p class="browseroutdated font">' . $browseroutdated . '</p>';
			echo '<![endif]-->';
		}

	// }
	

?>