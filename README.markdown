WordPress Toolset Plugin
===============

This Plugin provides tools to configure WordPress and tools to get things done.
Its about NOT separately managing all the same code on every WordPress theme.

Strategie
---------------
- First, keep it simple and flexible.
- Use abstract interface to connect functionality and presentation.
- Functionality should be able to be updated and extended at any time without a need to change the presentation code immediately.

Taktik
---------------
- Provide the tools as a WordPress plugin.
- Use a single config variable in theme.
- Provide a abstrakt-function to use tool-functionality.

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
		 	toolset.php
	themes/
		config/
			config.php
	
### Using a tool functionality

````php
tool( array(
	'name' => 'the abstract name of functionality',
	'param' => array(
		'key' => 'value', // parameters of the functionality
	),
) );
````
The tool() function
	- detects the real tool function name and its sourcefile by <code>plugins/toolset/tools/index.php</code> 
	- checks wheter the function exists and autoload the sourcefile of the function from <code>plugins/toolset/tools/…</code> 
	- runs the function and returns a result.

### Config

There is a single global variable, that holds any kind of global data to drive tools of the toolset. This variable must be set in the active theme in the function.php or bether in the /config/config.php file.
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

	// }

	// AUTOLOAD PHP CLASSES {

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
