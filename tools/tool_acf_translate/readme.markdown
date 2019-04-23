[back to overview](../../README.markdown#initial-functionality)

Tool ACF Translate
===============================

Translates ACF group and field complete text values.

title, page_title, menu_title, label, button_label, description, instructions, message, default_value, append, prepend, placeholder, choices values

It also tries to translate parts of a text using {{translate this text}}.

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
