<?php

	// WPML HACKS Version ( 1 ) {

		if ( !function_exists( 'icl_is_translated_duplicate' ) ) {

			function icl_is_translated_duplicate( $post_id ) {

				global $wpdb;
				/* if the post is a duplicate, then return the post_id of the base post */
				$sql = $wpdb->prepare( "SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = '_icl_lang_duplicate_of'", $post_id );
				$result = $wpdb->get_var( $sql );  

				$return = $result === false ? false : $result;

				return $return;
			}
		}

		if ( !function_exists( 'icl_get_language_code' ) ) {

			function icl_get_language_code() {

				// get the real language code on duplicated posts
				// they contain content with the default language
				global $post;

				$dlang = ICL_LANGUAGE_CODE;

				if ( isset( $post ) && icl_is_translated_duplicate( $post->ID ) ) {

					global $sitepress;

					$dlang = $sitepress->get_default_language();
				}

				return $dlang;
			}
		}

	// }

?>