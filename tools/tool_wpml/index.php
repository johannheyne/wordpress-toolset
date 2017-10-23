<?php

	// WPML HACKS Version ( 2 ) {

		// GET THE CURRENT LANGUAGE {

			/* ABOUT

				returns:

					outside a template:
					- the toolset setup lang ($GLOBALS['toolset']['sites'][{blog_id}]['lang']
					- false

					inside a template:
					- the default WPML-language if post is a duplicate
					- the WPML-language (ICL_LANGUAGE_CODE)

			*/

			function tool_wpml_post_is_duplicate( $post_id ) {

				global $wpdb;
				/* if the post is a duplicate, then return the post_id of the base post */
				$sql = $wpdb->prepare( "SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = '_icl_lang_duplicate_of'", $post_id );
				$result = $wpdb->get_var( $sql );

				$return = $result === false ? false : $result;

				return $return;
			};

			function tool_wpml_get_current_lang() {

				$r = false;

				// POST {

					global $post;

					if ( isset( $post ) ) {

						// RETURN FROM POST CACHE {

							if ( isset( $GLOBALS['toolset']['cache']['tool_wpml']['lang'][ $post->ID ] ) ) {

								$r = $GLOBALS['toolset']['cache']['tool_wpml']['lang'][ $post->ID ];
							}

						// }

						// GENERATE POST CACHE {

							else {

								// FROM DUPLICATES DEFAULT POST {

									if ( tool_wpml_post_is_duplicate( $post->ID ) ) {
										global $sitepress;

										$lang = $sitepress->get_default_language();

										$GLOBALS['toolset']['cache']['tool_wpml']['post_lang'][ $post->ID ] = $lang;

										$r = $lang;
									}

								// }

								// FROM POST {

									elseif ( defined( 'ICL_LANGUAGE_CODE' ) ) {

										$GLOBALS['toolset']['cache']['tool_wpml']['post_lang'][ $post->ID ] = ICL_LANGUAGE_CODE;

										$r = ICL_LANGUAGE_CODE;
									}

								// }
							}

						// }
					}

				// }

				// NO POST {

					else {

						if ( defined( 'ICL_LANGUAGE_CODE' ) ) {

							$r = ICL_LANGUAGE_CODE;
						}
						else {

							$blog_id = get_current_blog_id();

							if ( isset( $GLOBALS['toolset']['sites'][ $blog_id ]['lang'] ) ) {

								$r = $GLOBALS['toolset']['sites'][ $blog_id ]['lang'];
							}
						}

					}

				// }

				return $r;
			};

		// }

	// }
