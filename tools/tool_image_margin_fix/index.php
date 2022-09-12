<?php

	// IMAGE MARGIN FIX ( Version 2 ) {

		class fixImageMargins{

			public $xs = 0; //change this to change the amount of extra spacing

			public function __construct() {

				add_filter( 'img_caption_shortcode', array( &$this, 'fixme' ), 10, 3 );
			}

			public function fixme( $x, $attr, $content ) {

				extract( shortcode_atts( array(
						'id'	=> '',
						'align'	=> 'alignnone',
						'width'	=> '',
						'caption' => ''
					), $attr ) );

				// GET AI SIZE {

					$aisize = explode( '"', explode( '?size=', $content )[1] )[0];

				// }

				if ( 1 > (int) $width || empty( $caption ) ) {

					return $content;
				}

				if ( $id ) {

					$id = 'id="' . $id . '" ';
				}

				return '<div ' . $id . 'class="wp-caption ' . $align . ' capsize-' . $aisize . '" >' . $content . '<p class="wp-caption-text">' . $caption . '</p></div>';
			}
		}

		$fixImageMargins = new fixImageMargins();

	// }
