[back to overview](../../README.markdown#initial-functionality)

Tool Rest-Api
===============================

### Disable the WordPress Rest-API for frontend. Logged in users have access to the API.

````php
	$GLOBALS['toolset']['inits']['tool_rest_api'] = array(
		disable_for_public' => true
	);
````

[back to overview](../../README.markdown#initial-functionality)
