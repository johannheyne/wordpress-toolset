[back to overview](../../README.markdown#initial-functionality)

Tool ACF Save Post After
===============================

This tool fires a function after a page / post / options page / taxonomy / user / attachment is saved.

````php
	$GLOBALS['toolset'] = array(
		'inits' => array(
			'tool_acf_save_post_after' =>  array(
				'function' => function( $p = array() ) {

					// DEFAULTS {

						$defaults = array(
							'post_id' => false,
							'fields' => false,
						);

						$p = array_replace_recursive( $defaults, $p );

					// }

				},
			),
		)
	);
````

Use Cases
------------------------------

In general, if you want to do something with ACF fields content after they saved to the database.

If you use ACF for the blog content you may want to save the content also in the `post_content` database field of the post to use the wordpress exerpt / search or feed functions.

````php
	$GLOBALS['toolset'] = array(
		'inits' => array(
			'tool_acf_save_post_after' =>  array(
				'function' => function( $p = array() ) {

					// DEFAULTS {

						$defaults = array(
							'post_id' => false,
							'fields' => false,
						);

						$p = array_replace_recursive( $defaults, $p );

					// }
					
					if ( $_REQUEST['post_type'] === 'post' ) {

						$acf_content = '{get your ACF content}';

						wp_update_post( array(
							'ID' => $p['post_id'],
							'post_content' => $acf_content,
						) );
					}

				},
			),
		)
	);
````


[back to overview](../../README.markdown#initial-functionality)
