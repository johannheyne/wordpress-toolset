<?php

class BFIGitHubPluginUpdater {

	private $slug; // plugin slug
	private $pluginData; // plugin data
	private $username; // GitHub username
	private $repo; // GitHub repo name
	private $pluginFile; // __FILE__ of our plugin
	private $githubAPIResult; // holds data from GitHub
	private $githubAPIResults; // holds data from GitHub
	private $accessToken; // GitHub private repo token
	public $headers; // GitHub private repo token

	function __construct( $pluginFile, $gitHubUsername, $gitHubProjectName, $pluginData, $accessToken = '' ) {

		add_filter( "pre_set_site_transient_update_plugins", array( $this, "setTransitent" ) );
		add_filter( "plugins_api", array( $this, "setPluginInfo" ), 10, 3 );
		add_filter( "upgrader_post_install", array( $this, "postInstall" ), 10, 3 );

		$this->pluginFile = $pluginFile;
		$this->username = $gitHubUsername;
		$this->repo = $gitHubProjectName;
		$this->accessToken = $accessToken;
		$this->headers = '';
		$this->tested = $pluginData['tested'];
		$this->requires = $pluginData['requires'];
	}

	// Get information regarding our plugin from WordPress
	private function initPluginData() {

		$this->slug = plugin_basename( $this->pluginFile );
		$this->pluginData = get_plugin_data( $this->pluginFile );
	}

	public function admin_notice() {

		echo '<div class="notice notice-error is-dismissible">';
			echo '<p><strong>Die Verfügbarkeit von Updates für das Plugin "WordPressToolset" kann im Moment nicht geprüft werden.</strong><br>';
			echo 'Das Limit von ' . $this->headers['x-ratelimit-limit'] . ' API-Verbindungen zum <a href="https://github.com/johannheyne/wordpress-toolset" target="_blank">GitHub Repository des Plugins</a> war erreicht. Das Limit wird am ' . date( 'd.m.Y', $this->headers['x-ratelimit-reset'] ) . ' um ' . date( 'H:i', $this->headers['x-ratelimit-reset'] ) . ' Uhr wieder zurückgesetzt. Bitte prüfe dann noch einmal auf Updates.</p>';
		echo '</div>';
	}

	// Get information regarding our plugin from GitHub
	private function getRepoReleaseInfo() {

		// Only do this once
		if (
			empty( $_GET['force-check'] ) AND
			!empty( $this->githubAPIResult )
		) {

			return;
		}

		if ( !empty( $_GET['force-check'] ) ) {

			$old_release_data = get_transient( 'plugin_wordpress_toolset_latest_release_data' );
			delete_transient( 'plugin_wordpress_toolset_latest_release_data' );
		}

		if ( false === ( $this->githubAPIResults = get_transient( 'plugin_wordpress_toolset_latest_release_data' ) ) ) {

			// Query the GitHub API
			$url = "https://api.github.com/repos/{$this->username}/{$this->repo}/releases";

			// We need the access token for private repos
			/*if ( !empty( $this->accessToken ) ) {

				$url = add_query_arg( array( "access_token" => $this->accessToken ), $url );
			}*/

			$args = array(
				'headers' => array(
					'Accept' => 'application/vnd.github.v3+json',
					//'Authorization' => 'token ' . $this->accessToken,
				)
			);

			// Get the results
			$response = wp_remote_get( $url, $args );

			// CHECK GITHUB API CALL LIMIT {

				$this->headers = wp_remote_retrieve_headers( $response );
				$this->headers = (array) $this->headers;
				$this->headers = $this->headers[ chr(0) . '*' . chr(0) . 'data']; //trick to get protected array key

				// LIMIT REACHED {

					if ( $this->headers['x-ratelimit-remaining'] == '0' ) {

						add_action( 'admin_notices', array( $this, 'admin_notice' ) );

						if ( !empty( $old_release_data ) ) {

							// recover transient
							set_transient( 'plugin_wordpress_toolset_latest_release_data', $old_release_data, HOUR_IN_SECONDS );

							// recover release data
							$this->githubAPIResults = $old_release_data;
						}
					}

				// }

				// LIMIT NOT REACHED {

					else {

						$response_body = wp_remote_retrieve_body( $response );
						$this->githubAPIResults = @json_decode( $response_body );
					}

				// }

			// }

			// Set the transient and var by remote release data
			if (
				!empty( $this->githubAPIResults ) AND
				is_array( $this->githubAPIResults )
			) {

				set_transient( 'plugin_wordpress_toolset_latest_release_data', $this->githubAPIResults, DAY_IN_SECONDS );
			}

		}

		// Set the transient and var by remote release data
		if (
			!empty( $this->githubAPIResults ) AND
			is_array( $this->githubAPIResults )
		) {

			$this->githubAPIResult = $this->githubAPIResults[0]; // Use only the latest release
		}

	}

	// Push in plugin version information to get the update notification
	public function setTransitent( $transient ) {

		// If we have checked the plugin data before, don't re-check
		if ( empty( $transient->checked ) ) {

			return $transient;
		}

		// Get plugin & GitHub release information
		$this->initPluginData();
		$this->getRepoReleaseInfo();

		// Check the versions if we need to do an update
		$doUpdate = version_compare( $this->githubAPIResult->tag_name, $transient->checked[$this->slug] );

		// Update the transient to include our updated plugin data
		if ( $doUpdate == 1 ) {

			$package = $this->githubAPIResult->zipball_url;

			// Include the access token for private GitHub repos
			if ( !empty( $this->accessToken ) ) {

				$package = add_query_arg( array( "access_token" => $this->accessToken ), $package );
			}

			$obj = new stdClass();
			$obj->slug = $this->slug;
			$obj->new_version = $this->githubAPIResult->tag_name;
			$obj->url = $this->pluginData["PluginURI"];
			$obj->package = $package;
			$transient->response[$this->slug] = $obj;
		}

		return $transient;
	}

	public function infoWindowStyles() {

		echo '<style type="text/css">
			#plugin-information pre {
				display: block !important;
				background: #f7f7f7 !important;
				overflow: auto !important;
			}
			#plugin-information pre code {
				display: block !important;
				background: none !important;
				//min-width: 1000px !important;
			}
		</style>';
	}

	// Push in plugin version information to display in the details lightbox
	public function setPluginInfo( $false, $action, $response ) {


		// Get plugin & GitHub release information
		$this->initPluginData();
		$this->getRepoReleaseInfo();

		// If nothing is found, do nothing
		if ( empty( $response->slug ) || $response->slug != $this->slug ) {

			return false;
		}

		$this->infoWindowStyles();

		// Add our plugin information
		$response->last_updated = $this->githubAPIResult->published_at;
		$response->slug = $this->slug;
		$response->name	= $this->pluginData["Name"];
		$response->plugin_name	= $this->pluginData["Name"];
		$response->version = $this->githubAPIResult->tag_name;
		$response->author = $this->pluginData["AuthorName"];
		$response->homepage = $this->pluginData["PluginURI"];
		$response->tested = $this->tested;
		$response->requires = $this->requires;

		// This is our release download zip file
		$downloadLink = $this->githubAPIResult->zipball_url;

		// Include the access token for private GitHub repos
		if ( !empty( $this->accessToken ) ) {

			$downloadLink = add_query_arg(
				array( "access_token" => $this->accessToken ),
				$downloadLink
			);
		}
		$response->download_link = $downloadLink;

		// We're going to parse the GitHub markdown release notes, include the parser
		require_once( plugin_dir_path( __FILE__ ) . "Parsedown.php" );

		// DESCRIPTION CONTENT {

			$description_content[ 0 ] = '#Changes Since Last Plugin Update';
			$description_content[ 1 ] = 'This is the only chance to see what happend since the last plugin update. So please read the changes carefully and adapt your theme-code, before you update the plugin.';

			if ( is_array( $this->githubAPIResults ) and count( $this->githubAPIResults ) > 0 ) {

				foreach ( $this->githubAPIResults as $key => $item ) {

					if ( version_compare( $this->pluginData['Version'], $item->tag_name, '<' ) ) {

						$description_content[ $item->tag_name ] = '......................................................................................................................' . "\n\n";
						$description_content[ $item->tag_name ] .= '[Release v' . $item->tag_name . '](https://github.com/johannheyne/wordpress-toolset/releases/tag/' . $item->tag_name . ')' . "\n\n";
						//$description_content[ $item->tag_name ] .= date( "Y.m.d H.i.s", strtotime( $item->published_at ) ) . "\n\n";
						$description_content[ $item->tag_name ] .= str_replace( '####', '##', $item->body );

						$description_content[ $item->tag_name ] = preg_replace( "/requires WordPress:\s([\d\.]+)/i", '', $description_content[ $item->tag_name ] );
						$description_content[ $item->tag_name ] = preg_replace( "/tested WordPress:\s([\d\.]+)/i", '', $description_content[ $item->tag_name ] );
						$description_content[ $item->tag_name ] = preg_replace( "/requires PHP:\s([\d\.]+)/i", '', $description_content[ $item->tag_name ] );
					}
				}
			}

			$description_content[ 9999999998 ] = '......................................................................................................................' . "\n\n";
			$description_content[ 9999999999 ] = '<a href="' . $response->homepage . '/releases" target="_blank">You can also go to the releases and their changes on GitHub.</a> ';

			if ( $description_content ) {

				$description_content = implode( $description_content, "\n\n" );
			}

		// }

		// Create tabs in the lightbox
		$response->sections = array(
			'description' => $this->pluginData["Description"],
			'changelog' => class_exists( "Parsedown" )
				? Parsedown::instance()->parse( $description_content )
				: $description_content
		);

		// Gets the required version of WP if available {

			$matches = null;
			$all_matches = array();

			if ( is_array( $this->githubAPIResults ) and count( $this->githubAPIResults ) > 0 ) {

				foreach ( $this->githubAPIResults as $key => $item ) {

					preg_match( "/requires WordPress:\s([\d\.]+)/i", $item->body, $matches );

					if ( !empty( $matches ) ) {

						if ( is_array( $matches ) ) {

							if ( count( $matches ) > 1 ) {

								$all_matches[ $matches[1] ] = $matches[1];
							}
						}
					}
				}
			}

			if ( count( $all_matches ) != 0 ) {

				krsort( $all_matches );

				$response->requires = reset( $all_matches );
			}

		// }

		// Gets the tested version of WP if available {

			if ( is_array( $this->githubAPIResults ) and count( $this->githubAPIResults ) > 0 ) {

				$matches = null;
				$all_matches = null;

				foreach ( $this->githubAPIResults as $key => $item ) {

					preg_match( "/tested WordPress:\s([\d\.]+)/i", $item->body, $matches );

					if ( !empty( $matches ) ) {

						if ( is_array( $matches ) ) {

							if ( count( $matches ) > 1 ) {

								$all_matches[ $matches[1] ] = $matches[1];
							}
						}
					}
				}
			}

			if ( $all_matches ) {

				krsort( $all_matches );

				$response->tested = reset( $all_matches );
			}

		// }

		return $response;
	}

	// Perform additional actions to successfully install our plugin
	public function postInstall( $true, $hook_extra, $result ) {

		// Get plugin information
		$this->initPluginData();

		// Since we are hosted in GitHub, our plugin folder would have a dirname of
		// reponame-tagname change it to our original one:
		global $wp_filesystem;
		$pluginFolder = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . dirname( $this->slug );
		$wp_filesystem->move( $result['destination'], $pluginFolder );
		$result['destination'] = $pluginFolder;

		// Remember if our plugin was previously activated
		// $wasActivated = is_plugin_active( $this->slug );

		// Re-activate plugin if needed
		//if ( $wasActivated ) {
			$activate = activate_plugin( $this->slug );
		//}

		return $result;
	}
}
