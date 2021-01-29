[back to overview](../../README.markdown#initial-functionality)

ToolArrayPos
===============================

ToolPosArray lets you order array items by integers and rules. You also can nest items.

### Example

The following example orders the array items so, that the id`s are in the order a, b, c, d, e and b has children ordered b1, b2.

````php
$array = array(
	array(
		'pos_key' => 'c',
		'pos' => 10,
	),
	array(
		'pos_key' => 'a',
		'pos_top' => 10, // if multiple 'pos_top' items, the number reflects the order
	),
	array(
		'pos_key' => 'e',
		'pos_bottom' => 10, // if multiple 'pos_top' items, the number reflects the order
	),
	array(
		'pos_key' => 'b',
		'pos' => 10,
		'pos_before' => 'c', // if multiple 'pos_before' items, the order in the source array reflects the order
	),
	array(
		'pos_key' => 'd',
		'pos' => 10,
		'pos_after' => 'c', // if multiple 'pos_after' items, the order in the source array reflects the order
	),
	array(
		'pos_key' => 'b2',
		'parent_pos_key' => 'b',
		'pos' => 20,
	),
	array(
		'pos_key' => 'b1',
		'parent_pos_key' => 'b',
		'pos' => 10,
	),
);

$pos = new ToolArrayPos( array(
	'array' => $array, // array to sort
	'param' => array(
		'pos_key' => 'pos_key', // item array key used for positioning by pos_before and pos_after
		'parent_pos_key' => 'parent_pos_key', // item array key used for positioning by pos_before and pos_after
	),
));

$positioned_array = $pos->get();
````

You also can use nested rules of `pos_before` and `pos_after`. Also you can use `pos_before` and `pos_after` on items with `'pos_top'` and `'pos_bottom'`.

[back to overview](../../README.markdown#initial-functionality)
