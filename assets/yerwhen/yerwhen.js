// YerWhen Version 1

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

				// do something
			},

		});

	*/

	function YerWhen( param ) {

		var p = {
			when: false,
			then: false,
			timeout: false,
			param: {
				timeout: 10
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

			if ( ( i / 10 ) === p.param.timeout ) {

				window.clearInterval( interval );

				if ( jQuery.type( p.param.timeout ) === 'function' ) {

					p.timeout(); 
				}
			}

		}, 100 );

	};