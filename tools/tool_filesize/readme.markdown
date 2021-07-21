[back to overview](../../README.markdown#tools)

Tool "Filesize"
===============================

Returns a human readable filesize like "100,72 KB"

## tool_get_filesize

````php
$filesize_string = tool( array(
	'name' => 'tool_get_filesize',
	'param' => $attachment_id,
);
````

## tool_format_filesize()

The returning value is always round up. This is for example important, if an displayed filesize of an uploded file should compared to an given max upload filesize. So if the validation kicks in but both values are equal displayed because of normal rounding, the usability degreases.

````php
$decimals = array(
	0 => 0, // round for bytes
	1 => 0, // round for kb
	2 => 2, // round for MB
);

tool_format_filesize( $bytes, $decimals );
````

## Filters

The filters can be used to format the filesize number for different languages.

```php
// Determines the number of decimal places.
add_filter( 'tool_format_filesize/decimals', function( $decimals, $unit ) {

	// Differentiate between units
	// $unit === 0 -> 'Bytes'
	// $unit === 1 -> 'Kilobytes'
	// $unit === 2 -> 'Meagbytes'

	// prevents decimals for with byte and kilobyte formatet sizes
	if ( $unit < 2 ) {

		$decimals = 0;
	}

	return $decimals;
}, 10, 2 );
```

```php
// Specifies the separator for the decimal places.
add_filter( 'tool_format_filesize/dec_point', function( $dec_point ) {

	return $dec_point;
});
```

```php
// Determines the thousands separator.
add_filter( 'tool_format_filesize/thousands_sep', function( $thousands_sep ) {

	return $thousands_sep;
});
```

[back to overview](../../README.markdown#tools)
