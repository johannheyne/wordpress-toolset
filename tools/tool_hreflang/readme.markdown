[back to overview](../../README.markdown#tools)

Tool Hreflang
===============================

### tool_hreflang

Echos something like…
<link rel="alternate" href="https://www.domain.com/my-page/" hreflang="en">
<link rel="alternate" href="https://www.domain.com/fr/my-page/" hreflang="fr">
<link rel="alternate" href="https://www.domain.com/de/my-page/" hreflang="de">

````php
    tool( array(
    'name' => 'tool_hreflang',
    );
````

### Alternates Accross Sites

Adds fields to the posttypes that can be used to link to alternative posts from other multi-sites.

````php
if ( is_admin() ) {

    add_filter( 'toolset/hreflang/post_types', function( $post_types ) {

        array_push( $post_types, 'post' );
        array_push( $post_types, 'page' );

        return $post_types;
    } );
}
````

[back to overview](../../README.markdown#tools)
