<?php

	// DEFINES GLOBAL FIELD AND FORM MESSAGES {

		add_filter( 'class/Form/messages', function( $messages, $param ) {

			$messages['required'] = 'This field is required.';

			$messages['field_validation_error'] = 'At least one field has an validation error.';

			$messages['email_not_valid'] = 'The email is not valid.';

			$messages['filesize_to_large'] = 'The file size was to large.';

			$messages['file_format_not_allowed'] = 'The file format is not allowed.';

			$messages['email_sent'] = 'The email was sent.';

			// Add coresponding admin translation in plugins/wordpress-toolset/tools/forms/index.php

			return $messages;

		}, 10, 2 );

	// }

	class Form {

		/*
			Version: v1.0
		*/

		/* $p

			@value: Array (
						[form_id] => demo_default_form
						[form_group] => demo
						[form_attrs] => Array (
							[role] => form
							[method] => post
							[enctype] => default is "multipart/form-data" when method is post
							[action] =>
							[data-form-id] => demo_default_form
							[aria_label] => My Form
							[class] => Array (
								[0] => default-form form-style-basic
							)
						)
						[echo] => 1
						[is_request] => 1
						[has_messages] =>
					)
		*/

		private $p = false;

		private $items = array();

		private $form_messages = array();

		private $request = false;

		private $is_request = false; // whether the form is requested

		private $is_ajax_request = false;

		private $is_valide = true; // whether the form is valide

		private $has_messages = array(
			'fields' => false,
			'form' => false,
		); // whether the form has massages

		private $request_form_message_keys = array();

		private $fieldtypes = array();

		function __construct( $p = array( 'form_id' => '' ) ) {

			$p = apply_filters( 'class/Form/param', $p );
			$p = apply_filters( 'class/Form/param/form_id=' .  $p['form_id'], $p );

			// DEFAULTS {

				$defaults = array(
					'form_id' => '',
					'form_group' => '',
					'form_attrs' => array(
						'role' => 'form',
						'method' => 'get',
						'action' => '',
						'data-form-id' => $p['form_id'],
						'data-form-unique-id' => newid(),
						'enctype' => false,
					),
					'echo' => true,
					'email' => array(
						'status' => false,
						'email_body' => '',
						'email_header' => array(),
						'email_subject' => '',
						'email_from' => '',
						'email_from_name' => '',
						'email_to' => '',
					),
					'save' => array(
						'status' => false,
						'posttype' => false,
						'fields' => array(
							'form_field_name' => 'posttype_field_key',
						),
					),
					'return' => array( 'fields' ), // what to return on request, array( '' ) returns nothing but form messages
				);

				$this->p = tool_merge_defaults( $p, $defaults );

				// DYNAMIC PARAM CHANGES {

					if (
						empty( $this->p['form_attrs']['enctype'] ) AND
						'post' === $this->p['form_attrs']['method']
					) {

						$this->p['form_attrs']['enctype'] = 'multipart/form-data';
					}

				// }

			// }

			// INITS FIELDTYPES {

				if ( ! has_filter( 'class/Form/get_fields_html/field_type=text' ) ) {

					$this->init_text_field();
				}

				if ( ! has_filter( 'class/Form/get_fields_html/field_type=textarea' ) ) {

					$this->init_textarea_field();
				}

				if ( ! has_filter( 'class/Form/get_fields_html/field_type=radio' ) ) {

					$this->init_radio_field();
				}

				if ( ! has_filter( 'class/Form/get_fields_html/field_type=checkbox' ) ) {

					$this->init_checkbox_field();
				}

				if ( ! has_filter( 'class/Form/get_fields_html/field_type=checkboxes' ) ) {

					$this->init_checkboxes_field();
				}

				if ( ! has_filter( 'class/Form/get_fields_html/field_type=taxonomy_select' ) ) {

					$this->init_taxonomy_select_field();
				}

				if ( ! has_filter( 'class/Form/get_fields_html/field_type=select' ) ) {

					$this->init_select_field();
				}

				if ( ! has_filter( 'class/Form/get_fields_html/field_type=switch_toggle' ) ) {

					$this->init_switch_toggle_field();
				}

				if ( ! has_filter( 'class/Form/get_fields_html/field_type=submit' ) ) {

					$this->init_submit_field();
				}

				if ( ! has_filter( 'class/Form/get_fields_html/field_type=custom' ) ) {

					$this->init_custom_field();
				}

				if ( ! has_filter( 'class/Form/get_fields_html/field_type=email' ) ) {

					$this->init_email_field();
				}

				if ( ! has_filter( 'class/Form/get_fields_html/field_type=file' ) ) {

					$this->init_file_field();
				}

				if ( ! has_filter( 'class/Form/get_fields_html/field_type=search' ) ) {

					$this->init_search_field();
				}

				if ( ! has_filter( 'class/Form/get_fields_html/field_type=password' ) ) {

					$this->init_password_field();
				}

				if ( ! has_filter( 'class/Form/get_fields_html/field_type=date' ) ) {

					$this->init_date_field();
				}

				if ( ! has_filter( 'class/Form/get_fields_html/field_type=info' ) ) {

					$this->init_info_field();
				}

				if ( ! has_filter( 'class/Form/get_fields_html/field_type=hidden' ) ) {

					$this->init_hidden_field();
				}

				if ( ! has_filter( 'class/Form/get_fields_html/field_type=fieldset' ) ) {

					$this->init_fieldset_field();
				}

			// }

			// GETS FIELDTYPES {

				$this->fieldtypes = apply_filters( 'class/Form/add_fieldtype', $this->fieldtypes );

			// }

			// REGISTER FORM MESSAGES {

				$this->form_messages = apply_filters( 'class/Form/messages', $this->form_messages, $this->p );
				$this->form_messages = apply_filters( 'class/Form/messages/form_group=' . $this->p['form_group'], $this->form_messages, $this->p );
				$this->form_messages = apply_filters( 'class/Form/messages/form_id=' . $this->p['form_id'], $this->form_messages, $this->p );

			// }

			// REGISTERS CONTAINERS {

				$this->items = apply_filters( 'class/Form/containers', $this->items, $this->p );
				$this->items = apply_filters( 'class/Form/containers/form_group=' . $this->p['form_group'], $this->items, $this->p );
				$this->items = apply_filters( 'class/Form/containers/form_id=' . $this->p['form_id'], $this->items, $this->p );

			// }

			// REGISTERS FIELDSETS {

				$this->items = apply_filters( 'class/Form/fieldsets', $this->items, $this->p );
				$this->items = apply_filters( 'class/Form/fieldsets/form_group=' . $this->p['form_group'], $this->items, $this->p );
				$this->items = apply_filters( 'class/Form/fieldsets/form_id=' . $this->p['form_id'], $this->items, $this->p );

			// }

			// GET FORM ITEMS {

				$this->items = apply_filters( 'class/Form/items', $this->items, $this->p );
				$this->items = apply_filters( 'class/Form/items/form_id=' . $this->p['form_id'], $this->items, $this->p );
				$this->items = apply_filters( 'class/Form/items/form_group=' . $this->	p['form_group'], $this->items, $this->p );

			// }

			// ADDS FILTERS {

				add_filter( 'class/Form/required/form_id=' . $this->p['form_id'] . '&type=checkboxes', array( $this, 'filter_checkboxes_custom_field_key_required' ), 10, 2 );
				add_filter( 'class/Form/required/form_id=' . $this->p['form_id'] . '&type=file', array( $this, 'filter_files_required' ), 10, 2 );

				add_action( 'class/Form/before_html/form_id=' . $this->p['form_id'], array( $this, 'email_sent' ) );

			// }

			// ADDS ACTIONS {

				add_action( 'class/Form/request/is_email/form_id=' . $this->p['form_id'], array( $this, 'do_email' ), 10, 1 );

			// }

			// FORM REQUEST ACTION {

				if ( $this->is_form_request( $this->p['form_id'] ) ) {

					$this->is_request = true;
					$this->is_ajax_request = wp_doing_ajax();
					$this->request = $_REQUEST;

					if ( ! empty( $_FILES ) ) {

						$this->request = array_replace_recursive( $this->request, $_FILES );
					}

					$this->p['form_attrs']['data-form-unique-id'] = $this->request['form_unique_id'];

					// SANITIZE REQUEST

					$this->sanitize_request();
					$this->validate_fields();
					$this->updates_field_values();
					$this->gets_fields_form_message_keys();

					// REQUEST ACTION

					do_action( 'class/Form/request/form_id=' . $this->p['form_id'], $this->p );
					do_action( 'class/Form/request/form_group=' . $this->p['form_group'], $this->p );
					do_action( 'class/Form/request', $this->p );

					$this->request = apply_filters( 'class/Form/request_value/form_id=' . $this->p['form_id'], $this->request, $this->p );
					$this->request = apply_filters( 'class/Form/request_value/form_group=' . $this->p['form_group'], $this->request, $this->p );
					$this->request = apply_filters( 'class/Form/request_value', $this->request, $this->p );

					if (
						$this->is_valide AND
						! empty( $this->p['email']['status'] )
					) {

						do_action( 'class/Form/request/is_email/form_id=' . $this->p['form_id'], $this->p );
					}

					if (
						$this->is_valide AND
						! empty( $this->p['save']['status'] )
					) {

						do_action( 'class/Form/request/is_save/form_id=' . $this->p['form_id'], $this->p );
					}
				}

				// FILTERS

				add_filter( 'class/Form/form_prepend/form_id=' . $this->p['form_id'], array( $this, 'gets_form_messages_html' ), 10, 2 );

			// }

			// ADDONS {

				$this->addon_before_after_field();

			// }

			if ( $this->p['echo'] === true ) {

				echo $this->get_form();
			}
		}

		public function get_form() {

			$attrs = array();

			$attrs = array_replace_recursive( $attrs, $this->p['form_attrs'] );

			if ( ! empty( $this->p['form_post_id']  ) ) {

				$attrs['data-form-post-id'] = $this->p['form_post_id'];
			}

			do_action( 'class/Form/before_html/form_id=' . $this->p['form_id'], $this->p );

				$html = '';

				if ( false === $this->is_ajax_request ) {

					$html .= '<form' . attrs( $attrs ) . '>';
				}

				$html .= '<input type="hidden" name="form_id" value="' . $this->p['form_id'] . '" />';
				$html .= '<input type="hidden" name="form_unique_id" value="' . $this->p['form_attrs']['data-form-unique-id'] . '" />';

				// FORM POST ID {

					if ( ! empty( $this->p['form_post_id']  ) ) {

						$html .= '<input type="hidden" name="form_post_id" value="' . $this->p['form_post_id'] . '" />';
					}

				// }

				// POST ID {

					if ( ! empty( $_REQUEST['post_id'] ) ) {

						$post_id = $_REQUEST['post_id'];
					}
					else {

						$post_id = get_the_ID();
					}

					$html .= '<input type="hidden" name="post_id" value="' . $post_id. '" />';

				// }

				// FORM PREPEND {

					$html = apply_filters( 'class/Form/form_prepend', $html, $this->p );
					$html = apply_filters( 'class/Form/form_prepend/form_group=' . $this->p['form_group'], $html, $this->p );
					$html = apply_filters( 'class/Form/form_prepend/form_id=' . $this->p['form_id'], $html, $this->p );

				// }

				$html .= $this->get_form_fields();

				// FORM APPEND {

					$html = apply_filters( 'class/Form/form_append/form_id=' . $this->p['form_id'], $html, $this->p );
					$html = apply_filters( 'class/Form/form_append/form_group=' . $this->p['form_group'], $html, $this->p );
					$html = apply_filters( 'class/Form/form_append', $html, $this->p );

				// }

			if ( false === $this->is_ajax_request ) {

				$html .= '</form>';
			}

			return $html;
		}

		public function get_form_fields() {


			/*if (
				! $this->is_request OR // on page load
				(
					! $this->is_valide OR // form not valide
					in_array( 'fields', $this->p['return'] ) AND $this->is_valide // form is valide but do not show fields
				)
			) {

			}*/

			if (
				$this->is_request AND
				$this->is_valide AND
				! in_array( 'fields', $this->p['return'] )
			) {

				return '';
			}

			$html = '';

			// ITERATE FORM ITEMS  {

				$pos = new ToolArrayPos( array(
					'array' => $this->items, // array to sort
					'param' => array(
						'pos_key' => 'pos_key', // item array key used for positioning by pos_before and pos_after
					),
				));

				$this->items = $pos->get();

				foreach ( $this->items as $key => $item ) {

					// IS CONTAINER {

						if ( ! empty( $item['container'] ) ) {

							$html = $this->get_container( $html, $item );
							continue;
						}

					// }

					// IS FIELDSET (DEPRICATED) {

						if (
							! empty( $item['legend'] ) AND
							empty( $item['type'] )
						) {

							$html = $this->get_fieldset( $html, $item );
							continue;
						}

					// }

					// IS FIELD WITHOUT FIELDSET {

						if (
							empty( $item['fieldset_id'] ) AND
							empty( $item['container_id'] )
						) {

							$html = $this->get_field( $html, $item );
							//continue;
						}

					// }
				}

			// }


			return $html;
		}

		// PRE FORM RENDER FUNCTIONALITY

		private function sanitize_request() {

			foreach ( $this->items as $item ) {

				if (
					! empty( $item['sanitize'] ) AND
					isset( $item['attrs_field']['name'] ) AND
					isset( $this->request[ $item['attrs_field']['name'] ] )
				) {

					if ( $item['type'] === 'text' ) {

						$this->request[ $item['attrs_field']['name'] ] = $this->sanitize_text_field( $this->request[ $item['attrs_field']['name'] ] );
					}
					elseif ( $item['type'] === 'email' ) {

						$this->request[ $item['attrs_field']['name'] ] = $this->sanitize_email_field( $this->request[ $item['attrs_field']['name'] ] );
					}
					elseif ( $item['type'] === 'textarea' ) {

						$this->request[ $item['attrs_field']['name'] ] = $this->sanitize_textarea_field( $this->request[ $item['attrs_field']['name'] ] );
					}
					elseif ( $item['type'] === 'checkboxes' ) {

						$this->request[ $item['attrs_field']['name'] ] = $this->sanitize_checkboxes_field( $this->request[ $item['attrs_field']['name'] ] );
					}
					elseif ( $item['type'] === 'checkbox' ) {

						$this->request[ $item['attrs_field']['name'] ] = $this->sanitize_checkboxes_field( $this->request[ $item['attrs_field']['name'] ] );
					}
					elseif ( $item['type'] === 'file' ) {

						$this->request[ $item['attrs_field']['name'] ] = $this->sanitize_file_field( $this->request[ $item['attrs_field']['name'] ] );
					}
					elseif ( $item['type'] === 'select' ) {

						$this->request[ $item['attrs_field']['name'] ] = $this->sanitize_select_field( $this->request[ $item['attrs_field']['name'] ] );
					}
				}
			}
		}

		private function validate_fields() {

			foreach ( $this->items as &$item ) {

				$item['validation_messages'] = array(
					'field' => array(),
					'form' => array(),
				);

				$validation_return = false;

				// REQUIRED {

					if ( ! empty( $item['required'] ) ) {

						$required = false;

						if ( empty( $this->request[ $item['attrs_field']['name'] ] ) ) {

							$required = true;
						}

						$required = apply_filters( 'class/Form/required', $required, $item );
						$required = apply_filters( 'class/Form/required/form_id=' . $this->p['form_id'] . '&type=' . $item['type'], $required, $item );

						if ( true === $required ) {

							$item['validation_messages']['field'][] = 'required';
							$item['validation_messages']['form'][] = 'field_validation_error';

							$this->has_messages['fields'] = true;
						}
					}

				// }

				// FIELDTYPE VALIDATION {

					if (
						! empty( $item['type'] ) AND
						! empty( $this->fieldtypes[ $item['type'] ]['validation'] ) AND
						isset( $this->request[ $item['attrs_field']['name'] ] )
					) {

						$validation_return = $this->fieldtypes[ $item['type'] ]['validation']( $this->request[ $item['attrs_field']['name'] ], $item );
					}

				// }

				// CUSTOM VALIDATIONS {

					if (
						empty( $validation_return ) AND
						! empty( $item['validation'] )
					) {

						// SET DEFAULT VALUE WHEN FOR EXAMPLE AN UNCHECKED CHECKBOX NOT SEND BY FORM {

							$value = '';

							if ( ! empty( $this->request[ $item['attrs_field']['name'] ] ) ) {

								$value = $this->request[ $item['attrs_field']['name'] ];
							}

						// }

						$validation_return = $item['validation']( $value );
					}

				// }

				// ADDS FIELD VALIDATION KEYS {

					if ( ! empty( $validation_return['field'] )  ) {

						$item['validation_messages']['field'] = array_replace_recursive( $item['validation_messages']['field'], $validation_return['field'] );
					}

					if ( ! empty( $validation_return['form'] )  ) {

						$item['validation_messages']['form'] = array_replace_recursive( $item['validation_messages']['form'], $validation_return['form'] );
					}

					if ( ! empty( $item['validation_messages']['field'] ) ) {

						$this->has_messages['fields'] = true;
						$this->is_valide = false;
					}

					if ( ! empty( $item['validation_messages']['form'] ) ) {

						$this->has_messages['form'] = true;
						$this->is_valide = false;
					}

				// }

			}
		}

		private function updates_field_values() {

			foreach ( $this->items as &$item ) {

				if (
					! empty( $item['attrs_field']['name'] ) AND
					isset( $this->request[ $item['attrs_field']['name'] ] )
				) {

					$item['request_value'] = $this->request[ $item['attrs_field']['name'] ];
				}
			}
		}

		private function gets_fields_form_message_keys() {

			if ( $this->request['form_id'] === $this->p['form_id'] ) {

				foreach ( $this->items as $item ) {

					if ( ! empty( $item['validation_messages']['form'] ) ) {

						$this->request_form_message_keys = array_replace_recursive( $this->request_form_message_keys, $item['validation_messages']['form'] );
					}
				}
			}
		}

		// RENDER FUNCTIONALITY

		public function gets_form_messages_html( $html ) {

			if ( empty( $this->request_form_message_keys ) ) {

				return $html;
			}

			$message_array = array();

			// Removes several times the same entries
			$this->request_form_message_keys = array_unique( $this->request_form_message_keys );

			foreach ( $this->request_form_message_keys as $value ) {

				if ( isset( $this->form_messages[ $value ] ) ) {

					if ( is_array( $this->form_messages[ $value ] ) ) {

						$message_array[] = get_lang_value_from_array( $this->form_messages[ $value ] );
					}

					if ( is_string( $this->form_messages[ $value ] ) ) {

						$message_array[] = _x( $this->form_messages[ $value ], 'Formular', 'tool_translate' );
					}
				}
				else if ( is_array( $value ) ) {

					$temp[] = tool( array(
						'name' => 'tool_get_lang_value_from_array',
						'param' => $value,
					) );
				}
				else {

					$message_array[] = $value;
				}
			}

			// REPLACES FIELD NAME PLACEHOLDERS IN FORM MESSAGES {

				if (
					! empty( $this->request ) AND
					! empty( $message_array )
				) {

					foreach ( $this->request as $key => $value ) {

						if ( ! is_string( $value ) ) {

							continue;
						}

						foreach ( $message_array as $key2 => $value2 ) {

							$message_array[ $key2 ] = str_replace( '{' . $key . '}', $value, $message_array[ $key2 ] );
						}
					}
				}

			// }

			if ( ! empty( $message_array ) ) {

				$id_type = 'form_message';

				if ( in_array( 'email_sent', $this->request_form_message_keys ) ) {

					$id_type = 'email_sent';
				}

				$html .= '<div id="' . $id_type . '-' . $this->request['form_id'] . '-' . $this->request['form_post_id'] . '" data-form-message="prepend-form">';
					$html .= '<ol><li>' . implode( '</li><li>', $message_array ) . '</li></ol>';
				$html .= '</div>';
			}

			return $html;
		}

		private function get_container( $html, $container ) {

			// DEFAULTS {

				$defaults = array(
					'id' => false,
					'attrs' => array(),
				);

				//$p = array_replace_recursive( $defaults, $p );
				$container = tool_merge_defaults( $container, $defaults );

			// }

			$html .= '<div' . attrs( $container['attrs'] ) . '>';

				foreach ( $this->items as $key => $item ) {

					if (
						! empty( $item['container_id'] ) AND
						$item['container_id'] === $container['id'] AND
						empty( $item['fieldset_id'] )
					) {

						$html = $this->get_field( $html, $item );
					}
				}

			$html .= '</div>';

			return $html;
		}

		private function get_fieldset( $html, $fieldset ) {

			// DEFAULTS {

				$defaults = array(
					'attrs_field' => array(),
				);

				//$p = array_replace_recursive( $defaults, $p );
				$fieldset = tool_merge_defaults( $fieldset, $defaults );

			// }

			$html .= '<fieldset' . attrs( $fieldset['attrs_field'] ) . '>';
			$html .= '<legend>' . $fieldset['legend']  . '</legend>';

				foreach ( $this->items as $key => $item ) {

					if (
						! empty( $item['fieldset_id'] ) AND
						$item['fieldset_id'] === $fieldset['id']
					) {

						$html = $this->get_field( $html, $item );
					}
				}

			$html .= '</fieldset>';

			return $html;
		}

		private function get_field( $html, $item ) {

			$attrs = array(
				'class' => array( 'form-field' ),
			);

			if ( empty( $item['type'] ) ) {

				$item['type'] = '';
			}

			$attrs['data-type'] = $item['type'];

			if ( ! empty( $item['attrs_field']['name'] ) ) {

				$attrs['data-name'] = $item['attrs_field']['name'];
			}

			if ( ! empty( $item['attrs_elem'] ) ) {

				$attrs = tool_merge_defaults( $item['attrs_elem'], $attrs );
			}

			if ( $item['type'] !== 'fieldset' ) {

				$attrs = apply_filters( 'class/Form/item_attrs', $attrs, $this->p );
				$attrs = apply_filters( 'class/Form/item_attrs/form_group=' . $this->p['form_group'], $attrs, $this->p );
				$attrs = apply_filters( 'class/Form/item_attrs/form_id=' . $this->p['form_id'], $attrs, $this->p );

				$item = apply_filters( 'class/Form/item_param/type=' . $item['type'], $item, $this->p );

				$html_item = apply_filters( 'class/Form/before_item', '<div' . attrs( $attrs ) . '>', $this->p, $item, $attrs );
				$html .= apply_filters( 'class/Form/before_item/form_group=' . $this->p['form_group'], $html_item, $this->p );
			}

			$html = apply_filters( 'class/Form/get_fields_html/field_type=' . $item['type'], $html, $item );

			if ( $item['type'] !== 'fieldset' ) {

				$html_item = apply_filters( 'class/Form/after_item', '</div>', $this->p, $item, $attrs );
				$html .= apply_filters( 'class/Form/after_item/form_group=' . $this->p['form_group'], $html_item, $this->p );
			}

			return $html;
		}

		// FIELD TYPES

		public function init_info_field( $p = array() ) {

			add_filter( 'class/Form/add_fieldtype', function( $fieldtypes ) {

				$fieldtypes['info'] = array(
					'default_param' => array(
						'content' => '',
						'template' => array(
							'{before_field}',
							'{content}',
							'{after_field}',
						),
					),
				);

				return $fieldtypes;
			});

			add_filter( 'class/Form/get_fields_html/field_type=info', function( $html, $item ) {

				$p = array_replace_recursive( $this->fieldtypes['info']['default_param'], $item );

				// FILTER FIELD PARAM {

					$p = apply_filters( 'class/Form/field_parameters', $p );
					$p = apply_filters( 'class/Form/field_parameters/form_group=' . $this->p['form_group'], $p );

				// }

				// TEMPLATE {

					$template_data = array();

					if ( ! empty( $p['content'] ) ) {

						$template_data['content'] = $p['content'];
					}

					$html .= $this->do_field_template( $p['template'], $template_data, $p );

				// }

				return $html;

			}, 10, 2 );
		}

		public function init_text_field( $p = array() ) {

			add_filter( 'class/Form/add_fieldtype', function( $fieldtypes ) {

				$fieldtypes['text'] = array(
					'default_param' => array(
						'label' => '',
						'attrs_label' => array(),
						'attrs_field' => array(
							'name' => '',
							'value' => '',
						),
						'required' => false,
						'value' => '',
						'sanitize' => true,
						'template' => array(
							'{label}',
							'{description}',
							'{before_field}',
							'{field}',
							'{after_field}',
							'{validation}',
						),
					),
					'validation' => false,
				);

				return $fieldtypes;
			});

			add_filter( 'class/Form/get_fields_html/field_type=text', function( $html, $item ) {

				// DEFAULTS {

					$p = array_replace_recursive( $this->fieldtypes['text']['default_param'], $item );

				// }

				// FILTER FIELD PARAM {

					$p = apply_filters( 'class/Form/field_parameters', $p );
					$p = apply_filters( 'class/Form/field_parameters/form_group=' . $this->p['form_group'], $p );

				// }

				// REQUEST VALUE {

					if ( isset( $p['request_value'] ) ) {

						$p['attrs_field']['value'] = $p['request_value'];
					}

				// }

				// ATTRS LABEL {

					$attrs_label_defaults = array(
						'for' => $p['attrs_field']['name'],
					);

					$p['attrs_label'] = array_replace_recursive( $attrs_label_defaults, $p['attrs_label'] );
					$p['attrs_label']['for'] = $p['attrs_label']['for'] . ':' . newid();

				// }

				// ATTRS FIELD {

					$attrs_field_defaults = array(
						'type' => 'text',
						'id' => $p['attrs_field']['name'],
						'name' => $p['attrs_field']['name'],
						'class' => array(),
					);

					$p['attrs_field'] = array_replace_recursive( $attrs_field_defaults, $p['attrs_field'] );
					$p['attrs_field']['id'] = $p['attrs_label']['for'];

					if ( $p['required'] === true ) {

						$p['attrs_field']['required'] = true;
						$p['attrs_field']['class'][] = 'required';
					}

				// }

				// TEMPLATE {

					$template_data = array();

					if ( ! empty( $p['label'] ) ) {

						$template_data['label'] = '<label' . attrs( $p['attrs_label'] ) . '>' . $p['label'] . '</label>';
					}

					$template_data['field'] = '<input' . attrs( $p['attrs_field'] ) . '>';

					$html .= $this->do_field_template( $p['template'], $template_data, $p );

				// }

				return $html;
			}, 10, 2 );

		}

		public function init_search_field( $p = array() ) {

			add_filter( 'class/Form/add_fieldtype', function( $fieldtypes ) {

				$fieldtypes['search'] = array(
					'default_param' => array(
						'label' => '',
						'attrs_label' => array(),
						'attrs_field' => array(
							'name' => '',
							'value' => '',
						),
						'required' => false,
						'value' => '',
						'sanitize' => true,
						'template' => array(
							'{label}',
							'{description}',
							'{before_field}',
							'{field}',
							'{after_field}',
							'{validation}',
						),
					),
					'validation' => false,
				);

				return $fieldtypes;
			});

			add_filter( 'class/Form/get_fields_html/field_type=search', function( $html, $item ) {

				// DEFAULTS {

					$p = array_replace_recursive( $this->fieldtypes['search']['default_param'], $item );

				// }

				// FILTER FIELD PARAM {

					$p = apply_filters( 'class/Form/field_parameters', $p );
					$p = apply_filters( 'class/Form/field_parameters/form_group=' . $this->p['form_group'], $p );

				// }

				// REQUEST VALUE {

					if ( isset( $p['request_value'] ) ) {

						$p['attrs_field']['value'] = $p['request_value'];
					}

				// }

				// ATTRS LABEL {

					$attrs_label_defaults = array(
						'for' => $p['attrs_field']['name'],
					);

					$p['attrs_label'] = array_replace_recursive( $attrs_label_defaults, $p['attrs_label'] );
					$p['attrs_label']['for'] = $p['attrs_label']['for'] . ':' . newid();

				// }

				// ATTRS FIELD {

					$attrs_field_defaults = array(
						'type' => 'search',
						'id' => $p['attrs_field']['name'],
						'name' => $p['attrs_field']['name'],
						'class' => array(),
					);

					$p['attrs_field'] = array_replace_recursive( $attrs_field_defaults, $p['attrs_field'] );
					$p['attrs_field']['id'] = $p['attrs_label']['for'];

					if ( $p['required'] === true ) {

						$p['attrs_field']['required'] = true;
						$p['attrs_field']['class'][] = 'required';
					}

				// }

				// TEMPLATE {

					$template_data = array();

					if ( ! empty( $p['label'] ) ) {

						$template_data['label'] = '<label' . attrs( $p['attrs_label'] ) . '>' . $p['label'] . '</label>';
					}

					$template_data['field'] = '<input' . attrs( $p['attrs_field'] ) . '>';

					$html .= $this->do_field_template( $p['template'], $template_data, $p );

				// }

				return $html;
			}, 10, 2 );

		}

		public function init_date_field( $p = array() ) {

			add_filter( 'class/Form/add_fieldtype', function( $fieldtypes ) {

				$fieldtypes['date'] = array(
					'default_param' => array(
						'label' => '',
						'attrs_label' => array(),
						'attrs_field' => array(
							'name' => '',
							'value' => '',
							'pattern' => '\d{4}-\d{2}-\d{2}',
						),
						'required' => false,
						'value' => '',
						'sanitize' => true,
						'template' => array(
							'{label}',
							'{description}',
							'{before_field}',
							'{field}',
							'{after_field}',
							'{validation}',
						),
					),
					'validation' => false,
				);

				return $fieldtypes;
			});

			add_filter( 'class/Form/get_fields_html/field_type=date', function( $html, $item ) {

				// DEFAULTS {

					$p = array_replace_recursive( $this->fieldtypes['date']['default_param'], $item );

				// }

				// FILTER FIELD PARAM {

					$p = apply_filters( 'class/Form/field_parameters', $p );
					$p = apply_filters( 'class/Form/field_parameters/form_group=' . $this->p['form_group'], $p );

				// }

				// REQUEST VALUE {

					if ( isset( $p['request_value'] ) ) {

						$p['attrs_field']['value'] = $p['request_value'];
					}

				// }

				// ATTRS LABEL {

					$attrs_label_defaults = array(
						'for' => $p['attrs_field']['name'],
					);

					$p['attrs_label'] = array_replace_recursive( $attrs_label_defaults, $p['attrs_label'] );
					$p['attrs_label']['for'] = $p['attrs_label']['for'] . ':' . newid();

				// }

				// ATTRS FIELD {

					$attrs_field_defaults = array(
						'type' => 'date',
						'id' => $p['attrs_field']['name'],
						'name' => $p['attrs_field']['name'],
						'class' => array(),
					);

					$p['attrs_field'] = array_replace_recursive( $attrs_field_defaults, $p['attrs_field'] );
					$p['attrs_field']['id'] = $p['attrs_label']['for'];

					if ( $p['required'] === true ) {

						$p['attrs_field']['required'] = true;
						$p['attrs_field']['class'][] = 'required';
					}

				// }

				// TEMPLATE {

					$template_data = array();

					if ( ! empty( $p['label'] ) ) {

						$template_data['label'] = '<label' . attrs( $p['attrs_label'] ) . '>' . $p['label'] . '</label>';
					}

					$template_data['field'] = '<input' . attrs( $p['attrs_field'] ) . '>';

					$html .= $this->do_field_template( $p['template'], $template_data, $p );

				// }

				return $html;
			}, 10, 2 );

		}

		public function init_password_field( $p = array() ) {

			add_filter( 'class/Form/add_fieldtype', function( $fieldtypes ) {

				$fieldtypes['password'] = array(
					'default_param' => array(
						'label' => '',
						'attrs_label' => array(),
						'attrs_field' => array(
							'name' => '',
							'value' => '',
						),
						'required' => false,
						'value' => '',
						'sanitize' => true,
						'template' => array(
							'{label}',
							'{description}',
							'{before_field}',
							'{field}',
							'{after_field}',
							'{validation}',
						),
					),
					'validation' => false,
				);

				return $fieldtypes;
			});

			add_filter( 'class/Form/get_fields_html/field_type=password', function( $html, $item ) {

				// DEFAULTS {

					$p = array_replace_recursive( $this->fieldtypes['password']['default_param'], $item );

				// }

				// FILTER FIELD PARAM {

					$p = apply_filters( 'class/Form/field_parameters', $p );
					$p = apply_filters( 'class/Form/field_parameters/form_group=' . $this->p['form_group'], $p );

				// }

				// REQUEST VALUE {

					if ( isset( $p['request_value'] ) ) {

						$p['attrs_field']['value'] = $p['request_value'];
					}

				// }

				// ATTRS LABEL {

					$attrs_label_defaults = array(
						'for' => $p['attrs_field']['name'],
					);

					$p['attrs_label'] = array_replace_recursive( $attrs_label_defaults, $p['attrs_label'] );
					$p['attrs_label']['for'] = $p['attrs_label']['for'] . ':' . newid();

				// }

				// ATTRS FIELD {

					$attrs_field_defaults = array(
						'type' => 'password',
						'id' => $p['attrs_field']['name'],
						'name' => $p['attrs_field']['name'],
						'class' => array(),
					);

					$p['attrs_field'] = array_replace_recursive( $attrs_field_defaults, $p['attrs_field'] );
					$p['attrs_field']['id'] = $p['attrs_label']['for'];

					if ( $p['required'] === true ) {

						$p['attrs_field']['required'] = true;
						$p['attrs_field']['class'][] = 'required';
					}

				// }

				// TEMPLATE {

					$template_data = array();

					if ( ! empty( $p['label'] ) ) {

						$template_data['label'] = '<label' . attrs( $p['attrs_label'] ) . '>' . $p['label'] . '</label>';
					}

					$template_data['field'] = '<input' . attrs( $p['attrs_field'] ) . '>';

					$html .= $this->do_field_template( $p['template'], $template_data, $p );

				// }

				return $html;
			}, 10, 2 );

		}

		public function init_textarea_field( $p = array() ) {

			add_filter( 'class/Form/add_fieldtype', function( $fieldtypes ) {

				$fieldtypes['textarea'] = array(
					'default_param' => array(
						'label' => '',
						'attrs_label' => array(),
						'attrs_field' => array(
							'name' => '',
						),
						'required' => false,
						'validation' => false,
						'value' => '',
						'sanitize' => true,
						'template' => array(
							'{label}',
							'{description}',
							'{before_field}',
							'{field}',
							'{after_field}',
							'{validation}',
						),
					),
					'validation' => false,
				);

				return $fieldtypes;
			});

			add_filter( 'class/Form/get_fields_html/field_type=textarea', function( $html, $item ) {

				// DEFAULTS {

					$p = array_replace_recursive( $this->fieldtypes['textarea']['default_param'], $item );

				// }

				// FILTER FIELD PARAM {

					$p = apply_filters( 'class/Form/field_parameters', $p );
					$p = apply_filters( 'class/Form/field_parameters/form_group=' . $this->p['form_group'], $p );

				// }

				// ATTRS LABEL {

					$attrs_label_defaults = array(
						'for' => $p['attrs_field']['name'],
					);

					$p['attrs_label'] = array_replace_recursive( $attrs_label_defaults, $p['attrs_label'] );
					$p['attrs_label']['for'] = $p['attrs_label']['for'] . ':' . newid();

				// }

				// ATTRS FIELD {

					$attrs_field_defaults = array(
						'id' => $p['attrs_field']['name'],
						'name' => $p['attrs_field']['name'],
						'class' => array(),
					);

					$p['attrs_field'] = array_replace_recursive( $attrs_field_defaults, $p['attrs_field'] );
					$p['attrs_field']['id'] = $p['attrs_label']['for'];

					if ( $p['required'] === true ) {

						$p['attrs_field']['required'] = true;
						$p['attrs_field']['class'][] = 'required';
					}

				// }

				// TEMPLATE {

					$template_data = array();

					if ( ! empty( $p['label'] ) ) {

						$template_data['label'] = '<label' . attrs( $p['attrs_label'] ) . '>' . $p['label'] . '</label>';
					}

					$template_data['field'] = '<textarea' . attrs( $p['attrs_field'] ) . '>';

						if ( ! empty( $p['request_value'] ) ) {

							$template_data['field'] .= $p['request_value'];
						}

					$template_data['field'] .= '</textarea>';

					$html .= $this->do_field_template( $p['template'], $template_data, $p );

				// }

				return $html;
			}, 10, 2 );

		}

		public function init_radio_field( $p = array() ) {

			add_filter( 'class/Form/add_fieldtype', function( $fieldtypes ) {

				$fieldtypes['radio'] = array(
					'default_param' => array(
						'label' => '',
						'before_field' => false,
						'after_field' => false,
						'before_radio' => false,
						'after_radio' => false,
						'attrs_label' => array(

						),
						'attrs_field' => array(
							'name' => '',
							'value' => '',
						),
						'radio' => array(

						),
						'radio_layout' => 'align-vertical', // align-horizontal
						'required' => false,
						'validation' => false,
						'value' => '',
						'sanitize' => true,
						'template' => array(
							'{label}',
							'{description}',
							'{before_field}',
							'{field}',
							'{after_field}',
							'{validation}',
						),
						'value_divider' => ', ', // used by putting values into an email
					),
					'validation' => array(),
				);

				return $fieldtypes;
			});

			add_filter( 'class/Form/get_fields_html/field_type=radio', function( $html, $item ) {

				$p = array_replace_recursive( $this->fieldtypes['radio']['default_param'], $item );

				// FILTER FIELD PARAM {

					$p = apply_filters( 'class/Form/field_parameters', $p );
					$p = apply_filters( 'class/Form/field_parameters/form_group=' . $this->p['form_group'], $p );

				// }

				// ATTRS FIELD {

					$attrs_field_defaults = array(
						'type' => 'radio',
						'id' => false,
						'name' => $p['attrs_field']['name'],
						'class' => array(),
					);

					$p['attrs_field'] = array_replace_recursive( $attrs_field_defaults, $p['attrs_field'] );

					// ID {

						$p['attrs_field']['id'] = $p['attrs_field']['name'];

						if ( ! $p['attrs_field']['id'] ) {

							$p['attrs_field']['id'] = $p['attrs_field']['name'];
						}

						$p['attrs_field']['id'] = $p['attrs_field']['id'] . ':' . newid();

					// }

				// }

				// TEMPLATE {

					$template_data = array();

					if ( ! empty( $p['label'] ) ) {

						$template_data['label'] = '<label' . attrs( $p['attrs_label'] ) . '>' . $p['label'] . '</label>';
					}

					// RADIO LIST {

						$template_data['field'] = '<ul class="' . $p['radio_layout'] . '">';

						if ( ! empty( $p['radio'] ) ) {

							foreach ( $p['radio'] as $key => $item ) {

								$field_key = '';

								if ( isset( $item['attrs_field']['name'] ) ) {

									$field_key = $item['attrs_field']['name'];
									unset( $item['attrs_field']['name'] );
								}

								$attrs_field = array_replace_recursive( $p['attrs_field'], $item['attrs_field'], );

								$attrs_field['id'] = $p['attrs_field']['name'] . ':' . $key . ':' . newid();

								// NAME {

									$attrs_field['name'] = $p['attrs_field']['name'] . '[' . $field_key . ']';

								// }

								// CHECKED {

									if (
										false !== $this->request AND
										isset( $p['request_value'] )
									) {

										$attrs_field['checked'] = false;

										if (
											'' === $field_key AND
											in_array( $item['attrs_field']['value'], $p['request_value'] )
										) {

											$attrs_field['checked'] = true;
										}

										if ( '' !== $field_key ) {

											if ( isset( $this->request[ $p['attrs_field']['name'] ][ $field_key ] ) ) {

												$attrs_field['checked'] = true;
											}
										}
									}

								// }

								// REQUIRED {

									if ( $p['required'] === true ) {

										$attrs_field['required'] = true;
									}

								// }

								$template_data['field'] .= '<li>';

									// PREPEND CHECKBOXES ITEM {

										$template_data['field'] = apply_filters( 'class/Form/prepend_radio_item', $template_data['field'], $p );

									// }

									$template_data['field'] .= '<label>';

									// BEFORE {

										$before_radio = $p['before_radio'];

										if ( ! empty( $item['before_radio'] ) ) {

											$before_radio = $item['before_radio'];
										}

										if ( ! empty( $before_radio ) ) {

											$before_radio = apply_filters( 'class/Form/before_radio', '<span>' . $before_radio . '</span>&nbsp;', $p );
											$before_radio = apply_filters( 'class/Form/before_radio/form_group=' . $this->p['form_group'], $before_radio, $p );
											$before_radio = apply_filters( 'class/Form/before_radio/form_id=' . $this->p['form_group'], $before_radio, $p );

											$template_data['field'] .= $before_radio;
										}

									// }

									// INPUT {

										$template_data['field'] .= '<input' . attrs( $attrs_field ) . '>';

									// }

									// AFTER {

										$after_radio = $p['after_radio'];

										if ( ! empty( $item['after_radio'] ) ) {

											$after_radio = $item['after_radio'];
										}

										if ( ! empty( $after_radio ) ) {

											$after_radio = apply_filters( 'class/Form/after_checkbox', '&nbsp;<span>' . $after_radio . '</span>', $p );
											$after_radio = apply_filters( 'class/Form/after_checkbox/form_group=' . $this->p['form_group'], $after_radio, $p );
											$after_radio = apply_filters( 'class/Form/after_checkbox/form_id=' . $this->p['form_id'], $after_radio, $p );

											$template_data['field'] .= $after_radio;
										}

									// }

									$template_data['field'] .= '</label>';

									// APPEND CHECKBOXES ITEM {

										$template_data['field'] = apply_filters( 'class/Form/append_radio_item', $template_data['field'], $p );

									// }

								$template_data['field'] .= '</li>';
							}
						}

						$template_data['field'] .= '</ul>';

					// }

					$html .= $this->do_field_template( $p['template'], $template_data, $p );

				// }

				return $html;

			}, 10, 2 );
		}

		public function init_checkbox_field( $p = array() ) {

			add_filter( 'class/Form/add_fieldtype', function( $fieldtypes ) {

				$fieldtypes['checkbox'] = array(
					'default_param' => array(
						'label' => '',
						'before_field' => false,
						'after_field' => false,
						'custom_checkbox' => '<label class="custom-checkbox-wrap" for="{for}">{before_checkbox}<span class="custom-checkbox"></span>{after_checkbox}</label>',
						'before_checkbox' => false,
						'after_checkbox' => false,
						'attrs_label' => array(

						),
						'attrs_field' => array(
							'name' => '',
							'value' => '',
						),
						'required' => false,
						'validation' => false,
						'value' => '',
						'sanitize' => true,
						'template' => array(
							'{label}',
							'{description}',
							'{before_field}',
							'{field}',
							'{after_field}',
							'{validation}',
						),
					),
					'validation' => array(),
				);

				return $fieldtypes;
			});

			add_filter( 'class/Form/get_fields_html/field_type=checkbox', function( $html, $item ) {

				$p = array_replace_recursive( $this->fieldtypes['checkbox']['default_param'], $item );

				// FILTER FIELD PARAM {

					$p = apply_filters( 'class/Form/field_parameters', $p );
					$p = apply_filters( 'class/Form/field_parameters/form_group=' . $this->p['form_group'], $p );

				// }

				// REQUEST VALUE {

					if ( isset( $p['request_value'] ) ) {

						$p['attrs_field']['checked'] = true;
					}

				// }

				// REQUIRED {

					if ( $p['required'] === true ) {

						$p['attrs_field']['required'] = true;
					}

				// }

				// ATTRS FIELD {

					$attrs_field_defaults = array(
						'type' => 'checkbox',
						'id' => false,
						'name' => $p['attrs_field']['name'],
						'class' => array(),
					);

					$p['attrs_field'] = array_replace_recursive( $attrs_field_defaults, $p['attrs_field'] );

					if ( ! $p['attrs_field']['id'] ) {

						$p['attrs_field']['id'] = $p['attrs_field']['name'];
					}

					$p['attrs_field']['id'] = $p['attrs_field']['id'] . ':' . newid();

				// }

				// ATTRS LABEL {

					$attrs_label_defaults = array(
						'for' => $p['attrs_field']['id'],
					);

					$p['attrs_label'] = array_replace_recursive( $attrs_label_defaults, $p['attrs_label'] );
					$p['attrs_label']['for'] = $p['attrs_label']['for'] . ':' . newid();

				// }

				// TEMPLATE {

					$template_data = array();

					if ( ! empty( $p['label'] ) ) {

						$template_data['label'] = '<label' . attrs( $p['attrs_label'] ) . '>' . $p['label'] . '</label>';
					}

					$template_data['field'] = '<input' . attrs( $p['attrs_field'] ) . '>';

					if ( $p['custom_checkbox'] ) {

						$p['custom_checkbox'] = str_replace( '{for}', $p['attrs_field']['id'], $p['custom_checkbox'] );

						if ( ! empty( $p['before_checkbox'] ) ) {

							$before_checkbox = apply_filters( 'class/Form/before_checkbox', '<span>' . $p['before_checkbox'] . '</span>&nbsp;', $p );
							$before_checkbox = apply_filters( 'class/Form/before_checkbox/form_group=' . $this->p['form_group'], $before_checkbox, $p );
							$before_checkbox = apply_filters( 'class/Form/before_checkbox/form_id=' . $this->p['form_group'], $before_checkbox, $p );

							$p['custom_checkbox'] = str_replace( '{before_checkbox}', $before_checkbox, $p['custom_checkbox'] );
						}
						else {
							$p['custom_checkbox'] = str_replace( '{before_checkbox}', '', $p['custom_checkbox'] );
						}

						if ( ! empty( $p['after_checkbox'] ) ) {

							$after_checkbox = apply_filters( 'class/Form/after_checkbox', '&nbsp;<span>' . $p['after_checkbox'] . '</span>', $p );
							$after_checkbox = apply_filters( 'class/Form/after_checkbox/form_group=' . $this->p['form_group'], $after_checkbox, $p );
							$after_checkbox = apply_filters( 'class/Form/after_checkbox/form_id=' . $this->p['form_id'], $after_checkbox, $p );

							$p['custom_checkbox'] = str_replace( '{after_checkbox}', $after_checkbox, $p['custom_checkbox'] );
						}
						else {
							$p['custom_checkbox'] = str_replace( '{after_checkbox}', '', $p['custom_checkbox'] );
						}

						$template_data['field'] .= $p['custom_checkbox'];
					}

					$html .= $this->do_field_template( $p['template'], $template_data, $p );

				// }

				return $html;

			}, 10, 2 );

		}

		public function init_checkboxes_field( $p = array() ) {

			add_filter( 'class/Form/add_fieldtype', function( $fieldtypes ) {

				$fieldtypes['checkboxes'] = array(
					'default_param' => array(
						'label' => '',
						'before_field' => false,
						'after_field' => false,
						'custom_checkbox' => '<label class="custom-checkbox-wrap" for="{for}">{before_checkbox}<span class="custom-checkbox"></span>{after_checkbox}</label>',
						'before_checkbox' => false,
						'after_checkbox' => false,
						'attrs_label' => array(

						),
						'attrs_field' => array(
							'name' => '',
							'value' => '',
						),
						'checkboxes' => array(

						),
						'checkbox_layout' => 'align-vertical', // align-horizontal
						'required' => false,
						'validation' => false,
						'value' => '',
						'sanitize' => true,
						'template' => array(
							'{label}',
							'{description}',
							'{before_field}',
							'{field}',
							'{after_field}',
							'{validation}',
						),
						'value_divider' => ', ', // used by putting values into an email
					),
					'validation' => array(),
				);

				return $fieldtypes;
			});

			add_filter( 'class/Form/get_fields_html/field_type=checkboxes', function( $html, $item ) {

				$p = array_replace_recursive( $this->fieldtypes['checkboxes']['default_param'], $item );

				// FILTER FIELD PARAM {

					$p = apply_filters( 'class/Form/field_parameters', $p );
					$p = apply_filters( 'class/Form/field_parameters/form_group=' . $this->p['form_group'], $p );

				// }

				// ATTRS FIELD {

					$attrs_field_defaults = array(
						'type' => 'checkbox',
						'id' => false,
						'name' => $p['attrs_field']['name'],
						'class' => array(),
					);

					$p['attrs_field'] = array_replace_recursive( $attrs_field_defaults, $p['attrs_field'] );

					// ID {

						$p['attrs_field']['id'] = $p['attrs_field']['name'];

						if ( ! $p['attrs_field']['id'] ) {

							$p['attrs_field']['id'] = $p['attrs_field']['name'];
						}

						$p['attrs_field']['id'] = $p['attrs_field']['id'] . ':' . newid();

					// }

				// }

				// TEMPLATE {

					$template_data = array();

					if ( ! empty( $p['label'] ) ) {

						$template_data['label'] = '<label' . attrs( $p['attrs_label'] ) . '>' . $p['label'] . '</label>';
					}

					// CHECKBOXES {

						$template_data['field'] = '<ul class="' . $p['checkbox_layout'] . '">';

						if ( ! empty( $p['checkboxes'] ) ) {

							foreach ( $p['checkboxes'] as $key => $item ) {

								$field_key = '';

								if ( isset( $item['attrs_field']['name'] ) ) {

									$field_key = $item['attrs_field']['name'];
									unset( $item['attrs_field']['name'] );
								}

								$attrs_field = array_replace_recursive( $p['attrs_field'], $item['attrs_field'], );

								$attrs_field['id'] = $p['attrs_field']['name'] . ':' . $key . ':' . newid();

								// INDIVIDUALIZE FIELD ID {

									if (
										! empty( $field_key )
									) {

										$attrs_field['id'] = $attrs_field['id'] . ':' . $field_key ;
									}

								// }

								// NAME {

									$attrs_field['name'] = $p['attrs_field']['name'] . '[' . $field_key . ']';

								// }

								// CHECKED {

									if (
										false !== $this->request AND
										isset( $p['request_value'] )
									) {

										$attrs_field['checked'] = false;

										if (
											'' === $field_key AND
											in_array( $item['attrs_field']['value'], $p['request_value'] )
										) {

											$attrs_field['checked'] = true;
										}

										if ( '' !== $field_key ) {

											if ( isset( $this->request[ $p['attrs_field']['name'] ][ $field_key ] ) ) {

												$attrs_field['checked'] = true;
											}
										}

									}

								// }

								$template_data['field'] .= '<li>';

									// PREPEND CHECKBOXES ITEM {

										$template_data['field'] = apply_filters( 'class/Form/prepend_checkboxes_item', $template_data['field'], $p );

									// }

									// INPUT {

										$template_data['field'] .= '<input' . attrs( $attrs_field ) . '>';

									// }

									// CUSTOM CHECKBOX {

										if ( $p['custom_checkbox'] ) {

											$custom_checkbox = str_replace( '{for}', $attrs_field['id'], $p['custom_checkbox'] );

											$before_checkbox = $p['before_checkbox'];

											if ( ! empty( $item['before_checkbox'] ) ) {

												$before_checkbox = $item['before_checkbox'];
											}

											if ( ! empty( $before_checkbox ) ) {

												$before_checkbox = apply_filters( 'class/Form/before_checkbox', '<span>' . $before_checkbox . '</span>&nbsp;', $p );
												$before_checkbox = apply_filters( 'class/Form/before_checkbox/form_group=' . $this->p['form_group'], $before_checkbox, $p );
												$before_checkbox = apply_filters( 'class/Form/before_checkbox/form_id=' . $this->p['form_group'], $before_checkbox, $p );

												$custom_checkbox = str_replace( '{before_checkbox}', $before_checkbox, $custom_checkbox );
											}
											else {

												$custom_checkbox = str_replace( '{before_checkbox}', '', $custom_checkbox );
											}

											$after_checkbox = $p['after_checkbox'];

											if ( ! empty( $item['after_checkbox'] ) ) {

												$after_checkbox = $item['after_checkbox'];
											}

											if ( ! empty( $after_checkbox ) ) {

												$after_checkbox = apply_filters( 'class/Form/after_checkbox', '&nbsp;<span>' . $after_checkbox . '</span>', $p );
												$after_checkbox = apply_filters( 'class/Form/after_checkbox/form_group=' . $this->p['form_group'], $after_checkbox, $p );
												$after_checkbox = apply_filters( 'class/Form/after_checkbox/form_id=' . $this->p['form_id'], $after_checkbox, $p );

												$custom_checkbox = str_replace( '{after_checkbox}', $after_checkbox, $custom_checkbox );
											}
											else {

												$custom_checkbox = str_replace( '{after_checkbox}', '', $custom_checkbox );
											}

											$template_data['field'] .= $custom_checkbox;
										}

									// }

									// APPEND CHECKBOXES ITEM {

										$template_data['field'] = apply_filters( 'class/Form/append_checkboxes_item', $template_data['field'], $p );

									// }

								$template_data['field'] .= '</li>';
							}
						}

						$template_data['field'] .= '</ul>';

					// }

					$html .= $this->do_field_template( $p['template'], $template_data, $p );

				// }

				return $html;

			}, 10, 2 );
		}

		public function filter_checkboxes_custom_field_key_required( $requires, $field ) {

			foreach ( $field['checkboxes'] as $item ) {

				if ( isset( $item['attrs_field']['name'] ) ) {

					if ( ! isset( $this->request[ $field['attrs_field']['name'] ][ $item['attrs_field']['name'] ] ) ) {

						$requires = true;
					}
				}
				else {

					if (
						isset ( $this->request[ $field['attrs_field']['name'] ] ) AND
						! in_array( $item['attrs_field']['value'], $this->request[ $field['attrs_field']['name'] ] )
					) {

						$requires = true;
					}
				}
			}

			return $requires;
		}

		public function init_taxonomy_select_field( $p = array() ) {

			/* USAGE

				Returns a list element with list items containing taxonomy links

				get_taxonomy_select_field( array(
					'current_term_value' => '', // optional, if unset/false, then requires 'form_id' for auto setting 'current_term_value',
					'taxonomy' => 'category',
					'value_type' => 'permalink', // permalink, term_id
					'event' => array(
						'on_change' => 'change_location', // change_location, submit_form
					),
					'hide_empty' => false,
					'attrs_field' => array(
						'name' => 'category_term_id', // name of select field
						'value' => '',
					),
				));

			*/

			add_filter( 'class/Form/add_fieldtype', function( $fieldtypes ) {

				$fieldtypes['taxonomy_select'] = array(
					'default_param' => array(
						'taxonomy' => 'category',
						'hide_empty' => false,
						'value_type' => 'permalink', // permalink, term_id
						'event' => array(
							'on_change' => false, // change_location, submit_form
						),
						'attrs_label' => array(),
						'attrs_field' => array(
							'name' => '', // name of select field
							'value' => '', // name of select field
						),
						'sanitize' => true,
						'allow_null' => array(
							'label' => array(
								'default' => 'Choose…', // list of locales
							),
							'value' => '',
						),
						'template' => array(
							'{label}',
							'{description}',
							'{before_field}',
							'{field}',
							'{after_field}',
							'{validation}',
						),
					),
					'validation' => array(),
				);

				return $fieldtypes;
			});

			add_filter( 'class/Form/get_fields_html/field_type=taxonomy_select', function( $html, $item ) {

				$p = array_replace_recursive( $this->fieldtypes['taxonomy_select']['default_param'], $item );

				// FILTER FIELD PARAM {

					$p = apply_filters( 'class/Form/field_parameters', $p );
					$p = apply_filters( 'class/Form/field_parameters/form_group=' . $this->p['form_group'], $p );

				// }

				// REQUEST VALUE {

					if ( isset( $p['request_value'] ) ) {

						$p['attrs_field']['value'] = $p['request_value'];
					}

				// }

				// GET CURRENT TERM VALUE {

					$p['current_term_value'] = $p['attrs_field']['value'];

				// }

				// ATTRS LABEL {

					$attrs_label_defaults = array(
						'for' => $p['attrs_field']['name'],
					);

					$p['attrs_label'] = array_replace_recursive( $attrs_label_defaults, $p['attrs_label'] );
					$p['attrs_label']['for'] = $p['attrs_label']['for'] . ':' . newid();

				// }

				// ATTRS FIELD {

					$attrs_field_defaults = array(
						'id' => $p['attrs_field']['name'],
						'name' => $p['attrs_field']['name'],
						'class' => array(),
					);

					$p['attrs_field'] = array_replace_recursive( $attrs_field_defaults, $p['attrs_field'] );
					$p['attrs_field']['id'] = $p['attrs_label']['for'];

					if ( $p['required'] === true ) {

						$p['attrs_field']['required'] = true;
						$p['attrs_field']['class'][] = 'required';
					}

				// }

				// GET TERMS OF POST TAGS {

					$terms = get_terms( array(
						'taxonomy' => $p['taxonomy'],
						'hide_empty' => $p['hide_empty'],
					) );

					if ( is_wp_error( $terms ) ) {

						error_log( print_r( array( 'WordPress Error in: ' . __FILE__, $terms ), true) );

						return '';
					}

				// }

				// GETS OPTIONS LIST {

					$list = array();

					// ALLOW NULL {

						if ( ! empty( $p['allow_null'] ) ) {

							$attrs = array(
								'value' => $p['allow_null']['value'],
							);

							if ( $p['current_term_value'] == $p['allow_null']['value'] ) {

								$attrs['selected'] = 'selected';
							}

							$label = tool( array(
								'name' => 'tool_get_lang_value_from_array',
								'param' => $p['allow_null']['label'],
							) );

							array_push( $list, '<option' . attrs( $attrs ) . '>'. $label . '</option>' );
						}

					// }

					foreach ( $terms as $item ) {

						// SELECT OPTION ATTRIBUTES {

							$attrs = array(
								'value' => false,
							);

						// }

						// TRANSLATES TERM NAME {

							if ( $GLOBALS['toolset']['multilanguage'] ) {

								$name = get_lang_field( 'lang_taxonomy/name', 'term_' . $item->term_id );

								if ( ! empty( $name ) ) {

									$item->name = $name;
								}
							}

						// }

						// SELECTED {

							if (
								$p['value_type'] === 'term_id' AND
								$p['current_term_value'] == $item->term_id
							) {

								$attrs['selected'] = 'selected';
							}

							if (
								$p['value_type'] === 'permalink' AND
								$p['current_term_value'] == get_term_link( $item->term_id )
							) {

								$attrs['selected'] = 'selected';
							}

						// }

						// SETS VALUE {

							$value = '';

							if ( $p['value_type'] === 'term_id' ) {

								$attrs['value'] = $item->term_id;
							}

							if ( $p['value_type'] === 'permalink' ) {

								$attrs['value'] = get_term_link( $item->term_id );
							}

						// }

						array_push( $list, '<option' . attrs( $attrs ) . '>'. $item->name . '</option>' );
					}

				// }

				// ADDS EVENTS {

					// ADDS CHANGE LOCATION ON CHANGE {

						if ( $p['event']['on_change'] === 'change_location' ) {

							$p['attrs_field']['class'][] = 'js-select-field-event-change-location';
						}

					// }

					// ADDS SUBMIT FORM ON CHANGE {

						if ( $p['event']['on_change'] === 'submit_form' ) {

							$p['attrs_field']['class'][] = 'js-select-field-event-submit-form';
						}

					// }

				// }

				// TEMPLATE {

					if ( empty( $list ) ) {

						return '';
					}

					$template_data = array();

					if ( ! empty( $p['label'] ) ) {

						$template_data['label'] = '<label' . attrs( $p['attrs_label'] ) . '>' . $p['label'] . '</label>';
					}

					$template_data['field'] = '<select' . attrs( $p['attrs_field'] ) . '>' . implode( '', $list ) . '</select>';

					$html .= $this->do_field_template( $p['template'], $template_data, $p );

				// }

				return $html;

			}, 10, 2 );
		}

		public function init_select_field( $p = array() ) {

			/* USAGE

				get_select_field( array(
					'current_value' => '', // optional, if unset/false, then requires 'form_id' for auto setting 'current_term_value',
					'event' => array(
						'on_change' => 'change_location', // change_location, submit_form
					),
					'attrs_field' => array(
						'name' => '', // name of select field
					),
					'options' => array(

					),
				));

			*/

			add_filter( 'class/Form/add_fieldtype', function( $fieldtypes ) {

				$fieldtypes['select'] = array(
					'default_param' => array(
						'current_value' => 'category',
						'event' => array(
							'on_change' => false, // change_location, submit_form
						),
						'attrs_label' => array(),
						'attrs_field' => array(
							'name' => '', // name of select field
							//'value' => '', // name of select field
						),
						'sanitize' => true,
						'allow_null' => array(
							'label' => array(
								'default' => 'Choose…', // list of locales
							),
							'value' => '',
						),
						'options' => array(

						),
						'template' => array(
							'{label}',
							'{description}',
							'{before_field}',
							'{field}',
							'{after_field}',
							'{validation}',
						),
					),
					'validation' => array(),
				);

				return $fieldtypes;
			});

			add_filter( 'class/Form/get_fields_html/field_type=select', function( $html, $item ) {

				$p = array_replace_recursive( $this->fieldtypes['select']['default_param'], $item );

				// FILTER FIELD PARAM {

					$p = apply_filters( 'class/Form/field_parameters', $p );
					$p = apply_filters( 'class/Form/field_parameters/form_group=' . $this->p['form_group'], $p );

				// }

				// REQUEST VALUE {

					if ( isset( $p['request_value'] ) ) {

						$p['attrs_field']['value'] = $p['request_value'];
						$p['current_value'] = $p['request_value'];
					}

				// }

				// ATTRS LABEL {

					$attrs_label_defaults = array(
						'for' => $p['attrs_field']['name'],
					);

					$p['attrs_label'] = array_replace_recursive( $attrs_label_defaults, $p['attrs_label'] );
					$p['attrs_label']['for'] = $p['attrs_label']['for'] . ':' . newid();

				// }

				// ATTRS FIELD {

					$attrs_field_defaults = array(
						'id' => $p['attrs_field']['name'],
						'name' => $p['attrs_field']['name'],
						'class' => array(),
					);

					$p['attrs_field'] = array_replace_recursive( $attrs_field_defaults, $p['attrs_field'] );
					$p['attrs_field']['id'] = $p['attrs_label']['for'];

					if ( $p['required'] === true ) {

						$p['attrs_field']['required'] = true;
						$p['attrs_field']['class'][] = 'required';
					}

				// }

				// GETS OPTIONS LIST {

					$list = array();

					// ALLOW NULL {

						if ( ! empty( $p['allow_null'] ) ) {

							$attrs = array(
								'value' => $p['allow_null']['value'],
							);

							if ( $p['current_value'] == $p['allow_null']['value'] ) {

								$attrs['selected'] = 'selected';
							}

							$label = tool( array(
								'name' => 'tool_get_lang_value_from_array',
								'param' => $p['allow_null']['label'],
							) );

							array_push( $list, '<option' . attrs( $attrs ) . '>'. $label . '</option>' );
						}

					// }

					foreach ( $p['options'] as $value => $label ) {

						// SELECT OPTION ATTRIBUTES {

							$attrs = array(
								'value' => false,
							);

						// }

						// SELECTED {

							if ( $p['current_value'] == $value ) {

								$attrs['selected'] = 'selected';
							}

						// }

						// SETS VALUE {

							$attrs['value'] = $value;

						// }

						array_push( $list, '<option' . attrs( $attrs ) . '>'. $label . '</option>' );
					}

				// }

				// ADDS EVENTS {

					// ADDS CHANGE LOCATION ON CHANGE {

						if ( $p['event']['on_change'] === 'change_location' ) {

							$p['attrs_field']['class'][] = 'js-select-field-event-change-location';
						}

					// }

					// ADDS SUBMIT FORM ON CHANGE {

						if ( $p['event']['on_change'] === 'submit_form' ) {

							$p['attrs_field']['class'][] = 'js-select-field-event-submit-form';
						}

					// }

				// }

				// TEMPLATE { {

					if ( empty( $list ) ) {

						return '';
					}

					$template_data = array();

					if ( ! empty( $p['label'] ) ) {

						$template_data['label'] = '<label' . attrs( $p['attrs_label'] ) . '>' . $p['label'] . '</label>';
					}

					$template_data['field'] = '<select' . attrs( $p['attrs_field'] ) . '>' . implode( '', $list ) . '</select>';

					$html .= $this->do_field_template( $p['template'], $template_data, $p );

				// }

				return $html;

			}, 10, 2 );
		}

		public function init_switch_toggle_field( $p = array() ) {

			add_filter( 'class/Form/add_fieldtype', function( $fieldtypes ) {

				$fieldtypes['switch_toggle'] = array(
					'default_param' => array(
						'label' => '',
						'toggle' => '<span class="switch-toggle-on">{on}</span><span class="switch-toggle-off">{off}</span>',
						'label_on' => 'On',
						'label_off' => 'Off',
						'attrs_label' => array(
						),
						'attrs_toggle' => array(
							'class' => 'switch-toggle',
						),
						'attrs_field' => array(
							'class' => 'switch-toggle-checkbox',
							'name' => '',
							'value' => '',
						),
						'required' => false,
						'validation' => false,
						'value' => '',
						'sanitize' => true,
						'template' => array(
							'{label}',
							'{description}',
							'{field}',
							'{before_field}',
							'{toggle}',
							'{after_field}',
							'{validation}',
						),
					),
					'validation' => array(),
				);

				return $fieldtypes;
			});

			add_filter( 'class/Form/get_fields_html/field_type=switch_toggle', function( $html, $item ) {

				$p = array_replace_recursive( $this->fieldtypes['switch_toggle']['default_param'], $item );

				// FILTER FIELD PARAM {

					$p = apply_filters( 'class/Form/field_parameters', $p );
					$p = apply_filters( 'class/Form/field_parameters/form_group=' . $this->p['form_group'], $p );

				// }

				// REQUEST VALUE {

					if ( isset( $p['request_value'] ) ) {

						$p['attrs_field']['checked'] = true;
					}

				// }

				// ATTRS LABEL {

					$attrs_label_defaults = array(
						'for' => $p['attrs_field']['name'],
					);

					$p['attrs_label'] = array_replace_recursive( $attrs_label_defaults, $p['attrs_label'] );
					$p['attrs_label']['for'] = $p['attrs_label']['for'] . ':' . newid();

				// }

				// ATTRS TOGGLE {

					$attrs_toogle_defaults = array(
						'for' => $p['attrs_field']['name'],
					);

					$p['attrs_toggle'] = array_replace_recursive( $attrs_toogle_defaults, $p['attrs_toggle'] );

				// }

				// ATTRS FIELD {

					$attrs_field_defaults = array(
						'type' => 'checkbox',
						'id' => $p['attrs_field']['name'],
						'name' => $p['attrs_field']['name'],
						'class' => array(),
					);

					$p['attrs_field'] = array_replace_recursive( $attrs_field_defaults, $p['attrs_field'] );
					$p['attrs_field']['id'] = $p['attrs_label']['for'];

				// }

				// FILTER LABEL {
					$p['toggle'] = str_replace( '{on}', $p['label_on'], $p['toggle'] );
					$p['toggle'] = str_replace( '{off}', $p['label_off'], $p['toggle'] );

				// }

				// TEMPLATE {

					$template_data = array();

					if ( ! empty( $p['label'] ) ) {

						$template_data['label'] = '<label' . attrs( $p['attrs_label'] ) . '>' . $p['label'] . '</label>';
					}

					$template_data['field'] = '<input' . attrs( $p['attrs_field'] ) . '>';
					$template_data['toggle'] = '<label ' . attrs( $p['attrs_toggle'] ) . '>' . $p['toggle'] . '</label>';

					$html .= $this->do_field_template( $p['template'], $template_data, $p );

				// }

				return $html;

			}, 10, 2 );
		}

		public function init_submit_field( $p = array() ) {

			add_filter( 'class/Form/add_fieldtype', function( $fieldtypes ) {

				$fieldtypes['submit'] = array(
					'default_param' => array(
						'type' => 'submit',
						'element' => 'input', // input, button
						'attrs_field' => array(
							'type' => 'submit',
							'name' => '',
							'value' => '',
						),
						'template' => array(
							'{description}',
							'{before_field}',
							'{field}',
							'{after_field}',
						),
					),
					'validation' => array(),
				);

				return $fieldtypes;
			});

			add_filter( 'class/Form/get_fields_html/field_type=submit', function( $html, $item ) {

				$p = array_replace_recursive( $this->fieldtypes['submit']['default_param'], $item );

				// FILTER FIELD PARAM {

					$p = apply_filters( 'class/Form/field_parameters', $p );
					$p = apply_filters( 'class/Form/field_parameters/form_group=' . $this->p['form_group'], $p );

				// }

				// ATTRS FIELD {

					$attrs_field_defaults = array(
						'id' => $p['attrs_field']['name'],
					);

					$p['attrs_field'] = array_replace_recursive( $attrs_field_defaults, $p['attrs_field'] );
					$p['attrs_field']['id'] = $p['attrs_field']['id'] . ':' . newid();

				// }

				// TEMPLATE {

					$template_data = array();

					if ( 'input' === $p['element'] ) {

						$template_data['field'] = '<input' . attrs( $p['attrs_field'] ) . '>';
					}

					if ( 'button' === $p['element'] ) {

						$value = $p['attrs_field']['value'];
						unset( $p['attrs_field']['value'] );

						$template_data['field'] = '<button' . attrs( $p['attrs_field'] ) . '>' . $value . '</button>';
					}

					$html .= $this->do_field_template( $p['template'], $template_data, $p );

				// }

				return $html;

			}, 10, 2 );
		}

		public function init_custom_field( $p = array() ) {

			add_filter( 'class/Form/add_fieldtype', function( $fieldtypes ) {

				$fieldtypes['custom'] = array(
					'default_param' => array(
						'callback' => false,
						'template' => array(
							'{field}',
						),

					),
					'validation' => array(),
				);

				return $fieldtypes;
			});

			add_filter( 'class/Form/get_fields_html/field_type=custom', function( $html, $item ) {

				$p = array_replace_recursive( $this->fieldtypes['custom']['default_param'], $item );

				// FILTER FIELD PARAM {

					$p = apply_filters( 'class/Form/field_parameters', $p );
					$p = apply_filters( 'class/Form/field_parameters/form_group=' . $this->p['form_group'], $p );

				// }

				// TEMPLATE {

					if ( ! $p['callback'] ) {

						return '';
					}

					$template_data = array();

					$template_data['field'] = $p['callback']();

					$html .= $this->do_field_template( $p['template'], $template_data, $p );

				// }

				return $html;

			}, 10, 2 );
		}

		public function init_email_field( $p = array() ) {

			add_filter( 'class/Form/add_fieldtype', function( $fieldtypes ) {

				$fieldtypes['email'] = array(
					'default_param' => array(
						'label' => '',
						'attrs_label' => array(),
						'attrs_field' => array(
							'name' => '',
							'value' => '',
						),
						'required' => false,
						'validation' => function( $value, $field ) {

							$message_keys = array(
								//'field' => array(),
								//'form' => array(),
							);

							return $message_keys;
						},
						'value' => '',
						'sanitize' => true,
						'template' => array(
							'{label}',
							'{description}',
							'{before_field}',
							'{field}',
							'{after_field}',
							'{validation}',
						),
					),
					'validation' => function( $value ) {

						if ( ! filter_var( $value , FILTER_VALIDATE_EMAIL ) ) {

							$message_keys = array(
								'field' => array( 'email_not_valid' ),
								'form' => array( 'field_validation_error' ),
							);

							return $message_keys;
						}
					},
				);

				return $fieldtypes;
			});

			add_filter( 'class/Form/get_fields_html/field_type=email', function( $html, $item ) {

				$p = array_replace_recursive( $this->fieldtypes['email']['default_param'], $item );

				// FILTER FIELD PARAM {

					$p = apply_filters( 'class/Form/field_parameters', $p );
					$p = apply_filters( 'class/Form/field_parameters/form_group=' . $this->p['form_group'], $p );

				// }

				// REQUEST VALUE {

					if ( isset( $p['request_value'] ) ) {

						$p['attrs_field']['value'] = $p['request_value'];
					}

				// }

				// ATTRS LABEL {

					$attrs_label_defaults = array(
						'for' => $p['attrs_field']['name'],
					);

					$p['attrs_label'] = array_replace_recursive( $attrs_label_defaults, $p['attrs_label'] );
					$p['attrs_label']['for'] = $p['attrs_label']['for'] . ':' . newid();

				// }

				// ATTRS FIELD {

					$attrs_field_defaults = array(
						'type' => 'email',
						'id' => $p['attrs_field']['name'],
						'name' => $p['attrs_field']['name'],
						'class' => array(),
					);

					$p['attrs_field'] = array_replace_recursive( $attrs_field_defaults, $p['attrs_field'] );
					$p['attrs_field']['id'] = $p['attrs_label']['for'];

					if ( $p['required'] === true ) {

						$p['attrs_field']['required'] = true;
						$p['attrs_field']['class'][] = 'required';
					}

				// }

				// TEMPLATE {

					$template_data = array();

					if ( ! empty( $p['label'] ) ) {

						$template_data['label'] = '<label' . attrs( $p['attrs_label'] ) . '>' . $p['label'] . '</label>';
					}

					$template_data['field'] = '<input' . attrs( $p['attrs_field'] ) . '>';

					$html .= $this->do_field_template( $p['template'], $template_data, $p );

				// }

				return $html;

			}, 10, 2 );
		}

		public function init_file_field( $p = array() ) {

			add_filter( 'class/Form/add_fieldtype', function( $fieldtypes ) {

				$fieldtypes['file'] = array(
					'default_param' => array(
						'label' => '',
						'attrs_label' => array(),
						'attrs_field' => array(
							'name' => '',
							'value' => '',
						),
						'required' => false,
						'value' => '',
						'max_filesize' => false,
						'allowed_file_formats' => false,
						'allow_multiple_files' => false,
						'sanitize' => true,
						'template' => array(
							'{label}',
							'{description}',
							'{before_field}',
							'{field}',
							'{field_info}',
							'{after_field}',
							'{validation}',
						),
					),
					'validation' => function( $value, $item ) {

						$message_keys = array(
							'field' => array(),
							'form' => array(),
						);

						// SETUP FILES ARRAY {

							$files = array();

							if ( is_array( $value['name'] ) ) {

								foreach ( $value['name'] as $key => $file ) {

									$files[] = array(
										'name' => $value['name'][ $key ],
										'type' => $value['type'][ $key ],
										'tmp_name' => $value['tmp_name'][ $key ],
										'error' => $value['error'][ $key ],
										'size' => $value['size'][ $key ],
									);
								}
							}
							else {

								$files[] = $value;
							}

						// }

						foreach ( $files as  $file ) {

							// FILESIZE {

								if (
									! empty( $item['max_filesize'] ) AND
									$file['error'] === 0 AND
									isset( $file['size'] ) AND
									$item['max_filesize'] < $file['size']
								) {

									$message_keys['field'][] = 'filesize_to_large';
									$message_keys['form'][] = 'field_validation_error';
								}

							// }

							// ALLOWED FILE FORMATS {

								if (
									! empty( $item['allowed_file_formats'] ) AND
									$file['error'] === 0 AND
									isset( $file['name'] )
								) {

									$allowed_file_formats = $this->formats_allowed_file_formats( $item['allowed_file_formats'] );

									if ( ! empty( $allowed_file_formats ) ) {

										$name_arr = explode( '.', $file['name'] );
										$sufix = strtolower( end( $name_arr ) );

										if ( ! in_array( $sufix, $allowed_file_formats ) ) {

											$message_keys['field'][] = 'file_format_not_allowed';
											$message_keys['form'][] = 'field_validation_error';
										}
									}

									return $message_keys;
								}

							// }
						}

						return $message_keys;
					},
				);

				return $fieldtypes;
			});

			add_filter( 'class/Form/item_param/type=file', function( $param, $p ) {

				$hints = array();

				// APPLY MAXFILESIZE HINT {

					if ( ! empty( $param['max_filesize'] ) ) {

						$text = _x( 'Maximal allowed filesize: {value}', 'Formular', 'tool_translate' );
						$hints[] = str_replace( '{value}', $this->format_file_size( $param['max_filesize'] ), $text );
					}

				// }

				// APPLY ALLOWED FILE FORMATS HINT {

					if ( ! empty( $param['allowed_file_formats'] ) ) {

						$file_formats = $this->formats_allowed_file_formats( $param['allowed_file_formats'], true );
					}

					if ( ! empty( $file_formats ) ) {

						$text = _x( 'Allowed file types: {value}', 'Formular', 'tool_translate' );
						$hints[] = str_replace( '{value}', implode( ', ', $file_formats ), $text );
					}

				// }

				// ADD HINTS T0 FIELD DESCRIPTION {

					$param['_field_info'] = '';

					if ( ! empty( $hints ) ) {

						$param['_field_info'] .= '<ul class="form-field-info"><li>' . implode( '</li><li>', $hints ) . '</li></ul>';
					}

				// }

				return $param;

			}, 10, 2 );

			add_filter( 'class/Form/get_fields_html/field_type=file', function( $html, $item ) {

				// DEFAULTS {

					$p = array_replace_recursive( $this->fieldtypes['file']['default_param'], $item );

				// }

				// FILTER FIELD PARAM {

					$p = apply_filters( 'class/Form/field_parameters', $p );
					$p = apply_filters( 'class/Form/field_parameters/form_group=' . $this->p['form_group'], $p );

				// }

				// REQUEST VALUE {

					if ( isset( $p['request_value'] ) ) {

						$p['attrs_field']['value'] = $p['request_value'];
					}

				// }

				// ATTRS LABEL {

					$attrs_label_defaults = array(
						'for' => $p['attrs_field']['name'],
					);

					$p['attrs_label'] = array_replace_recursive( $attrs_label_defaults, $p['attrs_label'] );
					$p['attrs_label']['for'] = $p['attrs_label']['for'] . ':' . newid();

				// }

				// ATTRS FIELD {

					$attrs_field_defaults = array(
						'type' => 'file',
						'id' => $p['attrs_field']['name'],
						'name' => $p['attrs_field']['name'],
						'class' => array(),
					);

					$p['attrs_field'] = array_replace_recursive( $attrs_field_defaults, $p['attrs_field'] );
					$p['attrs_field']['id'] = $p['attrs_label']['for'];

					if ( $p['required'] === true ) {

						$p['attrs_field']['required'] = true;
						$p['attrs_field']['class'][] = 'required';
					}

				// }

				// ACCEPT {

					if ( ! empty( $p['allowed_file_formats'] ) ) {

						$accept_arr = explode( ',', $p['allowed_file_formats'] );

						foreach ( $accept_arr as $key => $item ) {

							$accept_arr[ $key ] = '.' . strtolower( trim( trim($item), '.' ) );
						}

						$p['attrs_field']['accept'] = implode( ',', $accept_arr );

					}

				// }

				// FILE LABEL {

					$p['attrs_field_label'] = array(
						'for' => $p['attrs_label']['for'],
						'class' => array( 'form-field-file-input', 'btn', 'btn-file' ),
					);

				// }

				// INPUT TEXT {

					$input_text = _x( 'Choose a file', 'Formular', 'tool_translate' );

				// }

				// ADDS FILE LIST {

					$tpl_field = '';

					if ( ! empty( $this->request[ $p['attrs_field']['name'] ]['name'] ) ) {

						$temp_files = $this->request[ $p['attrs_field']['name'] ];

						$tpl_field .= '<ul class="_files">';

						foreach ( $temp_files['name'] as $key => $file_name ) {

							$tpl_field .= '<li>' . $file_name . ' <span>(' . $this->format_file_size( $temp_files['size'][ $key ] ) . ')<span></li>';
						}

						$tpl_field .= '</ul>';

						$input_text = _x( '{value} files selected', 'Formular', 'tool_translate' );
						$input_text = str_replace( '{value}', count( $temp_files ) , $input_text );
					}

				// }

				// MULTIPLE {

					if ( ! empty( $p['allow_multiple_files'] ) ) {

						$p['attrs_field']['multiple'] = true;
						$p['attrs_field']['name'] .= '[]';
					}

				// }

				// TEMPLATE {

					$template_data = array();

					$template_data['field_info'] = $p['_field_info'];

					if ( ! empty( $p['label'] ) ) {

						$template_data['label'] = '<label' . attrs( $p['attrs_label'] ) . '>' . $p['label'] . '</label>';
					}

					$template_data['field'] = '<input' . attrs( $p['attrs_field'] ) . '>';
					$template_data['field'] .= '<label' . attrs( $p['attrs_field_label'] ) . ' data-label-text="' . _x( 'Choose a file', 'Formular', 'tool_translate' ) . '">' . $input_text . '</label>';
					$template_data['field'] .= $tpl_field;

					$html .= $this->do_field_template( $p['template'], $template_data, $p );

				// }

				return $html;

			}, 10, 2 );
		}

		public function init_hidden_field( $p = array() ) {

			add_filter( 'class/Form/before_item', function( $html, $p, $item, $attrs ) {

				if (
					'hidden' === $attrs['data-type'] AND
					empty( $item['label'] )
				) {

					return '';
				}

				return $html;

			}, 10, 4 );

			add_filter( 'class/Form/after_item', function( $html, $p, $item, $attrs ) {

				if (
					'hidden' === $attrs['data-type'] AND
					empty( $item['label'] )
				) {

					return '';
				}

				return $html;

			}, 10, 4 );

			add_filter( 'class/Form/add_fieldtype', function( $fieldtypes ) {

				$fieldtypes['hidden'] = array(
					'default_param' => array(
						'label' => '',
						'attrs_label' => array(),
						'attrs_field' => array(
							'name' => '',
							'value' => '',
						),
						'required' => false,
						'value' => '',
						'sanitize' => true,
						'template' => array(
							'{label}',
							'{description}',
							'{before_field}',
							'{field}',
							'{after_field}',
							'{validation}',
						),
					),
					'validation' => false,
				);

				return $fieldtypes;
			});

			add_filter( 'class/Form/get_fields_html/field_type=hidden', function( $html, $item ) {

				// DEFAULTS {

					$p = array_replace_recursive( $this->fieldtypes['hidden']['default_param'], $item );

				// }

				// FILTER FIELD PARAM {

					$p = apply_filters( 'class/Form/field_parameters', $p );
					$p = apply_filters( 'class/Form/field_parameters/form_group=' . $this->p['form_group'], $p );

				// }

				// REQUEST VALUE {

					if ( isset( $p['request_value'] ) ) {

						$p['attrs_field']['value'] = $p['request_value'];
					}

				// }

				// ATTRS LABEL {

					$attrs_label_defaults = array(
						'for' => $p['attrs_field']['name'],
					);

					$p['attrs_label'] = array_replace_recursive( $attrs_label_defaults, $p['attrs_label'] );
					$p['attrs_label']['for'] = $p['attrs_label']['for'] . ':' . newid();

				// }

				// ATTRS FIELD {

					$attrs_field_defaults = array(
						'type' => 'hidden',
						'id' => $p['attrs_field']['name'],
						'name' => $p['attrs_field']['name'],
						'class' => array(),
					);

					$p['attrs_field'] = array_replace_recursive( $attrs_field_defaults, $p['attrs_field'] );
					$p['attrs_field']['id'] = $p['attrs_label']['for'];

					if ( $p['required'] === true ) {

						$p['attrs_field']['required'] = true;
						$p['attrs_field']['class'][] = 'required';
					}

				// }

				// VALUE {

					if ( ! empty( $p['options'] ) ) {

						$p['attrs_field']['value'] = apply_filters( 'class/Form/field/hidden/value/key=' . $p['options'], $p['attrs_field']['value'], $p );
					}

				// }

				// TEMPLATE {

					$template_data = array();

					if ( ! empty( $p['label'] ) ) {

						$template_data['label'] = '<label' . attrs( $p['attrs_label'] ) . '>' . $p['label'] . '</label>';
					}

					$template_data['field'] = '<input' . attrs( $p['attrs_field'] ) . '>';

					$html .= $this->do_field_template( $p['template'], $template_data, $p );

				// }

				return $html;
			}, 10, 2 );

		}

		public function init_fieldset_field( $p = array() ) {

			add_filter( 'class/Form/add_fieldtype', function( $fieldtypes ) {

				$fieldtypes['fieldset'] = array(
					'default_param' => array(

					),
				);

				return $fieldtypes;
			});

			add_filter( 'class/Form/get_fields_html/field_type=fieldset', function( $html, $item ) {

				// DEFAULTS {

					//$p = array_replace_recursive( $this->fieldtypes['hidden']['default_param'], $item );

				// }

				$html = $this->get_fieldset( $html, $item );

				return $html;

			}, 10, 2 );
		}

		public function filter_files_required( $requires, $field ) {

			// FIXES MISSING FILE FIELD IN REQUEST
			if ( ! empty( $_FILES[ $field['attrs_field']['name'] ]['name'] ) ) {

				$requires = false;
			}

			return $requires;
		}

		// SANITIZING

		public function sanitize_text_field( $string ) {

			$string = sanitize_text_field( $string );

			return $string;
		}

		public function sanitize_textarea_field( $string ) {

			$string = sanitize_textarea_field( $string );

			return $string;
		}

		public function sanitize_email_field( $string ) {

			$string = sanitize_text_field( $string );

			return $string;
		}

		public function sanitize_checkboxes_field( $value ) {

			if ( is_array( $value ) ) {

				array_walk_recursive( $value, function( &$item, $key ) {

					$item = $this->sanitize_text_field( $item );
				} );
			}
			elseif ( is_string( $value ) ) {

				$value = $this->sanitize_text_field( $value );
			}

			return $value;
		}

		public function sanitize_select_field( $value ) {

			if ( is_array( $value ) ) {

				array_walk_recursive( $value, function( &$item, $key ) {

					$item = $this->sanitize_text_field( $item );
				} );
			}
			elseif ( is_string( $value ) ) {

				$value = $this->sanitize_text_field( $value );
			}

			return $value;
		}

		public function sanitize_file_field( $item ) {

			if ( isset( $item['name'] ) ) {

				if ( is_string( $item['name'] ) ) {

					$item['name'] = sanitize_file_name( $item['name'] );
				}
				elseif ( is_array( $item['name'] ) ) {

					foreach ( $item['name'] as $key => $value ) {

						$item['name'][ $key ] = sanitize_file_name( $value );
					}
				}
			}

			return $item;
		}

		public function sanitize_hidden_field( $value ) {

			if ( is_array( $value ) ) {

				array_walk_recursive( $value, function( &$item, $key ) {

					$item = $this->sanitize_text_field( $item );
				} );
			}
			elseif ( is_string( $value ) ) {

				$value = $this->sanitize_text_field( $value );
			}

			return $value;
		}

		// VALIDATION

		private function get_field_validation_html( $messages ) {

			$html = '';

			if ( empty( $messages['field'] ) ) {

				return $html;
			}

			if ( ! $this->is_form_request( $this->p['form_id'] ) ) {

				return $html;
			}

			$html .= '<span class="field-validation">';

				$temp = array();

				foreach ( $messages['field'] as $value ) {

					if ( ! empty( $this->form_messages[ $value ] ) ) {

						if ( is_array( $this->form_messages[ $value ] ) ) {

							$temp[] = tool( array(
								'name' => 'tool_get_lang_value_from_array',
								'param' => $this->form_messages[ $value ],
							) );
						}
						else {

							$temp[]  = _x( $this->form_messages[ $value ], 'Formular', 'tool_translate' );
						}

					}
					else if ( is_array( $value ) ) {

						$temp[] = tool( array(
							'name' => 'tool_get_lang_value_from_array',
							'param' => $value,
						) );
					}
					else {

						$temp[] = $value;
					}
				}

				$html .= implode( ' ', $temp );

			$html .= '</span>';

			return $html;
		}

		// EMAIL

		public function do_email( $p = array() ) {

			// CHECK FILTER {

				$check = apply_filters( 'class/Form/wp_mail/do_email/check', true, array(
					'request' => $this->request,
					'p' => $p,
				));

				if ( true !== $check ) {

					return;
				}

			// }

			// CHECKS EMAIL PARAMETERS {



			// }

			// GETS TO {

				$to = sanitize_email( $p['email']['email_to'] );

			// }

			// GETS SUBJET {

				$subject = $this->apply_email_placeholders( $p['email']['email_subject'] );

			// }

			// GETS HEADERS {

				$arr = array();
				$arr[] = $this->apply_email_placeholders( $p['email']['email_from_name'] );
				$arr[] = '<' . sanitize_email( $this->apply_email_placeholders( $p['email']['email_from'] ) ) . '>';

				$headers = array(
					'From: ' . implode( ' ', $arr ),
				);

				if ( ! empty( $p['email']['email_header'] ) ) {

					$headers = array_merge( $headers, $p['email']['email_header'] );
				}

			// }

			// GETS MESSAGE {

				$message = $this->apply_email_placeholders( $p['email']['email_body'] );

			// }

			// GETS ATTACHEMENTS {

				$attachements = array();

				// ITERATES FORM ITEMS {

					foreach ( $this->items as $item ) {

						// IS FIELDTYPE FILE
						if (
							isset( $item['type'] ) AND
							$item['type'] === 'file'
						) {

							// INITS FILES TEMP FOLDER {

								if ( empty( $attachmentdir ) ) {

									$path = wp_get_upload_dir();
									$basedir = $path['basedir'];
									$attachmentdir = $path['basedir'] . '/toolset_form-' . newid();
									mkdir( $attachmentdir );
								}

							// }

							// GETS ATTACHEMENTS FROM $_FILES {

								if ( ! empty( $_FILES[ $item['attrs_field']['name'] ]['name'] ) ) {

									$file = $_FILES[ $item['attrs_field']['name'] ];

									// has multiple filws <input type="file" multiple>
									if ( is_array( $file['name'] ) ) {

										foreach ( $file['name'] as $key => $name ) {

											if ( $name === '' ) { // Fixes empty multiple file input

												continue;
											}

											$status = apply_filters( 'class/Form/wp_mail/file_field', true, array(
												'file' => array(
													'name' => $file['name'][ $key ],
													'full_path' => $file['full_path'][ $key ],
													'type' => $file['type'][ $key ],
													'tmp_name' => $file['tmp_name'][ $key ],
													'error' => $file['error'][ $key ],
													'size' => $file['size'][ $key ],
												),
											));

											if ( true === $status ) {

												move_uploaded_file( $file['tmp_name'][ $key ], $attachmentdir . '/' . $name );
												$attachements[] = $attachmentdir . '/' . $name;
											}
										}
									}
									else {

										$status = apply_filters( 'class/Form/wp_mail/file_field', true, array(
											'file' => $file,
										));

										if ( true === $status ) {

											move_uploaded_file( $file['tmp_name'], $attachmentdir . '/' . $file['name'] );
											$attachements[] = $attachmentdir . '/' . $file['name'];
										}
									}
								}

							// }
						}
					}

				// }

			// }

			// GET EMAIL TO {

				$to = sanitize_email( $p['email']['email_to'] ); //

				if 	( empty( $to ) ) {

					$to = get_option( 'admin_email' );
				}

			// }

			// SEND EMAIL {

				//error_log( print_r( $to, true) );
				//error_log( print_r( $subject, true) );
				//error_log( print_r( $message, true) );
				//error_log( print_r( $headers, true) );
				//error_log( print_r( $attachements, true) );

				$mail_param = array(
					'to' => $to,
					'subject' => $subject,
					'message' => $message,
					'headers' => $headers,
					'attachements' => $attachements,
				);

				$mail_param = apply_filters('class/Form/wp_mail/param/form_id=' . $this->p['form_id'], $mail_param, array(
					'request' => $this->request,
				));

				$mail_param = apply_filters('class/Form/wp_mail/param', $mail_param, array(
					'request' => $this->request,
				));

				$mail = wp_mail( $mail_param['to'], $mail_param['subject'], $mail_param['message'], $mail_param['headers'], $mail_param['attachements'] );

				do_action( 'class/Form/wp_mail/after', array(
					'request' => $this->request,
					'mail_param' => $mail_param,
					'p' => $this->p,
					'this' => $this,
				) );

				do_action( 'class/Form/wp_mail/after/form_id=' . $this->p['form_id'], array(
					'request' => $this->request,
					'mail_param' => $mail_param,
					'p' => $this->p,
					'this' => $this,
				) );

				// MAY REMOVES ATTACHMENTS {

					if ( ! empty( $attachmentdir ) ) {

						foreach ( $attachements as $path ) {

							unlink( $path );
						}

						rmdir( $attachmentdir );
					}

				// }

			// }

			// ADD FORM MESSAGE {

				if (
					$mail
				) {

					do_action( 'class/Form/wp_mail/sent/form_id=' . $this->p['form_id'] );

					if ( empty( $this->p['form_post_id'] ) ) {

						wp_redirect( './?emailsent=' . $this->p['form_id'] );
						exit;
					}
					else {

						$this->p['return'] = array( '' );
						$this->is_request = true;
						$this->request_form_message_keys[] = 'email_sent';
					}
				}

			// }

		}

		private function implode_recur( $array = array(), $depth = '', $list_spacer = '	' ) {

			$output = '';
			$next_depth = $depth . $list_spacer;

			foreach ( $array as $key => $av ) {

				if ( is_string( $key ) ) {

					$output .= "\n" . $depth .  ' ' . $key;
				}

				if ( is_array( $av ) && ! empty( $av ) ) {

					$output .= $this->implode_recur( $av, $next_depth );
				}
				elseif ( is_array( $av ) ) {

					// empty array()
				}
				elseif ( ! is_bool( $av ) ) {

					$output .= "\n" . $depth .  ' ' . $av;
				}
				else {

				}
			}

			return $output;
		}

		public function apply_email_placeholders( $string = '' ) {

			$string = preg_replace_callback('/\{(.+?)\}/i', function( $placeholder ) {

				if ( isset( $this->request[ $placeholder[1] ] ) ) {

					$value = apply_filters( 'class/Form/wp_mail/placeholder/value/name=' . $placeholder[1], $this->request[ $placeholder[1] ] );

					// IS STRING

						if ( is_string( $value ) ) {

							return $value;
						}

					// IS MULTIDIMENSIONAL ARRAY

						elseif (
							isset( $this->request[ $placeholder[1] ] ) &&
							is_array( $value )
						) {

							return $this->implode_recur( $value );
						}

					// IS SIMPLE ARRAY

						elseif ( is_array( $this->request[ $placeholder[1] ] ) ) {

							$divider = ', ';

							foreach ( $this->items as $item ) {

								if (
									$item['attrs_field']['name'] === $placeholder[1] AND
									isset( $item['value_divider'] )
								) {

									$divider = $item['value_divider'];
								}
							}

							return implode( $divider, $this->request[ $placeholder[1] ] );
						}
				}

				return apply_filters( 'class/Form/wp_mail/placeholder/value/name=' . $placeholder[1], '', false );

			}, $string );

			return $string;
		}

		public function email_sent() {

			if (
				! empty( $_REQUEST['emailsent'] ) AND
				$_REQUEST['emailsent'] === $this->p['form_id']
			) {

				$this->p['return'] = array( '' );
				$this->is_request = true;
				$this->request_form_message_keys[] = 'email_sent';
			}
		}

		// HELPER

		private function get_request_value( $name, $value ) {

			if ( $this->is_form_request( $this->p['form_id'] ) ) {

				if ( isset( $this->request[ $name ] ) ) {

					return $this->request[ $name ];
				}

				return $value;
			}
		}

		public function is_form_request( $form_id, $validate = false ) {

			if (
				! empty( $_REQUEST['form_id'] ) AND
				$form_id == $_REQUEST['form_id']
			) {

				//error_log( print_r( $this->has_messages, true) );
				if ( false === $validate ) {

					return true;
				}
				else {

					if (
						false === $this->has_messages['fields'] AND
						false === $this->has_messages['fields']
					) {

						return true;
					}
				}
			}

			return false;
		}

		public function do_field_template( $template = array(), $data = array(), $p = array() ) {

			$data = apply_filters( 'class/Form/do_field_template/data', $data, $p );

			if ( ! empty( $p['validation_messages'] ) ) {

				$data['validation'] = $this->get_field_validation_html( $p['validation_messages'] );
			}

			$html = '';
			$data_temp = array();

			foreach ( $data as $key => $value ) {

				$data_temp[ '{' . $key . '}' ] = $value;
			}

			// MAY ADDS DESCRITION TO TEMPLATE {

				if ( ! empty( $p['description'] ) ) {

					$data_temp[ '{description}' ] = '<div class="form-field-description">' . $p['description'] . '</div>';
				}

			// }

			$data = $data_temp;

			foreach ( $template as $key => $value ) {

				if ( isset( $data[ $value ] ) ) {

					$html .= str_replace(
						$value,
						$data[ $value ],
						$template[ $key ]
					);
				}
			}

			return $html;
		}

		public function formats_allowed_file_formats( $formats, $dot = false ) {

			// takes an array or an comma separatet list of file sufixes wether with trailing dot or not
			// returns an array of file sufixes without trailing dot

			if ( is_string(  $formats ) ) {

				$allowed_file_formats = explode( ',',  $formats );
			}

			if ( is_array(  $formats ) ) {

				$allowed_file_formats =  $formats;
			}

			if ( ! empty( $allowed_file_formats ) ) {

				foreach ( $allowed_file_formats as $key => $format ) {

					$format = trim( strtolower( trim( $format ) ), '.' );

					if ( $dot ) {

						$allowed_file_formats[ $key ] = '.' . $format;
					}
					else {

						$allowed_file_formats[ $key ] = $format;
					}
				}

				return $allowed_file_formats;
			}

			return false;
		}

		public function format_file_size( $number ) {

			return tool_format_filesize( $number, array(
				0 => 0,
				1 => 0,
				2 => 2,
			) );
		}

		// ADDONS

		public function addon_before_after_field() {

			add_filter( 'class/Form/do_field_template/data', function( $data, $p ) {

				if ( ! empty( $p['before_field'] ) ) {

					$data['before_field'] = $p['before_field'];
				}

				if ( ! empty( $p['after_field'] ) ) {

					$data['after_field'] = $p['after_field'];
				}

				return $data;
			}, 1, 2 );

		}

	}
