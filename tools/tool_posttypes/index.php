<?php

	function tool_posttypes_register() {

		if ( empty( $GLOBALS['toolset']['inits']['tool_posttypes']['posttypes'] ) ) {

			return false;
		}

		global $current_blog;

		foreach ( $GLOBALS['toolset']['inits']['tool_posttypes']['posttypes'] as $name => $args ) {

			$register = true;

			if ( isset( $args['toolset'] ) ) {

				if ( isset( $args['toolset']['sites'] ) ) {

					if (
						isset( $current_blog )
						AND ! in_array( $current_blog->blog_id, $args['toolset']['sites'] )
					) {

						$register = false;
					}
				}
			}

			if ( $register ) {

				register_post_type( $name, $args );
			}
		}
	}

	add_action( 'init', 'tool_posttypes_register' );

	function tool_get_admin_current_post_type( $p ) {

		// DEFAULTS {

			$defaults = array(
				'is_archive' => true,
				'is_single' => true,
			);

			$p = array_replace_recursive( $defaults, $p );

		// }

		global $post, $typenow, $current_screen;

		// we have a post so we can just get the post type from that
		if (
			$p['is_single'] AND
			$post && $post->post_type
		) {

			return $post->post_type;
		}

		// check the global $current_screen object - set in sceen.php
		elseif (
			$current_screen && $current_screen->post_type
		) {

			if ( ! $p['is_single'] AND $current_screen->base == 'post' ) {

				return false;
			}

			if ( ! $p['is_archive'] AND $current_screen->base == 'edit' ) {

				return false;
			}

			return $current_screen->post_type;
		}

		// check the global $typenow - set in admin.php
		elseif ( $typenow ) {

			return $typenow;
		}

		// lastly check the post_type querystring
		elseif ( isset( $_REQUEST['post_type'] ) ) {

			return sanitize_key( $_REQUEST['post_type'] );
		}

		//we do not know the post type!
		return null;
	}

	function tool_get_current_post_type() {

		$post_type = get_post_type();

		if ( empty( $post_type ) ) {

			global $wp_query;

			if (
				! empty( $wp_query ) AND
				! empty( $wp_query->query_vars['post_type'] )
			) {

				$post_type = $wp_query->query_vars['post_type'];
			}
		}

		return $post_type;
	}
