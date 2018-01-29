<?php

	// UNREGISTER DEFAULT WIDGETS ( Version 2 ) {

		function unregister_default_wp_widgets() {

			$p = array();

			if ( is_array( $GLOBALS['toolset']['inits']['tool_widgets_unregister_defaults'] ) ) {

				$p = $GLOBALS['toolset']['inits']['tool_widgets_unregister_defaults'];
			}

			// DEFAULTS {

				$defaults = array(
					'exclude' => array(),
				);

				$p = array_replace_recursive( $defaults, $p );

			// }

			$widgets = array(
				'pages' => 'WP_Widget_Pages',
				'calendar' => 'WP_Widget_Calendar',
				'archives' => 'WP_Widget_Archives',
				'links' => 'WP_Widget_Links',
				'meta' => 'WP_Widget_Meta',
				'search' => 'WP_Widget_Search',
				'text' => 'WP_Widget_Text',
				'categories' => 'WP_Widget_Categories',
				'recent_posts' => 'WP_Widget_Recent_Posts',
				'recent_comments' => 'WP_Widget_Recent_Comments',
				'rss' => 'WP_Widget_RSS',
				'tag_cloud' => 'WP_Widget_Tag_Cloud',
				'nav_menu' => 'WP_Nav_Menu_Widget',
				'audio' => 'WP_Widget_Media_Audio',
				'image' => 'WP_Widget_Media_Image',
				'video' => 'WP_Widget_Media_Video',
				'gallery' => 'WP_Widget_Media_Gallery',
				'html' => 'WP_Widget_Custom_HTML',
			);

			foreach ( $widgets as $key => $value ) {

				if ( ! in_array( $key, $p['exclude'] ) ) {

					unregister_widget( $widgets[ $key ] );
				}
			}

		}

		add_action( 'widgets_init', 'unregister_default_wp_widgets', 1 );

	// }
