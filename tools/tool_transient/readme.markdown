[back to overview](../../README.markdown#tools)

Tool Transient
===============================

````php
	tool( array(
		'name' => 'tool_transient',
		'param' => array(
			'id' => 'unique-transient-id',
			'time' => 'next_hour', // 'next_hour', 'tomorrow' or expiration time in seconds or use a WordPress time constants
			'call' => function() {
				
				return 'an array, object or regular variable';
			},
		),
	) );
	
	/* SYNCED EXPIRE SETUP
	
		'time' => 'next_hour' // This sets the remaining seconds to the next full hour.
		'time' => 'tomorrow' // This sets the remaining seconds to midnight.
	
	*/
	
	/* WordPress Time Constants
		
		MINUTE_IN_SECONDS  = 60 (seconds)
		HOUR_IN_SECONDS    = 60 * MINUTE_IN_SECONDS
		DAY_IN_SECONDS     = 24 * HOUR_IN_SECONDS
		WEEK_IN_SECONDS    = 7 * DAY_IN_SECONDS
		YEAR_IN_SECONDS    = 365 * DAY_IN_SECONDS
	
	*/
````

[WordPress Transients API](https://codex.wordpress.org/Transients_API)

[back to overview](../../README.markdown#tools)
