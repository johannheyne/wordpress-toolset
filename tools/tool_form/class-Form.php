<?php

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

		private $is_valide = true; // whether the form is valide

		private $has_messages = false; // whether the form has massages

		private $request_form_message_keys = array();

		private $fieldtypes = array();

		function __construct( $p = array( 'form_id' => '' ) ) {

			// DEFAULTS {

				$defaults = array(
					'form_id' => '',
					'form_group' => '',
					'form_attrs' => array(
						'role' => 'form',
						'method' => 'get',
						'action' => '',
						'data-form-id' => $p['form_id'],
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

				$this->p = array_replace_recursive( $defaults, $p );

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

			// }

			// GETS FIELDTYPES {

				$this->fieldtypes = apply_filters( 'class/Form/add_fieldtype', $this->fieldtypes );

			// }

			// REGISTER FORM MESSAGES {

				$this->form_messages = apply_filters( 'class/Form/messages', $this->form_messages, $this->p );
				$this->form_messages = apply_filters( 'class/Form/messages/form_group=' . $this->p['form_group'], $this->form_messages, $this->p );
				$this->form_messages = apply_filters( 'class/Form/messages/form_id=' . $this->p['form_id'], $this->form_messages, $this->p );

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
					$this->request = $_REQUEST;

					// SANITIZE REQUEST

					$this->sanitize_request();
					$this->validate_fields();
					$this->updates_field_values();
					$this->gets_fields_form_message_keys();

					// REQUEST ACTION

					do_action( 'class/Form/request/form_id=' . $this->p['form_id'], $this->p );
					do_action( 'class/Form/request/form_group=' . $this->p['form_group'], $this->p );
					do_action( 'class/Form/request', $this->p );

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

			do_action( 'class/Form/before_html/form_id=' . $this->p['form_id'], $this->p );

			$html = '<form' . attrs( $attrs ) . '>';

				$html .= '<input type="hidden" name="form_id" value="' . $this->p['form_id'] . '" />';

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

			$html .= '</form>';

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

					// IS FIELDSET {

						if ( ! empty( $item['legend'] ) ) {

							$html = $this->get_fieldset( $html, $item );
						}

					// }

					// IS FIELD WITHOUT FIELDSET {

						elseif ( empty( $item['fieldset_id'] ) ) {

							$html = $this->get_field( $html, $item );
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
					isset( $this->request[ $item['attrs_field']['name'] ] )
				) {

					if ( $item['type'] === 'text' ) {

						$this->request[ $item['attrs_field']['name'] ] = $this->sanitize_text_field( $this->request[ $item['attrs_field']['name'] ] );
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

						$validation_return = $this->fieldtypes[ $item['type'] ]['validation']( $this->request[ $item['attrs_field']['name'] ] );
					}

				// }

				// CUSTOM VALIDATIONS {

					if (
						empty( $validation_return ) AND
						! empty( $item['validation'] ) AND
						isset( $this->request[ $item['attrs_field']['name'] ] )
					) {

						$validation_return = $item['validation']( $this->request[ $item['attrs_field']['name'] ] );
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

			foreach ( $this->request_form_message_keys as $value ) {

				if ( isset( $this->form_messages[ $value ] ) ) {

					$message_array[] = get_lang_value_from_array( $this->form_messages[ $value ] );
				}
			}

			if ( ! empty( $message_array ) ) {

				$html .= '<div data-form-message="prepend-form">';
					$html .= '<ol><li>' . implode( '</li><li>', $message_array ) . '</li></ol>';
				$html .= '</div>';
			}

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

			if ( ! empty( $item['type'] ) ) {

				$attrs['data-type'] = $item['type'];
			}

			$attrs = apply_filters( 'class/Form/item_attrs', $attrs, $this->p );
			$attrs = apply_filters( 'class/Form/item_attrs/form_group=' . $this->p['form_group'], $attrs, $this->p );
			$attrs = apply_filters( 'class/Form/item_attrs/form_id=' . $this->p['form_id'], $attrs, $this->p );

			$html_item = apply_filters( 'class/Form/before_item', '<div' . attrs( $attrs ) . '>', $this->p );
			$html .= apply_filters( 'class/Form/before_item/form_group=' . $this->p['form_group'], $html_item, $this->p );

			$html = apply_filters( 'class/Form/get_fields_html/field_type=' . $item['type'], $html, $item );

			$html_item = apply_filters( 'class/Form/after_item', '</div>', $this->p );
			$html .= apply_filters( 'class/Form/after_item/form_group=' . $this->p['form_group'], $html_item, $this->p );

			return $html;
		}

		// FIELD TYPES

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

				// }

				// ATTRS FIELD {

					$attrs_field_defaults = array(
						'type' => 'text',
						'id' => $p['attrs_field']['name'],
						'name' => $p['attrs_field']['name'],
						'class' => array(),
					);

					$p['attrs_field'] = array_replace_recursive( $attrs_field_defaults, $p['attrs_field'] );

					if ( $p['required'] === true ) {

						$p['attrs_field']['required'] = true;
						$p['attrs_field']['class'][] = 'required';
					}

				// }

				// TEMPLATE {

					$template_data = array();

					$template_data['label'] = '<label' . attrs( $p['attrs_label'] ) . '>' . $p['label'] . '</label>';
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

				// }

				// ATTRS FIELD {

					$attrs_field_defaults = array(
						'type' => 'text',
						'id' => $p['attrs_field']['name'],
						'name' => $p['attrs_field']['name'],
						'class' => array(),
					);

					$p['attrs_field'] = array_replace_recursive( $attrs_field_defaults, $p['attrs_field'] );

					if ( $p['required'] === true ) {

						$p['attrs_field']['required'] = true;
						$p['attrs_field']['class'][] = 'required';
					}

				// }

				// TEMPLATE {

					$template_data = array();

					$template_data['label'] = '<label' . attrs( $p['attrs_label'] ) . '>' . $p['label'] . '</label>';
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

				// }

				// ATTRS LABEL {

					$attrs_label_defaults = array(
						'for' => $p['attrs_field']['id'],
					);

					$p['attrs_label'] = array_replace_recursive( $attrs_label_defaults, $p['attrs_label'] );

				// }

				// TEMPLATE {

					$template_data = array();

					$template_data['label'] = '<label' . attrs( $p['attrs_label'] ) . '>' . $p['label'] . '</label>';

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

					// }

				// }

				// TEMPLATE {

					$template_data = array();

					$template_data['label'] = '<label' . attrs( $p['attrs_label'] ) . '>' . $p['label'] . '</label>';

					// CHECKBOXES {

						$template_data['field'] = '<ul>';

						if ( ! empty( $p['checkboxes'] ) ) {

							foreach ( $p['checkboxes'] as $key => $item ) {

								$field_key = '';

								if ( isset( $item['attrs_field']['name'] ) ) {

									$field_key = $item['attrs_field']['name'];
									unset( $item['attrs_field']['name'] );
								}

								$attrs_field = array_replace_recursive( $p['attrs_field'], $item['attrs_field'], );

								$attrs_field['id'] = $p['attrs_field']['name'] . ':' . $key;

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

				// }

				// ATTRS FIELD {

					$attrs_field_defaults = array(
						'id' => $p['attrs_field']['name'],
						'name' => $p['attrs_field']['name'],
						'class' => array(),
					);

					$p['attrs_field'] = array_replace_recursive( $attrs_field_defaults, $p['attrs_field'] );

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

					$template_data['label'] = '<label' . attrs( $p['attrs_label'] ) . '>' . $p['label'] . '</label>';
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
							'value' => '', // name of select field
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

				// }

				// ATTRS FIELD {

					$attrs_field_defaults = array(
						'id' => $p['attrs_field']['name'],
						'name' => $p['attrs_field']['name'],
						'class' => array(),
					);

					$p['attrs_field'] = array_replace_recursive( $attrs_field_defaults, $p['attrs_field'] );

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

					$template_data['label'] = '<label' . attrs( $p['attrs_label'] ) . '>' . $p['label'] . '</label>';
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
						'label' => '<span class="switch-toggle-on">{on}</span><span class="switch-toggle-off">{off}</span>',
						'label_on' => 'On',
						'label_off' => 'Off',
						'attrs_label' => array(
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
							'{description}',
							'{field}',
							'{before_field}',
							'{label}',
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

				// }

				// ATTRS FIELD {

					$attrs_field_defaults = array(
						'type' => 'checkbox',
						'id' => $p['attrs_field']['name'],
						'name' => $p['attrs_field']['name'],
						'class' => array(),
					);

					$p['attrs_field'] = array_replace_recursive( $attrs_field_defaults, $p['attrs_field'] );

				// }

				// FILTER LABEL {

					$p['label'] = str_replace( '{on}', $p['label_on'], $p['label'] );
					$p['label'] = str_replace( '{off}', $p['label_off'], $p['label'] );

				// }

				// TEMPLATE {

					$template_data = array();

					$template_data['field'] = '<input' . attrs( $p['attrs_field'] ) . '>';
					$template_data['label'] = '<label' . attrs( $p['attrs_label'] ) . '>' . $p['label'] . '</label>';

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

				// }

				// TEMPLATE {

					$template_data = array();

					$template_data['field'] = '<input' . attrs( $p['attrs_field'] ) . '>';

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

				// }

				// ATTRS FIELD {

					$attrs_field_defaults = array(
						'type' => 'email',
						'id' => $p['attrs_field']['name'],
						'name' => $p['attrs_field']['name'],
						'class' => array(),
					);

					$p['attrs_field'] = array_replace_recursive( $attrs_field_defaults, $p['attrs_field'] );

					if ( $p['required'] === true ) {

						$p['attrs_field']['required'] = true;
						$p['attrs_field']['class'][] = 'required';
					}

				// }

				// TEMPLATE {

					$template_data = array();

					$template_data['label'] = '<label' . attrs( $p['attrs_label'] ) . '>' . $p['label'] . '</label>';
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

				// }

				// ATTRS FIELD {

					$attrs_field_defaults = array(
						'type' => 'file',
						'id' => $p['attrs_field']['name'],
						'name' => $p['attrs_field']['name'],
						'class' => array(),
					);

					$p['attrs_field'] = array_replace_recursive( $attrs_field_defaults, $p['attrs_field'] );

					if ( $p['required'] === true ) {

						$p['attrs_field']['required'] = true;
						$p['attrs_field']['class'][] = 'required';
					}

				// }

				// TEMPLATE {

					$template_data = array();

					$template_data['label'] = '<label' . attrs( $p['attrs_label'] ) . '>' . $p['label'] . '</label>';
					$template_data['field'] = '<input' . attrs( $p['attrs_field'] ) . '>';

					$html .= $this->do_field_template( $p['template'], $template_data, $p );

				// }

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

				foreach ( $messages['field'] as $value ) {

					if ( ! empty( $this->form_messages[ $value ] ) ) {

						$html .= tool( array(
							'name' => 'tool_get_lang_value_from_array',
							'param' => $this->form_messages[ $value ],
						) );
					}
					else if ( is_array( $value ) ) {

						$html .= tool( array(
							'name' => 'tool_get_lang_value_from_array',
							'param' => $value,
						) );
					}
					else {

						$html .= $value;
					}
				}

			$html .= '</span>';

			return $html;
		}

		// EMAIL

		public function do_email( $p = array() ) {

			// CHECKS EMAIL PARAMETERS {



			// }

			// GETS TO {

				$to = $p['email']['email_to'];

			// }

			// GETS SUBJET {

				$subject = $this->apply_email_placeholders( $p['email']['email_subject'] );

			// }

			// GETS HEADERS {

				$arr = array();
				$arr[] = $this->apply_email_placeholders( $p['email']['email_from_name'] );
				$arr[] = '<' . $this->apply_email_placeholders( $p['email']['email_from'] ) . '>';

				$headers = array(
					'From: ' .  implode( ' ', $arr ),
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
									move_uploaded_file( $file['tmp_name'], $attachmentdir . '/' . $file['name'] );
									$attachements[] = $attachmentdir . '/' . $file['name'];
								}

							// }
						}
					}

				// }

			// }

			// GET EMAIL TO {

				$to = $p['email']['email_to']; //

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

				$mail = wp_mail( $to, $subject, $message, $headers, $attachements );

				// MAY REMOVES ATTACHMENTS {

					if ( ! empty( $attachmentdir ) ) {

						foreach ( $attachements as $path) {

							unlink( $path );
						}

						rmdir( $attachmentdir );
					}

				// }

			// }

			// ADD FORM MESSAGE {

				if ( $mail ) {

					wp_redirect( './?emailsent=' . $this->p['form_id'] );
					exit;
				}
				else {

				}

			// }

		}

		private function apply_email_placeholders( $string = '' ) {

			$string = preg_replace_callback('/\{(.+?)\}/i', function( $placeholder ) {

				if ( isset( $this->request[ $placeholder[1] ] ) ) {

					if ( is_string( $this->request[ $placeholder[1] ] ) ) {

						return $this->request[ $placeholder[1] ];
					}

					if ( is_array( $this->request[ $placeholder[1] ] ) ) {

						$divider = ', ';

						foreach ( $this->items as $item ) {

							if ( $item['attrs_field']['name'] === $placeholder[1] ) {

								$divider = $item['value_divider'];
							}
						}

						return implode( $divider, $this->request[ $placeholder[1] ] );
					}
				}

				return '';

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

					if ( false === $this->has_messages ) {

						return true;
					}
				}
			}

			return false;
		}

		public function do_field_template( $template = array(), $data = array(), $p ) {

			$data = apply_filters( 'class/Form/do_field_template/data', $data, $p );

			if ( ! empty( $p['validation_messages'] ) ) {

				$data['validation'] = $this->get_field_validation_html( $p['validation_messages'] );
			}

			$html = '';
			$data_temp = array();

			foreach ( $data as $key => $value ) {

				$data_temp[ '{' . $key . '}' ] = $value;
			}

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

	// DEFINES GLOBAL FIELD AND FORM MESSAGES {

		add_filter( 'class/Form/messages', function( $messages, $param ) {

			$messages['required'] = array(
				'default' => 'This field is required.',
			);

			$messages['field_validation_error'] = array(
				'default' => 'At least one field has an validation error.',
			);

			$messages['email_not_valid'] = array(
				'default' => 'The email is not valid.',
			);

			$messages['email_sent'] = array(
				'default' => 'The email was sent.',
			);

			return $messages;

			}, 10, 2 );

	// }
