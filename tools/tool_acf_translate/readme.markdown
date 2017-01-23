[back to overview](../../README.markdown#initial-functionality)

Tool ACF Translate
===============================

Translates titles and labels of ACF groups and fields.

````php
	$GLOBALS['toolset'] = array(
		'inits' => array(
			'tool_acf_translate' => array(
				'strings' => array(
					// it is general practice to use english as key language
					'Hello' => array(
						'de_DE' => 'Hallo',
						'fr_FR' => 'Bonjour'
					),
					'World' => array(
						'de_DE' => 'Welt',
						'fr_FR' => 'Monde'
					)
				),
				'locale' => 'de_DE' // optional, default by get_locale()
			)
		)
	);
````

[back to overview](../../README.markdown#initial-functionality)
