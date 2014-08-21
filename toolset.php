<?php
/*
	@package   WordPressToolset
	@author    Your Name mail@johannheyne
	@license   GPL-2.0+
	@link      https://github.com/johannheyne/wordpress-toolset
	@copyright 2014 Johann Heyne

	@wordpress-plugin
	Plugin Name:       Toolset
	Plugin URI:        https://github.com/johannheyne/wordpress-toolset
	Description:       Provides functionality fore use in themes.
	Version:           0.0.31
	Author:            Johann Heyne
	Author URI:        www.johannheyne.de
	Text Domain:       toolset
	License:           GPL-2.0+
	License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
	GitHub Plugin URI: https://github.com/johannheyne/wordpress-toolset
	GitHub Branch:    master
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// UPDATER {

	/*include_once( 'updater.php' );

	if ( is_admin() ) { // note the use of is_admin() to double check that this is happening in the admin
	    $config = array(
	        'slug' => plugin_basename(__FILE__), // this is the slug of your plugin
	        'proper_folder_name' => 'toolset', // this is the name of the folder your plugin lives in
	        'api_url' => 'https://api.github.com/repos/johannheyne/wordpress-toolset', // the github API url of your github repo
	        'raw_url' => 'https://raw.githubusercontent.com/johannheyne/wordpress-toolset/master/', // the github raw url of your github repo
	        'github_url' => 'https://github.com/johannheyne/wordpress-toolset', // the github url of your github repo
	        'zip_url' => 'https://github.com/johannheyne/wordpress-toolset/zipball/master', // the zip url of the github repo
	        'sslverify' => false, // wether WP should check the validity of the SSL cert when getting an update, see https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/2 and https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/4 for details
	        'requires' => '3.5', // which version of WordPress does your plugin require?
	        'tested' => '3.9.2', // which version of WordPress is your plugin tested up to?
	        'readme' => 'README.md', // which file to use as the readme for the version number
	        'access_token' => '', // Access private repositories by authorizing under Appearance > Github Updates when this example plugin is installed
	    );
	    new WP_GitHub_Updater( $config );
	}*/

// }

// UPDATER 2 {

	require_once( 'BFIGitHubPluginUploader.php' );

	if ( is_admin() ) {

	    new BFIGitHubPluginUpdater( __FILE__, 'johannheyne', "wordpress-toolset" );
	}

// }

require_once( plugin_dir_path( __FILE__ ) . 'functions.php' );
