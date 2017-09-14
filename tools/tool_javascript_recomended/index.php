<?php

	// JAVASCRIPT RECOMENDED {

		function tool_javascript_recomended( $p = array() ) {

			// DEFAULTS {

				$defaults = array(
					'template' => '<p class="javascript_recomended">{message}</p>',
					'lang' => false,
					'messages' => array(
						'en' => 'Javascript is turned off… Please activate Javascript to have all features of the site!',
						'de' => 'Javascript ist deaktiviert… Bitte aktiviere Javascript, um alle Funktionen der Webseite nutzen zu können.',
					),
				);

				$p = array_replace_recursive( $defaults, $GLOBALS['toolset']['inits']['tool_javascript_recomended'], $p );

				$vars['lang'] = false;

			// }

			if ( $p['lang'] ) {

				$vars['lang'] = $p['lang'];
			}
			else {

				// if Multisite is used for different Languages
				$vars['lang'] = config_get_curr_site_id();
			}

			// message in current language
			if ( isset( $p['messages'][ $vars['lang'] ] ) ) {

				$message = $p['messages'][ $vars['lang'] ];
			}
			else {

				$message = $p['messages']['en'];
			}

			echo '<noscript>';
				echo str_replace( '{message}',  $message, $p['template'] );
			echo '</noscript>';
		}

	// }

?>
