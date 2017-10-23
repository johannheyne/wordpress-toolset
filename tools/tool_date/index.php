<?php

	// DATE ( Version 5 ) {

		function tool_date( $p = array() ) {

			// DEFAULTS {

				$defaults = array(
					'date' => false,
					'format' => 'd.m.Y',
					'local' => 'de_DE'
				);

				$p = array_replace_recursive( $defaults, $p );

			// }

			// default date
			if ( ! $p['date'] ) {

				$p['date'] = time();
			}

			if ( strpos( $p['date'], ':' ) !== false || strpos( $p['date'], '-' ) !== false ) {

				$p['date'] = mysql2date( 'U', $p['date'] );
			}

			setlocale( LC_ALL, $p['local'] );

			return date( $p['format'], $p['date'] );
		}

		function tool_html_time( $p = array() ) {

			/* Info

				Takes a databasefield datevalue like 20130227 or
				a date/time value like 2013022701230 and
				returns a formated time tag.
			*/

			$p += array(
				'field' => false, // 20130227 or 2013022701230
				'format' => 'd.m.Y',
				'timezone' => false
				/* timezone sources:
					http://www.php.net/manual/de/function.date-default-timezone-set.php
					http://www.php.net/manual/de/timezones.php
				*/
			);

			if ( $p['field'] ) {

				$date_time = str_split( $p['field'], 8 );

				$date_arr = str_split( $date_time[0], 2 );

				if ( !isset( $date_time[1] ) ) {

					$date_time[1] = '';
				}

				$time_arr = str_split( str_pad ( $date_time[1], 6, '0', STR_PAD_RIGHT ), 2 );

				$mysql2date_input_format = $date_arr[0] . $date_arr[1] . '-' . $date_arr[2] . '-' . $date_arr[3] . ' ' . $time_arr[0] . ':' . $time_arr[0] . ':' . $time_arr[0];

				$timestamp = mysql2date( 'U', $mysql2date_input_format );

				// use custom timezone
				if ( $p['timezone'] ) {

					$timezone_default = date_default_timezone_get();
					date_default_timezone_set( $p['timezone'] );
				}

				$time = date( 'c', $timestamp );
				$text = date( $p['format'], $timestamp );

				// define default timezone
				if ( $p['timezone'] ) {
					date_default_timezone_set( $timezone_default );
				}

				return '<time datetime="' . $time . '">' . $text . '</time>';
			}
			else {

				return false;
			}
		}

	// }
