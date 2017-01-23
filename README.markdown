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

Documentation
---------------

### Initial Functionality

````php
$GLOBALS['toolset'] = array(
	'inits' => array(
		// initial functionalities configurations
	)
);
````
* [WordPress Head](docs/tool_wp_head.markdown)
* [WordPress Meta Title](tools/tool_meta_title/readme.markdown)
* [WordPress Remove Footprint](docs/tool_remove_wp_footprint.markdown)
* [WordPress Postboxes](tools/tool_postboxes/readme.markdown)
* [WordPress Unregister Default Widgets](tools/tool_widgets_unregister_defaults/readme.markdown)
* [WordPress Remove Metaboxes](tools/tool_metabox_remove/readme.markdown)
* [WordPress Add CSS Classes to Menu Items](tools/tool_menu_add_css_classes/readme.markdown)
* [WordPress Notify Post Author if Comments](tools/tool_wp_comment_notification_notify_author/readme.markdown)
* [WordPress Comment Moderation Email Recipients](tools/tool_wp_comment_moderation_recipients/readme.markdown)
* [WordPress Images Sizes](tools/tool_image_sizes/readme.markdown)
* [Adaptive Images](tools/tool_adaptive_images/readme.markdown)
* [ACF Save Post After](tools/tool_acf_save_post_after/readme.markdown)
* [ACF Translate](tools/tool_acf_translate/readme.markdown)
* [API SiteData](tools/tool_api_sitedata/readme.markdown)

### Tools

* [Javascript Recomended Message](tools/tool_javascript_recomended/readme.markdown)
* [Ajax Call](tools/tool_ajax/readme.markdown)
* [Meta Title](tools/tool_meta_title/readme.markdown)
* [Meta Description](tools/tool_meta_description/readme.markdown)
* [User Login/Logout](tools/tool_wp_user/readme.markdown)
* [Instagram](tools/tool_instagram/readme.markdown)
* [Transient](tools/tool_transient/readme.markdown)

### Assets

* [YerScriptMediaQueries](docs/yerscriptmediaqueries.markdown)
* [YerParallax](docs/yerparallax.markdown)
* [YerWhen](docs/yerwhen.markdown)
* [YerRespFontSize](docs/yerrespfontsize.markdown)


