<?php

	// TOOL TRANSIENT {

		function tool_transient( $p = array() ) {

			// Source: https://codex.wordpress.org/Transients_API
			// DEFAULTS {

				$defaults = array(
					'id' => 'tool_stransient',
					'time' => '1', // 'tomorrow', 'next_hour'
					'call' => false,
				);

				$p = array_replace_recursive( $defaults, $p );

				$r = false;

			// }

			if ( $p['call'] ) {

				$result = get_transient( $p['id'] );

				if ( false === $result ) {

					$result = $p['call']();

					// SET TIME TO REMAINING SECONDS OF THE DAY {

						if ( $p['time'] === 'tomorrow'  ) {

							$datetime1 = new DateTime('now');
							$datetime2 = new DateTime('tomorrow');
							$arr = $datetime1->diff( $datetime2 );
							$p['time'] = $arr->s + ( $arr->i * 60 ) + ( $arr->h * 3600 );
						}

					// }

					// SET TIME TO REMAINING SECONDS UNTIL NEXT HOUR {

						if ( $p['time'] === 'next_hour'  ) {

							$p['time'] = ( ( 59 - date( 'i' ) ) * 60 ) + date( 's' );
						}

					// }

					set_transient( $p['id'], $result, $p['time'] );
				}

				$r = $result;
			}

			return $r;
		};

	// }
	

?>