[back to overview](../../README.markdown#initial-functionality)

ToolArrayLayout
===============================

`ToolArrayLayout` lets you layout HTML lists from array data.

### Usage

```php
$ToolArrayLayout = new ToolArrayLayout(array(
	'data' => $data, // May use ToolArrayPos before to sort the data
	//'list_elem' => 'ul',
	//'item_elem' => 'li',
	'list_attrs' => array(
		'class' => array( '' ),
	),
	//'list_attrs/depth=1' => array(),
	'item_attrs' => array(
		'class' => array( '' ),
	),
	//'item_attrs/depth=1' => array(),
	'callback_item' => function( $p ) {

		/*
			$p['key']
			$p['depth']
			$p['data']
		*/

		return '';
	},
	/*'callback_item/depth=1' => function( $p ) {

		return '';
	},*/
	'echo' => false,
));
```

### Example

```php

$data = array(
	'1' => array(
		'name' => 'John',

		'children' = array(
			'1' => array(
				'book' => 'Weyney',
			),
			'2' => array(
				'book' => 'Earth',
			),
		),
	),
	'2' => array(
		'name' => 'Marry',

		'children' = array(
			'1' => array(
				'book' => 'Sky',
			),
			'2' => array(
				'book' => 'Plants',
			),
		),
	),
);

$ToolArrayLayout = new ToolArrayLayout(array(
	'data' => $data,
	'list_elem' => 'ul',
	'item_elem' => 'li',
	'list_attrs' => array(
		'class' => array( '' ),
	),
	'item_attrs' => array(
		'class' => array( '' ),
	),
	'callback_item' => function( $p ) {

		/*
			$p['key']
			$p['depth']
			$p['data']
		*/

		$return = '';

		$return .= 'My name is ' . $p['data']['name'];

		if ( ! empty( $p['data']['children'] ) ) {

			$return .= 'I love this books: ';
		}

		return $return;
	},
	'callback_item/depth=1' => function( $p ) {

		return $p['data']['book'];
	},
));
```

[back to overview](../../README.markdown#initial-functionality)
