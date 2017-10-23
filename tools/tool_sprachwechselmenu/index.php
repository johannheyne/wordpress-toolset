<?php

	// SPRACHWECHSELMENU ( Version 2 ) {

		/* header.php

			<?php

				echo tool_sprachwechselmenu( array(
					'class_link' => 'diblock menu-lang-item sprite sprite-flagge-{id} font'
				) );

			?>
		*/

		function tool_sprachwechselmenu( $p = array() ) {

			$p += array(
				'replace' => array(),
				'show_current_lang' => false,
				'class_wrap' => 'menu-lang absolute',
				'class_link' => 'diblock menu-lang-item sprite sprite-flagge-{id} font'
			);

			global $wpdb, $post;
			$blog_list = $wpdb->get_results("SELECT * FROM {$wpdb->blogs}");

			$current_blog_id = get_current_blog_id();

			$ret = '';
			$ret .= '<div class="' . $p['class_wrap'] . '">';
			foreach ( $blog_list as $blog ) {

					$site = $GLOBALS['toolset']['sites'][ $blog->blog_id ];

					//$link = get_permalink( $post->ID );
					$obj = get_field( 'lang_' . $site['id'] );

					if (
						isset($obj->ID) ||
						( $p['show_current_lang'] === true && $current_blog_id == $blog->blog_id )
					)  {

						tool_switch_to_blog( (int)$blog->blog_id );
						/* get_permalink() ignores rewrite slug from register_post_type()
						   thats why some slugs has to be replaced here. */
						$link = false;
						if ( isset($obj->ID) ) $link = strtr( get_permalink( $obj->ID ), $p['replace'] );
						tool_restore_blog();

						$class = strtr( $p['class_link'], array( '{id}' => $site['id'] ) );

						if ( $link ) $ret .= '<a class="' .$class . '" href="' . @$link . '">' . $site['name']  . '</a>';
						if ( !$link ) $ret .= '<span class="' .$class . '">' . $site['name']  . '</span>';
					}
			}

			$ret .=  '</div>';

			return $ret;
		}

	// }
