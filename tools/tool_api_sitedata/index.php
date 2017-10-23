<?php

	// TOOL API SITEDATA {

		function tool_api_sitedata() {

			if (
				isset( $GLOBALS['toolset']['inits']['tool_api_sitedata']['secret'] )
				AND $GLOBALS['toolset']['inits']['tool_api_sitedata']['secret'] !== ''
			) {

				add_filter( 'query_vars', function( $qv ) {

					$qv[] = 'sitedata';
					return $qv;
				});

				add_action( 'template_redirect', function() {

					$input = get_query_var( 'sitedata' );
					$secret = $GLOBALS['toolset']['inits']['tool_api_sitedata']['secret'];

					if ( ! empty( $input ) ) {

						if ( $secret === $input ) {

							if ( ! function_exists( 'get_plugins' ) ) {

								require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
							}

							$data = array(
								'wp-version'	=> $GLOBALS['wp_version'],
								'plugins' => get_plugins()
							);

							wp_send_json_success( $data );
						}
						else {

							wp_send_json_error();
						}
					}

				} );
			}

		}

		if ( isset( $GLOBALS['toolset']['inits']['tool_api_sitedata'] ) ) {

			tool_api_sitedata();
		}

	// }
