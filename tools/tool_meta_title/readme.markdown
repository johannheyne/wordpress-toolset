[back to overview](../../README.markdown#tools)

Tool tool_meta_title
===============================

This tool generates a `<title></title>` by rules.

````php
    tool( array(
        'name' => 'tool_meta_title',
        'param' => array(
            'rules' => array(
                '{page_title}' => true, // order for need
                '{site_title}' => true,
            ),
            'delimiter' => ' - ',
            'page_title_on_homepage' => false,
            'prepend_posttype_name_on_archives' => false,
            'titles' => array(
                'is_home' => 'Blog',
                'is_404' => '404',
            ),
        ),
    ) );
````
`prepend_posttype_name_on_archives` prepends the posttype name on custom posttype archives. Supports is_category() with single_cat_title(), is_date() with single_month_title(), is_tag() with single_tag_title();

[back to overview](../../README.markdown#tools)
