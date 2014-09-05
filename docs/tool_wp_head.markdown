[back to overview](../README.markdown#initial-functionality)

WordPress Head
===============

This influences the output of the WordPress ````wp_head()```` function.

```php
$GLOBALS['toolset'] = array(
    'inits' => array(
        'tool_wp_head' => array(
            'feed_links_extra' => array(
                'show' => false,
            ),
            'feed_links' => array(
                'show' => false,
            ),
            'rsd_link' => array(
                'show' => false,
            ),
            'wlwmanifest_link' => array(
                'show' => false,
            ),
            'index_rel_link' => array(
                'show' => true,
            ),
            'wp_shortlink_wp_head' => array(
                'show' => false,
            ),
            'adjacent_posts_rel_link_wp_head' => array(
                'show' => false,
            ),
            'parent_post_rel_link' => array(
                'show' => false,
            ),
            'start_post_rel_link' => array(
                'show' => false,
            ),
            'adjacent_posts_rel_link' => array(
                'show' => false,
            ),
            'wp_generator' => array(
                'show' => false,
            ),
            'rel_canonical' => array(
                'show' => true,
            ),
        ),
    ),
);
`````

[back to overview](../README.markdown#initial-functionality)
