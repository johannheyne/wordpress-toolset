<?php

	add_action( 'init', function() {

		// DEFAULTS {

			$defaults = array(

				'login_wrap_class' => 'user-login-wrap',
				'form_class' => 'user-login-form',
				'form_item_class' => 'user-login-form-item',

				'messages_class' => 'user-login-message',

				'redirect_id' => 'login_redirect',
				'login_redirect_url' => true, // true: default get_permalink()
				
				'user_id' => 'login_user',
				'user_label' => 'User',
				'user_placeholder' => 'User',
				
				'password_id' => 'login_password',
				'password_label' => 'Password',
				'password_type' => 'text',
				'password_placeholder' => 'Password',
				
				'submit_id' => 'login_submit',
				'submit_class' => 'user-login-form-submit',
				'submit_label' => 'Submit',
				
				'reset' => true,
				'reset_id' => 'login_reset',
				'reset_class' => 'user-login-form-reset',
				'reset_label' => 'Reset',

			);

			if ( ! isset( $GLOBALS['toolset']['inits']['tool_wp_user']['login'] ) ) {

				$GLOBALS['toolset']['inits']['tool_wp_user']['login'] = array();
			}

			$p = array_replace_recursive( $defaults, $GLOBALS['toolset']['inits']['tool_wp_user']['login'] );

			$GLOBALS['toolset']['cache']['tool_wp_user']['login'] = $p;

		// }

		// LOGIN {

			if ( isset( $_POST[ $p['submit_id'] ] ) ) {

				$creds = array(
					'user_login' => sanitize_text_field( $_POST[ $p['user_id'] ] ),
					'user_password' => sanitize_text_field( $_POST[ $p['password_id'] ] ),
					'remember' => false
				);

				add_action( 'wp_login', function( $user_login, $user ) {

					$p = $GLOBALS['toolset']['cache']['tool_wp_user']['login'];

					if ( isset( $_POST[ $p['submit_id'] ] ) AND $user ) {

						wp_redirect( esc_url( $_POST[ $p['redirect_id'] ] ) );
					}

				}, 10, 2);

				$user = wp_signon( $creds, false );

				if ( is_wp_error( $user ) ) {

					$GLOBALS['plugin_login_tools']['login_error'] = $user;
				}
				else {

					$login_amount_total = get_user_meta(  $user->ID, 'login_tools_login_amount_total', true );

					if ( $login_amount_total === false ) {

						$login_amount_total = 0;
					}

					$login_amount_total = $login_amount_total + 1;
					update_user_meta( $user->ID, 'login_tools_login_amount_total', $login_amount_total );

					$login_amount_total = $login_amount_total + 1;
					update_user_meta( $user->ID, 'login_tools_last_login', current_time( 'mysql' ) );
				}

			}

		// }
	} );

	function tool_wp_user_login_form( $p = array() ) {

		// DEFAULTS {

			unset( $p['redirect_id'] );
			unset( $p['user_id'] );
			unset( $p['password_id'] );
			unset( $p['submit_id'] );
			unset( $p['reset_id'] );

			$p = array_replace_recursive( $GLOBALS['toolset']['cache']['tool_wp_user']['login'], $p );

			if ( $p['login_redirect_url'] === true ) {

				$p['login_redirect_url'] = get_permalink();
			}

		// }

		$return = '';

		$form_data = array(
			'login_user' => '',
			'login_password' => ''
		);

		if ( isset( $_POST['login_submit'] ) ) {

			$form_data['login_user'] = sanitize_text_field( $_POST[ $p['user_id'] ] );
			$form_data['login_password'] = sanitize_text_field( $_POST[ $p['password_id'] ] );
		}

		if ( ! is_user_logged_in() ) {

			$return .= '<div class="' . $p['login_wrap_class'] . '">';

				if ( isset( $GLOBALS['plugin_login_tools']['login_error'] ) ) {

					$return .= '<div class="' . $p['messages_class'] . '">';
				
					foreach ( $GLOBALS['plugin_login_tools']['login_error']->errors as $item ) {

						foreach ( $item as $value ) {

							$return .= $value;
						}
					}

					$return .= '</div>';
				}

				$return .= '<form class="' . $p['form_class'] . '" action="" method="post" enctype="multipart/form-data" name="name" target="_top">';

					$return .= '<div class="' . $p['form_item_class'] . '">';
						$return .= '<label for="' . $p['user_id'] . '">' . $p['user_label'] . '</label><input type="text" id="' . $p['user_id'] . '" name="' . $p['user_id'] . '" value="' . $form_data['login_user'] . '" placeholder="' . $p['user_placeholder'] . '" size="40" />';
					$return .= '</div>';

					$return .= '<div class="' . $p['form_item_class'] . '">';
						$return .= '<label for="' . $p['password_id'] . '">' . $p['password_label'] . '</label><input type="' . $p['password_type'] . '" id="' . $p['password_id'] . '" name="' . $p['password_id'] . '" value="' . $form_data['login_password'] . '" placeholder="' . $p['password_placeholder'] . '" size="40" />';
					$return .= '</div>';

					$return .= '<div class="' . $p['form_item_class'] . '">';

						$return .= '<input name="' . $p['redirect_id'] . '" type="hidden" value="' . $p['login_redirect_url'] . '"/>';

						$return .= '<input class="' . $p['submit_class'] . '" name="' . $p['submit_id'] . '" type="submit" value="' . $p['submit_label'] . '">';

						if ( $p['reset'] !== '' ) {

							$return .= '<input class="' . $p['reset_class'] . '" name="' . $p['reset_id'] . '" type="reset" value="' . $p['reset_label'] . '">';
						}

					$return .= '</div>';

				$return .= '</form>';

			$return .= '</div>';
		}

		return $return;
	}

	function tool_wp_user_logout_link( $p = array() ) {

		// DEFAULTS {

			$defaults = array(
				'logout_wrap_class' => '',
				'logout_link_class' => '',
				'logout_label' => 'Logout',
				'logout_redirect_url' => true, // true: default get_permalink()
			);

			if ( ! isset( $GLOBALS['toolset']['inits']['tool_wp_user']['logout'] ) ) {

				$GLOBALS['toolset']['inits']['tool_wp_user']['logout'] = array();
			}

			$p = array_replace_recursive( $GLOBALS['toolset']['inits']['tool_wp_user']['logout'], $p );

			$p = array_replace_recursive( $defaults, $p );

			if ( $p['logout_redirect_url'] === true ) {

				$p['logout_redirect_url'] = get_permalink();
			}

		// }

		$return = '';

		if ( is_user_logged_in() ) {

			$return .= '<div class="' . $p['logout_wrap_class'] . '">';
				$return .= '<a class="' . $p['logout_link_class'] . '" href="' . wp_logout_url( $p['logout_redirect_url'] ) . '">' . $p['logout_label'] . '</a>';
			$return .= '</div>';
		}

		return $return;
	}

?>