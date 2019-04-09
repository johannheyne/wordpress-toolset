<?php

	$GLOBALS['toolset']['classes']['ToolHreflang'] = new ToolHreflang();

	function tool_hreflang( $p = array() ) {

		// DEFAULTS {

			$defaults = array(
				//'term' => '',
			);

			$p = array_replace_recursive( $defaults, $p );

			$return = '';

		// }

		if (
			! isset( $GLOBALS['toolset']['multisite'] ) OR
			empty( $GLOBALS['toolset']['sites'] ) OR
			empty( $GLOBALS['toolset']['current_url'] ) OR
			empty( $GLOBALS['toolset']['frontend_locale'] )
		) {

			return '';
		}

		// SIMPLE WORDPRESS SITE {

			if ( ! $GLOBALS['toolset']['multisite'] ) {

				$site_url = get_home_url();
				$current_relative_url = str_replace( $site_url, '', $GLOBALS['toolset']['current_url'] );

				$array = explode( '/', $current_relative_url );
				array_shift( $array);

				if ( $array[0] == $GLOBALS['toolset']['frontend_locale'] ) {

					array_shift( $array );
					$current_relative_url = '/' . implode( '/', $array );
				}

				foreach ( $GLOBALS['toolset']['sites']['1']['languages'] as $key => $item ) {

					if ( $key === 0 ) {

						$return .= '<link rel="alternate" href="' . $site_url . $current_relative_url . '" hreflang="' . convert_hreflang( $item['language'] ) .'" />';
					}
					else {

						$return .= '<link rel="alternate" href="' . $site_url . '/' . $item['language'] . $current_relative_url . '" hreflang="' . convert_hreflang( $item['language'] ) .'" />';
					}
				}
			}

		// }

		// MULTISITE {

			else {

				$site_id = $GLOBALS['toolset']['blog_id'];
				$site_url = get_home_url( $site_id );

				if (
					$GLOBALS['toolset']['blog_id'] == 1 AND
					is_front_page()
				 ) {

					$return .= '<link rel="alternate" href="' . $site_url . '" hreflang="x-default" />';
				}
				else {

					$current_relative_url = str_replace( $site_url, '', $GLOBALS['toolset']['current_url'] );

					$array = explode( '/', $current_relative_url );
					array_shift( $array);

					if ( $array[0] == $GLOBALS['toolset']['frontend_locale'] ) {

						array_shift( $array );
						$current_relative_url = '/' . implode( '/', $array );
					}

					// GET HREFLANG POST META {

						foreach ( $GLOBALS['toolset']['sites'] as $site_id => $site ) {

							$hreflang_post_id = $GLOBALS['toolset']['classes']['ToolHreflang']->get_post_meta_hreflang( get_the_ID(), $site['id'] );

							if ( ! empty( $hreflang_post_id ) ) {

								foreach ( $GLOBALS['toolset']['sites'][ $site_id ]['languages'] as $language ) {

									$permalink = '';

									switch_to_blog( $site_id );

										$permalink = get_lang_permalink( $hreflang_post_id, $language['language'] );

									restore_current_blog();

									if (
										! empty( $GLOBALS['toolset']['sites'][ $site_id ]['country_code'] ) AND
										strpos( $language['language'], '_' )
									) {

										$language['language'] = str_replace( '_' . $GLOBALS['toolset']['sites'][ $site_id ]['country_code'], '', $language['language'] );
										$language['language'] .= '_' . $GLOBALS['toolset']['sites'][ $site_id ]['country_code'];
									}

									$return .= '<link rel="alternate" href="' . $permalink . '" hreflang="' . $language['language'] . '" />';
								}
							}

							//$return .= '<link rel="alternate" href="' . $permalink . '" hreflang="x-default" />';
						}

					// }

					/*foreach ( $GLOBALS['toolset']['sites']['1']['languages'] as $key => $item ) {

						if ( $key === 0 ) {

							$return .= '<link rel="alternate" href="' . $site_url . $current_relative_url . '" hreflang="' . convert_hreflang( $item['language'] ) .'" />';
						}
						else {

							$return .= '<link rel="alternate" href="' . $site_url . '/' . $item['language'] . $current_relative_url . '" hreflang="' . convert_hreflang( $item['language'] ) .'" />';
						}
					}*/
				}
			}

		// }

		echo $return;
	}
