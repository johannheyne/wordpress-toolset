<?php

	class Form {

		/*
			Version: v1.0
		*/

		private $p = false;

		private $items = array();

		private $form_messages = array();

		function __construct( $p = array() ) {

			// DEFAULTS {

				$defaults = array(
					'form_id' => '',
					'form_group' => '',
					'form_attrs' => array(
						'role' => 'form',
						'method' => 'get',
						'action' => './',
						'enctype' => 'multipart/form-data',
					),
					'echo' => true,
					'is_request' => false, // whether the form is requested
					'has_messages' => false, // whether the form has massages
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

			// FORM REQUEST ACTION {

				if ( $this->is_form_request( $this->p['form_id'] ) ) {

					$this->p['is_request'] = true;

					// SANITIZE REQUEST
					$this->sanitize_request();
					$this->validate_fields();
					$this->updates_field_values();

					// REQUEST ACTION
					do_action( 'class/Form/request/form_id=' . $this->p['form_id'], $this->p );
					do_action( 'class/Form/request/form_group=' . $this->p['form_group'], $this->p );
					do_action( 'class/Form/request', $this->p );
				}

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
					isset( $_REQUEST[ $item['attrs_field']['name'] ] )
				) {

					if ( $item['type'] === 'text' ) {

						$_REQUEST[ $item['attrs_field']['name'] ] = $this->sanitize_text_field( $_REQUEST[ $item['attrs_field']['name'] ] );
					}
				}
			}
		}

		private function validate_fields() {

			foreach ( $this->items as &$item ) {

				if (
					! empty( $item['validation'] )
				) {

					$value = '';

					if ( isset( $_REQUEST[ $item['attrs_field']['name'] ] ) ) {

						$value = $_REQUEST[ $item['attrs_field']['name'] ];
					}

					$item['validation_messages'] = $item['validation']( $value );
					$this->p['has_messages'] = true;
				}
			}
		}

		private function updates_field_values() {

			foreach ( $this->items as &$item ) {

				if (
					! empty( $item['attrs_field']['name'] ) AND
					isset( $_REQUEST[ $item['attrs_field']['name'] ] )
				) {

					$item['request_value'] = $_REQUEST[ $item['attrs_field']['name'] ];
				}
			}

		}

		// RENDER FUNCTIONALITY

		private function get_fieldset( $html, $fieldset ) {

			$html .= '<fieldset>';
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

			$attrs = apply_filters( 'class/Form/item_attrs', $attrs, $this->p );
			$attrs = apply_filters( 'class/Form/item_attrs/form_group=' . $this->p['form_group'], $attrs, $this->p );
			$attrs = apply_filters( 'class/Form/item_attrs/form_id=' . $this->p['form_id'], $attrs, $this->p );

			$html_item = apply_filters( 'class/Form/before_item', '<div' . attrs( $attrs ) . '>', $this->p );
			$html .= apply_filters( 'class/Form/before_item/form_group=' . $this->p['form_group'], $html_item, $this->p );

				if ( $item['type'] === 'text'  ) {

					$html .= $this->get_text_field( $item );
				}

				if ( $item['type'] === 'taxonomy_select'  ) {

					$html .= $this->get_taxonomy_select_field( $item );
				}

				if ( $item['type'] === 'select'  ) {

					$html .= $this->get_select_field( $item );
				}

				if ( $item['type'] === 'custom'  ) {

					$html .= $this->get_custom_field( $item );
				}

				if ( $item['type'] === 'submit'  ) {

					$html .= $this->get_submit_field( $item );
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
				);

				$p = array_replace_recursive( $defaults, $p );

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

			$html = '<label' . attrs( $p['attrs_label'] ) . '>' . $p['label'] . '</label>';
			$html .= '<input' . attrs( $p['attrs_field'] ) . '>';

			if ( ! empty( $p['validation_messages'] ) ) {

				$html .= $this->get_field_validation_html( $p['validation_messages'] );
			}

			return $html;
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
				);

				$p = array_replace_recursive( $defaults, $p );

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

			// BUILDS SELECT FIELD {

				$html = '';

				if ( ! empty( $list ) ) {

					$html .= '<label' . attrs( $p['attrs_label'] ) . '>' . $p['label'] . '</label>';
					$html .= '<select' . attrs( $p['attrs_field'] ) . '>' . implode( '', $list ) . '</select>';
				}

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
					'current_value' => '',
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
				);

				$p = array_replace_recursive( $defaults, $p );

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

			// BUILDS SELECT FIELD {

				$html = '';

				if ( ! empty( $list ) ) {

					$html .= '<label' . attrs( $p['attrs_label'] ) . '>' . $p['label'] . '</label>';
					$html .= '<select' . attrs( $p['attrs_field'] ) . '>' . implode( '', $list ) . '</select>';
				}

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
				);

				$p = array_replace_recursive( $defaults, $p );

			// }


			// ATTRS FIELD {

				$attrs_field_defaults = array(
					'id' => $p['attrs_field']['name'],
				);

				$p['attrs_field'] = array_replace_recursive( $attrs_field_defaults, $p['attrs_field'] );

			// }

			$html = '<input' . attrs( $p['attrs_field'] ) . '>';

			return $html;
		}

		public function get_custom_field( $p = array() ) {

			// DEFAULTS {

				$defaults = array(
					'callback' => false,
				);

				$p = array_replace_recursive( $defaults, $p );

			// }

			$html = '';

			if ( $p['callback'] ) {

				$html .= $p['callback']();
			}

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

				if ( isset( $_REQUEST[ $name ] ) ) {

					return $_REQUEST[ $name ];
				}

				return $value;
			}
		}

		public function is_form_request( $form_id ) {

			if (
				! empty( $_REQUEST['form_id'] ) AND
				$form_id == $_REQUEST['form_id']
			) {

				return true;
			}

			return false;
		}
	}
