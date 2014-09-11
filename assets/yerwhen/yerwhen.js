// YerWhen Version 2

	/*
		USAGE
		=====

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

	*/

	function YerWhen( param ) {

		var p = {
			when: false, // function
			then: false, // function
			ontimeout: false, // function
			param: {
				timeout: 60000, // number in milliseconds
				interval: 100, // number in milliseconds
			}
		};

		p = jQuery.extend( true, p, param );

		var i = 0,
			interval = undefined;

		interval = window.setInterval( function() {

			i++;

			if ( jQuery.type( p.when ) === 'function' ) {

				var status = p.when( true );

				if ( status === true ) {

					window.clearInterval( interval );

					if ( jQuery.type( p.then ) === 'function'  ) {

						p.then(); 
				    }
				}

			}

			if ( ( i * p.param.interval ) === p.param.timeout ) {

				window.clearInterval( interval );

				if ( jQuery.type( p.param.timeout ) === 'function' ) {

					p.timeout(); 
				}
			}

		}, p.param.interval );

	};