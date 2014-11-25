[back to overview](../../README.markdown#tools)

Tool tool_javascript_recomended
===============================

Echo´s a `<noscript>` tag with a message about Javascript recomended.

````php

$GLOBALS['toolset'] = array(
    'inits' => array(
        'tool_javascript_recomended' => array(
            'template' => '<p class="javascript_recomended">{message}</p>',
			'messages' => array(
				'en' => 'Javascript is turned off… Please activate Javascript to have all features of the site!',
			),
        ),
    ),
);

tool( array(
	'name' => 'tool_javascript_recomended',
) );
````

[back to overview](../../README.markdown#tools)
