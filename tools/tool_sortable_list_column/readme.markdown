[back to overview](../../README.markdown#tools)

Tool Sortable List Column
===============================

Adds sortable columns to WordPress admin lists of posts, pages, custom posttypes and taxonomies.

### Class wpSortableListColumn

````php
add_action( 'admin_init', function() {

	new wpSortableListColumn( array(
		'posttype' => '', // post, page, taxonomies
		'postname' => '', // post, page, custom posttype, taxonomy name
		'position' => 4,
		'colid' => '',
		'collabel' => _x( '', '', 'toolset' ),
		'rowlabelfunction' => function( $colid, $tag_id, $that ) {

			if ( $colid === '' ) {

				// example of getting labels from a ACF taxonomy field
				$label = $that->get_taxonomy_term_meta_label( array(
					'taxonomy_name' => '', // taxonomy name of the admin list
					'meta_post_type' => '', // post_type the meta taxonomy belongs to
					'meta_taxonomy_name' => '', // taxonomy name of the meta term
					'meta_key' => '',
					'meta_id' => $tag_id,
				));

				echo $label;
			}
		},
		'sorttype' => 'meta',
		'sortmetakey' => '',
		'sortmetaorderby' => 'meta_value',
		'sorttaxname' => '',
	));

});

````

[back to overview](../../README.markdown#tools)
