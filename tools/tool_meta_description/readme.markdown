[back to overview](../../README.markdown#tools)

Tool tool_meta_description
===============================

This tool generates a `<meta name="description" content=""/>` tag.

````php
	tool( array(
		'name' => 'tool_meta_description',
		'param' => array(
			'acf_field_name_page_decription' => 'meta_page_description' // default
		)
	) );
````

This tool takes a global description from an ACF field by name `opt_site_description_global . THEME_LANG_SUFIX` and may replace this by a custom page description with ACF field name "meta_page_description".

The `THEME_LANG_SUFIX` is set by the tool "tool_localization_constants" and makes a description for every language possible. The `THEME_LANG_SUFIX` works together with the WPML Plugin.

[back to overview](../../README.markdown#tools)
