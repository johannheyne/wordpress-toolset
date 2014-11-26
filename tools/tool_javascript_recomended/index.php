<?php

	// JAVASCRIPT RECOMENDED {

		function tool_javascript_recomended() {

			// DEFAULTS {

				$defaults = array(
					'template' => '<p class="javascript_recomended">{message}</p>',
					'messages' => array(
						'en' => 'Javascript is turned off… Please activate Javascript to have all features of the site!',
						'de' => 'Javascript ist deaktiviert… Bitte aktiviere Javascript, um alle Funktionen der Webseite nutzen zu können.',
					),
				);

				$p = array_replace_recursive( $defaults, $GLOBALS['toolset']['inits']['tool_javascript_recomended'] );

			// }

			// default message
			$message = $p['messages']['en'];

			// message in current language
			if ( isset( $p['messages'][ config_get_curr_site_id() ] ) ) {

				$message = $p['messages'][ config_get_curr_site_id() ];
			}

			echo '<noscript>';
				echo str_replace( '{message}',  $message, $p['template'] );
			echo '</noscript>';
		}

	// }

?>