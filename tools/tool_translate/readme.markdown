[back to overview](../../README.markdown#tools)

Tool Translate
===============================

### Translations Admin List

Registers a posttype 'translations' and provides an Admin Interface for translating strings. The strings must registered by the following code snippet and appears automatically on the 'translations' posttype list for translation.

````php
$GLOBALS['toolset']['classes']['ToolsetTranslation']->add_text( array(
	'text' => 'products',
	'context' => 'URL Slug',
	// The text domain is 'tool_translate'.
));
````

Get translation with…
````php
$string = _x( 'products', 'URL SLug', 'tool_translate' );
````

The context 'URL Slug' especially adds the translations to the rewrite rule of 'products'. The context 'URL Slug' must be used in combination with rewriting custom posttype slugs…
````php
'rewrite' => array(
	'slug' => _x( 'products', 'URL SLug', 'tool_translate' ),
	'with_front' => false // prevents "/blog/" on mainsite of multisites
),
````

### Class ToolsetL10N

Translate strings for Frontend and Admin Area.

Defines a Translation…
````php
$GLOBALS['toolset']['classes']['ToolsetL10N']->_x( array(
	'text' => 'Translation',
	'translations' => array(
		'default' => 'Translation',
		'de' =>  'Übersetzung', // 'de' also translates locales like 'de_DE', 'de_AU'
	),
	'context' => 'my_context',
	'domain' => 'my_text_domain',
	'locale' => 'user', // 'user' translates by user locale, 'front' translates by frontend locale
));
````

Gets a translation…
````php
$string = _x( 'Translation', 'my_context', 'my_text_domain' );
````

[back to overview](../../README.markdown#tools)
