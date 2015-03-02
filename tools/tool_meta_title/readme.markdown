[back to overview](../../README.markdown#tools)

Tool tool_meta_title
===============================

This tool generates a `<title></title>` by rules.

````php
	tool( array(
		'rules' => array(
			'{sitetitle}' => true,
			'{pagetitle}' => true,
		),
		'delimiter' => ' ',
		'pagetitle_on_hompage' => false,
		'prepend_sitetitle_on_custom_pagetitle' => false
	) );
````

[back to overview](../../README.markdown#tools)
