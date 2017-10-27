[back to overview](../../README.markdown#initial-functionality)

WordPress Head
===============

This influences the output of the WordPress `wp_head()` function.

```php
$GLOBALS['toolset'] = array(
	'inits' => array(
		'tool_wp_head' => array(

			// remove_action()
			'{action_name}' => array(
				'action' => 'wp_head', // default
				'priority' => 10, // default
				'show' => false, // false/true required
			),
			// or
			'{action_name}' => array(
				'action' => array(
					array(
						'name' => 'wp_head',
						'priority' => 10, // default
					)
				),
				'show' => false, // false/true required
			),

			// add_filter()
			'{filter_name}' => array(
				'filter' => {function_name} or function( $arg1 ) { return $arg1; }, // use add_filter(), disable 'action'
				'priority' => 10, // default
				'accepted_args' => 1, // default
			),
		),
	),
);

/* DEFAULTS
'feed_links_extra' => array(
	'show' => false,
),
'feed_links' => array(
	'show' => false,
),
'rsd_link' => array(
	'show' => false,
),
'wlwmanifest_link' => array(
	'show' => false,
),
'index_rel_link' => array(
	'show' => true,
),
'wp_shortlink_wp_head' => array(
	'show' => false,
),
'adjacent_posts_rel_link_wp_head' => array(
	'show' => false,
),
'parent_post_rel_link' => array(
	'show' => false,
),
'start_post_rel_link' => array(
	'show' => false,
),
'adjacent_posts_rel_link' => array(
	'show' => false,
),
'wp_generator' => array(
	'show' => false,
),
'rel_canonical' => array(
	'show' => true,
),

// DNS-PREFECH
// Source: https://developer.mozilla.org/en-US/docs/Web/HTTP/Controlling_DNS_prefetching
'wp_resource_hints' => array(
	'show' => false,
	'priority' => 2,
),
'emoji_svg_url' => array(
	'show' => false,
	'filter' => '__return_false',
),
*/
`````

[back to overview](../../README.markdown#initial-functionality)
