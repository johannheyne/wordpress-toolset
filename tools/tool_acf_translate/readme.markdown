[back to overview](../../README.markdown#initial-functionality)

Tool ACF Translate
===============================

Translates titles and labels of ACF groups and fields.

````php
	tool( array(
		'name' => 'tool_acf_translate',
		'param' => array(
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
			)
		),
	) );

````

[back to overview](../../README.markdown#initial-functionality)
