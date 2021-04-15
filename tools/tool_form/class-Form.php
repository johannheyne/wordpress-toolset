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

		private $has_messages = false; // whether the form has massages

		private $request_form_message_keys = array();

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
					),
					'echo' => true,
					'is_request' => false,
					'return' => array( 'fields' ), // what to return on request
				);

				$this->p = array_replace_recursive( $defaults, $p );

			// }

			// REGISTER FORM MESSAGES {

				$this->form_messages = apply_filters( 'class/Form/messages', $this->form_messages, $this->p );
				$this->form_messages = apply_filters( 'class/Form/messages/form_group=' . $this->p['form_group'], $this->form_messages, $this->p );
				$this->form_messages = apply_filters( 'class/Form/messages/form_id=' . $this->p['form_id'], $this->form_messages, $this->p );

			// }

			// REGISTES FIELDSETS {

				$this->items = apply_filters( 'class/Form/fieldsets', $this->items, $this->p );
				$this->items = apply_filters( 'class/Form/fieldsets/form_group=' . $this->p['form_group'], $this->items, $this->p );
				$this->items = apply_filters( 'class/Form/fieldsets/form_id=' . $this->p['form_id'], $this->items, $this->p );

			// }

			// GET FORM ITEMS {

				$this->items = apply_filters( 'class/Form/items', $this->items, $this->p );
				$this->items = apply_filters( 'class/Form/items/form_id=' . $this->p['form_id'], $this->items, $this->p );
				$this->items = apply_filters( 'class/Form/items/form_group=' . $this->p['form_group'], $this->items, $this->p );

			// }

			// ADDS FILTERS {

				if ( ! has_filter( 'class/Form/required?type=checkboxes' ) ) {

					add_filter( 'class/Form/required?type=checkboxes', array( $this, 'filter_checkboxes_custom_field_key_required' ), 10, 2 );
				}


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

					// FILTERS

					add_filter( 'class/Form/form_prepend', array( $this, 'gets_form_messages_html' ), 10, 2 );

					// REQUEST ACTION

					do_action( 'class/Form/request/form_id=' . $this->p['form_id'], $this->p );
					do_action( 'class/Form/request/form_group=' . $this->p['form_group'], $this->p );
					do_action( 'class/Form/request', $this->p );
				}

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

			$html = '<form' . attrs( $attrs ) . '>';

				$html .= '<input type="hidden" name="form_id" value="' . $this->p['form_id'] . '" />';

				// FORM PREPEND {

					$html = apply_filters( 'class/Form/form_prepend', $html, $this->p );
					$html = apply_filters( 'class/Form/form_prepend/form_group=' . $this->p['form_group'], $html, $this->p );

				// }

				if ( in_array( 'fields', $this->p['return'] ) ) {

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
				}

				// FORM APPEND {

					$html = apply_filters( 'class/Form/form_append/form_group=' . $this->p['form_group'], $html, $this->p );
					$html = apply_filters( 'class/Form/form_append', $html, $this->p );

				// }

			$html .= '</form>';

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
						$required = apply_filters( 'class/Form/required?type=' . $item['type'], $required, $item );

						if ( true === $required ) {

							$item['validation_messages']['field'][] = 'required';
							$item['validation_messages']['form'][] = 'field_validation_error';

							$this->has_messages['fields'] = true;
						}

					}

				// }

				// CUSTOM VALIDATIONS {

					if (
						! empty( $item['validation'] ) AND
						isset( $this->request[ $item['attrs_field']['name'] ] )
					) {

						$validation_return = $item['validation']( $this->request[ $item['attrs_field']['name'] ] );

						if ( ! empty( $validation_return['field'] )  ) {

							$item['validation_messages']['field'] = array_replace_recursive( $item['validation_messages']['field'], $validation_return['field'] );
						}

						if ( ! empty( $validation_return['form'] )  ) {

							$item['validation_messages']['form'] = array_replace_recursive( $item['validation_messages']['form'], $validation_return['form'] );
						}

						if ( ! empty( $item['validation_messages']['field'] ) ) {

							$this->has_messages['fields'] = true;
						}

						if ( ! empty( $item['validation_messages']['form'] ) ) {

							$this->has_messages['form'] = true;
						}
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

			if ( $this->request['form_id'] !== $this->p['form_id'] ) {

				return $html;
			}

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

				if ( $item['type'] === 'text'  ) {

					$html .= $this->get_text_field( $item );
				}

				if ( $item['type'] === 'textarea'  ) {

					$html .= $this->get_textarea_field( $item );
				}

				if ( $item['type'] === 'checkbox'  ) {

					$html .= $this->get_checkbox_field( $item );
				}

				if ( $item['type'] === 'checkboxes'  ) {

					$html .= $this->get_checkboxes_field( $item );
				}

				if ( $item['type'] === 'taxonomy_select'  ) {

					$html .= $this->get_taxonomy_select_field( $item );
				}

				if ( $item['type'] === 'select'  ) {

					$html .= $this->get_select_field( $item );
				}

				if ( $item['type'] === 'switch_toggle'  ) {

					$html .= $this->get_switch_toggle_field( $item );
				}

				if ( $item['type'] === 'custom'  ) {

					$html .= $this->get_custom_field( $item );
				}

				if ( $item['type'] === 'submit'  ) {

					$html .= $this->get_submit_field( $item );
				}

				if ( $item['type'] === 'email'  ) {

					$html .= $this->get_email_field( $item );
				}

				$html_item = apply_filters( 'class/Form/after_item', '</div>', $this->p );
				$html .= apply_filters( 'class/Form/after_item/form_group=' . $this->p['form_group'], $html_item, $this->p );

			return $html;
		}

		// FIELD TYPES

		public function get_text_field( $p = array() ) {

			// DEFAULTS {

				$defaults = array(
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
				);

				$p = array_replace_recursive( $defaults, $p );

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

				$html = $this->do_field_template( $p['template'], $template_data, $p );

			// }

			return $html;
		}

		public function get_textarea_field( $p = array() ) {

			// DEFAULTS {

				$defaults = array(
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
				);

				$p = array_replace_recursive( $defaults, $p );

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

				$html = $this->do_field_template( $p['template'], $template_data, $p );

			// }

			return $html;
		}

		public function get_checkbox_field( $p = array() ) {

			// DEFAULTS {

				$defaults = array(
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
				);

				$p = array_replace_recursive( $defaults, $p );

			// }

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

				$html = $this->do_field_template( $p['template'], $template_data, $p );

			// }

			return $html;
		}

		public function get_checkboxes_field( $p = array() ) {

			// DEFAULTS {

				$defaults = array(
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
				);

				$p = array_replace_recursive( $defaults, $p );

			// }

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

						foreach ( $p['checkboxes'] as $item ) {

							$field_key = '';

							if ( isset( $item['attrs_field']['name'] ) ) {

								$field_key = $item['attrs_field']['name'];
								unset( $item['attrs_field']['name'] );
							}

							$attrs_field = array_replace_recursive( $p['attrs_field'], $item['attrs_field'], );

							$attrs_field['id'] = $p['attrs_field']['name'];

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

				$html = $this->do_field_template( $p['template'], $template_data, $p );

			// }

			return $html;
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

		public function get_taxonomy_select_field( $p = array() ) {

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

			// DEFAULTS {

				$defaults = array(
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
				);

				$p = array_replace_recursive( $defaults, $p );

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

				$html = $this->do_field_template( $p['template'], $template_data, $p );

			// }

			return $html;
		}

		public function get_select_field( $p = array() ) {

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

			// DEFAULTS {

				$defaults = array(
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
				);

				$p = array_replace_recursive( $defaults, $p );

			// }

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

				$html = $this->do_field_template( $p['template'], $template_data, $p );

			// }

			return $html;
		}

		public function get_switch_toggle_field( $p = array() ) {

			// DEFAULTS {

				$defaults = array(
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
				);

				$p = array_replace_recursive( $defaults, $p );

			// }

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

				$html = $this->do_field_template( $p['template'], $template_data, $p );

			// }

			return $html;
		}

		public function get_submit_field( $p = array() ) {

			// DEFAULTS {

				$defaults = array(
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
				);

				$p = array_replace_recursive( $defaults, $p );

			// }

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

				$html = $this->do_field_template( $p['template'], $template_data, $p );

			// }

			return $html;
		}

		public function get_custom_field( $p = array() ) {

			// DEFAULTS {

				$defaults = array(
					'callback' => false,
					'template' => array(
						'{field}',
					),

				);

				$p = array_replace_recursive( $defaults, $p );

			// }

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

				$html = $this->do_field_template( $p['template'], $template_data, $p );

			// }

			return $html;
		}

		public function get_email_field( $p = array() ) {

			// DEFAULTS {

				$defaults = array(
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
				);

				$p = array_replace_recursive( $defaults, $p );

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

				$html = $this->do_field_template( $p['template'], $template_data, $p );

			// }

			return $html;
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
