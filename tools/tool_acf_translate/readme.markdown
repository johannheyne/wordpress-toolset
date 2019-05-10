[back to overview](../../README.markdown#initial-functionality)

Tool ACF Translate
===============================

Translates ACF group and field complete text values.

title, page_title, menu_title, label, button_label, description, instructions, message, default_value, append, prepend, placeholder, choices values

__Translate String Parts__<br>
It also tries to translate parts of a text using `{{translate this text part}}`.<br>
If you hook into the filter 'acf/fields/flexible_content/layout_title' to customize a layout title, you must wrap the original translatable title with `"<span class="acf-translate">$title</span>"` to translate the title separately from other title content.

````php
	tool( array(
		'name' => 'tool_acf_translate',
		'param' => array(
			'strings' => array(
				// it is general practice to use english as key language
				'Hello' => array(
					'default' => 'Hello', // used if no locale matches
					'de' => 'Hallo', // translation for language in general de_{x}
					'de_DE' => 'Hallo', // translation for localized language
					'fr_FR' => 'Bonjour'
				),
				'World' => array(
					'default' => 'World', // used if no locale matches
					'de' => 'Welt', // translation for language in general de_{x}
					'de_DE' => 'Welt', // translation for localized language
					'fr_FR' => 'Monde'
				)
			)
		),
	) );

````

[back to overview](../../README.markdown#initial-functionality)
