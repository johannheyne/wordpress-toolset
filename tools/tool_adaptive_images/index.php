<?php

	// DISABLE WP SRCSET ( Version 1 ) {

		add_filter( 'wp_calculate_image_srcset', function() {

			$sources = array();

			return $sources;
		}, 1000, 2 );

	// }

	// IMAGE SIZE ( Version 7 ) {

		add_filter('intermediate_image_sizes_advanced', function( $sizes ) {

			// unset( $sizes['thumbnail']);
			// unset( $sizes['medium']);
			unset( $sizes['medium_large']);
			unset( $sizes['1536x1536']); // since WordPress 5.3
			unset( $sizes['2048x2048']); // since WordPress 5.3
			// unset( $sizes['large']); // required for WordPress image detail view

			return $sizes;
		});

		/* sizes for icons and previews in wordpress */
		add_image_size( 'thumbnail', '160', '160', /* crop */ false ); // required for postthumb preview in post/pages
		add_image_size( 'medium', '118', '118', /* crop */ false ); // used for media listing

	// }

	// ADAPTIVE IMAGES ( Version 24 (AIFWP 1.1) ) {

		add_image_size( 'adaptive-image-base', '2560', '2560', /* crop */ false );

		// GET ADAPTIVE IMAGE {

			function get_adaptive_image( $p = array() ) {

				// DEFAULTS {

					$defaults = array(
						'name' => 'full',
						'ratio' => false,
						'id' => false,
						'id_default' => false,
						'file' => false,
						'alt' => false,
						'img_class' => '',
						'img_class_resp' => 'resp',
						'img_data' => false,
						'img_title' => false,
						'link_image' => false, /* true or size */
						'link_page' => false, /* true or id of page */
						'link_url' => false,
						'link_class' => false,
						'link_target' => false,
						'root_class' => false,
						'link_rel' => false,
						'link_title' => false,
						'link_data' => false,
						'wrap' => false,
						'wrap_class' => false,
						'style' => false,
						'figcaption_cont' => false,
						'figure_class' => 'figure',
						'figcaption_class' => 'figcaption'
					);

					$p = array_replace_recursive( $defaults, $p );

				// }

				// Version 07.09.2017

				// DEFINE VARS {

					$sufix = '';
					$img_attr = array();
					$img_src = false;
					$link_url = false;
					$link_rel = false;
					$link_title = false;
					$link_data = false;
					$link_class = '';
					$link_target = '';
					$title = false;
					$link_title = false;
					$return = '';

				// }

				// SET VAR IMAGE SUFIX {

					$sufix = '?size=' . $p['name'];

					// ADDS RATIO {

						if ( $p['ratio'] ) {

							$sufix = $sufix . '&ratio=' . str_replace( ':', '-', $p['ratio'] );
						}

					// }

				// }

				// SET IMAGE SRC {

					if ( $p['id'] ) {

						$img_param = wp_get_attachment_image_src( $p['id'], $p['name'] );
						$img_src = $img_param[0];
					}

					// IF IMG FILE WAS DELETED TRY DEFAULT IMAGE {

						if (
							empty( $img_src ) AND
							! empty( $p['id_default'] )
						) {

							$p['id'] = $p['id_default'];

							$img_param = wp_get_attachment_image_src( $p['id'], $p['name'] );
							$img_src = $img_param[0];
						}

					// }

					if ( $p['file'] ) {

						$img_src = get_bloginfo('template_url') . '/' . $p['file'];
					}

				// }

				// SET ROOT CLASS {

					if ( $p['root_class'] ) {

						if ( $p['link_image'] OR $p['link_page'] OR $p['link_url'] ) {

							 $p['link_class'] = trim( $p['link_class'] . ' ' . $p['root_class'] );
						}

						elseif ( $p['wrap'] ) {

							 $p['wrap_class'] = trim( $p['wrap_class'] . ' ' . $p['root_class'] );
						}

						else {

							 $p['img_class'] = trim( $p['img_class'] . ' ' . $p['root_class'] );
						}
					}

				// }

				// SET IMAGE ATTRIBUTE "DATA" {

					if ( $p['img_data'] ) {

						foreach ( $p['img_data'] as $key => $value ) {

							$img_attr['data-' . $key] = $value;
						}
					}

				// }

				// SET LINK ATTRIBUTE "DATA" {

					if ( $p['link_data'] ) {

						foreach ( $p['link_data'] as $key => $value ) {

							$link_data .= ' data-' .$key . '="' . $value . '"';
						}
					}

				// }

				// SET LINK URL {

					if ( $p['link_image'] AND $p['link_image'] != 'true' AND $p['link_image'] != 'false' ) {

						$link_url = get_adaptive_image_src( array( 'name' => $p['link_image'], 'id' => $p['id'] ) );
					}

					// BY SRC {

						if ( $p['link_image'] == 'true') {

							$link_url = $img_src;
						}

					// }

					// BY LINK PAGE {

						if ( $p['link_page'] ) {

							$link_url = get_permalink();
						}

						if ( $p['link_page'] AND is_int( $p['link_page'] ) ) {

							$link_url = get_permalink( $p['link_page'] );
						}

					// }

					// BY LINK URL {

						if ( $p['link_url'] ) {

							$link_url = $p['link_url'];
						}

					// }

				// }

				// SET LINK CLASS {

					if ( $p['link_class'] ) {

						$link_class = ' class="' . $p['link_class'] . '"';
					}

				// }

				// SET LINK TARGET {

					if ( $p['link_target'] ) {

						$link_target = ' target="' . $p['link_target'] . '"';
					}

				// }

				// SET LINK REL {

					if ( $p['link_rel'] ) {

						$link_rel = ' rel="' . $p['link_rel'] . '"';
					}

				// }

				// SET LINK TITLE {

					if ( $p['link_title'] ) {

						$title = $p['link_title'];

						if ( $p['link_title'] === 'titel' ) {

							$data = get_post( $p['id'] );
							$title = $data->post_titel;
						}

						if ( $p['link_title'] === 'beschriftung' ) {

							$data = get_post( $p['id'] );
							$title = $data->post_excerpt;
						}

						if ( $p['link_title'] === 'alt' ) {

							if ( ! empty( $GLOBALS['toolset']['frontend_locale'] ) ) {

								$title = get_post_meta( $p['id'], 'lang_attachement_image_' . $GLOBALS['toolset']['frontend_locale'] . '_alt', true );
							}
							else {

								$title = get_post_meta( $p['id'], '_wp_attachment_image_alt', true );
							}
						}

						$link_title = ' title="' . $title . '"';
					}

				// }

				// PREPARE RETURN {

					if ( $img_src ) {

						// WRAP OPEN {

							if ( $p['wrap'] ) {

								if ( $p['wrap_class'] ) {

									$wrap_class = ' class="' .$p['wrap_class'] . '"';
								}

								$return .= '<' . $p['wrap'] . $wrap_class . '>';
							}

						// }

						// IMAGE ALT {

							if ( empty( $p['alt'] ) ) {

								if ( ! empty( $GLOBALS['toolset']['frontend_locale'] ) ) {

									$p['alt'] = get_post_meta( $p['id'], 'lang_attachement_image_' . $GLOBALS['toolset']['frontend_locale'] . '_alt', true );
								}
								else {

									$p['alt'] = get_post_meta( $p['id'], '_wp_attachment_image_alt', true );
								}
							}

						// }

						// IMAGE SRC {

							$img_attr['src'] = $img_src . $sufix;

							if ( strpos( $img_src, '.svg' ) !== false ) {

								$img_attr['src'] = $img_src;
							}

						// }

						// IMAGE / CAPTION CLASSES {

							$img_attr['class'] = trim( $p['img_class_resp'] );

							if ( ! $p['figcaption_cont'] ) {

								if ( $p['img_class'] ) {

									$img_attr['class'] .= ' ' . trim( $p['img_class'] );
								}

								if ( $p['name'] ) {

									$img_attr['class'] .= ' size-' . trim( $p['name'] );
								}
							}
							else {

								$caption_attr['class'] = trim( $p['img_class'] );

								if ( $p['name'] ) {

									$caption_attr['class'] .= ' size-' . trim( $p['name'] );
								}

								if ( $p['figure_class'] ) {

									$caption_attr['class'] .= ' ' . trim( $p['figure_class'] );
								}
							}

						// }

						// FIGCAPTION BEGIN {

							if ( $p['figcaption_cont'] ) {

								$return .= '<figure class="' . $caption_attr['class'] . '">';
							}

						// }

						// LINK OPEN {

							if ( $link_url OR $link_class OR $link_rel OR $link_title OR $link_target ) {

								if ( $link_url ) {

									$link_url =  ' href="' . $link_url . '"';
								}

								$return .= '<a' . $link_url . $link_class . $link_rel . $link_title . $link_data . $link_target . '>';
							}

						// }

						// FILTER{

							$return = apply_filters( 'get_adaptive_image/before_img_tag', $return, $p );

						// }

						// IMAGE BY ID {

							if ( $p['id'] ) {

								if ( $p['alt'] ) {

									$img_attr['alt'] = $p['alt'];
								}
								if ( $p['style'] ) {

									$img_attr['style'] = $p['style'];
								}

								$return .= wp_get_attachment_image( $p['id'], 'adaptive-image-base', false, $img_attr );
							}

						// }

						// IMAGE BY FILE {

							if ( $p['file'] ) {

								$img_alt = '';

								if ( $p['alt'] ) {

									$img_alt .= ' alt="' . $p['alt'] . '"';
								}

								if ( $p['style'] ) {

									$img_style .= ' style="' . $p['style'] . '"';
								}
								$return .= '<img src="' . $img_attr['src'] . '" class="' . $img_attr['class'] . '"' . $img_alt . $img_style . '/>';
							}

						// }

						// FILTER {

							$return = apply_filters( 'get_adaptive_image/after_img_tag', $return, $p );

						// }

						// LINK CLOSE {

							if (  $link_url OR $link_class OR $link_rel OR $link_title OR $link_target ) {

								$return .= '</a>';
							}

						// }

						// FIGCAPTION END {

							if ( $p['figcaption_cont'] ) {

								$return .= '<figcaption class="' . $p['figcaption_class'] . '">';
									$return .= $p['figcaption_cont'];
								$return .= '</figcaption>';

								$return .= '</figure>';
							}

						// }

						// WRAP CLOSE {

							if ( $p['wrap'] ) {

								$return .= '</' . $p['wrap'] . '>';
							}

						// }

						// FILTERS {

							// remove image dimensions attributes
							$return = remove_image_dimensions_attributes( $return );

						// }

					}

				// }

				// RETURN {

					return  $return;

				// }

			}

		// }

		// GET SRC {

			function get_adaptive_image_src( $p = array(
				'name' => 'full',
				'id' => false,
			) ) {

				$sufix = '?size=' . $p['name'];

				/* image src */
				$img_param = wp_get_attachment_image_src( $p['id'], 'adaptive-image-base' );
				$img_src = $img_param[0];

				return  $img_src.$sufix;
			}

		// }

		// GET DATA {

			function get_adaptive_image_data( $p = array(
				'name' => 'full',
				'id' => false,
			) ) {

				$sufix = '?size=' . $p['name'];

				/* image src */
				$img_param = wp_get_attachment_image_src( $p['id'], 'adaptive-image-base' );

				$data = array();
				$data['src'] = $img_param[0].$sufix;
				$data['w'] = $img_param[1];
				$data['h'] = $img_param[2];
				$data['rel_h'] = ( 100 / $data['w'] ) * $data['h'];
				$data['ratio'] = ( 1 / $data['w'] ) * $data['h'];

				$aspect_ratio = tool_get_aspect_ratio( $data['w'], $data['h'] );
				$data['aspect_ratio'] = $aspect_ratio['array'];
				$data['orig'] = false;

				if ( ! $img_param[3] ) {

					$data['orig'] = true;
				}

				return  $data;
			}

		// }

		// GET ASPECT RATIO {

			function tool_get_aspect_ratio( $a, $b ) {

				$gcd = function( $a, $b ) use ( &$gcd ) {

					return ( $a % $b ) ? $gcd( $b, $a % $b ) : $b;
				};

				$g = $gcd( $a, $b );

				$return = array();
				$return['string'] = $a/$g . ':' . $b/$g;
				$return['array'] = array( $a/$g, $b/$g );

				return $return;
			}

		// }

		// MULTISITE {

			function multisite_urls_2_real_urls( $buffer ) {

				global $current_blog;

				if ( config_get_curr_blog_id() > 1 ) {

					$buffer = str_replace( $current_blog->path  . 'files', '/backend/wp-content/blogs.dir/' . config_get_curr_blog_id() . '/files', $buffer );
				}

				return $buffer;
			}

			function buffer_start() { ob_start("multisite_urls_2_real_urls"); }
			function buffer_end() { ob_end_flush(); }

			if ( config_get_curr_blog_id() > 1 ) {

				add_action('wp_head', 'buffer_start');
				add_action('wp_footer', 'buffer_end');
				add_action('admin_head', 'buffer_start');
				add_action('admin_footer', 'buffer_end');
			}

		// }

		// STYLES {

			add_action( 'wp_head', function() {

				include( get_template_directory() . '/config/adaptive-images-config.php' );

				$styles = array();

				foreach ( $setup as $size => $item1 ) {

					if (  isset( $item1['use-max-width'] ) ) {

						foreach ( $item1['resolutions'] as $resolution => $item2 ) {

							if ( isset( $config['resolutions'][ $resolution ] ) ) {

								$resolution = $config['resolutions'][ $resolution ];
							}

							if ( isset( $item2['w'] ) ) {

								if ( !isset( $styles[ $resolution ] ) ) {

									$styles[ $resolution ] = '';
								}

								$styles[ $resolution ] .= 'img.size-' . $size . '{max-width: ' . $item2['w'] . 'px;}';
							}
						}
					}
				}

				if ( count( $styles ) > 0 ) {

					echo '<style rel="stylesheet" type="text/css">';

						foreach ( $styles as $key => $item ) {

						   echo '@media screen and (min-width: ' . $key . 'px) {' . $item . '}';
						}

					echo '</style>';
				}

			} );

		// }

	// }

	// IMAGE SIZES FOR EDITOR ( Version 5 ) {

		function set_ai_editor_imagesizes() {

			foreach ( $GLOBALS['toolset']['inits']['tool_adaptive_images']['editor_imagesizes'] as $size => $item ) {

				add_image_size( $size, $item['width'], $item['height'], $item['crop'] );

				add_filter ( 'wp_prepare_attachment_for_js',  function( $response, $attachment, $meta ) use ( $size ) {

					if ( isset( $meta['sizes'][ $size ] ) ) {

						$attachment_url = wp_get_attachment_url( $attachment->ID );
						$base_url = str_replace( wp_basename( $attachment_url ), '', $attachment_url );
						$size_meta = $meta['sizes'][ $size ];

						$response['sizes'][ $size ] = array(
							'height'        => $size_meta['height'],
							'width'         => $size_meta['width'],
							'url'           => $base_url . $size_meta['file'],
							'orientation'   => $size_meta['height'] > $size_meta['width'] ? 'portrait' : 'landscape',
						);
					}

					return $response;
				} , 10, 3  );
			}
		}

		// define image-sizes at the media-popup
		function add_ai_image_size_names( $sizes ) {

			// adding relevant media-sizes to the select-input for inserting into the editor

			//if ( isset( $_REQUEST['post'] ) ) {

				$posttype = get_post_type( @$_REQUEST['post'] );

				foreach ( $GLOBALS['toolset']['inits']['tool_adaptive_images']['editor_imagesizes'] as $size => $item ) {

					$check = true;

					if ( is_admin() && $item['posttypes'] && !in_array( $posttype, $item['posttypes'] ) ) {
						$check = false;
					}

					if ( $check ) $sizes[$size] = $item['label'];
				}
			//}

			return $sizes;
		}

		// add Adaptive-Image parameter size to image-url and zoom to href-url
		function ai_image_send_to_editor( $html, $id, $caption = false, $title = false, $align = false, $url = false, $size = false, $alt = false ) {

			$src = wp_get_attachment_image_src( $id, 'adaptive-image-base', false );

			foreach ( $GLOBALS['toolset']['inits']['tool_adaptive_images']['editor_imagesizes'] as $size => $item ) {

				if ( strpos( $html, 'size-' . $size ) !== false ) {

					$html = preg_replace( '/(.*)(src="(.*)\.(jpg|jpeg|gif|png)")(.*)/', '$1src="' . $src[0] . '?size=' . $size . '"$5', $html );
				}
			}

			$html = preg_replace( '/(.*)(href="(.*)\.(jpg|jpeg|gif|png)")(.*)/', '$1href="' . $src[0] . '?size=zoom"$5', $html );

			return $html;
		}

		if ( isset( $GLOBALS['toolset']['inits']['tool_adaptive_images']['editor_imagesizes'] ) && is_array( $GLOBALS['toolset']['inits']['tool_adaptive_images']['editor_imagesizes'] ) ) {

			set_ai_editor_imagesizes();
			add_filter( 'image_size_names_choose','add_ai_image_size_names', 10, 1 );
			add_filter( 'image_send_to_editor', 'ai_image_send_to_editor', 10, 8 );
		}

	// }

	// ADAPTIVE IMAGES RESPONSIVE BACKGROUND IMAGE {

		function tool_get_adaptive_image_resp( $p = array() ) {

			// DEFAULTS {

				$defaults = array(
					'images' => false, // array(''=>''): id, name, mediaquery
					'class' => false,
					'template' => '<a href="{href}" class="{class_wrap}">{text}</a>',
				);

				$p = array_replace_recursive( $defaults, $p );

				$r = '';

			// }

			// VARS {

				$var['styles'] = '';
				$var['html'] = '';

			// }

			// STYLES {

				$var['styles'] .= '<style>/* responsive image styles for .' . $p['class'] . ' */';

					// GENERAL STYLES {

						$var['styles'] .= '.' . $p['class'] . '{';
							$var['styles'] .= 'width: 100%;';
							$var['styles'] .= 'background-size:cover;';
						$var['styles'] .= '}';

						$var['styles'] .= '.' . $p['class'] . ':after{';
							$var['styles'] .= 'content:"";';
							$var['styles'] .= 'display:inline-block;';
						$var['styles'] .= '}';

					// }

					if ( $p['images'] AND is_array( $p['images'] ) ) {

						foreach ( $p['images'] as $key => $item ) {

							$src = get_adaptive_image_src( array(
								'id' => $item['img_id'],
								'name' => $item['ai_name'],
							) );

							$size = getimagesize( $src );

							// MEDIAQUERY STYLES {

								if ( $item['mediaquery'] ) {

									$var['styles'] .= '@media' . $item['mediaquery'] . '{';
								}

									$var['styles'] .= '.' . $p['class'] . ':after{';
										$var['styles'] .= 'padding-top:' . ( 100 / $size[0] ) * $size[1] . '%;';
									$var['styles'] .= '}';
									$var['styles'] .= '.' . $p['class'] . '{';
										$var['styles'] .= 'background-image:url(' . $src . ');';
									$var['styles'] .= '}';

								if ( $item['mediaquery'] ) {

									$var['styles'] .= '}';
								}

							// }

						}
					}

				$var['styles'] .= '</style>';

			// }

			// HTML {

				$var['html'] = $p['template'];

				$replace = array (
					'{class}' => $p['class'],
				);

				$var['html'] = strtr( $var['html'], $replace );

			// }

			// RETURN {

				$r = $var['styles'] . $var['html'];

			// }

			return $r;
		};

	// }
