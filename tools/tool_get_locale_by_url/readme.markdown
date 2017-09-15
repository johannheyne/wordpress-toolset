[back to overview](../../README.markdown#tools)

Tool Get Locale by URL
===============================

This get´s the locale from an URL. It takes the first part of the URL and returns it, if it compare with defined locales.

````php
	tool( array(
		'name' => 'tool_get_locale_by_url',
		'param' => array(
			'default_locale' => 'en', // default
			'locales' => array( 'en' ), // default
			'url' => $_REQUEST['REQUEST_URI'], // default
		),
	);
````

[back to overview](../../README.markdown#tools)
