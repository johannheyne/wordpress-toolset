[back to overview](../../README.markdown#tools)

Order Posts
===============================

### Hierarchical Order Posts

Child posts will follow its parent posts. The queried order will remain. Adds trailing level dashes to post title. Keeps original post title in "post_title_orig".

````php
$posts  = get_posts(array(
	'numberposts' => -1,
	'post_status' => 'any',
	'post_type' => 'page',
	'orderby' => 'menu_order', // this order will remain
	'order' => 'ASC',
));

$ordered_posts = array();

tool_hierachical_order_posts( $posts, $ordered_posts );

foreach( $ordered_posts as $post ) {

	echo $post->post_title; // has trailing level dashes
	echo $post->post_title_orig; // the original post title
}
````

[back to overview](../../README.markdown#tools)
