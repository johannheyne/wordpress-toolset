[back to overview](../../README.markdown#initial-functionality)

Tool Posttypes
===============================

- [Registers Custom PostTypes By Config](#registers-custom-posttypes-by-config)
- [Gets Current PostType (Frontend)](#gets-current-posttype-frontend)
- [Gets Current PostType (Admin)](#gets-current-posttype-admin)


### Registers Custom PostTypes By Config

````php
add_action( 'toolset_config', function() {

	config_add_init( array(
		'name' => 'tool_posttypes',
		'data' => array(
			'posttypes' => array(
				'{posttype_slug}' => array(

					// MultiSite: restrict posttype to sites

					'toolset' => array(
						'sites' => array( 1 ),
					),

					// regulare register_post_type() arguments

					'labels' => array(
						'name' => 'Kickstarts',
						'singular_name' => 'Kickstart',
						'menu_name' => 'Kickstarts',
						'add_new' => 'add_new',
						'add_new_item' => 'Add',
						'edit_item' => 'Edit',
						'new_item' => 'New',
						'view_item' => 'Show',
						'search_items' => 'Search',
						'not_found' =>  'Nothing found',
						'not_found_in_trash' => 'Nothing in Trash',
						'parent_item_colon' => ''
					),

					'public' => true,
					'publicly_queryable' => true,
					'show_ui' => true,
					'query_var' => true,
					'capability_type' => 'page',
					'hierarchical' => true,
					//'show_in_menu' => 'edit.php?post_type=name', // as a submenu

					'rewrite' => array(
						'slug' => _x( 'demo', 'URL slug' ),
						'with_front' => false // prevents "/blog/" on mainsite of multisites
					),

					'menu_position' => 20,
						// 5 - below Posts
						// 10 - below Media
						// 15 - below Links
						// 20 - below Pages
						// 25 - below comments
						// 60 - below first separator
						// 65 - below Plugins
						// 70 - below Users
						// 75 - below Tools
						// 80 - below Settings
						// 100 - below second separator


					'supports' => array( 'title', 'editor'),  // editor für ACF benötigt
						// supports
						//	  'title'
						//	  'editor' (content)
						//	  'author'
						//	  'thumbnail' (featured image, current theme must also support post-thumbnails)
						//	  'excerpt'
						//	  'trackbacks'
						//	  'custom-fields'
						//	  'comments' (also will see comment count balloon on edit screen)
						//	  'revisions' (will store revisions)
						//	  'page-attributes' (menu order, hierarchical must be true to show Parent option)
						//	  'post-formats' add post formats, see http://codex.wordpress.org/Post_Formats

					'has_archive' => 'kickstarts', // domain.com/kickstarts/
						// http://mark.mcwilliams.me/wordpress-3-1-introduces-custom-post-type-archives/

					//'taxonomies' => array( 'category', 'post_tag', 'demos_kategorien' )
						// category = default post category
						// post_tag = default post tags
						// kickstart = custom taxomonie name

					//'capabilities' => array(
					//	'create_posts' => false, // Removes support for the "Add New" function
					//),
					// 'map_meta_cap' => true, // Set to false, if users are not allowed to edit/delete existing posts

				),
			),
		),
	) );

}, 10 );
````



### Gets Current PostType (Frontend)

````php
$current_posttype = tool( array(
	'name' => 'tool_get_current_post_type',
));
````



### Gets Current PostType (Admin)

````php
if ( is_admin() ) {

	add_action( 'current_screen', function() {

		$current_posttype = tool( array(
			'name' => 'tool_get_admin_current_post_type',
			'param' => array(
				'is_archive' => true, // requires tool execution in action "current_screen"
				'is_single' => true,  // requires tool execution in action "current_screen"
			)
		));
	}
}
````


[back to overview](../../README.markdown#tools)
