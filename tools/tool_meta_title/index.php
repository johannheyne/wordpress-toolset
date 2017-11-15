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
					'delimiter' => ' - ',
					'rules' => false,
					'page_title_on_hompage' => false,
					'prepend_posttype_name_on_archives' => false,
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

						$v['{site_title}'] = get_bloginfo( 'name' );
					}

					if ( $key === '{page_title}' && $value ) {

						if (
							$p['page_title_on_hompage'] === false &&
							in_array( get_the_ID(), $p['homepages'] )
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

					$v['{page_title}'] = 'Blog';
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

			// }

			// CUSTOM PAGE TITLE {

				if ( function_exists( 'get_field' ) ) {

					$v['title_custom_page'] = get_field( 'meta_page_title' );

					// OLD VERSION {

						if ( ! $v['title_custom_page'] ) {

							$v['title_custom_page'] = get_field( 'meta_seitentitel' );

						}

					// }

					if ( $v['title_custom_page'] ) {

						$v['{page_title}'] = $v['title_custom_page'];
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
