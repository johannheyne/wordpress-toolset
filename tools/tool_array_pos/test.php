<?php

	$array = array(
		array(
			'id' => 'c',
			'pos' => 10,
		),
		array(
			'id' => 'a',
			'pos_top' => 10, // if multiple 'pos_top' items, the number reflects the order
		),
		array(
			'id' => 'before a',
			'pos_top' => 5, // if multiple 'pos_top' items, the number reflects the order
		),
		array(
			'id' => 'e',
			'pos_bottom' => 10, // if multiple 'pos_top' items, the number reflects the order
		),
		array(
			'id' => 'after e',
			'pos_bottom' => 15, // if multiple 'pos_top' items, the number reflects the order
		),
		array(
			'id' => 'b',
			'pos_before' => 'c', // if multiple 'pos_before' items, the order in the source array reflects the order
		),
		array(
			'id' => 'd',
			'pos_after' => 'c', // if multiple 'pos_after' items, the order in the source array reflects the order
		),
		array(
			'id' => 'after d',
			'pos_after' => 'd', // if multiple 'pos_after' items, the order in the source array reflects the order
		),
		array(
			'id' => 'pos_before after d',
			'pos_before' => 'after d', // if multiple 'pos_after' items, the order in the source array reflects the order
		),
		array(
			'id' => 'pos_before after e',
			'pos_before' => 'after e', // if multiple 'pos_after' items, the order in the source array reflects the order
		),
	);

	$pos = new ToolArrayPos( array(
		'array' => $array, // array to sort
		'param' => array(
			'pos_key' => 'id', // item array key used for positioning by pos_before and pos_after
		),
	));

	$positioned_array = $pos->get();

	error_log( print_r( $positioned_array, true) );
