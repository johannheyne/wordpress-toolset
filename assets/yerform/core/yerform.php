<?php

	/**
	* Yerform
	*
	* an e-mail form class for PHP 5.3.28 or newer
	*
	* @version		1.0
	* @author		Johann Heyne
	* @copyright	Copyright (c) Johann Heyne
	* @license		MIT
	* @link			https://github.com/johannheyne/yerform
	*/

	class YerForm {

		protected $list_before = '<div>';
		protected $list_after = '</div>';
		protected $list_item_before = '<div>';
		protected $list_item_after = '</div>';
		protected $label_before = '<div>';
		protected $label_after = '</div>';
		protected $fields_before = '<div class="yerform-fields-wrap">';
		protected $fields_after = '</div>';
		protected $field_before = '<div class="yerform-field">';
		protected $field_after = '</div>';
		protected $messagetable_before = '<div class="yerform-message-table">';
		protected $messagetable_after = '</div>';
		protected $fieldtable_before = '<div class="yerform-field-table">';
		protected $fieldtable_after = '</div>';
		protected $infoaftertable_before = '<div class="yerform-infoafter-table">';
		protected $infoaftertable_after = '</div>';
		protected $fieldcell_before = '<div class="yerform-field-cell">';
		protected $fieldcell_after = '</div>';

		protected $depht = 1;
		protected $code = '';
		protected $request = false;
		protected $files = false;
		protected $sent = false;
		protected $set = false;
		protected $fields = false;
		protected $config = false;
		protected $expiretime = 3600;
		protected $p_list = array();
		protected $p_group = array();
		protected $fields_defaults = array();
		protected $dateformats_phpjs = array();
		protected $textcurr;

		public $form_id = 'yerform';
		public $field_text_size = 40;
		public $field_text_maxlength = 200;
		public $field_textarea_cols = 70;
		public $field_textarea_rows = 7;
		public $required_label_sufix = '<span class="required">*</span>';
		public $messages = false;
		public $validation = false;
		public $text = false;

		public function __construct() {

			$this->config['honeypot'] = false;

			$this->set_fields_defaults();
			$this->set_dateformats_phpjs();

			if (
				$_REQUEST &&
				isset( $_REQUEST['yerform-check'] ) &&
				$_REQUEST['yerform-check'] + $this->expiretime > time()
			) {

				foreach ( $_REQUEST as $key => $value ) {

					$this->request[ $key ] = $this->sanitize( $value );
				}

				$this->files = $_FILES;
			}
		}

		/** 
		* setup default parameters for fields
		*/

		protected function set_fields_defaults() {

			$this->fields_defaults = array(

				'field_text' => array(
					'label' => false, 
					'name' => false,
					'array' => false,
					'size' => false,
					'maxlength' => $this->field_text_maxlength,
					'padding' => array(0,0),
					'layout' => false,
					'placeholder' => false,
					'class' => false
				),
				'field_textarea' => array(
					'label' => false, 
					'name' => false,
					'array' => false,
					'cols' => $this->field_textarea_cols,
					'rows' => $this->field_textarea_rows,
					'padding' => array(0,0),
					'layout' => false
				),
				'field_select' => array(
					'label' => false, 
					'name' => false,
					'value' => '',
					'array' => false,
					'padding' => array(0,0),
					'layout' => false,
					'data' => array( '' => 'choose…' )
				),
				'field_date' => array(
					'label' => false, 
					'name' => false,
					'use_field_type' => 'date',
					'array' => false,
					'size' => false,
					'maxlength' => $this->field_text_maxlength,
					'padding' => array(0,0),
					'layout' => false,
					'returnformat' => 'd.m.Y',
					'datepicker' => false,
					'datepicker-mindate' => 0,
					'datepicker-maxdate' => 0,
					'datepicker-dateformat' => 'd.m.Y',
					'datepicker-iconurl' => false,
					'validation' => false
				),
				'field_checkbox' => array(
					'label' => false, 
					'name' => false,
					'array' => false,
					'data' => 'checked',
					'checked' => false,
					'padding' => array(0,0),
					'layout' => false,
					'labeltype' => 'field-after'
				),
				'field_radio' => array(
					'label' => false, 
					'name' => false,
					'array' => false,
					'data' => 'checked',
					'checked' => false,
					'padding' => array(0,0),
					'layout' => false,
					'labeltype' => 'field-after'
				),
				'field_file' => array(
					'label' => false, 
					'name' => false,
					'array' => false,
					'size' => $this->field_text_size / 2,
					'padding' => array(0,0),
					'layout' => false
				),
				'field_html' => array(
					'padding' => array(0,0),
					'content' => ''
				),
				'field_hidden' => array(
					'name' => false,
					'array' => false,
					'value' => false				
				)
			);
		}

		/** 
		* setup default parameters for fields
		*/

		protected function set_dateformats_phpjs() {

			$this->dateformats_phpjs = array(
				 'd' => 'dd', // day of month (two digit)
				 'j' => 'd', // day of month (no leading zero)
				 'z' => 'o', // day of the year (no leading zeros)
				 'D' => 'D', // day name short
				 'l' => 'DD', // day name long
				 'm' => 'mm', // month of year (two digit)
				 'n' => 'm', // month of year (no leading zero)
				 'M' => 'M', // month name short
				 'F' => 'MM', // month name long
				 'y' => 'y', // year (two digit)
				 'Y' => 'yy', // year (four digit)
				 'U' => '@', // Unix timestamp (ms since 01/01/1970)
			);
		}

		/** 
		* sanitizing
		*/

		protected function sanitize( $string ) {

			$string = strip_tags( $string );
			$string = htmlspecialchars( $string, ENT_QUOTES );
			return $string;
		}

		/** 
		* configuration
		*/

		public function config( $p = array() ) {

			$defaults = array(
				'form_class' => '',
				'action' => '',
				'sent_page' => false,
				'honeypot' => false,
				'mail_form' => false,
				'mail_send_script' => 'swift',
				'mail_send_methode' => 'phpmail', // phpmail, sendmail, smtp
				'mail_send_config' => array(
					'sendmail' => array(
						'path' => false // OSX MAMP: '/usr/sbin/sendmail -t -i -f' 
					),
					'smtp' => array(
						'server' => false,
						'port' => false,
						'user' => false,
						'password' => false,
						'ssl' => false
					)
				),
				'mail_text' => false,
				'mail_subject' => false,
				'call_function_on_validation_is_true' => false,
				'message_after_function_was_fired' => false,
				'sender_mail' => false,
				'sender_name' => false,
				'field_sender_mail' => false,
				'fields_sender_name' => false,
				'recipient_mail' => false,
				'recipient_name' => false,
				'language' => 'en-US'
			);

			$p = array_replace_recursive( $defaults, $p );

			$this->config = array_replace_recursive( $this->config, $p );

			$defaults = array(
				'message_error_main' => array( 'typ'=>'error', 'text'=>'Could not send form! Check the following fields: {fields}' ),
				'message_sending' => array( 'typ'=>'info', 'text'=>'Sending!' ),
				'message_sent' => array( 'typ'=>'info', 'text'=>'Sent!' ),
				'message_honeypot' => array( 'typ'=>'info', 'text'=>'Yer cheating!' ),
				'messages_validation' => array(
					'required' => array( 'text'=>'required' ),
					'email' => array( 'text'=>'invalid' )
				),
				'message_checkdate' => 'date does not exists',
				'message_dateformat' => 'please format the date like 01.06.2013',
				'fieldset' => array(
					'require_info' => array(
						'text' => 'Fields marked with {require_symbol} are reqired.'
					)
				)
			);

			$this->text[ $this->config['language' ] ] = array_replace_recursive( $defaults, $this->text[ $this->config['language' ] ] );

			$this->textcurr = $this->text[ $this->config['language' ] ];
		}

		/** 
		* collecting all formparameter.
		*/

		public function set( $f , $p = array() ) {

			$p += array(
				'display' => true
			);

			/* if there is a field for the email of the form sender,
			   than make sure, to have the validationoptions for required and email.
			*/

			if (
				$this->config['mail_form'] &&
				$this->config['field_sender_mail'] &&
				isset( $p['name'] ) &&
				$p['name'] === $this->config['field_sender_mail']
			) {

				if ( isset( $p['validation'] ) ) {

					$p['validation'] += array(
						998 => array(
							'type' => 'required',
							'cond' => true,
							'message' => $this->textcurr['messages_validation']['required']['text']
						),
						999 => array(
							'type' => 'email',
							'message' => $this->textcurr['messages_validation']['email']['text']
						)
					);
				}
				else {

					$p += array(
						'validation' => array(
							0 => array(
								'type' => 'required',
								'cond' => true,
								'message' => $this->textcurr['messages_validation']['required']['text']
							),
							1 => array(
								'type' => 'email',
								'message' => $this->textcurr['messages_validation']['email']['text']
							)
						)
					);
				}
			}

			$this->set[] = array(
				'f' => $f,
				'p' => $p
			);

			if (
				$f === 'field_hidden' OR
				$f === 'field_text' OR
				$f === 'field_textarea' OR
				$f === 'field_select' OR
				$f === 'field_checkbox' OR
				$f === 'field_radio' OR
				$f === 'field_file' OR
				$f === 'field_date'
			) {

				$this->fields[ $p['name'] ] = $p;
			}
		}

		/** 
		* runs the workflow ( validation, sending, formbuilding )
		*/

		public function run( $p = array() ) {

			/*
				Parameter
				output: echo, return
			*/

			$p += array(
				'output' => 'echo'
			);

			$ret = '';
			$show_form = true;

			// if request
			if ( $this->request ) {

				// do validation
				$this->validation();

				// if not valid set error message
				if ( $this->validation !== false ) {

					$this->messages['message_error_main'] = true;
				}

				// if valid then send mail and build message
				if (
					$this->config['mail_form'] &&
					$this->validation === false
				) {

					$this->send_mail();
				}

				// if valid then fire function
				if (
					$this->config['call_function_on_validation_is_true'] &&
					$this->validation === false
				) {

					$ret_func = call_user_func($this->config['call_function_on_validation_is_true'], array(
						'request' => $this->request
					));

					$ret_func += array(
						'request' => true,
					);

					if ( !$return['request']  ) {

						$this->request = false;
						$this->sent = true;
						$show_form = false;

					}
				}
			}

			// mail sending
			if ( $this->sent === true ) {

				$this->messages['message_sending'] = true;
				$this->messages();
				$show_form = false;

				if (
					$_GET AND
					isset( $_GET['ajax'] )
				) {

				}
				else {

					$ret .= '<meta http-equiv="refresh" content="0; URL=' . $this->config['sent_page'] . '?sent=true">';
				}
			}

			// mail sent
			elseif (
				$this->config['mail_form'] AND
				$_GET AND
				isset( $_GET['sent'] ) AND
				$_GET['sent'] === 'true'
			) {

				$this->messages['message_sent'] = true;
				$this->messages();
				$show_form = false;
			}

			elseif (
				$this->config['call_function_on_validation_is_true'] AND
				isset( $_GET['sent'] ) AND
				$_GET['sent'] === 'true'
			) {

				if ( $this->config['message_after_function_was_fired'] ) {

					$this->messages['message_after_function_was_fired'] = true;
					$this->messages();
					$show_form = false;
				}
			}

			// if not send or fired a function show form
			if ( $show_form ) {

				// honeypot
				if ( $this->config['honeypot'] ) {

					$this->list_begin( array( 'class' => 'yerform-invisible' ) );

					$this->field_text( array(
						'name' => strtolower( $this->config['honeypot'] ),
						'label' => $this->config['honeypot'],
						'class' => strtolower( $this->config['honeypot'] )
						)
					);

					$this->list_end();
				}

				// walk the form settings
				foreach( $this->set as $key => $item) {

					if ( $item['p']['display'] === true ) {

						if ( $item['f'] === 'field_hidden' ) {

							$this->field_hidden( $item['p'] );
						}
					}
				}

				foreach( $this->set as $key => $item) {

					if ( $item['p']['display'] === true ) {

						if ( $item['f'] === 'list_begin' )		{ $this->list_begin( $item['p'] ); }
						if ( $item['f'] === 'list_end' )		{ $this->list_end(); }
						if ( $item['f'] === 'group_begin' )		{ $this->group_begin( $item['p'] ); }
						if ( $item['f'] === 'group_end' )		{ $this->group_end(); }
						if ( $item['f'] === 'field_text' )		{ $this->field_text( $item['p'] ); }
						if ( $item['f'] === 'field_textarea' )	{ $this->field_textarea( $item['p'] ); }
						if ( $item['f'] === 'field_select' )	{ $this->field_select( $item['p'] ); }
						if ( $item['f'] === 'field_checkbox' )	{ $this->field_checkbox( $item['p'] ); }
						if ( $item['f'] === 'field_radio' )		{ $this->field_radio( $item['p'] ); }
						if ( $item['f'] === 'field_date' )		{ $this->field_date( $item['p'] ); }
						if ( $item['f'] === 'field_file' )		{ $this->field_file( $item['p'] ); }
						if ( $item['f'] === 'field_html' )		{ $this->field_html( $item['p'] ); }
						if ( $item['f'] === 'form_buttons' )	{ $this->form_buttons( $item['p'] ); }
						if ( $item['f'] === 'fieldset_begin' )	{ $this->fieldset_begin( $item['p'] ); }
						if ( $item['f'] === 'fieldset_end' )	{ $this->fieldset_end( $item['p'] ); }
						if ( $item['f'] === 'require_info' )	{ $this->require_info( $item['p'] ); }
						if ( $item['f'] === 'messages' )		{ $this->messages( $item['p'] ); }
					}
				}
			}

			$ret .= $this->get_form();

			if ( $p['output'] == 'echo' ) {

				echo $ret;
			}

			if ( $p['output'] == 'return' ) {

				return $ret;
			}
		}

		/** 
		* validation
		*/

		protected function validation() {

			/* go thru each item of set */
			foreach( $this->set as $num => $field ) {

				$f = $field['f'];
				$p = $field['p'];

				/* issit a field for validation */
				if ( 
					$f === 'field_text' OR
					$f === 'field_textarea' OR
					$f === 'field_date' OR	
					$f === 'field_checkbox' OR	
					$f === 'field_radio' OR	
					$f === 'field_date' OR	
					$f === 'field_file' OR	
					$f === 'field_select'	
				) {

				$p += $this->fields_defaults[ $f ];

				/* add default validation types to field */

				if ( $f === 'field_date' ) {

					/* add validations */
					$p['validation'][-2] = array(
						'type' => 'date-format'
					);

					$p['validation'][-1] = array(
						'type' => 'date-checkdate'
					);

					ksort($p['validation']);
				}

				/* before validation of the field */

				if ( $f === 'field_date' ) {

					// use a temporare var of request
					$p['temp_value'] = $this->request[ $p['name'] ];

					// get the value from alternate field of datepicker if exists
					if ( $this->request[ $p['name'] . '_yerform' ] !== '' ) {

						$p['temp_value'] = $this->request[ $p['name'] . '_yerform' ];
					}

					$p['timestamp'] = false;
					$p['date_parsed'] = false;

					if ( $p['temp_value'] !== '' ) {

						// timestamp
						$p['timestamp'] = strtotime( $p['temp_value'] );

						/* may reformat the date anyway in dd.mm.yy because of iOS date UI-datepicker inputs wont fit */
						$p['temp_value'] = date( 'd.m.Y', $p['timestamp'] );

						/* parse day */
						$p['date_parsed'] = date_parse( $p['temp_value'] );
					}
				}

				/* are there validations for this field */
				if ( isset( $p['validation'] ) ) {

					/* go thru each validation-rule of the field */
					foreach( $p['validation'] as $key => $valid ) {

						// type of required, disables all other validations rules 
						if (
							$valid['type'] === 'required' AND
							$valid['cond'] === true
						) {

							if ( ! isset( $valid['message'] ) ) {

								if ( isset( $this->textcurr['messages_validation']['required']['text'] ) ) {

									$valid['message'] = $this->textcurr['messages_validation']['required']['text'];
								}
								
								if ( isset( $this->textcurr['fields'][ $p['name'] ]['required']['text'] ) ) {

									$valid['message'] = $this->textcurr['fields'][ $p['name'] ]['validation']['required']['text'];
								}
							}

							// FILES
							if (
								! isset( $this->request[ $p['name'] ] ) AND
								isset( $this->files[ $p['name'] ] ) AND
								$this->files[ $p['name'] ]['error'] !== 0
							) {

								$this->validation[ $p['name'] ][] = $valid['message'];
							}
							
							// CHECKBOX / RADIO
							if (
								! isset( $this->request[ $p['name'] ] ) AND
								! isset( $this->files[ $p['name'] ] )
							) {

								$this->validation[ $p['name'] ][] = $valid['message'];
							}

							// OTHER FIELDS
							if (
								isset( $this->request[ $p['name'] ] ) AND
								$this->request[ $p['name'] ] === ''
							) {

								$this->validation[ $p['name'] ][] = $valid['message'];
							}
						}

						// all other validation rules 
						if ( !isset( $this->validation[ $p['name'] ] ) ) {

							// if
							if ( $valid['type'] === 'if' ) {

								if ( $this->ifit( $valid['value'], $valid['operator'], $this->request[ $p['name'] ] ) ) {

									if ( isset( $this->textcurr['messages_validation']['if']['text'] ) ) {

										$valid['message'] = $this->textcurr['messages_validation']['if']['text'];
									}

									if ( isset( $this->textcurr['fields'][ $p['name'] ]['if']['text'] ) ) {

										$valid['message'] = $this->textcurr['fields'][ $p['name'] ]['validation']['if']['text'];
									}
									
									$this->validation[ $p['name'] ][] = $valid['message'];
								}
							}

							// expression
							if ( $valid['type'] === 'expression' ) {

								if ( !ereg( $valid['cond'], $this->request[ $p['name'] ] ) ) {

									if ( isset( $this->textcurr['messages_validation']['expression']['text'] ) ) {

										$valid['message'] = $this->textcurr['messages_validation']['expression']['text'];
									}

									if ( isset( $this->textcurr['fields'][ $p['name'] ]['expression']['text'] ) ) {

										$valid['message'] = $this->textcurr['fields'][ $p['name'] ]['validation']['expression']['text'];
									}

									$this->validation[ $p['name'] ][] = $valid['message'];
								}
							}

							// dateformat
							if ( $valid['type'] === 'date-format' ) {

								if (
									$p['temp_value'] !== '' &&
									! ereg( "^[0-9]{2}\.[0-9]{2}\.[0-9]{4}$", $p['temp_value'] )
								) {

									if ( isset( $this->textcurr['messages_validation']['dateformat']['text'] ) ) {

										$valid['message'] = $this->textcurr['messages_validation']['dateformat']['text'];
									}

									if ( isset( $this->textcurr['fields'][ $p['name'] ]['dateformat']['text'] ) ) {

										$valid['message'] = $this->textcurr['fields'][ $p['name'] ]['validation']['dateformat']['text'];
									}

									$this->validation[ $p['name'] ][] = $this->config['message_dateformat'];
								}
							}

							// checkdate
							if ( $valid['type'] === 'date-checkdate' ) {

								if (
									$p['temp_value'] !== '' &&
									! isset( $this->validation[ $p['name'] ] ) AND
									! checkdate( $p['date_parsed']['month'], $p['date_parsed']['day'], $p['date_parsed']['year'] )
								) {

									if ( isset( $this->textcurr['messages_validation']['checkdate']['text'] ) ) {

										$valid['message'] = $this->textcurr['messages_validation']['checkdate']['text'];
									}

									if ( isset( $this->textcurr['fields'][ $p['name'] ]['checkdate']['text'] ) ) {

										$valid['message'] = $this->textcurr['fields'][ $p['name'] ]['validation']['checkdate']['text'];
									}

									$this->validation[ $p['name'] ][] = $this->config['message_checkdate'];
								}
							}

							// date
							if ( $valid['type'] === 'date' ) {

								// min-max
								if ( !isset( $this->validation[ $p['name'] ] ) ) {

									if (
										isset( $valid['min'] ) OR
										isset( $valid['max'] )
									) {

										$array = array (
											"{min}" => date( "d.m.Y", $this->datestamp( $valid['min'] ) ),
											"{max}" => date( "d.m.Y", $this->datestamp( $valid['max'] ) )
										);
										$valid['message-min-max'] = strtr($valid['message-min-max'], $array);
									}

									if ( isset( $valid['min'] ) ) {

										if ( $p['timestamp'] < $this->datestamp( $valid['min'] ) ) {

											$this->validation[ $p['name'] ][] = $valid['message-min-max'];
										}
									}

									if ( isset( $valid['max'] ) ) {

										if ( $p['timestamp'] > $this->datestamp( $valid['max'] ) ) {

											$this->validation[ $p['name'] ][] = $valid['message-min-max'];
										}
									}
								}

								// dependency
								if (
									! isset( $this->validation[ $p['name'] ] ) AND
									isset( $valid['dependency'] )
								) {

									$date_dep = explode( '.', $this->request[ $valid['dependency']['field'] ] );

									$timestamp_dep = strtotime( $valid['dependency']['value'] , strtotime( $date_dep[2] . '-' . $date_dep[1] . '-' . $date_dep[0] ) );
									$value = $timestamp - $timestamp_dep;

									if ( ! $this->ifit( $value, $valid['dependency']['operator'], 0 ) ) {

										$this->validation[ $p['name'] ][] =	 $valid['dependency']['message'];
									}
								}

							}

							// integer
							 if ( $valid['type'] === 'integer' ) {

								 if ( $this->get_field_value( $p ) !== '' ) {

									if (
										! is_numeric( $this->get_field_value( $p ) ) OR
										(int)$this->get_field_value( $p ) != $this->get_field_value( $p )
									) {

										if ( isset( $this->textcurr['messages_validation']['integer']['text'] ) ) {

											$valid['message'] = $this->textcurr['messages_validation']['integer']['text'];
										}

										if ( isset( $this->textcurr['fields'][ $p['name'] ]['integer']['text'] ) ) {

											$valid['message'] = $this->textcurr['fields'][ $p['name'] ]['validation']['integer']['text'];
										}
										
										$this->validation[ $this->get_field_name( $p ) ][] = $valid['message'];
									 }
								 }
							 }

							// range
							if (
								$valid['type'] === 'range' AND
								! isset( $this->validation[ $this->get_field_name( $p ) ] ) AND
								$this->get_field_value( $p ) != ''
							) {

									if (
										(float)$this->get_field_value( $p ) < (float)$valid['min'] OR
										(float)$this->get_field_value( $p ) > (float)$valid['max']
									) {

										$array = array (
											"{min}" => $valid['min'],
											"{max}" => $valid['max']
										);
										$valid['message'] = strtr($valid['message'], $array);

										$this->validation[ $this->get_field_name( $p ) ][] = $valid['message'];
									}
								}
							}

							// email
							if ( $valid['type'] === 'email' ) {

								if ( $this->get_field_value( $p ) !== '' ) {

									if ( ! filter_var( $this->get_field_value( $p ), FILTER_VALIDATE_EMAIL ) ) {

										if ( isset( $this->textcurr['messages_validation']['email']['text'] ) ) {

											$valid['message'] = $this->textcurr['messages_validation']['email']['text'];
										}

										if ( isset( $this->textcurr['fields'][ $p['name'] ]['email']['text'] ) ) {

											$valid['message'] = $this->textcurr['fields'][ $p['name'] ]['validation']['email']['text'];
										}

										$this->validation[ $this->get_field_name( $p ) ][] = $valid['message'];
									}
								}
							}
						}
					}
				}

				/* after validation of field */
				if ( $f === 'field_date' ) {

					$this->set[ $num ]['p']['timestamp'] = $p['timestamp'];
					$this->set[ $num ]['p']['temp_value'] = $p['temp_value'];
					$this->set[ $num ]['p']['temp_value_yerform'] = $this->request[ $p['name'] . '_yerform' ];
					$this->set[ $num ]['p']['temp_request'] = date( $p['returnformat'], (int)$p['timestamp'] );
				}

			}

			/* check honeypot */
			if (
				$this->config['honeypot'] AND
				$this->request[ strtolower( $this->config['honeypot'] ) ] != ''
			) {

				$this->validation = true;
				$this->messages['message_honeypot'] = true;
			}

			/* after validations succsess */
			if ( $this->validation === false )	{

				foreach( $this->set as $num => $field ) {

					$p = $field['p'];

					if (
						isset( $p['name'] ) &&
						isset( $p['temp_request'] )
					) {

						$this->request[ $p['name'] ] = $p['temp_request'];
					}

					if (
						isset( $p['name'] ) &&
						isset( $this->request[ $p['name'] . '_yerform' ] )
					) {

						unset($this->request[ $p['name'] . '_yerform' ]);
					}

					if (
						isset( $p['timestamp'] ) &&
						$p['timestamp']
					) {

						$this->request[ $p['name'] . '.timestamp'] = $p['timestamp'];
					}
				}
			}

		}

		/** 
		* sending mail
		*/

		protected function send_mail() {

			/* sender email */
			$sender_mail = false;
			if ( $this->config['sender_mail'] ) {

				$sender_mail = $this->config['sender_mail'];
			}
			if ( $this->config['field_sender_mail'] ) {

				$sender_mail = $this->request[ $this->config['field_sender_mail'] ];
			}

			/* sender name */
			$sender_name = false;
			if ( $this->config['fields_sender_name'] ) {

				foreach ( $this->config['fields_sender_name'] as $key => $value ) {

					$sender_name .= $this->request[ $value ] . ' ';
				}
			}
			if ( $this->config['sender_name'] ) {

				$sender_name = $this->config['sender_name'];
			}
			$sender_name = trim( $sender_name );

			/* mail subject */
			$mail_subject = $this->config['mail_subject'];

			/* mailtext */
			$mail_text = $this->config['mail_text'];

			// get off the whitespace of lines
			$mail_text_arr = explode( "\n", $mail_text );
			foreach($mail_text_arr as $key => $value) {

				$mail_text_arr[ $key ]	= trim( $value );
			}
			$mail_text = implode( "\n", $mail_text_arr );

			// fill placeholders
			$array = false;

			foreach( $this->fields as $name => $item) {

				$value = $_REQUEST[ $name ];

				if ( is_array($value) ) {

					$value = trim( implode( ', ', $value ), ', ' );
				}

				$array['{' . $name . '}'] = $value;
			}

			$mail_text = trim( strtr($mail_text, $array) );

			// mail it with Swiftmailer

			require_once( dirname(__FILE__) . '/../assets/swift/lib/swift_required.php' );

			if ( $this->config['mail_send_script'] === 'swift' ) {

				// Create the Transport

					// if ( function_exists('proc_open') ) { print_o( 'yes');  } else { print_o( 'no' ); }

					/* PHP Mail */

					if ( $this->config['mail_send_methode'] === 'phpmail' ) {

						$transport = Swift_MailTransport::newInstance();
					}

					/* Sendmail */

					if ( $this->config['mail_send_methode'] === 'sendmail' ) {

						$transport = Swift_SendmailTransport::newInstance( $this->config['mail_send_config']['sendmail']['path'] . ' ' . $this->config['recipient_mail'] );
					}

					/* SMTP */

					if ( $this->config['mail_send_methode'] === 'smtp' ) {

						$transport = Swift_SmtpTransport::newInstance();

						if ( $this->config['mail_send_config']['smtp']['server'] ) {

							$transport->setHost( $this->config['mail_send_config']['smtp']['server'] );
						}

						if ( $this->config['mail_send_config']['smtp']['port'] ) {

							$transport->setPort( $this->config['mail_send_config']['smtp']['port'] );
						}

						if ( $this->config['mail_send_config']['smtp']['user'] ) {

							$transport->setUsername( $this->config['mail_send_config']['smtp']['user'] );
						}

						if ( $this->config['mail_send_config']['smtp']['password'] ) {

							$transport->setPassword( $this->config['mail_send_config']['smtp']['password'] );
						}

						if ( $this->config['mail_send_config']['smtp']['ssl'] ) {

							$transport->setEncryption('ssl');
						}

					}

				// Create the Mailer using your created Transport
				$mailer = Swift_Mailer::newInstance( $transport );

				// Create a message
				$message = Swift_Message::newInstance( $mail_subject )
					->setFrom( array( $sender_mail => $sender_name ) )
					->setTo( array( $this->config['recipient_mail'] => $this->config['recipient_name'] ) )
					->setBody( trim( stripslashes( $mail_text ) ) );

				// Attache files
				if ( isset($_FILES) ) {

					foreach ( $_FILES as $key => $item ) {

						if ( $item['tmp_name'] != '' ) {

							$message->attach( Swift_Attachment::fromPath( $item['tmp_name'] )->setFilename($item['name']) );
						}
					}
				}

				//print_o( $mail_subject . $sender_mail .  $sender_name . $mail_text . $this->config['recipient_mail'] . $this->config['recipient_name'] );
				//print_o( $message );

				// Send the message
				$result = $mailer->send( $message );
				if ( $result ) {

					$this->sent = true;
					$this->request = false;
				}
			}

		}

		/** 
		* Gibt gesamtes Formular zurück
		*
		* @vari		code
		*/

		protected function get_form() {

			$data = '';

			if ( $this->config['language'] ) {

				$data .= ' data-language="' . $this->config['language'] . '"';
			}

			$ret = '';
			$ret .= '<form id="' . $this->form_id . '" class="yerform ' . $this->config['form_class'] . '" action="' . $this->config['action'] . '" method="post" enctype="multipart/form-data" name="yerform" target="_self"' . $data . '>';
			$ret .= '<input name="yerform-check" type="hidden" value="' . time() . '"/>';
			$ret .= $this->code;
			$ret .= '</form>';

			return $ret;
		}

		/** 
		* require_info
		*/

		protected function require_info( $p = array() ) {

			$p += array(
				'text' => 'Fields marked with {require_symbol} are reqired.'
			);

			$p['text'] = str_replace( '{require_symbol}', $this->required_label_sufix, $p['text'] );

			$ret = '<p class="require_info">' . $p['text'] . '</p>';

			$this->code .= $ret;
		}

		/** 
		* require_info
		*/

		protected function messages() {

			$ret = '';

			/* get fields of validation error and build a string */
			$fieldnames_string = false;

			if (
				isset ( $this->validation ) AND
				is_array( $this->validation )
			) {

				foreach ( $this->validation as $key => $item ) {

					$temp = $this->fields[$key];
					$temp += array( 'no_required_label_sufix' => true );

					$fieldnames[] = $this->return_label( $temp	 );

					unset( $temp );
				}
			}

			if ( isset( $fieldnames ) ) {

				$fieldnames_string = implode( ', ', $fieldnames );
			}

			/* loop the messages */
			if ( is_array( $this->messages ) ) {

				$ret .= '<div class="yerform-messages">';

				foreach($this->messages as $key => $value ) {

					$message = $this->textcurr[ $key ];

					if ( $fieldnames_string ) {

						$message['text'] = str_replace( '{fields}', $fieldnames_string, $message['text'] );
					}

					$ret .= '<div class="yerform-messages-' . $message['typ'] . '">' . $message['text'] . '</div>';
				}
				$ret .= '</div>';
			}

			$this->code .= $ret;
		}

		/** 
		* field standard options
		*	'label' => 'no name', 
		*	'labeltype' => false, 
		*	'label_sufix' => false,
		*	'name' => 'noname',
		*	'array' => false,
		*	'value' => '',
		*	'prefix' => false,
		*	'sufix' => false,
		*	'info-before' => false,
		*	'info-after' => false,
		*/

		/** 
		* Textfeld
		* gibt den HTML-Code für ein Textfeld aus.
		*
		* @child	get_label()
		* @vari		list_item_before
		* @vari		fields_before
		* @vari		fields_after
		* @vari		code
		*/

		protected function field_text( $p = array() ) {

			$p['fieldtype'] = 'field_text';

			$p += $this->fields_defaults[ $p['fieldtype'] ];

			if ( $p['class'] ) {

				$p['class'] = ' ' . trim( $p['class'] );
			}

			$attr = '';

			if ( $p['placeholder'] ) {

				$attr .= ' placeholder="' . $p['placeholder'] . '"';
			}

			$size = '';

			if ( $p['size'] ) {

				$size .= ' size="' . $p['size'] . '"';
			}

			$ret = '';
			$ret .= $this->list_item_before( $p );
				$ret .= $this->get_label( $p );
				$ret .= $this->messagetable_before;
					$ret .= $this->get_info_before( $p );
					$ret .= $this->infoaftertable_before;
						$ret .= $this->fieldcell_before;
							$ret .= $this->fieldtable_before;
								$ret .= $this->get_field_prefix( $p );
								$ret .= $this->field_before;

									$ret .= '<input class="yerform-field-text' . $p['class'] . '" type="text" id="' . $this->get_field_name( $p ) . '" name="' . $this->get_field_name( $p ) . '" value="' . $this->get_field_value( $p ) . '"' . $size . ' maxlength="' . $p['maxlength'] . '"' . $attr . '/>';

								$ret .= $this->field_after;
								$ret .= $this->get_field_sufix( $p );
							$ret .= $this->fieldtable_after;
						$ret .= $this->fieldcell_after;
						$ret .= $this->get_info_after( $p );
					$ret .= $this->infoaftertable_after;
					$ret .= $this->get_field_messages( $p );
				$ret .= $this->messagetable_after;
			$ret .= $this->list_item_after();
			$this->code .= $ret;
		}

		/** 
		* Textarea
		*/

		protected function field_textarea( $p = array() ) {

			$p['fieldtype'] = 'field_textarea';

			$p += $this->fields_defaults[ $p['fieldtype'] ];

			$ret = '';
			$ret .= $this->list_item_before( $p );
				$ret .= $this->get_label( $p );
				$ret .= $this->messagetable_before;
					$ret .= $this->get_info_before( $p );
					$ret .= $this->infoaftertable_before;
						$ret .= $this->fieldcell_before;
							$ret .= $this->fieldtable_before;
								$ret .= $this->get_field_prefix( $p );
								$ret .= $this->field_before;

									$ret .= '<textarea id="' . $this->get_field_name( $p ) . '" name="' . $this->get_field_name( $p ) . '" cols="' . $p['cols'] . '" rows="' . $p['rows'] . '">' . $this->get_field_value( $p ) . '</textarea>';

								$ret .= $this->field_after;
								$ret .= $this->get_field_sufix( $p );
							$ret .= $this->fieldtable_after;
						$ret .= $this->fieldcell_after;
						$ret .= $this->get_info_after( $p );
					$ret .= $this->infoaftertable_after;
					$ret .= $this->get_field_messages( $p );
				$ret .= $this->messagetable_after;
			$ret .= $this->list_item_after();
			$this->code .= $ret;
		}

		/** 
		* Select
		* gibt den HTML-Code für ein Textfeld aus.
		*
		* @child	get_label()
		* @vari		list_item_before
		* @vari		fields_before
		* @vari		fields_after
		* @vari		code
		*/

		protected function field_select( $p = array() ) {

			$p['fieldtype'] = 'field_select';

			$p += $this->fields_defaults[ $p['fieldtype'] ];

			$ret = '';
			$ret .= $this->list_item_before( $p );
				$ret .= $this->get_label( $p );
				$ret .= $this->messagetable_before;
					$ret .= $this->get_info_before( $p );
					$ret .= $this->infoaftertable_before;
						$ret .= $this->fieldcell_before;
							$ret .= $this->fieldtable_before;
								$ret .= $this->get_field_prefix( $p );
								$ret .= $this->field_before;

									$ret .= '<select id="' . $this->get_field_name( $p ) . '" name="' . $this->get_field_name( $p ) . '">';
										foreach($p['data'] as $key => $value) {

											// optgroup
											if ( is_array( $value ) ) {

												$ret .= '<optgroup label="' . $key . '">';

													foreach ( $value as $key2 => $value2 ) {

														// optgroup option

														if ( $this->get_field_value( $p ) == $key2 ) {

															$selected = ' selected';
														}
														else {

															$selected = '';
														}

														$ret .= '<option value="' . $key2 . '"' . $selected . '>' . $value2 . '</option>';
													}

												$ret .= '</optgroup>';
											}

											// option
											else {

												if ( $this->get_field_value( $p ) == $key ) {

													$selected = ' selected';
												}
												else {

													$selected = '';
												}

												$ret .= '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
											}
										}
									$ret .= '</select>';

								$ret .= $this->field_after;
								$ret .= $this->get_field_sufix( $p );
							$ret .= $this->fieldtable_after;
						$ret .= $this->fieldcell_after;
						$ret .= $this->get_info_after( $p );
					$ret .= $this->infoaftertable_after;
					$ret .= $this->get_field_messages( $p );
				$ret .= $this->messagetable_after;
			$ret .= $this->list_item_after();
			$this->code .= $ret;
		}

		/** 
		* Datumsfeld
		* gibt den HTML-Code f&uuml;r ein Datumsfeld aus.
		*
		* @child	get_label()
		* @vari		list_item_before
		* @vari		fields_before
		* @vari		fields_after
		* @vari		code
		*/

		protected function field_date( $p = array() ) {

			$p['fieldtype'] = 'field_date';

			$p += $this->fields_defaults[ $p['fieldtype'] ];

			$class = '';
			if ( $p['datepicker'] ) {

				$class = ' datepicker';
			}

			$size = '';
			if ( $p['size'] ) {

				$size .= ' size="' . $p['size'] . '"';
			}

			/* get the min and max day setings for jquery-datepicker from validation info */
			if (
				$p['datepicker'] AND
				$p['validation']
			) {

				foreach( $p['validation'] as $num => $item ) {

					if ( $item['type'] === 'date' ) {

						if ( isset( $item['min'] ) ) {

							$p['datepicker-min'] = ( $this->datestamp( $item['min'] ) - mktime( 0, 0, 0, date("m"), date("d"), date("Y")) ) / 86400 . 'd';
						}
						if ( isset( $item['max'] ) ) {

							$p['datepicker-max'] = ( $this->datestamp( $item['max'] ) - mktime( 0, 0, 0, date("m"), date("d"), date("Y")) ) / 86400 . 'd';
						}
					}
				}
			}

			$data = '';
			if ( $p['datepicker'] ) {

				$data .= ' data-datepicker-mindate="' . $p['datepicker-mindate'] . '"';
				$data .= ' data-datepicker-maxdate="' . $p['datepicker-maxdate'] . '"';
				$data .= ' data-datepicker-dateformat="' . strtr( $p['datepicker-dateformat'], $this->dateformats_phpjs ) . '"';
				$data .= ' data-datepicker-yerformdateformat="' . strtr( 'd.m.Y', $this->dateformats_phpjs ) . '"';
				if ( $p['datepicker-iconurl'] ) {

					$data .= ' data-datepicker-iconurl="' . $p['datepicker-iconurl'] . '"';
				}
			}

			$ret = '';
			$ret .= $this->list_item_before( $p );
				$ret .= $this->get_label( $p );
				$ret .= $this->messagetable_before;
					$ret .= $this->get_info_before( $p );
					$ret .= $this->infoaftertable_before;
						$ret .= $this->fieldcell_before;
							$ret .= $this->fieldtable_before;
								$ret .= $this->get_field_prefix( $p );
								$ret .= $this->field_before;

									$ret .= '<input class="yerform-field-' . $p['use_field_type'] . ' field-margin-right' . $class . '" type="' . $p['use_field_type'] . '" id="' . $this->get_field_name( $p ) . '" name="' . $this->get_field_name( $p ) . '" value="' . $this->get_field_value( $p ) . '"' . $size . ' maxlength="' . $p['maxlength'] . '"' .  $data . '/>';
									$ret .= '<input id="' . $this->get_field_name( $p ) . '_yerform" name="' . $this->get_field_name( $p ) . '_yerform" type="hidden" value="' . @$p['temp_value_yerform']. '"/>';

								$ret .= $this->field_after;
								$ret .= $this->get_field_sufix( $p );
							$ret .= $this->fieldtable_after;
						$ret .= $this->fieldcell_after;
						$ret .= $this->get_info_after( $p );
					$ret .= $this->infoaftertable_after;
					$ret .= $this->get_field_messages( $p );
				$ret .= $this->messagetable_after;
			$ret .= $this->list_item_after();
			$this->code .= $ret;
		}

		/** 
		* Checkbox
		* gibt den HTML-Code für eine oder mehrere checkboxen aus.
		*
		* @child	get_label()
		* @vari		list_item_before
		* @vari		fields_before
		* @vari		fields_after
		* @vari		code
		*/

		protected function field_checkbox( $p = array() ) {

			$p['fieldtype'] = 'field_checkbox';

			$p += $this->fields_defaults[ $p['fieldtype'] ];

			if (
				$this->get_field_value( $p ) !== '' OR
				(
					! $this->request AND
					$p['checked'] === true
				)
			) {

				$checked = ' checked="checked"';
			}
			else {

				$checked = '';
			}

			$ret = '';
			$ret .= $this->list_item_before( $p );
				$ret .= $this->get_label( $p );
				$ret .= $this->messagetable_before;
					$ret .= $this->get_info_before( $p );
					$ret .= $this->infoaftertable_before;
						$ret .= $this->fieldcell_before;
							$ret .= $this->fieldtable_before;
								$ret .= $this->get_field_prefix( $p );
								$ret .= $this->field_before;

									$ret .= '<input type="checkbox" id="' . $this->get_field_name( $p ) . '" name="' . $this->get_field_name( $p ) . '" value="' . $p['data'] . '"' . $checked . '/>';

									if ( $p['labeltype'] === 'field-after' ) {

										$ret .= '<span class="yerform-field-after">' . $this->return_label( $p ) . '</span>';
									}

								$ret .= $this->field_after;
								$ret .= $this->get_field_sufix( $p );
							$ret .= $this->fieldtable_after;
						$ret .= $this->fieldcell_after;
						$ret .= $this->get_info_after( $p );
					$ret .= $this->infoaftertable_after;
					$ret .= $this->get_field_messages( $p );
				$ret .= $this->messagetable_after;
			$ret .= $this->list_item_after();
			$this->code .= $ret;

		}

		/** 
		* Radio
		* gibt den HTML-Code für eine Radiobox aus.
		*
		* @child	get_label()
		* @vari		list_item_before
		* @vari		fields_before
		* @vari		fields_after
		* @vari		code
		*/

		protected function field_radio( $p = array() ) {

			$p['fieldtype'] = 'field_radio';

			$p += $this->fields_defaults[ $p['fieldtype'] ];

			$checked = '';

			if ( $this->get_field_value( $p ) === $p['data'] ) {

				$checked = ' checked="checked"';
			}

			if (
				$this->get_field_value( $p ) === '' &&
				$p['checked'] === true
			) {

				$checked = ' checked="checked"';
			}

			$ret = '';
			$ret .= $this->list_item_before( $p );
				$ret .= $this->get_label( $p );
				$ret .= $this->messagetable_before;
					$ret .= $this->get_info_before( $p );
					$ret .= $this->infoaftertable_before;
						$ret .= $this->fieldcell_before;
							$ret .= $this->fieldtable_before;
								$ret .= $this->get_field_prefix( $p );
								$ret .= $this->field_before;

									$ret .= '<input type="radio" id="' . $this->get_field_name( $p ) . '" name="' . $this->get_field_name( $p ) . '" value="' . $p['data'] . '"' . $checked . '/>';

									if ( $p['labeltype'] === 'field-after' ) {

										$ret .= '<span class="yerform-field-after">' . $this->return_label( $p ) . '</span>';
									}

								$ret .= $this->field_after;
								$ret .= $this->get_field_sufix( $p );
							$ret .= $this->fieldtable_after;
						$ret .= $this->fieldcell_after;
						$ret .= $this->get_info_after( $p );
					$ret .= $this->infoaftertable_after;
					$ret .= $this->get_field_messages( $p );
				$ret .= $this->messagetable_after;
			$ret .= $this->list_item_after();
			$this->code .= $ret;
		}

		/** 
		* File
		* gibt den HTML-Code für ein Dateifeld aus.
		*
		* @child	get_label()
		* @vari		list_item_before
		* @vari		fields_before
		* @vari		fields_after
		* @vari		code
		*/

		protected function field_file( $p = array() ) {

			$p['fieldtype'] = 'field_file';

			$p += $this->fields_defaults[ $p['fieldtype'] ];

			$ret = '';
			$ret .= $this->list_item_before( $p );
				$ret .= $this->get_label( $p );
				$ret .= $this->messagetable_before;
					$ret .= $this->get_info_before( $p );
					$ret .= $this->infoaftertable_before;
						$ret .= $this->fieldcell_before;
							$ret .= $this->fieldtable_before;
								$ret .= $this->get_field_prefix( $p );
								$ret .= $this->field_before;

									$ret .= '<input name="' . $this->get_field_name( $p ) . '" type="file" size="' . $p['size'] . '">';

								$ret .= $this->field_after;
								$ret .= $this->get_field_sufix( $p );
							$ret .= $this->fieldtable_after;
						$ret .= $this->fieldcell_after;
						$ret .= $this->get_info_after( $p );
					$ret .= $this->infoaftertable_after;
					$ret .= $this->get_field_messages( $p );
				$ret .= $this->messagetable_after;
			$ret .= $this->list_item_after();
			$this->code .= $ret;
		}

		/** 
		* HTML
		* gibt HTML-Code aus.
		*
		* @child	get_label()
		* @vari		list_item_before
		* @vari		fields_before
		* @vari		fields_after
		* @vari		code
		*/

		protected function field_html( $p = array() ) {

			$p['fieldtype'] = 'field_html';

			$p += $this->fields_defaults[ $p['fieldtype'] ];

			$ret = '';
			$ret .= $this->list_item_before( $p );
				$ret .= $p['content'];
			$ret .= $this->list_item_after();
			$this->code .= $ret;
		}

		/** 
		* Hidden
		* gibt den HTML-Code für ein Hidden-Feld aus.
		*
		* @child	get_label()
		* @vari		list_item_before
		* @vari		fields_before
		* @vari		fields_after
		* @vari		code
		*/

		protected function field_hidden( $p = array() ) {

			$p['fieldtype'] = 'field_hidden';

			$p += $this->fields_defaults[ $p['fieldtype'] ];

			$ret = '';

			$ret .= '<input id="' . $this->get_field_name( $p ) . '" name="' . $this->get_field_name( $p ) . '" type="hidden" value="' . $this->get_field_value( $p ) . '"/>';
			$this->code .= $ret;
		}

		/** 
		* Strukturelemente, Begin und Ende von Listen.
		*
		* @vari		list_before
		* @vari		list_after
		* @vari		code
		*/

		protected function list_begin( $p = array() ) {

			$p += array(
				'class' => false,
				'layout' => array( 'block' )
			);

			$this->p_list= $p;

			$class = false;

			if ( $p['class'] ) {

				$class .= ' ' . $p['class'];
			}

			$class .= ' yerform-depht-' . $this->depht;
			if ( in_array( 'block', $p['layout'] ) ) { $class .= ' yerform-list-block'; }
			if ( in_array( 'table', $p['layout'] ) ) { $class .= ' yerform-list-table'; }
			if ( in_array( 'inline', $p['layout'] ) ) { $class .= ' yerform-list-inline'; }
			if ( in_array( 'label-table', $p['layout'] ) ) { $class .= ' yerform-list-label-table'; }

			$this->code .= str_replace('>', ' class="yerform-list ' . $class . '">', $this->list_before);
		}

		protected function list_end() {

			$this->p_list = array();
			$this->code .= $this->list_after;
		}

		/** 
		* Strukturelement, Gruppe.
		*
		* @vari		list_before
		* @vari		list_after
		* @vari		list_item_before
		* @vari		list_item_after
		* @vari		label_before
		* @vari		label_after
		* @vari		code
		*/

		protected function group_begin( $p = array() ) {

			$p += array(
				'label' => false,
				'class' => false,
				'group-layout' => 'block',
				'list-layout' => 'block',
				'list-gap' => false
			);

			$this->p_group = $p;

			$this->depht = $this->depht + 1;

			$class = '';
			if ( $p['class'] ) { $class = ' ' . $p['class']; }
			if ( $p['group-layout'] === 'block' ) { $class .= ' yerform-group-block'; }
			if ( $p['group-layout'] === 'inline' ) { $class .= ' yerform-group-inline'; }

			$this->code .= str_replace('>', ' class="yerform-list-item-group' . $class . '">', $this->list_item_before);
			//$class = 'yerform-list-item-group-inner';
			//$this->code .= '<div class="' . $class . '">';

			if ( $p['label'] !== false ) {

				$this->code .= str_replace('>', ' class="yerform-group-label">', $this->label_before);

				if ( $p['label'] != '' ) {

					$this->code .= '<label>' . $p['label'] . '</label>';
				}

				$this->code .= $this->label_after;
			}

			$this->code .= str_replace('">', ' yerform-group">', $this->fields_before);

			$class = '';
			if ( $p['list-layout'] === 'block' ) { $class .= 'yerform-list-block'; }
			if ( $p['list-layout'] === 'table' ) { $class .= 'yerform-list-table'; }
			if ( $p['list-layout'] === 'inline' ) { $class .= 'yerform-list-inline'; }

			$class .= ' yerform-depht-' . $this->depht;
			if ( $p['list-gap'] ) { $class .= ' yerform-list-gap'; }
			$ret = str_replace('>', ' class="yerform-list ' . $class . '">', $this->list_before);
			$this->code .= $ret;

		}

		protected function group_end() {

			$this->p_group = array();
			$this->code .= $this->list_after;
			$this->code .= $this->fields_after;
			//$this->code .= '</div>';
			$this->code .= $this->list_item_after;

			$this->depht = $this->depht - 1;
		}

		/** 
		* "required" Zeichen
		* Gibt Zeichen f&uuml;r "required" Felder zuzr&uuml;ck.
		*
		* @parent	get_label()
		* @vari		required_label_sufix
		*/

		protected function get_require_label_sufix( $p = array() ) {

			$check = false;

			$p += array(
				'no_required_label_sufix' => false
			);

			if (
				$p['no_required_label_sufix'] === false &&
				isset( $p['validation'] )
			) {

				foreach( $p['validation'] as $key => $item ) {

					if (
						$item['type'] === 'required' AND
						$item['cond'] === true
					) $check = $this->required_label_sufix;
				}
			}

			return $check;
		}

		/** 
		* Label
		* Gibt den HTML-Code f&uuml;r ein Label aus.
		* F&uuml;gt Zeichen an Label an f&uuml;r "required" Felder.
		*
		* @parent	field_text()
		* @vari		label_before
		* @vari		label_after
		*/

		protected function return_label( $p = array() ) {

			$p += array(
				'label' => false,
				'no_required_label_sufix' => false
			);

			if ( $p['label'] === false ) {

				if ( isset( $this->textcurr['fields'][ $this->get_field_name( $p ) ]['label'] ) ) {

					$p['label'] = $this->textcurr['fields'][ $this->get_field_name( $p ) ]['label'];
				}
			}

			$ret = '';

			$ret .= $p['label'];

			if ( ! $p['no_required_label_sufix'] ) {

				$ret .= $this->get_require_label_sufix( $p );
			}

			if ( $p['label'] ) {

				return $ret;
			}
			else {

				return false;
			}
		}

		/** 
		* Label
		* Gibt den HTML-Code f&uuml;r ein Label aus.
		* F&uuml;gt Zeichen an Label an f&uuml;r "required" Felder.
		*
		* @parent	field_text()
		* @vari		label_before
		* @vari		label_after
		*/

		protected function get_label( $p = array() ) {

			$p += array(
				'label' => false, 
				'labeltype' => false, 
				'name' => false,
				'label_sufix' => false,
				'no_required_label_sufix' => false,
				'return' => 'html', // text
			);


			$class = 'yerform-label-wrap';

			if (
				$p['labeltype'] === 'field-after' OR
				$p['labeltype'] === 'none'
			) {

				$class .= ' yerform-displaynone';
			}

			$p['label'] = $this->return_label( $p );

			$ret = '';

			if ( $p['return'] == 'html' ) {

				$ret .= str_replace('>', ' class="' . $class . '">', $this->label_before);

				$class_input = '';
				$class_inputsufix = '';

				if ( $p['labeltype'] === 'field-after' ) {

					$class_inputsufix .= ' class="yerform-displaynone"';
				}

				if ( $p['label'] ) {

					$ret .= '<label for="' . $p['name'] . '"' . $class_input . '>' . $p['label'] . '</label>';

					if ( $p['label_sufix'] ) {

						$ret .= '<span' . $class_inputsufix . '>' . $p['label_sufix'] . '</span>';
					}

					$ret .= $this->label_after;

				}
			}

			if ( $p['label'] ) {

				return $ret;
			}
			else {

				return false;
			}
		}

		/** 
		* Formularbuttons
		* Gibt die Formularbuttons aus.
		*
		* @vari		list_item_before
		* @vari		list_item_after
		*/

		protected function form_buttons( $p = array() ) {

			$p += array(
				'submit' => true,
				'submit_label' => false,
				'submit_class' => false,
				'submit_btn_class' => false,
				'reset' => true,
				'reset_label' => false,
				'reset_class' => false,
				'reset_btn_class' => false
			);

			if ( $p['submit_label'] === false ) {

				if ( isset( $this->textcurr['buttons']['submit']['label'] ) ) {

					$p['submit_label'] = $this->textcurr['buttons']['submit']['label'];
				}
			}
			else {

				$p['submit_label'] = 'Submit';
			}

			if ( $p['reset_label'] === false ) {

				if ( isset( $this->textcurr['buttons']['reset']['label'] ) ) {

					$p['reset_label'] = $this->textcurr['buttons']['reset']['label'];
				}
			}
			else {

				$p['reset_label'] = 'Reset';
			}

			$ret = '';
			$ret .= '<div class="yerform-buttons">';

			if ( $p['reset'] ) {

				$ret .= '<div class="yerform-reset ' . $p['reset_class'] . '"><input class="yerform-reset-btn ' . $p['reset_btn_class'] . '" name="reset" type="reset" value="' . $p['reset_label'] . '"/></div>';
			}

			$ret .= '<div class="yerform-submit ' . $p['submit_class'] . '"><input class="yerform-submit-btn ' . $p['submit_btn_class'] . '" name="submit" type="submit" value="' . $p['submit_label'] . '"/></div>';
			$ret .= '</div>';

			$this->code .= $ret;
		}

		/** 
		* Gibt List-Item Anfangstag aus
		*
		* @vari		list_item_before
		* @vari		list_item_after
		*/

		protected function list_item_before( $p = array() ) {

			$p += array(
				'layout' => array(),
				'class' => false,
				'padding' => array( 0, 0 ),
				'size' => false
			);

			if ( count( $this->p_list['layout'] ) > 0 ) {

				foreach ( $this->p_list['layout'] as $key => $value ) {

					$p['layout'][] = $value;
				}
			}

			$tag = $this->list_item_before;

			$class = 'yerform-list-item';

			if ( isset( $this->validation[ $this->get_field_name( $p ) ] ) ) {

				$class .= ' yerform-list-item-error';
			}

			if ( isset($p['fieldtype']) ) {

				$class .= ' yerform-item-type-' . $p['fieldtype'];
			}

			if ( $p['class'] ) {

				$class .= ' ' . $p['class'];
			}

			if ( $p['size'] ) {

				$class .= ' yerform-list-item-sized';
			}

			if ( in_array( 'label-inline', $p['layout'] ) ) {

				$class .= ' yerform-label-inline';
			}

			if ( in_array( 'info-inline', $p['layout'] ) ) {

				$class .= ' yerform-info-inline';
			}

			if ( in_array( 'info-sized', $p['layout'] ) ) {

				$class .= ' yerform-info-sized';
			}

			if ( in_array( 'input-sized', $p['layout'] ) ) {

				$class .= ' yerform-input-sized';
			}

			$style = '';

			if ( $p['padding'][0] > 0 ) {

				$style .= 'padding-left: ' . $p['padding'][0] . 'px;';
			}

			if ( $p['padding'][1] > 0 ) {

				$style .= 'padding-right: ' . $p['padding'][1] . 'px;';
			}

			$ret = str_replace('>', ' style="' . $style . '" class="' . $class . '">', $tag);

			if (
				isset($this->p_group['list-layout']) &&
				$this->p_group['list-layout'] == 'table'
			) {

				$ret .= '<div class="yerform-list-item-table">';
			}

			//if ( $this->depht > 1 ) $ret .= '<div class="' . $class2 . '">';

			return $ret;
		}

		protected function list_item_after( $p = array() ) {

			$ret = '';

			//if ( $this->depht > 1 ) $ret .= '</div>';

			if (
				isset($this->p_group['list-layout']) &&
				$this->p_group['list-layout'] == 'table'
			) {

				$ret .= '</div>';
			}

			$ret .= $this->list_item_after;

			return $ret;
		}

		/** 
		* Feldset, Begin und Ende
		*
		* @vari		code
		*/

		protected function fieldset_begin( $p = array() ) {

			$p += array(
				'legend' => false,
				'name' => 'fieldset-1',
				'class' => false,
				'require_info' => false,
				'class_legend' => false
			);

			if ( $p['legend'] === false ) {

				if ( isset( $this->textcurr['fieldsets'][ $p['name'] ]['legend'] ) ) {

					$p['legend'] = $this->textcurr['fieldsets'][ $p['name'] ]['legend'];
				}
			}

			if ( $p['require_info'] === false ) {

				if ( isset( $this->textcurr['fieldset']['require_info']['text'] ) ) {

					$p['require_info'] = $this->textcurr['fieldset']['require_info'];
				}

				if ( isset( $this->textcurr['fieldsets'][ $p['name'] ]['require_info']['text'] ) ) {

					$p['require_info'] = $this->textcurr['fieldsets'][ $p['name'] ]['require_info'];
				}
			}

			$class = 'yerform-fieldset-wrap';

			if ( $p['class'] ) {

				$class = ' ' . $p['class'];
			}

			$this->code .= '<div class="' . $class . '">';
			$this->code .= '<fieldset class="yerform-fieldset">';

			if ( $p['legend'] !== false ) {

				$this->code .= '<legend class="yerform-fieldset-legend ' . $p['class_legend'] . '">' . $p['legend'] . '</legend>';
			}

			if ( $p['require_info'] ) {

				$this->require_info( $p['require_info'] );
			}
		}

		protected function fieldset_end() {

			$this->code .= '</fieldset></div>';
		}

		/** 
		* Requests
		*/

		protected function textfilter( $string ) {

			return stripslashes( $string );
		}

		/** 
		* output a timestamp relative from date and not exact time in sec of now
		*/

		public function datestamp( $string ) {

			return strtotime( $string , strtotime( date("Y-m-d") ) );
		}

		/** 
		* if width an given operator
		*/

		protected function ifit( $var1, $op, $var2 ) {

			switch ( $op ) {

				case "=":  return $var1 == $var2;
				case "!=": return $var1 != $var2;
				case ">=": return $var1 >= $var2;
				case "<=": return $var1 <= $var2;
				case ">":  return $var1 >  $var2;
				case "<":  return $var1 <  $var2;
				default:	   return true;
			}	
		}

		/** 
		* get_fieldname
		*/

		protected function get_field_name( $p = array() ) {

			$p += array(
				'array' => false,
				'name' => false
			);

			$name = $p['name'];

			if ( $p['array'] !== false ) {

				$name .= '[' . $p['array'] . ']'; 
			}

			return $name;
		}

		/** 
		* get_fieldvalue
		*/

		protected function get_field_value( $p = array() ) {

			$p += array(
				'array' => false,
				'value' => ''
			);

			$value = $p['value'];

			if ( isset($this->request[ $p['name'] ]) ) {

				$value = @$this->request[ $p['name'] ];

				if ( $p['array'] !== false ) {

					$value = @$this->request[ $p['name'] ][ $p['array'] ];
				}
			}

			$value = $this->textfilter( $value );

			return $value;
		}

		/** 
		* get_field_messages
		*/

		protected function get_field_messages( $p = array() ) {

			$p += array(
				'info' => false,
				'info_html' => false
			);

			$ret = false;

			if ( $p['info'] ) {

				$ret .= '<span class="info">' . $p['info'] . '</span>';
			}

			if ( $p['info_html'] ) {

				$ret .= '<div class="info_html">' . $p['info_html'] . '</div>';
			}

			if ( isset( $this->validation[ $this->get_field_name( $p ) ] ) ) {

				$ret .= '<span class="yerform-field-message-error">' . implode( '<br/>', $this->validation[ $this->get_field_name( $p ) ] ) . '</span>';
			}

			if ( $ret ) {

				return '<div class="yerform-field-message">' . $ret . '</div>';
			}
			else {

				return $ret;
			}
		}

		/** 
		* get_field_prefix
		*/

		protected function get_field_prefix( $p = array() ) {

			$p += array(
				'prefix' => false
			);

			$ret = false;

			if ( $p['prefix'] ) {

				$ret .= '<div class="yerform-field-prefix">';
				$ret .= '<span>' . $p['prefix'] . '</span>';
				$ret .= '</div>';
			}

			return $ret;
		}

		/** 
		* get_field_sufix
		*/

		protected function get_field_sufix( $p = array() ) {

			$p += array(
				'sufix' => false
			);

			$ret = false;

			if ( $p['sufix'] ) {

				$ret .= '<div class="yerform-field-sufix">';
				$ret .= '<span>' . $p['sufix'] . '</span>';
				$ret .= '</div>';
			}

			return $ret;
		}

		/** 
		* get_info_before
		*/

		protected function get_info_before( $p = array() ) {

			$p += array(
				'info-before' => false
			);

			$ret = false;

			if ( $p['info-before'] ) {

				$ret .= '<div class="yerform-info-before">';
				$ret .= '<span>' . $p['info-before'] . '</span>';
				$ret .= '</div>';
			}

			return $ret;
		}

		/** 
		* get_info_after
		*/

		protected function get_info_after( $p = array() ) {

			$p += array(
				'info-after' => false
			);

			$ret = false;

			if ( $p['info-after'] ) {

				$ret .= '<div class="yerform-info-after">';
				$ret .= '<span>' . $p['info-after'] . '</span>';
				$ret .= '</div>';
			}

			return $ret;
		}

	}

?>
