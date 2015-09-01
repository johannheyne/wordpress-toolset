[back to overview](../../README.markdown#initial-functionality)

Tool WP User
===============================

This tool provides a WordPress user login form and a logout link.

````php

	// Config

	$GLOBALS['toolset'] = array(
		'inits' => array(
			'tool_wp_user' => array(

				'login' => array(

					'login_wrap_class' => 'user-login-wrap',
					'form_class' => 'user-login-form',
					'form_item_class' => 'user-login-form-item',

					'messages_class' => 'user-login-message',

					'redirect_id' => 'login_redirect',
					'login_redirect_url' => true, // true is equal to get_permalink()

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

				),

				'logout' => array(
					'logout_wrap_class' => 'user-logout-wrap',
					'logout_link_class' => 'user-logout-link',
					'logout_label' => 'Logout',
					'logout_redirect_url' => true, // true: default get_permalink()
				)
			)
		)
	);
	
	// Login Form
	
	echo tool( array(
		'name' => 'tool_wp_user_login_form',
		'param' => array(
			// you can change all $GLOBALS['toolset']['inits']['tool_wp_user']['login'] parameter here exept the ID´s of the fields 
		)
	) );

	// Logout Link

	echo tool( array(
		'name' => 'tool_wp_user_logout_link',
		'param' => array(
			// you can change all $GLOBALS['toolset']['inits']['tool_wp_user']['logout'] parameter here
		)
	) );
````

[back to overview](../../README.markdown#initial-functionality)
