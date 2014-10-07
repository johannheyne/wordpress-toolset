[back to overview](../README.markdown#assets)

Meta Title
===========

Returns the HTML title tag.

````php
tool( array(
	'name' => 'tool_meta_title',
	'param' => array(
		'rules' => array(
			'{sitetitle}' => true,
			'{pagetitle}' => true,
		),
		'delimiter' => ' ',
		'pagetitle_on_hompage' => false
	)
) );
````

[back to overview](../README.markdown#assets)