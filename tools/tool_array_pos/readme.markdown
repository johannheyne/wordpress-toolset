[back to overview](../../README.markdown#initial-functionality)

ToolArrayPos
===============================

ToolPosArray lets you order array items by integers and rules.

### Example

The following example orders the array items so, that the id`s are in the order a, b, c, d, e.

````php
$array = array(
	array(
		'id' => 'c',
		'pos' => 10,
	),
	array(
		'id' => 'a',
		'pos_top' = 10, // if multiple 'pos_top' items, the number reflects the order
	),
	array(
		'id' => 'e',
		'pos_bottom' = 10, // if multiple 'pos_top' items, the number reflects the order
	),
	array(
		'id' => 'b',
		'pos_before' => 'c', // if multiple 'pos_before' items, the order in the source array reflects the order
	),
	array(
		'id' => 'd',
		'pos_after' => 'c', // if multiple 'pos_after' items, the order in the source array reflects the order
	),
);

$pos = new ToolArrayPos( array(
	'array' => $array, // array to sort
	'param' => array(
		'pos_key' => 'id', // item array key used for positioning by pos_before and pos_after
	),
));

$positioned_array = $pos->get();
````

You also can use nested rules of `pos_before` and `pos_after`. Also you can use `pos_before` and `pos_after` on items with `'pos_top'` and `'pos_bottom'`.

[back to overview](../../README.markdown#initial-functionality)
