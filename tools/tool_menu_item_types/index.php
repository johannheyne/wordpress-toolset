<?php

	if ( ! empty( $GLOBALS['toolset']['inits']['tool_menu_item_types'] ) ) {

		add_filter( 'nav_menu_link_attributes', function( $atts, $item, $args, $depth ) {

			$link_type = get_field( 'link_type', $item->ID );

			if ( trim( $atts['href'] ) === 'mailto:' ) {

				$email = get_lang_field( 'opt_contactdata/email', 'option' );
				$atts['href'] = 'mailto:' . antispambot( $email );
			}

			if ( trim( $atts['href'] ) === 'tel:' ) {

				$phone = get_lang_field( 'opt_contactdata/fon', 'option' );
				$atts['href'] = get_tel_url( array(
					'number' => $phone,
				));
			}

			if ( trim( $atts['href'] ) === 'fax:' ) {

				$fax = get_lang_field( 'opt_contactdata/fax', 'option' );
				$atts['href'] = get_tel_url( array(
					'number' => $fax,
					'prefix' => 'fax:',
				));
			}

			return $atts;
		}, 11, 4 );

		add_filter( 'nav_menu_item_title', function( $title, $item, $args, $depth ) {

			if ( trim( $item->url ) === 'mailto:' ) {

				if ( empty( $menu_name ) ) {

					$value = get_lang_field( 'opt_contactdata/email', 'option' );
					$title = antispambot( $value );
				}
			}

			if ( trim( $item->url ) === 'tel:' ) {

				if ( empty( $menu_name ) ) {

					$value = get_lang_field( 'opt_contactdata/fon', 'option' );
					$title = $value;
				}
			}

			if ( trim( $item->url ) === 'fax:' ) {

				if ( empty( $menu_name ) ) {

					$value = get_lang_field( 'opt_contactdata/fax', 'option' );
					$title = $value;
				}
			}

			return $title;
		}, 11, 4 );

	}
