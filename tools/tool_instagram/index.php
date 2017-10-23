<?php

	function tool_instagram_embed( $p = array() ) {

		// DEFAULTS {

			$defaults = array(
				'code' => false, // instagram image code
				'layout' => array(
					'type' => 'basic',
					'classes-prefix' => '',
					'classes' => array(
						'wrap' => 'instagram-wrap',
						'img-link' => 'instagram-img-link',
						'img' => 'instagram-img',
						'caption' => 'instagram-caption',
						'title' => 'instagram-title',
						'author' => 'instagram-author',
						'provider' => 'instagram-provider',
					),
				),
				'title_tags_link' => true,
				'title_tags_remove' => false,
				'oembed_url' => 'http://api.instagram.com/oembed?url=http://instagr.am/p/{code}/',
				'media_url' => 'http://instagram.com/p/{code}/media/?size={size}',
				'short_url' => 'https://instagram.com/p/{code}/',
				'tag_url' => 'https://instagram.com/explore/tags/{code}/',
			);

			$p = array_replace_recursive( $defaults, $p );

			// RETURN {

				$r = array(
					'title' => false,
					'media_id' => false,
					'sizes' => array(
						'thumbnail' => array(
							'url' => false,
							'width' => 150, // 150
							'height' => 150, // 150
						),
						'medium' => array(
							'url' => false,
							'width' => 320, // 320
							'height' => 320, // 320
						),
						'large' => array( // named thumbnail by instagram
							'url' => false,
							'width' => 640, // 640
							'height' => 640, // 640
						),
						'full' => array(
							'url' => false,
							'width' => false, // 1080 or less
							'height' => false, // 1080 or less
						)
					),
					'author' => array(
						'name' => false,
						'url' => false,
						'id' => false,
					),
					'provider' => array(
						'name' => false,
						'url' => false,
					),
					'layout' => false,
					'widget' => array(
						'html' => false,
					),
					'version' => false,
					'type' => false,
				);

			// }

		// }

		if ( $p['code'] ) {

			// GET DATA {

				$url = strtr( $p['oembed_url'], array(
					'{code}' => $p['code'],
				) );

				$response = wp_remote_get( $url );

				$data = json_decode( wp_remote_retrieve_body( $response ), true );

			// }

			// DATA TO RETURN {

				$r['title'] = $data['title'];
				$r['media_id'] = $data['media_id'];
				$r['sizes']['large'] = array(
					'url' => $data['thumbnail_url'],
					'width' => $data['thumbnail_width'],
					'height' => $data['thumbnail_height'],
				);
				$r['author']['name'] = $data['author_name'];
				$r['author']['url'] = $data['author_url'];
				$r['author']['id'] = $data['author_id'];
				$r['provider']['name'] = $data['provider_name'];
				$r['provider']['url'] = $data['provider_url'];
				$r['widget']['html'] = $data['html'];
				$r['version'] = $data['version'];
				$r['type'] = $data['type'];

			// }

			// SIZES {

				$r['sizes']['thumbnail']['url'] = strtr( $p['media_url'], array(
					'{code}' => $p['code'],
					'{size}' => 't',
				) );

				$r['sizes']['medium']['url'] = strtr( $p['media_url'], array(
					'{code}' => $p['code'],
					'{size}' => 'm',
				) );

				$r['sizes']['full']['url'] = strtr( $p['media_url'], array(
					'{code}' => $p['code'],
					'{size}' => 'l',
				) );

			// }

			// CONVER TITEL TAGS TO LINKS {

				if ( $p['title_tags_link'] ) {

					$r['title'] = str_replace( "\n", ' ', $r['title'] );
					$arr = explode( ' ', $r['title'] );

					foreach ( $arr as $key => $string ) {

						if ( $string[0] === '#' ) {

							$url = strtr( $p['tag_url'], array(
									'{code}' => str_replace( '#', '', $string ),
							) );

							$arr[ $key ] = '<a href="' . $url . '" target="_blank">' . $string . '</a>';
						}
					}

					$r['title'] = implode( ' ', $arr );
				}

			// }

			// REMOVE TITLE TAGS {

				if ( $p['title_tags_remove'] ) {

					$r['title'] = str_replace( "\n", ' ', $r['title'] );
					$arr = explode( ' ', $r['title'] );

					foreach ( $arr as $key => $string ) {

						if ( $string[0] === '#' ) {

							unset( $arr[ $key ] );
						}
					}

					$r['title'] = implode( ' ', $arr );
				}

			// }

			// SHORT URL {

				$r['short_url'] = strtr( $p['short_url'], array(
					'{code}' => $p['code'],
				) );

			// }

			// RETURN LAYOUT {

				if ( $p['layout'] ) {

					if ( $p['layout']['type'] === 'basic' ) {

						$r['layout'] .= '<figure class="' . $p['layout']['classes-prefix'] . $p['layout']['classes']['wrap'] . '">';
							$r['layout'] .= '<a class="' . $p['layout']['classes-prefix'] . $p['layout']['classes']['img-link'] . '" href="' . $r['short_url'] . '"target="_blank"><img class="' . $p['layout']['classes-prefix'] . $p['layout']['classes']['img'] . '" src="' . $r['sizes']['large']['url'] . '"></a>';
							$r['layout'] .= '<figcaption class="' . $p['layout']['classes-prefix'] . $p['layout']['classes']['caption'] . '">';
								$r['layout'] .= '<p class="' . $p['layout']['classes-prefix'] . $p['layout']['classes']['title'] . '">' . $r['title'] . '</p>';
								$r['layout'] .= '<a class="' . $p['layout']['classes-prefix'] . $p['layout']['classes']['author'] . '" href="' . $r['author']['url'] . '" target="_blank">' . $r['author']['name'] . '</a>';
								$r['layout'] .= '<a class="' . $p['layout']['classes-prefix'] . $p['layout']['classes']['provider'] . '" href="' . $r['provider']['url'] . '" target="_blank">' . $r['provider']['name'] . '</a>';
							$r['layout'] .= '</figcaption>';
						$r['layout'] .= '</figure>';
					}
				}

			// }
		}

		return $r;
	}
