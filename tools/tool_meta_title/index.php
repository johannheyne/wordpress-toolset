<?php

	// TOOL META TITLE ( Version 2 ) {

		function tool_meta_title( $p = array() ) {

			// DEFAULTS {

				$defaults = array(
					'delimiter' => ' - ',
					'rules' => false,
					'page_title_on_hompage' => false,
					'prepend_posttype_name_on_archives' => false,
				);

				$p = array_replace_recursive( $defaults, $p );

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
				'title' => '',
				'post_type' => '',
				'post_type_name' => '',
			);

			// GET TITLE PARTS {

				foreach ( $p['rules'] as $key => $value ) {

					if ( $key === '{site_title}' && $value ) {

						$v['{site_title}'] = get_bloginfo( 'name' );
					}

					if ( $key === '{page_title}' && $value ) {

						if ( ! $p['page_title_on_hompage'] && get_the_ID() == get_option( 'page_on_front' ) ) {

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

					$v['title_custom_page'] = get_field( 'meta_seitentitel' );

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

?>