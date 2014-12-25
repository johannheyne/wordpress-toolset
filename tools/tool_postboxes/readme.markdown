[back to overview](../../README.markdown#initial-functionality)

Tool tool_postboxes
===============================

Adds postboxes and handles saving data.

````php

$GLOBALS['toolset'] = array(
    'inits' => array(
        'postboxes' => array(
            'add' => array(
				'company' => array(
					'title' => 'Company Data',
					'post_type' => 'companies', // 'post', 'page', 'dashboard', 'link', 'attachment' or 'custom_post_type'
					'context' => 'normal', // 'normal', 'advanced', or 'side'
					'priority' => 'default', // 'high', 'core', 'default' or 'low'
					'fields' => array(
						'company_name' => true // save that field
					),
					'content' => function( $object ) {

						echo '<p>';
							echo '<label for="company_name">Name</label>';
							echo '<br />';
							echo '<input class="widefat" type="text" name="company_name" id="company_name" value="' . esc_attr( get_post_meta( $object->ID, 'company_name', true ) ) . '" size="30" />';
						echo '</p>';
					},
				),
			),
        ),
    ),
);
````

[back to overview](../../README.markdown#initial-functionality)
