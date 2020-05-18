<?php

	if ( ! empty( $GLOBALS['toolset']['inits']['tool_menu_item_types'] ) ) {

		if ( is_admin() ) {

			add_action( 'admin_enqueue_scripts', function() {

				wp_enqueue_script( 'tool_menu_item_types', $GLOBALS['toolset']['plugins_url'] . '/wordpress-toolset/tools/tool_menu_item_types/admin-script.js', array( 'jquery' ), config_get_theme_version() );
				wp_enqueue_style( 'tool_menu_item_types', $GLOBALS['toolset']['plugins_url'] . '/wordpress-toolset/tools/tool_menu_item_types/admin-styles.css', array(), config_get_theme_version() );
			});
		}

		add_filter( 'nav_menu_css_class', function( $classes, $item, $args, $depth ) {

			$link_type = get_field( 'link_type', $item->ID );

			if ( $link_type !== 'url' ) {

				$classes[] = 'link-type-' . $link_type;
			}

			return $classes;
		}, 11, 4 );

		add_filter( 'nav_menu_link_attributes', function( $atts, $item, $args, $depth ) {

			$link_type = get_field( 'link_type', $item->ID );

			if ( $link_type === 'email' ) {

				$email = get_lang_field( 'opt_contactdata/email', 'option' );
				$atts['href'] = 'mailto:' . antispambot( $email );
			}

			if ( $link_type === 'phone' ) {

				$phone = get_lang_field( 'opt_contactdata/fon', 'option' );
				$atts['href'] = get_tel_url( array(
					'number' => $phone,
				));
			}

			if ( $link_type === 'fax' ) {

				$fax = get_lang_field( 'opt_contactdata/fax', 'option' );
				$atts['href'] = get_tel_url( array(
					'number' => $fax,
					'prefix' => 'fax:',
				));
			}

			return $atts;
		}, 11, 4 );

		add_filter( 'nav_menu_item_title', function( $title, $item, $args, $depth ) {

			$menu_name = get_lang_field( 'menu_item/menu_name', $item->ID );
			$link_type = get_field( 'link_type', $item->ID );

			if ( $link_type === 'email' ) {

				if ( empty( $menu_name ) ) {

					$value = get_lang_field( 'opt_contactdata/email', 'option' );
					$title = antispambot( $value );
				}
			}

			if ( $link_type === 'phone' ) {

				if ( empty( $menu_name ) ) {

					$value = get_lang_field( 'opt_contactdata/fon', 'option' );
					$title = $value;
				}
			}

			if ( $link_type === 'fax' ) {

				if ( empty( $menu_name ) ) {

					$value = get_lang_field( 'opt_contactdata/fax', 'option' );
					$title = $value;
				}
			}

			return $title;
		}, 11, 4 );

	}
