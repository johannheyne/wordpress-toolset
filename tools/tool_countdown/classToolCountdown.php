<?php

	class ToolCountdown {

		public $param;

		public $expired = false;

		function __construct( $p ) {

			// DEFAULTS {

				$defaults = array(
					'event_time' => false,
					'caption' => false,
					'label_days' => 'Days',
					'label_hours' => 'Hours',
					'label_minutes' => 'Minutes',
					'label_secounds' => 'Secounds',
					'expired_template' => false,
				);

				$this->param = array_replace_recursive( $defaults, $p );

			// }

			$this->register_scripts();

			add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
		}

		function register_scripts() {

			wp_enqueue_script( 'tool_countdown', plugin_dir_url( __FILE__ ) . 'script.js', array(), '1.0' );
		}

		function get_counter() {

			// CALCS REMAINING TIME {

				$current_time = time();
				$event_time = strtotime( $this->param['event_time'] );
				$rem_time = $event_time - $current_time;

				if ( $rem_time > 0 ) {

					$s = floor( $rem_time );
					$m = floor( $s / 60 );
					$h = floor( $m / 60 );
					$d = floor( $h / 24 );

					$h %= 24;
					$m %= 60;
					$s %= 60;

					if ( $h < 10 ) {

						$h = '0' . $h;
					}

					if ( $m < 10 ) {

						$m = '0' . $m;
					}

					if ( $s < 10 ) {

						$s = '0' . $s;
					}
				}
				else {

					$s = '00';
					$m = '00';
					$h = '00';
					$d = '0';

					$this->expired = true;
				}

			// }

			// HTML OF COUNTER {

				$html = '';

				$html .= '<div class="countdown-root" data-countdown-event-time="' . $this->param['event_time'] . '">';

					if ( false === $this->expired ) {

						if ( is_callable( $this->param['expired_template'] ) ) {

							$html .= '<script class="js-countdown-expire-template" type="text/template">' . $this->param['expired_template']() . '</script>';
						}

						$html .= '<table class="countdown-table js-countdown-table">';

							// CAPTION {

								if ( ! empty( $this->param['caption'] ) ) {

									$html .= '<caption class="countdown-caption">' . $this->param['caption'] . '</caption>';
								}

							// }

							$html .= '<tbody>';

								$html .= '<tr class="countdown-numbers">';
									$html .= '<td><span class="js-countdown-d">' . $d . '</span></td>';
									$html .= '<td><span class="js-countdown-h">' . $h . '</span></td>';
									$html .= '<td><span class="js-countdown-m">' . $m . '</span></td>';
									$html .= '<td><span class="js-countdown-s">' . $s . '</span></td>';
								$html .= '</tr>';

								$html .= '<tr class="countdown-labels">';
									$html .= '<td><span>' . $this->param['label_days'] . '</span></td>';
									$html .= '<td><span>' . $this->param['label_hours'] . '</span></td>';
									$html .= '<td><span>' . $this->param['label_minutes'] . '</span></td>';
									$html .= '<td><span>' . $this->param['label_secounds'] . '</span></td>';
								$html .= '</tr>';

							$html .= '</tbody>';

						$html .= '</table>';
					}

					if (
						true === $this->expired AND
						is_callable( $this->param['expired_template'] )
					) {

						$html .= $this->param['expired_template']();
					}

				$html .= '</div>';

			// }

			return $html;
		}

	}
