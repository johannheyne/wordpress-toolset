[back to overview](../../README.markdown#tools)

Tool Translate
===============================

Enable Tool by…

````php
$GLOBALS['toolset']['inits']['tool_translate'] = true;
````

### Admin UI for Translations

Registers a posttype 'translations' and provides an Admin Interface for translating strings. The strings must registered by the following code snippet and appears automatically on the 'translations' posttype list for translation.

````php
$GLOBALS['toolset']['classes']['ToolsetTranslation']->add_text( array(
    'text' => 'products', // use it like _x( 'products', 'URL Slug', 'tool_translate' ),
    'context' => 'URL Slug',
    'param' => array(
        'text_default' => 'products', // The default text if there is no translation
        'type' => 'text', // editing field type: 'text', 'textarea'
        'description' => 'URL Slug of Posttype "products"',
        'default_transl' => array(
            'de' => 'produkte',
            'en' => 'products',
        ),
		'js' => 'current', // false, 'all' (all languages), 'current' (current language) // translation accessable with App.ln.get( string, context, domain );
    ),
    // The text domain is 'tool_translate'.
));
````

#### Get translation with…
````php
// PHP
$string = _x( 'products', 'URL Slug', 'tool_translate' );
````

````php
// JS
App.ln.get( 'products', 'URL Slug', 'tool_translate' );
App.ln.get( { 'products', 'en' }, 'URL Slug', 'tool_translate' );
````
#### URL Rewrite translation
The context __'URL Slug'__ especially adds the translations to the rewrite rule of 'products'. The context 'URL Slug' must be used in combination with rewriting custom posttype slugs…
````php
'rewrite' => array(
    'slug' => _x( 'products', 'URL Slug', 'tool_translate' ),
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
    'locale' => 'auto', // 'auto' detetects locale wether from admin user or frontend, 'user' translates by user locale, 'front' translates by frontend locale
));
````

Gets a translation…
````php
$string = _x( 'Translation', 'my_context', 'my_text_domain' );
````

[back to overview](../../README.markdown#tools)
