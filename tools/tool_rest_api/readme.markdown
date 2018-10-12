[back to overview](../../README.markdown#initial-functionality)

Tool REST-API
===============================

### Disable the WordPress REST-API for frontend. Logged in users have access to the API.

````php
	$GLOBALS['toolset']['inits']['tool_rest_api'] = array(
		disable_for_public' => true
	);
````

[back to overview](../../README.markdown#initial-functionality)
