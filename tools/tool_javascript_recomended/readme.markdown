[back to overview](../../README.markdown#tools)

Tool tool_javascript_recomended
===============================

Echo´s a `<noscript>` tag with a message about Javascript recomended.
If the site is a multisite and do not use the WPML-plugin, you have to setup the language.

````php

$GLOBALS['toolset'] = array(
    'inits' => array(
        'tool_javascript_recomended' => array(
            'template' => '<p class="javascript_recomended">{message}</p>',
			'messages' => array(
				'en' => 'Javascript is turned off… Please activate Javascript to have all features of the site!',
			),
			'lang' => 'en', // only if multisite an no WPML-plugin solution
        ),
    ),
);

tool( array(
	'name' => 'tool_javascript_recomended',
) );
````

[back to overview](../../README.markdown#tools)
