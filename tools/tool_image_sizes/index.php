<?php

	// IMAGE SIZES ( Version 1 ) {

		function tool_add_image_sizes() {

			foreach ( $GLOBALS['toolset']['inits']['tool_image_sizes']['add_image_sizes'] as $size => $item ) {

				add_image_size( $size, $item['width'], $item['height'], $item['crop'] );
			}
		}

		function tool_editor_images_remove( $sizes ) {

			// removing media-sizes from select-input for inserting into the editor
			//if ( isset( $_REQUEST['post'] ) ) {

				$posttype = get_post_type( $_REQUEST['post'] );

				foreach ( $GLOBALS['toolset']['inits']['tool_image_sizes']['editor_images_remove'] as $size => $posttypes ) {

					$check = true;

					if ( is_admin() && ! in_array( $posttype, $posttypes ) ) {

						$check = false;
					}

					if ( $check ) {

						unset( $sizes[ $size ] );
					}
				}
			//}

			return $sizes;
		}

		function tool_editor_images_add( $sizes ) {

			// adding relevant media-sizes to the select-input for inserting into the editor

			//if ( isset( $_REQUEST['post'] ) ) {

				$posttype = get_post_type( $_REQUEST['post'] );

				foreach ( $GLOBALS['toolset']['inits']['tool_image_sizes']['editor_images_add'] as $size => $item ) {

					$check = true;

					if ( is_admin() && $item['posttypes'] && ! in_array( $posttype, $item['posttypes'] ) ) {

						$check = false;
					}

					if ( $check ) {

						$sizes[ $size ] = $item['label'];
					}
				}
			//}

			return $sizes;
		}

		if ( isset( $GLOBALS['toolset']['inits']['tool_image_sizes']['add_image_sizes'] ) && is_array( $GLOBALS['toolset']['inits']['tool_image_sizes']['add_image_sizes'] ) ) {

			tool_add_image_sizes();
		}

		if ( isset( $GLOBALS['toolset']['inits']['tool_image_sizes']['editor_images_add'] ) && is_array( $GLOBALS['toolset']['inits']['tool_image_sizes']['editor_images_add'] ) ) {

			add_filter( 'image_size_names_choose','tool_editor_images_add', 10, 1 );
		}

		if ( isset( $GLOBALS['toolset']['inits']['tool_image_sizes']['editor_images_remove'] ) && is_array( $GLOBALS['toolset']['inits']['tool_image_sizes']['editor_images_remove'] ) ) {

			add_filter( 'image_size_names_choose','tool_editor_images_remove', 10, 1 );
		}

	// }

?>
