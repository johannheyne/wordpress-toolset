<?php

	// POSTBOXES REMOVE Version 1 {

		add_action( 'admin_head', function() {

			if (
				isset( $GLOBALS['toolset']['inits']['tool_metabox_remove'] )
				&& is_array( $GLOBALS['toolset']['inits']['tool_metabox_remove'] )
			) {
				
				$defaults_metabox = array(
					'pages' => array( 'post', 'page', 'attachment', 'link', 'dashboard' ),
					'context' => array( 'normal', 'advanced', 'side' ),
				);
				
				$wp_metaboxes = array(
					'author' => 'authordiv',
					'category' => 'categorydiv',
					'commentstatus' => 'commentstatusdiv',
					'comments' => 'commentsdiv',
					'format' => 'formatdiv',
					'pageparent' => 'pageparentdiv',
					'postcustom' => 'postcustom',
					'postexcerpt' => 'postexcerpt',
					'postimagediv' => 'postimage',
					'revisions' => 'revisionsdiv',
					'slug' => 'slugdiv',
					'submit' => 'submitdiv',
					'tags' => 'tagsdiv-post_tag',
					'trackbacks' => 'trackbacksdiv',
				);
				
				/*
					'author'
					'category'
					'commentstatus'
					'comments'
					'format'
					'pageparent'
					'postcustom'
					'postexcerpt'
					'postimagediv'
					'revisions'
					'slug'
					'submit'
					'tags'
					'trackbacks'
				*/
				
				foreach ( $GLOBALS['toolset']['inits']['tool_metabox_remove'] as $key => $items ) {
					
					if ( ! isset( $items['pages'] ) or count( $items['pages'] ) < 1 ) {
						
						$items['pages'] = $defaults_metabox['pages'];
					}
					
					if ( ! isset( $items['context'] ) or count( $items['context'] ) < 1 ) {
						
						$items['context'] = $defaults_metabox['context'];
					}

					if ( isset( $wp_metaboxes[ $key ] ) ) {

						$key = $wp_metaboxes[ $key ];
					}

					foreach ( $items['pages'] as $page ) {
						
						foreach ( $items['context'] as $context ) {
						
							error_log( print_r( $key, true) );
							error_log( print_r( $page, true) );
							error_log( print_r( $context, true) );
							
							// http://codex.wordpress.org/Function_Reference/remove_meta_box
							remove_meta_box( $key, $page, $context );
						}
						
					}
				}
			}

		} );

	// }
?>