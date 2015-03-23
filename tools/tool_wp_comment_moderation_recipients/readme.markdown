[back to overview](../../README.markdown#initial-functionality)

Tool tool_wp_comment_moderation_recipients
===============================

WordPress sends moderation emails only to the admins email of WordPress. This tool can change the email adresses they will used to send the comment moderation emails.

In the example the admins email stored in the WordPress options will be removed and the email of an chief editor will be added.

````php
	$GLOBALS['toolset'] = array(
		'inits' => array(
			'tool_wp_comment_moderation_recipients' => array(
				'emails_remove' => array( 'admin_email' ),
				'emails_add' => array( 'chiefeditor@email.com' ),
			)
		)
	);
````

[back to overview](../../README.markdown#initial-functionality)
