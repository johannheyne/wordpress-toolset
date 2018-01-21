<?php

	// CHANGE EDITOR SHORTCODE CAPTION ( Version 1 ) {

		remove_shortcode( 'caption' );

		function shortcode_caption( $p, $content ) {

			// DEFAULTS {

				$defaults = array(
					'id' => false,
					'align' => false,
					'width' => false,
				);

				$p = array_replace_recursive( $defaults, $p );

			// }

			// VARS {

				$vars['return'] = '';
				$vars['cont_arr'] = explode( '> ', $content );
				$vars['figure_class'] = array( 'wp-caption' );
				$vars['figure_id'] = '';
				$vars['figure_style'] = array();

			// }

			// GET AI SIZE {

				$ai_size_class = 'capsize-' . explode( '"', explode( 'size-', $vars['cont_arr'][0] )[1] )[0];
				array_push( $vars['figure_class'], $ai_size_class );

			// }

			// ATTRIBUTE {

				if ( $p['id'] ) {

					$vars['figure_id'] = $p['id'];
				}

				if ( $p['align'] ) {

					array_push( $vars['figure_class'], $p['align'] );
				}

				/*if ( $p['width'] ) {

					array_push( $vars['figure_style'], 'width:' . $p['width'] . 'px;' );
				}*/


				// ATTRIBUTE HTML {

					if ( $vars['figure_id'] ) {

						$vars['figure_id'] = ' id="' . $vars['figure_id'] . '"';
					}

					if ( $vars['figure_class'] ) {

						$vars['figure_class'] = ' class="' . implode( ' ', $vars['figure_class'] ) . '"';
					}

					if ( $vars['figure_style'] ) {

						$vars['figure_style'] = ' style="' . implode( ' ', $vars['figure_style'] ) . '"';
					}

				// }


			// }

			// HTML {

				$vars['return'] .= '<figure' . $vars['figure_id'] . $vars['figure_class'] . $vars['figure_style'] . '>';

					$vars['return'] .= $vars['cont_arr'][0] . '> ';

					if ( isset( $vars['cont_arr'][1] ) ) {

						unset( $vars['cont_arr'][0] );

						$vars['return'] .= '<figcaption class="wp-caption-text">';
							$vars['return'] .= trim( implode( '</a>', $vars['cont_arr'] ) );
						$vars['return'] .= '</figcaption>';
					}

				$vars['return'] .= '</figure>';

			// }

			return $vars['return'];
		}

		add_shortcode( 'caption', 'shortcode_caption' );

	// }
