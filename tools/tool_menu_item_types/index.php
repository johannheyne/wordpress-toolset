<?php

	if ( is_admin() ) {

		/* ACF TRANSLATIONS */

			tool( array(
				'name' => 'tool_acf_translate',
				'param' => array(
					'strings' => array(
						'tool_menu_item_types_note' => array( // also translates {{Word}}
							'default' => '<div style="margin-top: -6px;"><span class="tooltip tooltip-style-menu-item">Using custom e-mail, phone and fax links.<span class="tooltiptext tooltiptext-align-left"><b class="tooltip-heading">E-Mail</b>Add <code>mailto:</code> before the e-mail adress in the URL field.<b class="tooltip-heading">Phone Number</b>Add <code>tel:</code> before the phone number in the URL field.<b class="tooltip-heading">Fax Number</b>Add <code>fax:</code> before the fax number in the URL field.</span></span><span class="tooltip tooltip-style-menu-item">Using e-mail, phone and fax links from <a href="admin.php?page=option-contactdata">Contactdata</a>.<span class="tooltiptext tooltiptext-align-left">Just put <code>mailto:</code>, <code>tel:</code> or <code>fax:</code> to the URL field without a following number or e-mail address. Leave the field "Displayed Name" empty.</span></span></div>',
							'de' => '<div style="margin-top: -6px;"><span class="tooltip tooltip-style-menu-item">Individuelle E-Mail, Telefon und Fax Links.<span class="tooltiptext tooltiptext-align-left"><b class="tooltip-heading">E-Mail</b>Füge <code>mailto:</code> vor der E-Mailadresse im URL Feld ein.<b class="tooltip-heading">Telefonnummer</b>Füge <code>tel:</code> vor der Telefonnumeer im URL Feld ein.<b class="tooltip-heading">Faxnummer</b>Füge <code>fax:</code> vor der Faxnummer im URL Feld ein.</span></span><span class="tooltip tooltip-style-menu-item">E-Mail, Telefon und Fax Links aus <a href="admin.php?page=option-contactdata">Kontaktdaten</a>.<span class="tooltiptext tooltiptext-align-left">Füge nur <code>mailto:</code>, <code>tel:</code> bzw. <code>fax:</code> in das URL Feld ein ohne Nummer oder E-Mail Adresse. Lasse das Feld "Angezeigter Name" frei.</span></span></div>',
						),
						// ToolAcfTranslateItem
					)
				),
			) );

			// use tool_acf_translate_string( 'string' ) for translating strings straight

		/**/
	}


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

			$menu_name = get_lang_field( 'menu_item/menu_name', $item->ID );

			if ( trim( $item->url ) === 'mailto:' ) {

				if ( empty( $menu_name ) ) {

					$value = get_lang_field( 'opt_contactdata/email', 'option' );
					$title = antispambot( $value );
				}

				$title = '<span>' . $title . '</span>';
			}

			if ( trim( $item->url ) === 'tel:' ) {

				if ( empty( $menu_name ) ) {

					$value = get_lang_field( 'opt_contactdata/fon', 'option' );
					$title = $value;
				}

				$title = '<span>' . $title . '</span>';
			}

			if ( trim( $item->url ) === 'fax:' ) {

				if ( empty( $menu_name ) ) {

					$value = get_lang_field( 'opt_contactdata/fax', 'option' );
					$title = $value;
				}

				$title = '<span>' . $title . '</span>';
			}

			return $title;
		}, 11, 4 );

	}
