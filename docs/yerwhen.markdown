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

		// do something
	},

});
````

[back to overview](../README.markdown#assets)