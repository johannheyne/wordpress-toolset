[back to overview](../../README.markdown#initial-functionality)

Tool Posttypes
===============================

### Add Custom PostType

````php
add_action( 'toolset_config', function() {

    config_add_init( array(
        'name' => 'tool_posttypes',
        'data' => array(
            'posttypes' => array(
                'example' => array(
                    'toolset' => array(
                        'sites' => array( 1 ), // MultiSite: restrict posttype to sites
                    ),
                    // regulare register_post_type() arguments
                ),
            ),
        ),
    ) );

}, 10 );
````

### Get Admins Current PostType

````php
if ( is_admin() ) {

    $current_posttype = tool( array(
        'name' => 'tool_get_admin_current_post_type',
    );

    if ( $current_posttype === 'product' ) {

    }
}
````

[back to overview](../../README.markdown#tools)
