[back to overview](../../README.markdown#tools)

Tool Multilanguage
===============================

### tool_multilanguage_get_lang_list

Returns the list of all languages with labels in
the required language and countrycode.

````php
$array = tool( array(
    'name' => 'tool_multilanguage_get_lang_list',
    'param' => array(
        'locale' => 'ca_FR',
    ),
));

// OR

$array = tool( array(
    'name' => 'tool_multilanguage_get_lang_list',
    'param' => array(
        'lang' => 'ca',
        'country' => 'fr', // default: false
    ),
));
````

### tool_multilanguage_get_lang_label

Returns the language label of an given langcode translated an locale.

````php
$string = tool( array(
    'name' => 'tool_multilanguage_get_lang_label',
    'param' => array(
        'langcode' => 'en_US',
        'locale' => 'ca_FR',
    ),
));

// OR

$string = tool( array(
    'name' => 'tool_multilanguage_get_lang_label',
    'param' => array(
        'langcode' => 'en_US',
        'lang' => 'fr',
        'country' => 'ca', // default: false
    ),
));
````

### tool_multilanguage_get_country_label

Returns the country label translated by an locale.

````php
$string = tool( array(
    'name' => 'tool_multilanguage_get_country_label',
    'param' => array(
        'countrycode' => 'US',
        'locale' => 'ca_FR',
    ),
));
````

### tool_multilanguage_get_regions

Returns the regions with labels translated by an locale.

````php
$string = tool( array(
    'name' => 'tool_multilanguage_get_regions',
    'param' => array(
        'locale' => 'ca_FR',
    ),
));
````

### tool_multilanguage_country_in_region

Returns the regions with labels translated by an locale.

````php
$string = tool( array(
    'name' => 'tool_multilanguage_country_in_region',
    'param' => array(
        'country_code' => 'ca',
        'region_code' => 'eu',
    ),
));
````

[back to overview](../../README.markdown#tools)
