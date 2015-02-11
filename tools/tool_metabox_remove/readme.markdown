[back to overview](../../README.markdown#initial-functionality)

Tool tool_metabox_remove
===============================

This tool removes WordPress meta-boxes.

````php
	$GLOBALS['toolset'] = array(
		'inits' => array(
			'tool_metabox_remove' => array(
				'{metabox key}' => array(
					'pages' => array( '{costum-posttype}', 'post', 'page', 'attachment', 'link', 'dashboard' ), // default is all native once
					'context' => array( 'normal', 'advanced', 'side' ), // default is all
				),
			),
		)
	);

	/* WORDPRESS METABOX KEYS

		'author'
		'category'
		'commentstatus'
		'comments'
		'format'
		'pageparent'
		'postcustom'
		'postexcerpt'
		'postimagediv'
		'revisions'
		'slug'
		'submit'
		'tags'
		'trackbacks'
		
	*/
````

[back to overview](../../README.markdown#initial-functionality)
