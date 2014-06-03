wordpress-toolset
===============

A toolset and coding guide using wordpress.
Its about using and extending a basic toolbox for any site.

Version
---------------
~Current Version:0.0.4~

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
        tools
          index.php
          {toolname}/
            index.php
            styles.css
            scripts.js
        toolset.php
    theme/
      config/
        config.php
      functions.php
    
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

There is a single global variable, that holds any kind of global data to drive tools.
````php
$GLOBALS['theme'] = array( /* tools configurations goes here */ );
````
