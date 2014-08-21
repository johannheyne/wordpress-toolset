<?php

	// ACF BUILD OPTIONPAGE ( Version 1 ) {

		function tool_acf_optionpage_build( $p = array() ) {

			// DEFAULTS {

				$defaults = array(
					'setup' => false,
				);

				/* 
					Example for get_field( 'opt_contactform_fields_surename_de', 'options' );

					$setup = array(
						'optionpage_name' => 'Kontaktform',
						'optionpage_key' => 'contactform',
						'fieldgroups' => array(

							// FIELDS {

								array(
									'key' => 'fields',
									'label' => 'Felder',
									'fields' => array(
										array(
											'type' => 'text',
											'key' => 'surename',
											'label' => 'Vorname',
											'default_value' => 'Surename',
										),
									),
								),

							// }
						),
					);

					tool_acf_optionpage_build( array(
						'setup' => $setup,
					) );

				*/

				$p = array_replace_recursive( $defaults, $p );

			// }

			if ( $p['setup'] ) {

				// REGISTER OPTIONSPAGE {

					if ( function_exists( 'register_options_page' ) ) {

						acf_add_options_sub_page( $p['setup']['optionpage_name'] );
					}

				// }

				// FIELDS {

					$lang_array = unserialize( THEME_LANG_ARRAY );

					if ( isset( $p['setup']['fieldgroups'] ) && count( $p['setup']['fieldgroups'] ) > 0 ) {

						foreach ( $p['setup']['fieldgroups'] as $key1 => $fg ) {

							$fields = array();

							foreach ( $lang_array as $key => $item ) {

								// LANGUAGE TAB

								$fields[] = array (
									'key' => 'field_opt_' . $p['setup']['optionpage_key'] . '_' . $fg['key'] . '_tab_' . $item['language_code'],
									'label' => $item['translated_name'],
									'name' => '',
									'type' => 'tab',
								);

								// FIELDS {

								foreach ( $fg['fields'] as $key => $item2 ) {

									if ( $item2['type'] == 'text' ) {

										$fields[] = array (
											'key' => 'field_opt_' . $p['setup']['optionpage_key'] . '_' . $fg['key'] . '_' . $item2['key'] . '_' . $item['language_code'],
											'label' => $item2['label'],
											'name' => 'opt_' . $p['setup']['optionpage_key'] . '_' . $fg['key'] . '_' . $item2['key'] . '_' . $item['language_code'],
											'type' => 'text',
											'required' => 1,
											'default_value' => $item2['default_value'],
											'formatting' => 'none',
										);
									}
								}

								// FIELDS }

							}

							// }
							//print_o( $fields );

							// INIT FIELDS {

								register_field_group( array (
									'id' => 'acf_' . $p['setup']['optionpage_key'] . '_' . $fg['key'],
									'title' => $fg['label'],
									'fields' => $fields,
									'location' => array (
										'rules' => array (
											array (
												'param' => 'options_page',
												'operator' => '==',
												'value' => 'acf-options-' . strtolower( $p['setup']['optionpage_name'] ),
												'order_no' => 0,
											),
										),
										'allorany' => 'all',
									),
									'options' => array (
										'position' => 'normal',
										'layout' => 'default',
										'hide_on_screen' => array (),
									),
									'menu_order' => 999,
								));

							// }
						}

					}

				// }
			}

		}

	// }

	// ACF BUILD OPTIONPAGES ( Version 1 ) {

	    foreach ( $GLOBALS['toolset']['inits']['tool_acf_optionpage'] as $item ) {

			tool_acf_optionpage_build( array(
		        'setup' => $item,
			) );
	    }

	// }

?>