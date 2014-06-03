<?php

	// ATTACHMENT REDIRECT {

		function attachment_redirect() {  

			global $post;

			if ( is_attachment() && isset( $post->post_parent ) && is_numeric( $post->post_parent ) && ( $post->post_parent != 0 ) ) {

				wp_redirect( get_permalink( $post->post_parent ), 301 );
			} elseif ( is_attachment() && isset( $post->post_parent ) && is_numeric( $post->post_parent ) && ( $post->post_parent < 1 ) ) {

				exit;	   
			}
		}

		add_action( 'template_redirect', 'attachment_redirect', 1 );

	// }

?>