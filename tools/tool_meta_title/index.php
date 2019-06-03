<?php

	// TOOL META TITLE ( Version 2 ) {

		// BACKEND SET STATES {

			if (
				is_admin() AND
				! empty( $_REQUEST['post'] )
			) {

				if (
					$GLOBALS['toolset']['inits']['tool_meta_title']['page_title_on_hompage'] === false &&
					in_array( $_REQUEST['post'], $GLOBALS['toolset']['inits']['tool_meta_title']['homepages'] )
				) {

					$GLOBALS['toolset']['inits']['tool_meta_title']['state']['no_page_title'] = true;
				}
			}

		// }

		function tool_meta_title( $p = array() ) {

			// DEFAULTS {

				$defaults = array(
					'post_id' => get_the_ID(),
					'delimiter' => ' - ',
					'rules' => false,
					'page_title_on_hompage' => false, // boolean
					'site_title' => false,
					'prepend_posttype_name_on_archives' => false,
					'titles' => array(
						'is_home' => 'Blog',
						'is_404' => '404',
					),
				);

				if ( empty( $GLOBALS['toolset']['inits']['tool_meta_title'] ) ) {

					$GLOBALS['toolset']['inits']['tool_meta_title'] = array();
				}

				if ( ! is_array( $GLOBALS['toolset']['inits']['tool_meta_title'] ) ) {

					$GLOBALS['toolset']['inits']['tool_meta_title'] = array();
				}

				if ( empty( $GLOBALS['toolset']['inits']['tool_meta_title']['page_title_on_hompage'] ) ) {

					$GLOBALS['toolset']['inits']['tool_meta_title']['page_title_on_hompage'] = false;
				}

				if ( empty( $GLOBALS['toolset']['inits']['tool_meta_title']['homepages'] ) ) {

					$GLOBALS['toolset']['inits']['tool_meta_title']['homepages'] = array(
						get_option( 'page_on_front' ),
					);
				}

				$p = array_replace_recursive( $defaults, $GLOBALS['toolset']['inits']['tool_meta_title'], $p );

				if ( ! $p['rules'] ) {

					$p['rules'] = array(
						'{page_title}' => true,
						'{site_title}' => true,
					);
				}

				if ( is_404() ) {

					$p['post_id'] = get_field( 'opt_404_page', 'option' );
				}

			// }

			$v = array(
				'{site_title}' => false,
				'{page_title}' => false,
				'title' => array(),
				'post_type' => '',
				'post_type_name' => '',
			);

			// GET TITLE PARTS {

				foreach ( $p['rules'] as $key => $value ) {

					if ( $key === '{site_title}' && $value ) {

						if ( empty( $p['site_title'] ) ) {

							$v['{site_title}'] = get_bloginfo( 'name' );
						}
						else {

							$v['{site_title}'] = $p['site_title'];
						}
					}

					if ( $key === '{page_title}' && $value ) {

						if (
							$p['page_title_on_hompage'] === false &&
							in_array( $p['post_id'], $p['homepages'] )
						) {

							// no page title on homepage
						}
						else {

							$v['{page_title}'] = get_the_title();
						}
					}
				}

			// }

			// ARCHIVE {

				if ( is_home() ) {

					$v['{page_title}'] = $p['titles']['is_home'];
				}

				if ( is_archive() ) {

					if ( $p['prepend_posttype_name_on_archives'] ) {

						$v['post_type'] = get_post_type();
						$v['post_type_obj'] = get_post_type_object( $v['post_type'] );
						$v['post_type_name'] = $v['post_type_obj']->labels->name . ' ';
					}

					$v['{page_title}'] = post_type_archive_title( '', false );
				}

				if ( is_category() ) {

					$v['{page_title}'] = $v['post_type_name'] . single_cat_title( ' ', false );
				}

				if ( is_date() ) {

					$v['{page_title}'] = $v['post_type_name'] . single_month_title( ' ', false );
				}

				if ( is_tag() ) {

					$v['{page_title}'] = $v['post_type_name'] . single_tag_title( ' ', false );
				}

				if ( is_404() ) {

					$v['{page_title}'] = $p['titles']['is_404'];
				}

			// }

			// CUSTOM PAGE TITLE {

				if ( function_exists( 'get_field' ) ) {

					if ( empty( $GLOBALS['toolset']['multilanguage'] ) ) {

						$v['title_custom_page'] = get_field( 'meta_page_title', $p['post_id'] );

						// OLD VERSION {

							if ( ! $v['title_custom_page'] ) {

								$v['title_custom_page'] = get_field( 'meta_seitentitel', $p['post_id'] );

							}

						// }

						if ( $v['title_custom_page'] ) {

							$v['{page_title}'] = $v['title_custom_page'];
						}
					}
					else {

						$v['title_custom_page'] = get_lang_field( 'page_meta_searchengines/title', $p['post_id'] );

						if ( ! empty( $v['title_custom_page'] ) ) {

							$v['{page_title}'] = $v['title_custom_page'];
						}
					}
				}

			// }

			// BUILD SITE TITLE {

				foreach ( $p['rules'] as $key => $value ) {

					if ( $v[ $key ] ) {

						$v['title'][] = $v[ $key ];
					}
				}

				$v['return'] = implode( $p['delimiter'], $v['title'] );

			// }

			echo '<title>' . $v['return'] . '</title>' . "\n";
		}

	// }
