<?php

	// REMOVING IMAGE DIMENSIONS ATTRIBUTES ( Version 1 ) {

		//add_filter( 'post_thumbnail_html', 'remove_image_dimensions_attributes', 20 );
		//add_filter( 'image_send_to_editor', 'remove_image_dimensions_attributes', 20 );
		add_filter( 'the_content', 'remove_image_dimensions_attributes', 20 );

		function remove_image_dimensions_attributes( $html ) {

			$html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );

			return $html;
		}

	// }
