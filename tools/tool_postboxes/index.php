<?php

	// POSTBOXES Version 1 {

		function tool_postboxes_add() {

			// Source: http://www.smashingmagazine.com/2011/10/04/create-custom-post-meta-boxes-wordpress/
			// Alternative: https://github.com/farinspace/wpalchemy

			add_action( 'add_meta_boxes', function() {

				foreach ( $GLOBALS['toolset']['inits']['tool_postboxes']['add'] as $id => $item ) {

					add_meta_box(
						$id . '_post_box',
						$item['title'],
						function( $object, $box ) {

							wp_nonce_field( basename( __FILE__ ), str_replace( '_post_box', '', $box['id'] ) . '_post_class_nonce' );

							$GLOBALS['toolset']['inits']['tool_postboxes']['add'][ str_replace( '_post_box', '', $box['id'] ) ]['content']( $object );
						},
						$item['post_type'], // 'post', 'page', 'dashboard', 'link', 'attachment' or 'custom_post_type'
						$item['context'], // 'normal', 'advanced', or 'side'
						$item['priority'] // 'high', 'core', 'default' or 'low'
					);
				}

			} );
		};

		function tool_postboxes_safe( $post_id, $post ) {

			// Source: http://www.smashingmagazine.com/2011/10/04/create-custom-post-meta-boxes-wordpress/
			// Alternative: https://github.com/farinspace/wpalchemy

			foreach ( $GLOBALS['toolset']['inits']['tool_postboxes']['add'] as $id => $item ) {

				if ( $post->post_type == $item['post_type'] ) {

					/* Verify the nonce before proceeding. */
					if ( ! isset( $_POST[ $id . '_post_class_nonce'] ) || ! wp_verify_nonce( $_POST[ $id . '_post_class_nonce'], basename( __FILE__ ) ) ) {

						return $post_id;
					}

					/* Get the post type object. */
					$post_type = get_post_type_object( $post->post_type );

					/* Check if the current user has permission to edit the post. */
					if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {

						return $post_id;
					}

					foreach ( $item['fields'] as $meta_key => $field ) {

						$new_meta_value = $_POST[ $meta_key ];

						/* Get the meta value of the custom field key. */
						$meta_value = get_post_meta( $post_id, $meta_key, true );

						/* If a new meta value was added and there was no previous value, add it. */
						if ( $new_meta_value && '' == $meta_value ) {

							add_post_meta( $post_id, $meta_key, $new_meta_value, true );
						}
						  /* If the new meta value does not match the old value, update it. */
						elseif ( $new_meta_value && $new_meta_value != $meta_value ) {

							update_post_meta( $post_id, $meta_key, $new_meta_value );
						}

						/* If there is no new meta value but an old value exists, delete it. */
						elseif ( '' == $new_meta_value && $meta_value ) {

							delete_post_meta( $post_id, $meta_key, $meta_value );
						}
					}
				}
			}
		}

		if ( isset( $GLOBALS['toolset']['inits']['tool_postboxes']['add'] ) ) {

			add_action( 'load-post.php', 'tool_postboxes_add' );
			add_action( 'load-post-new.php', 'tool_postboxes_add' );
			add_action( 'save_post', 'tool_postboxes_safe', 10, 2 );
		}

	// }
