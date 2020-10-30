[back to overview](../../README.markdown#tools)

Tool "Filesize"
===============================

Returns a human readable filesize like "100,72 KB"

````php
$filesize_string = tool( array(
	'name' => 'tool_get_filesize',
	'param' => $attachment_id,
);
````

## Filters

The filters can be used to format the filesize number for different languages.

```php
// Determines the number of decimal places.
add_filter( 'tool_format_filesize/decimals', function( $decimals ) {

	return $decimals;
});
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
