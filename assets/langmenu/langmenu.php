<?php
	
	/* SPRACHMENU */
	
	$blog_list = $wpdb->get_results("SELECT * FROM {$wpdb->blogs}");
	
	/* get_permalink() ignores rewrite slug from register_post_type()
	   thats why these slugs has to be replaced.
	*/
	
	$replace['de'] = array(
		'/products/' => '/produkte/'
	);
	$replace['en'] = array(
		'/produkte/' => '/products/'
	);
	
	foreach ( $blog_list as $blog ) {
		
		$site = $GLOBALS['toolset']['sites'][ $blog->blog_id ];
		
		$link[ $site['id'] ] = get_permalink( $post->ID );
		$obj_id = get_field( 'lang_' . $site['name'], false, false );
		
		if ( isset($obj_id) )  {
			
			tool_switch_to_blog( (int)$blog->blog_id );
			
			$link[ $site['id'] ] = strtr( get_permalink( $obj_id ), $replace[ $site['id'] ] );
			tool_restore_blog();
		}
	}
	
	echo '<div class="menu-lang">';
		echo '<a class="diblock menu-lang-item sprite sprite-flagge-de" href="' . @$link['de'] . '">Deutsch</a>';
		echo '<a class="diblock menu-lang-item sprite sprite-flagge-en" href="' . @$link['en']  . '">English</a>';
	echo '</div>';
	
?>