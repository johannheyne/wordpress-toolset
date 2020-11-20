<?php

	class Form {

		/*
			Version: v1.0
		*/

		public $p = false;

		public $settings = false;

		public $items = array();

		public $form_messages = array();

		public $form_validations = array();

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
					'has_messages' => false, // whether the form has general massages
					'has_form_messages' => false, // whether the form has global massages
					'has_field_messages' => false, // whether a form field has massages
					'templates' => array(
						'field_label' => array(
							'ph' => array(
								'attrs' => array(
								),
							),
							'tpl' => '<label{attrs}>{content}</label>',
						),
						'before_field' => array(
							'ph' => array(
								'attrs' => array(
									'class' => 'before-field',
								),
							),
							'tpl' => '<span{attrs}>{content}</span>',
						),
						'after_field' => array(
							'ph' => array(
								'attrs' => array(
									'class' => 'after-field',
								),
							),
							'tpl' => '<span{attrs}>{content}</span>',
						),
						'field_description' => array(
							'ph' => array(
								'attrs' => array(
									'class' => 'field-description',
								),
							),
							'tpl' => '<p{attrs}>{content}</p>',
						),
						'field_validation_message' => array(
							'ph' => array(
								'attrs' => array(
									'class' => 'field-validation',
								),
							),
							'tpl' => '<span{attrs}>{content}</span>',
						),
						'switch_toggle' => array(
							'ph' => array(
								'attrs_label' => array(),
								'toggle_on_text' => '',
								'toggle_off_text' => '',
							),
							'tpl' => '<label{attrs_label}><span class="switch-toggle"><span class="switch-toggle-on">{toggle_on_text}</span><span class="switch-toggle-off">{toggle_off_text}</span></span></label>',
						),
					),
				);

				$this->p = array_replace_recursive( $defaults, $p );

				// FILTERS TEMPLATES {

					$this->p['templates'] = apply_filters( 'class/Form/templates', $this->p['templates'] );
					$this->p['templates'] = apply_filters( 'class/Form/templates/form_group=' . $this->p['form_group'], $this->p['templates'] );
					$this->p['templates'] = apply_filters( 'class/Form/templates/form_id=' . $this->p['form_id'], $this->p['templates'] );

				// }

			// }

			// REGISTER FORM MESSAGES {

				$this->form_messages = apply_filters( 'class/Form/messages', $this->form_messages, $this->p );
				$this->form_messages = apply_filters( 'class/Form/messages/form_group=' . $this->p['form_group'], $this->form_messages, $this->p );
				$this->form_messages = apply_filters( 'class/Form/messages/form_id=' . $this->p['form_id'], $this->form_messages, $this->p );

			// }

			// REGISTER FORM MESSAGE ITEM {

				$form_messages_item = array(
					'type' => 'form_messages',
					'item_wrapper' => false,
					'pos' => 0,
				);
				$form_messages_item = apply_filters( 'class/Form/messages/settings', $form_messages_item );
				$form_messages_item = apply_filters( 'class/Form/messages/settings/form_group=' . $this->p['form_group'], $form_messages_item );
				$form_messages_item = apply_filters( 'class/Form/messages/settings/form_id=' . $this->p['form_id'], $form_messages_item );
				$this->items[] = $form_messages_item;

			// }

			// ADDS FIELDSETS {

				$this->items = apply_filters( 'class/Form/fieldsets', $this->items, $this->p );
				$this->items = apply_filters( 'class/Form/fieldsets/form_group=' . $this->p['form_group'], $this->items, $this->p );
				$this->items = apply_filters( 'class/Form/fieldsets/form_id=' . $this->p['form_id'], $this->items, $this->p );

			// }

			// ADDS FORM ITEMS {

				$this->items = apply_filters( 'class/Form/items', $this->items, $this->p );
				$this->items = apply_filters( 'class/Form/items/form_id=' . $this->p['form_id'], $this->items, $this->p );
				$this->items = apply_filters( 'class/Form/items/form_group=' . $this->p['form_group'], $this->items, $this->p );

			// }

			// ADDS FORM REQUEST ACTION {

				if ( $this->is_form_request( $this->p['form_id'] ) ) {

					$this->p['is_request'] = true;

					// SANITIZE REQUEST
					$this->sanitize_request();
					$this->validate_fields();
					$this->updates_field_values();

					// REQUEST ACTION
					do_action( 'class/Form/request/form_id=' . $this->p['form_id'], $this->p, $this );
					do_action( 'class/Form/request/form_group=' . $this->p['form_group'], $this->p, $this );
					do_action( 'class/Form/request', $this->p, $this );
				}

			// }

			if ( $this->p['echo'] === true ) {

				echo $this->get_form();
			}
		}

		public function get_form() {

			$attrs = array();

			$attrs = array_replace_recursive( $attrs, $this->p['form_attrs'] );

			// ADDS MESSAGES CLASSES TO FORM ELEMENT {

				if (
					$this->p['has_form_messages'] === true OR
					$this->p['has_field_messages'] === true
				) {

					$attrs['class'][] = 'has-messages';
				}

				if ( $this->p['has_field_messages'] === true ) {

					$attrs['class'][] = 'has-field-messages';
				}

				if ( $this->p['has_form_messages'] === true ) {

					$attrs['class'][] = 'has-form-messages';
				}

			// }

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

					if ( ! empty( $item['validation_messages']['field'] ) ) {

						$this->p['has_messages'] = true;
						$this->p['has_field_messages'] = true;
					}

					if ( ! empty( $item['validation_messages']['form'] ) ) {

						$this->p['has_messages'] = true;
						$this->p['has_form_messages'] = true;
						$this->form_validations += $item['validation_messages']['form'];
					}
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

			$html_field = '';

			if ( $item['type'] === 'text'  ) {

				$html_field = $this->get_text_field( $item );
			}

			if ( $item['type'] === 'taxonomy_select'  ) {

				$html_field = $this->get_taxonomy_select_field( $item );
			}

			if ( $item['type'] === 'select'  ) {

				$html_field = $this->get_select_field( $item );
			}

			if ( $item['type'] === 'switch_toggle'  ) {

				$html_field = $this->get_switch_toggle_field( $item );
			}

			if ( $item['type'] === 'custom'  ) {

				$html_field = $this->get_custom_field( $item );
			}

			if ( $item['type'] === 'submit'  ) {

				$html_field = $this->get_submit_field( $item );
			}

			if ( $item['type'] === 'form_messages'  ) {

				$html_field = $this->get_form_messages( $item );
			}

			if ( ! isset( $item['item_wrapper'] ) ) {

				if (
					isset( $item['validation_messages'] ) AND
					! empty ( $item['validation_messages']['field']
				) ) {

					$attrs['class'][] = 'has-field-message';
				}

				$attrs = apply_filters( 'class/Form/item_attrs', $attrs, $this->p );
				$attrs = apply_filters( 'class/Form/item_attrs/form_group=' . $this->p['form_group'], $attrs, $this->p );
				$attrs = apply_filters( 'class/Form/item_attrs/form_id=' . $this->p['form_id'], $attrs, $this->p );

				$html_item = apply_filters( 'class/Form/before_item', '<div' . attrs( $attrs ) . '>', $this->p );
				$html .= apply_filters( 'class/Form/before_item/form_group=' . $this->p['form_group'], $html_item, $this->p );
			}

			$html .= $html_field;

			if ( ! isset( $item['item_wrapper'] ) ) {

				$html_item = apply_filters( 'class/Form/after_item', '</div>', $this->p );
				$html .= apply_filters( 'class/Form/after_item/form_group=' . $this->p['form_group'], $html_item, $this->p );
			}

			return $html;
		}

		private function get_field_label_html( $p = array() ) {

			// DEFAULTS {

				$defaults = array(
					'label' => false,
					'attrs_label' => false,
				);

				$p = array_replace_recursive( $defaults, $p );

			// }

			$html = '';

			if ( $p['label'] !== false ) {

				$ph = $this->p['templates']['field_label']['ph'];
				$ph['attrs'] = array_replace_recursive( $ph['attrs'], $p['attrs_label'] );
				$ph['content'] = $p['label'];

				$html = $this->do_template(  $this->p['templates']['field_label']['tpl'], $ph );
			}

			return $html;
		}

		private function get_field_description_html( $p = array() ) {

			// DEFAULTS {

				$defaults = array(
					'description' => false,
				);

				$p = array_replace_recursive( $defaults, $p );

			// }

			$html = '';

			if ( $p['description'] !== false ) {

				$ph = $this->p['templates']['field_description']['ph'];
				$ph['content'] = $p['description'];

				$html = $this->do_template(  $this->p['templates']['field_description']['tpl'], $ph );
			}

			return $html;
		}

		private function get_field_before_field_html( $p = array() ) {

			// DEFAULTS {

				$defaults = array(
					'description' => false,
				);

				$p = array_replace_recursive( $defaults, $p );

			// }

			$html = '';

			if ( $p['before_field'] !== false ) {

				$ph = $this->p['templates']['before_field']['ph'];
				$ph['content'] = $p['before_field'];

				$html = $this->do_template(  $this->p['templates']['before_field']['tpl'], $ph );
			}

			return $html;
		}

		private function get_field_after_field_html( $p = array() ) {

			// DEFAULTS {

				$defaults = array(
					'description' => false,
				);

				$p = array_replace_recursive( $defaults, $p );

			// }

			$html = '';

			if ( $p['after_field'] !== false ) {

				$ph = $this->p['templates']['after_field']['ph'];
				$ph['content'] = $p['after_field'];

				$html = $this->do_template(  $this->p['templates']['after_field']['tpl'], $ph );
			}

			return $html;
		}

		private function get_field_validation_message_html( $p = array() ) {

			// DEFAULTS {

				$defaults = array(
				);

				$p = array_replace_recursive( $defaults, $p );

			// }

			$html = '';

			if ( ! $this->is_form_request( $this->p['form_id'] ) ) {

				return $html;
			}

			if ( empty( $p['validation_messages']['field'] ) ) {

				return $html;
			}

			$ph = $this->p['templates']['field_validation_message']['ph'];

			foreach ( $p['validation_messages']['field'] as $value ) {

				if ( ! empty( $this->form_messages[ $value ] ) ) {

					$ph['content'] = tool( array(
						'name' => 'tool_get_lang_value_from_array',
						'param' => $this->form_messages[ $value ],
					) );
				}
				else {

					$ph['content'] = $value;
				}
			}

			$html = $this->do_template(  $this->p['templates']['field_validation_message']['tpl'], $ph );

			return $html;
		}

		public function get_form_messages( $p = array() ) {

			// DEFAULTS {

				$defaults = array();

				$p = array_replace_recursive( $defaults, $p );

			// }

			$html = '<ul class="form-messages">';

				// VALIDATIONS {

					if ( ! empty( $this->form_validations ) ) {

						foreach ( $this->form_validations as $key => $value ) {

							$html .= '<li class="form-messages-item" data-type="validation">';

								if ( ! empty( $this->form_messages[ $value ] ) ) {

									$html .= tool( array(
										'name' => 'tool_get_lang_value_from_array',
										'param' => $this->form_messages[ $value ],
									) );
								}
								else {

									$html .= $value;
								}

							$html .= '</li>';

						}
					}

				// }

			$html .= '</ul>';

			return $html;
		}

		// FIELD TYPES

		public function get_text_field( $p = array() ) {

			// DEFAULTS {

				$defaults = array(
					'label' => false,
					'before_field' => false,
					'after_field' => false,
					'attrs_label' => array(),
					'attrs_field' => array(
						'name' => '',
						'value' => '',
					),
					'required' => false,
					'validation' => false,
					'value' => '',
					'sanitize' => true,
					'description' => false,
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

			// TEMPLATING {

				$template_parts = array();
				$template_parts['label'] = $this->get_field_label_html( $p );
				$template_parts['description'] = $this->get_field_description_html( $p );
				$template_parts['before_field'] = $this->get_field_before_field_html( $p );
				$template_parts['after_field'] = $this->get_field_after_field_html( $p );
				$template_parts['validation'] = $this->get_field_validation_message_html( $p );
				$template_parts['field'] = '<input' . attrs( $p['attrs_field'] ) . '>';

				$html = $this->do_template( $p['template'], $template_parts );

			// }

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
					'label' => '{label_text}',
					'before_field' => false,
					'after_field' => false,
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
					'description' => false,
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

				if ( empty( $list ) ) {

					return '';
				}

				$template_parts = array();
				$template_parts['label'] = $this->get_field_label_html( $p );
				$template_parts['description'] = $this->get_field_description_html( $p );
				$template_parts['before_field'] = $this->get_field_before_field_html( $p );
				$template_parts['after_field'] = $this->get_field_after_field_html( $p );
				$template_parts['validation'] = $this->get_field_validation_message_html( $p );
				$template_parts['field'] = '<select' . attrs( $p['attrs_field'] ) . '>' . implode( '', $list ) . '</select>';

				$html = $this->do_template( $p['template'], $template_parts );

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
					'label' => '',
					'before_field' => false,
					'after_field' => false,
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
					'description' => false,
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

				if ( empty( $list ) ) {

					return '';
				}

				$template_parts = array();
				$template_parts['label'] = $this->get_field_label_html( $p );
				$template_parts['description'] = $this->get_field_description_html( $p );
				$template_parts['before_field'] = $this->get_field_before_field_html( $p );
				$template_parts['after_field'] = $this->get_field_after_field_html( $p );
				$template_parts['validation'] = $this->get_field_validation_message_html( $p );
				$template_parts['field'] = '<select' . attrs( $p['attrs_field'] ) . '>' . implode( '', $list ) . '</select>';

				$html = $this->do_template( $p['template'], $template_parts );

			// }

			return $html;
		}

		public function get_switch_toggle_field( $p = array() ) {

			// DEFAULTS {

				$defaults = array(
					'label' => '',
					'before_field' => false,
					'after_field' => false,
					'attrs_label' => array(),
					'attrs_field' => array(
						'name' => '',
						'value' => 'on',
					),
					'validation' => false,
					'sanitize' => true,
					'description' => false,
					'toggle_on_text' => '',
					'toggle_off_text' => '',
					'template' => array(
						'{field}',
						'{label}',
						'{description}',
						'{before_field}',
						'{toggle}',
						'{after_field}',
						'{validation}',
					),
				);

				$p = array_replace_recursive( $defaults, $p );

			// }

			$html = '';

			// REQUEST VALUE {

				if ( isset( $p['request_value'] ) ) {

					$p['attrs_field']['checked'] = '';
				}

			// }

			// REQUIRED {

				if ( $p['required'] === true ) {

					$p['attrs_field']['required'] = true;
					$p['attrs_field']['class'][] = 'required';
				}

			// }

			// ADDS REQUIRED LABEL AND INPUT ELEMENT ATTRS {

				// <label for=""> {

					if ( empty( $p['attrs_label']['for'] ) ) {

						$p['attrs_label']['for'] = $p['attrs_field']['name'];
					}

				// }

				// <input id=""> {

					if ( empty( $p['attrs_field']['id'] ) ) {

						$p['attrs_field']['id'] = $p['attrs_field']['name'];
					}

				// }

				// <input class="switch-toggle-checkbox"> {

					if ( empty( $p['attrs_field']['class'] ) ) {

						$p['attrs_field']['class'] = array();
					}

					$p['attrs_field']['class'][] = 'switch-toggle-checkbox';

				// }

			// }

			// BUILDS FIELD {

				$template_parts = array();
				$template_parts['field'] = '<input type="checkbox"' . attrs( $p['attrs_field'] ) . '/>';
				$template_parts['label'] = $this->get_field_label_html( $p );
				$template_parts['description'] = $this->get_field_description_html( $p );
				$template_parts['before_field'] = $this->get_field_before_field_html( $p );

				$template_parts['toggle'] = $this->do_template( $this->p['templates']['switch_toggle']['tpl'], array(
					'attrs_label' => $p['attrs_label'],
					'toggle_on_text' => $p['toggle_on_text'],
					'toggle_off_text' => $p['toggle_off_text'],
				) );

				$template_parts['after_field'] = $this->get_field_after_field_html( $p );
				$template_parts['validation'] = $this->get_field_validation_message_html( $p );

				$html = $this->do_template( $p['template'], $template_parts );

			// }

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

		public function get_submit_field( $p = array() ) {

			// DEFAULTS {

				$defaults = array(
					'type' => 'submit',
					'before_field' => false,
					'after_field' => false,
					'attrs_field' => array(
						'type' => 'submit',
						'name' => '',
						'value' => '',
					),
					'description' => false,
					'template' => array(
						'{before_field}',
						'{field}',
						'{after_field}',
						'{description}',
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

			// TEMPLATING {

				$template_parts = array();
				$template_parts['description'] = $this->get_field_description_html( $p );
				$template_parts['before_field'] = $this->get_field_before_field_html( $p );
				$template_parts['after_field'] = $this->get_field_after_field_html( $p );
				$template_parts['field'] = '<input' . attrs( $p['attrs_field'] ) . '>';

				$html = $this->do_template( $p['template'], $template_parts );

			// }

			return $html;
		}

		// SANITIZING

		public function sanitize_text_field( $string ) {

			$string = sanitize_text_field( $string );

			return $string;
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

		private function do_template( $template, $placeholders ) {

			/*
				$template: array/string
					array( '<div{one_attrs}>{one_content}</div>', '<div{two_attrs}>{two_content}</div>' )
					string '<div{one_attrs}>{one_content}</div>'

				$placeholders: array

					Note:	string 'attrs' in placeholder name converts
							placeholder array to key="value"

					array(
						'attrs_one' => array(
							'class' => 'one',
						),
						'content_one' => array( '<h1>Hello</h1>', '<p>World</p>' ),
						'attrs_two' => array(
							'class' => 'two',
						),
						'content_two' => '<h1>Hello</h1><p>World</p>',
					)
			*/

			// TEMPLATE ARRAY TO STRING {

				if ( is_array( $template ) ) {

					$template = implode( '', $template );
				}

			// }

			// DO PLACEHOLDERS {

				foreach ( $placeholders as $key => $value ) {

					// DETECT ATTRS {

						if ( strpos( $key, 'attrs' ) !== false ) {

							$value = attrs( $value );
						}

					// }

					// DETECT ARRAYS {

						if ( is_array( $value ) ) {

							$value = implode( '', $value );
						}

					// }

					$template = str_replace( '{' . $key . '}', $value, $template );
				}

			// }

			// REMOVES LEFTOVER PLACEHOLDERS {

				$template = preg_replace( '/\{\w+\}/i', '', $template );

			// }

			return $template;
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
