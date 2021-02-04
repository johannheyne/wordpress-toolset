[Theme Doc](../../Kickstart/WordPress/ThemeDoc/index.md)

Übersicht
==============

- [Documentation](#documentation)
	- [Tools](#tools)

WordPress ToolSet Plugin
===============

This WordPress plugin provides a foundation to configurate WordPress, provides PHP tools and Javascript assets for frequent tasks.
It is about NOT separately managing all the same repeating code on every WordPress project.

Strategie
---------------
- First, keep using configuration and tools simple and flexible.
- Use abstract interface to connect tools and presentation.
- Documenting changes and instructions since last update in the update changelog.

Taktik
---------------
- Provide the functionality, tools and assets as a single WordPress plugin.
- Use a single global config variable in the theme.
- Provide a abstract function to use all tools.

Structure
---------------

	plugins/
		toolset/
			assets/
			tools/
				index.php
				{toolname}/
					index.php
					styles.css
					scripts.js
					readme.markdown
		 	toolset.php
	themes/
		theme/
			config/
				config.php

### Using a Tool Functionality

````php
tool( array(
	'name' => 'the abstract name of functionality',
	'param' => array(
		'key' => 'value', // parameters of the functionality
	),
) );
````
The tool() Function
	- detects the real tool function name and its sourcefile by <code>plugins/toolset/tools/index.php</code>
	- checks wheter the function exists and autoload the sourcefile of the function from <code>plugins/toolset/tools/…</code>
	- runs the function and returns a result.

### Config

There is a single global variable, that holds any kind of global data to drive tools of the toolset. This variable must be set in the active theme in the function.php or better in an /config/config.php file.

````php
$GLOBALS['toolset'] = array( /* toolset configuration options goes here */ );
````

Lets walk true the basic configuration options.

````php
$GLOBALS['toolset'] = array(

	// THEME VERSION {

		// The theme version is used to force reloading all cached files of a theme
		// via example_changed_file.js?v=0.1 to make changes happen in a browser.

		'theme-version' => '0.1',

	// }

	// DOMAINS {

		// configurates the domains for the development, staging and production server

		'domains' => array(
			$_SERVER["HTTP_HOST"] => 'live',
			'kickstart.domain.de' => 'test',
			'kickstart.dev' => 'local',
		),

		// Notice if you are using MAMP on a Mac!
		// Avoide using '.local' domains. This makes your local Site realy slow because
		// '.local' conflict with Bonjour, which treats anything with a '.local' TLD as a Bonjour server.
		// read this: http://www.bram.us/2011/12/12/mamp-pro-slow-name-resolving-with-local-vhosts-in-lion-fix/
		// and this: https://discussions.apple.com/message/15834652#15834652.

	// }

	// SITES {

		// sites holds basic information for the site ore even mulitple sites on a WordPress multisite installation
		// this example intents, that we are using sites for building a multilanguage website

		'sites' => array(
			'1' => array(
				'key' => 1,
				'id' => 'en',
				'slug' => '',
				'name' => 'English',
			),
			'2' => array(
				'key' => 2,
				'id' => 'de',
				'slug' => 'de',
				'name' => 'German',
			),
		),

	// }

	// LANGUAGES {

		'langcode' => array(
			'en' => 'en',
			'de' => 'de',
		),
		'countrycode' => array(
			'en' => 'en-US',
			'de' => 'de-DE',
		),

		'admin_locale' => get_locale(),

	// }

	// REGISTER PHP CLASSES FOR AUTOLOAD FROM YOUR THEME {

		// You do not need to define classes from the toolset plugin

		'autoload_php_classes' => array(
			// 'ClassName' => 'path/to/class.php',
		),

	// }
);
````

## Documentation


### Initial Functionality

````php
$GLOBALS['toolset'] = array(
	'inits' => array(
		// initial functionalities configurations
	)
);
````
* [WordPress Head](tools/tool_wp_head/readme.markdown)
* [WordPress Meta Title](tools/tool_meta_title/readme.markdown)
* [WordPress Remove Footprint](tools/tool_remove_wp_footprint/readme.markdown)
* [WordPress Postboxes](tools/tool_postboxes/readme.markdown)
* [WordPress Unregister Default Widgets](tools/tool_widgets_unregister_defaults/readme.markdown)
* [WordPress Remove Metaboxes](tools/tool_metabox_remove/readme.markdown)
* [WordPress Add CSS Classes to Menu Items](tools/tool_menu_add_css_classes/readme.markdown)
* [WordPress Notify Post Author if Comments](tools/tool_wp_comment_notification_notify_author/readme.markdown)
* [WordPress Comment Moderation Email Recipients](tools/tool_wp_comment_moderation_recipients/readme.markdown)
* [WordPress Images Sizes](tools/tool_image_sizes/readme.markdown)
* [WordPress REST-API](tools/tool_rest_api/readme.markdown)
* [Adaptive Images](tools/tool_adaptive_images/readme.markdown)
* [ACF Save Post After](tools/tool_acf_save_post_after/readme.markdown)
* [ACF Translate](tools/tool_acf_translate/readme.markdown)
* [API SiteData](tools/tool_api_sitedata/readme.markdown)
* [HTML5](tools/tool_html5/readme.markdown)

### Tools

* [Array Positioning](tools/tool_array_pos/readme.markdown)
* [Array Layout](tools/tool_array_layout/readme.markdown)
* [Ajax Call](tools/tool_ajax/readme.markdown)
* [Cookie Notice](tools/tool_cookie_notice/readme.markdown)
* [Filesize](tools/tool_filesize/readme.markdown)
* [Form](tools/tool_form/readme.md)
* [Hreflang](tools/tool_hreflang/readme.markdown)
* [Hierarchical Order Posts](tools/tool_order_posts/readme.markdown)
* [Instagram](tools/tool_instagram/readme.markdown)
* [Javascript Recomended Message](tools/tool_javascript_recomended/readme.markdown)
* [Get Locale by URL](tools/tool_get_locale_by_url/readme.markdown)
* [Meta Title](tools/tool_meta_title/readme.markdown)
* [Meta Description](tools/tool_meta_description/readme.markdown)
* [Meta Robots](tools/tool_meta_robots/readme.markdown)
* [Multilanguage](tools/tool_multilanguage/readme.markdown)
* [Register Navigation Menu](tools/tool_nav_menu_register/readme.markdown)
* [Posttype](tools/tool_posttypes/readme.markdown)
* [Sortable Admin List Columns](tools/tool_sortable_list_column/readme.markdown)
* [Transient](tools/tool_transient/readme.markdown)
* [Translate](tools/tool_translate/readme.markdown)
* [User Login/Logout](tools/tool_wp_user/readme.markdown)

### Assets

* [YerScriptMediaQueries](assets/yerscriptmediaqueries/readme.markdown)
* [YerParallax](assets/yerparallax/readme.markdown)
* [YerWhen](assets/yerwhen/readme.markdown)
* [YerRespFontSize](assets/yerrespfontsize/readme.markdown)

### Filters
* [toolset/tool_html_buffer/buffer](tools/tool_html_buffer/readme.markdown)
* [toolset/tool_fields_value/value](tools/tool_fields_value/readme.markdown)
