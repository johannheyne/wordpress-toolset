<?php

	// ACF BUILD OPTIONPAGE ( Version 3 ) {

		// requires: tool_localization_constants
		// Source: http://www.advancedcustomfields.com/resources/acf_add_options_sub_page/

		/* USAGE

			$setup = array(
				'optionpage_name' => 'Optionpage',
				'optionpage_key' => 'optionpage',
				'parent' => false,
				'slug' => false,
				'menu' => false,
				'capability' => 'manage_options',
				'sites' => false, // array( '1' )
				'fieldgroups' => array(

					// FIELDGROUPS {

						array(
							'key' => 'fields',
							'label' => 'Fields',
							'fields' => array(

								// FIELDS {

									// TEXT
									array(
										'type' => 'text',
										'key' => 'name',
										'label' => 'Name',
										'default_value' => '',
										'required' => false,
									),

									// WYSIWYG
									array(
										'type' => 'wysiwyg',
										'key' => 'name',
										'label' => 'Name',
										'default_value' => '',
										'column_width' => '',
										'toolbar' => 'full',
										'media_upload' => 'no',
									),

								// }
							),
						),

					// }
				),
			);

			tool_acf_optionpage_build( array(
				'setup' => $setup,
			) );

			Example for get_field( 'opt_{optionpage_key}_{fieldgroup_key}_{field_key}_{lang}', 'options' );

		*/

		function tool_acf_optionpage_build( $p = array() ) {

			// DEFAULTS {

				$defaults = array(
					'setup' => array(
						'optionpage_name' => false,
						'optionpage_key' => false,
						'parent' => false,
						'slug' => false,
						'menu' => false,
						'capability' => 'manage_options',
						'sites' => false, // array( '1' )
					),
				);

				$p = array_replace_recursive( $defaults, $p );

			// }

			if ( $p['setup'] ) {

				// REGISTER OPTIONSPAGE {

					// Source: http://www.advancedcustomfields.com/resources/acf_add_options_sub_page/

					if ( function_exists( 'register_options_page' ) ) {

						$check = true;

						if ( $p['setup']['sites'] ) {

							global $current_blog;

							if ( isset( $current_blog ) AND ! in_array( $current_blog->blog_id, $p['setup']['sites'] ) ) {

								$check = false;
							}
						}

						if ( $check ) {

							acf_add_options_sub_page( array(
								'title' => $p['setup']['optionpage_name'],
								'parent' => $p['setup']['parent'],
								'capability' => $p['setup']['capability'],
								'slug' => $p['setup']['slug'],
								'menu' => $p['setup']['menu'],
							) );
						}

					}

				// }

				// FIELDS {

					$lang_array = unserialize( THEME_LANG_ARRAY );

					if ( isset( $p['setup']['fieldgroups'] ) && count( $p['setup']['fieldgroups'] ) > 0 ) {

						foreach ( $p['setup']['fieldgroups'] as $key1 => $fg ) {

							$fields = array();

							foreach ( $lang_array as $key => $item ) {

								// LANGUAGE TAB

								if ( count( $lang_array ) > 1 ) {

									$fields[] = array (
										'key' => 'field_opt_' . $p['setup']['optionpage_key'] . '_' . $fg['key'] . '_tab_' . $item['language_code'],
										'label' => $item['translated_name'],
										'name' => '',
										'type' => 'tab',
									);
								}

								// FIELDS {

								foreach ( $fg['fields'] as $key => $item2 ) {

									$defaults = array(
										'required' => true,
										'formatting' => 'none',
										'column_width' => '',
										'toolbar' => 'full',
										'media_upload' => 'no',
									);

									$item2 = array_replace_recursive( $defaults, $item2 );

									if ( $item2['type'] == 'text' ) {

										$fields[] = array (
											'type' => 'text',
											'key' => 'field_opt_' . $p['setup']['optionpage_key'] . '_' . $fg['key'] . '_' . $item2['key'] . '_' . $item['language_code'],
											'name' => 'opt_' . $p['setup']['optionpage_key'] . '_' . $fg['key'] . '_' . $item2['key'] . '_' . $item['language_code'],
											'label' => $item2['label'],
											'required' => $item2['required'],
											'default_value' => $item2['default_value'],
											'formatting' => $item2['formatting'],
										);
									}

									if ( $item2['type'] == 'wysiwyg' ) {

										$fields[] = array (
											'type' => 'wysiwyg',
											'key' => 'field_opt_' . $p['setup']['optionpage_key'] . '_' . $fg['key'] . '_' . $item2['key'] . '_' . $item['language_code'],
											'name' => 'opt_' . $p['setup']['optionpage_key'] . '_' . $fg['key'] . '_' . $item2['key'] . '_' . $item['language_code'],
											'label' => $item2['label'],
											'default_value' => $item2['default_value'],
											'column_width' => $item2['column_width'],
											'toolbar' => $item2['toolbar'],
											'media_upload' => $item2['media_upload'],
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