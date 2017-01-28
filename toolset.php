<?php
/*
	@package   wordpress-toolset
	@author    Johann Heyne mail@johannheyne
	@license   GPL-2.0+
	@link      https://github.com/johannheyne/wordpress-toolset
	@copyright 2014-2017 Johann Heyne

	@wordpress-plugin
	Plugin Name:       WordPress ToolSet
	Plugin URI:        https://github.com/johannheyne/wordpress-toolset
	Description:       On place for reusable functionalities and assets.
	Version:           0.0.154
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

	// Source: http://code.tutsplus.com/tutorials/distributing-your-plugins-in-github-with-automatic-updates--wp-34817

	require_once( 'BFIGitHubPluginUploader.php' );

	if ( is_admin() ) {

	    new BFIGitHubPluginUpdater( __FILE__, 'johannheyne', "wordpress-toolset" );
	}

// }

require_once( plugin_dir_path( __FILE__ ) . 'functions.php' );
