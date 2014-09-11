[back to overview](../README.markdown#assets)

YerWhen
===========

A basic JavaScript function to execute something on one ore more conditions.

````javascript
YerWhen({

	when: function ( status ) {

		// status is by default true

		if ( something_is_ready !== true ) {

			status = false;
		}

		return status;
	},

	then: function () {

		// do something on conditions check succsess
	},
	
	timeout: function () {

		// do something on conditions check timeout
	},
	
	param: {
		timeout: 60000, // number in milliseconds
		interval: 100, // number in milliseconds
	},

});
````

`YerWhen.param:timeout` defines the time in millisecond after the conditions check ends and the `YerWhen.timeout` function is executed.

`YerWhen.param:interval` defines the time in millisecond between the conditions checks.


[back to overview](../README.markdown#assets)