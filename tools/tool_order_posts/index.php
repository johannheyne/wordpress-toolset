<?php

	// HIERARCHICAL ORDER POSTS {

		/*
			$ordered_posts = array();
			hierachical_order_posts( $posts, $ordered_posts );

			// do semthing with $ordered_posts

			Adds property 'level' to each post object representing hierachical depth starting with 0
			Adds property 'post_title_orig' holding the original post title
			Updates property 'post_title' with trailing "-" representing level
		*/

		function tool_hierachical_order_posts( &$posts, &$result, $parent_id = 0, $level = 0 ) {

			foreach ( $posts as $post ) {

				if ( $post->post_parent == $parent_id ) {

					$result[] = $post;

					$post->level = $level;
					$post->post_title_orig = $post->post_title;
					$post->post_title = '';

					for ( $i = 0; $i < $level; $i++ ) {

						$post->post_title .= '-';
					}

					if ( $post->level > 0 ) {

						$post->post_title .= ' ';
					}

					$post->post_title .= $post->post_title_orig;

					$level_new = $level + 1;

					tool_hierachical_order_posts( $posts, $result, $post->ID, $level_new );
				}
			}
		}

	// }
