<?php

	// DATE ( Version 6 ) {

		function tool_date( $p = array() ) {

			// DEFAULTS {

				$defaults = array(
					'date' => false,
					'format' => false,
					'timezone' => false,
					'local' => false,
					'locale' => false,
					'date_formatter' => 'medium',
					'time_formatter' => 'none',
				);

				$p = array_replace_recursive( $defaults, $p );

			// }

			// DATE {

				if ( ! $p['date'] ) {

					$p['date'] = time();
				}

				if ( strpos( $p['date'], ':' ) !== false || strpos( $p['date'], '-' ) !== false ) {

					$p['date'] = mysql2date( 'U', $p['date'] );
				}

			// }

			// LOCALE {

				if ( empty( $p['locale'] ) ) {

					$p['locale'] = get_locale();

					if ( ! empty( $GLOBALS['toolset']['frontend_locale'] ) ) {

						$p['locale'] = $GLOBALS['toolset']['frontend_locale'];
					}
				}

				if ( ! empty( $p['local'] ) ) {

					$p['locale'] = $p['local'];
				}

			// }

			// TIMEZONE {

				if ( empty( $p['timezone'] ) ) {

					$p['timezone'] = wp_timezone_string();
				}

				if ( strpos( $p['timezone'], ':' ) ) {

					$p['timezone'] = 'GMT' . $p['timezone'];
				}

			// }

			// DATE FORMATTER {

				if ( $p['date_formatter'] === 'short' ) {

					$date_formatter = IntlDateFormatter::SHORT;
				}
				elseif ( $p['date_formatter'] === 'medium' ) {

					$date_formatter = IntlDateFormatter::MEDIUM;
				}
				elseif ( $p['date_formatter'] === 'long' ) {

					$date_formatter = IntlDateFormatter::LONG;
				}
				elseif ( $p['date_formatter'] === 'full' ) {

					$date_formatter = IntlDateFormatter::FULL;
				}
				else {

					$date_formatter = IntlDateFormatter::NONE;
				}

			// }

			// TIME FORMATTER {

				if ( $p['time_formatter'] === 'short' ) {

					$time_formatter = IntlDateFormatter::SHORT;
				}
				elseif ( $p['time_formatter'] === 'medium' ) {

					$time_formatter = IntlDateFormatter::MEDIUM;
				}
				elseif ( $p['time_formatter'] === 'long' ) {

					$time_formatter = IntlDateFormatter::LONG;
				}
				elseif ( $p['time_formatter'] === 'full' ) {

					$time_formatter = IntlDateFormatter::FULL;
				}
				else {

					$time_formatter = IntlDateFormatter::NONE;
				}

			// }

			$fmt = datefmt_create(
				$p['locale'],
				$date_formatter,
				$time_formatter,
				$p['timezone'],
				IntlDateFormatter::GREGORIAN,
				$p['format'],
			);

			$date = $fmt->format( $p['date'] );

			return $date;
		}

		function tool_html_time( $p = array() ) {

			/* Info

				Takes a databasefield datevalue like 20130227 or
				a date/time value like 2013022701230 or
				a timestamp
				and returns a formated time tag.
			*/

			$p += array(
				'field' => false, // 20130227 or 2013022701230
				'date' => false,
				'format' => false,
				'timezone' => false,
				'local' => false,
				'locale' => false,
				'date_formatter' => 'medium',
				'time_formatter' => 'none',
				/* timezone sources:
					http://www.php.net/manual/de/function.date-default-timezone-set.php
					http://www.php.net/manual/de/timezones.php
				*/
			);

			if ( $p['field'] ) {

				// GETS TIME FROM DATE STRING {

					$date_time = str_split( $p['field'], 8 );

					$date_arr = str_split( $date_time[0], 2 );

					if ( !isset( $date_time[1] ) ) {

						$date_time[1] = '';
					}

					$time_arr = str_split( str_pad ( $date_time[1], 6, '0', STR_PAD_RIGHT ), 2 );

					$mysql2date_input_format = $date_arr[0] . $date_arr[1] . '-' . $date_arr[2] . '-' . $date_arr[3] . ' ' . $time_arr[0] . ':' . $time_arr[0] . ':' . $time_arr[0];

					$p['time'] = mysql2date( 'U', $mysql2date_input_format );

				// }
			}

			// DATE {

				if ( ! $p['date'] ) {

					$p['date'] = time();
				}

				if ( strpos( $p['date'], ':' ) !== false || strpos( $p['date'], '-' ) !== false ) {

					$p['date'] = mysql2date( 'U', $p['date'] );
				}

			// }

			// LOCALE {

				if ( empty( $p['locale'] ) ) {

					$p['locale'] = get_locale();

					if ( ! empty( $GLOBALS['toolset']['frontend_locale'] ) ) {

						$p['locale'] = $GLOBALS['toolset']['frontend_locale'];
					}
				}

				if ( ! empty( $p['local'] ) ) {

					$p['locale'] = $p['local'];
				}

			// }

			// TIMEZONE {

				if ( empty( $p['timezone'] ) ) {

					 $p['timezone'] = wp_timezone_string();
				}

			// }

			// DATE FORMATTER {

				if ( $p['date_formatter'] === 'short' ) {

					$date_formatter = IntlDateFormatter::SHORT;
				}
				elseif ( $p['date_formatter'] === 'medium' ) {

					$date_formatter = IntlDateFormatter::MEDIUM;
				}
				elseif ( $p['date_formatter'] === 'long' ) {

					$date_formatter = IntlDateFormatter::LONG;
				}
				elseif ( $p['date_formatter'] === 'full' ) {

					$date_formatter = IntlDateFormatter::FULL;
				}
				else {

					$date_formatter = IntlDateFormatter::NONE;
				}

			// }

			$time_c = date( 'c', $p['date'] );

			$date = tool( array(
				'name' => 'tool_date',
				'param' => array(
					'date' => $p['date'], // optional, '20211206'
					'format' => $p['format'], // optional, 'd.m.Y'
					'local' => $p['local'], // optional, 'de_DE'
					'timezone' => $p['timezone'], // optional, 'Europe/Berlin'
					'date_formatter' => $p['date_formatter'], // optional: none, short, medium, long, full
					'time_formatter' => $p['time_formatter'], // optional: none, short, medium, long, full
				)
			));

			return '<time class="_date" datetime="' . $time_c . '">' . $date . '</time>';
		}

	// }
