// YerScriptMediaQueries
// Version 2

// USAGE {

	/*

	var mediaqueries = new YerScriptMediaQueries();
	mediaqueries.init({
		'mediaqueries': [
			{
				breakpoint: 650,
				before: function(){

					// action before breakpoint
				},
				after: function(){

					// action after breakpoint
				}
			}
		],
	});

	*/

// }

function YerScriptMediaQueries() {

	var t = this;

	t.defaults = {
		'resize_treshold': 500,
		'mediaqueries': undefined,
	};

	t.set = {
		'resize_timeout': undefined
	};

	t.init =  function( p ) {

		t.set = jQuery.extend( true, t.defaults, p );

		if ( typeof t.set.mediaqueries === 'object' ) {

			t.loop_breakpoints();
			
			var win = jQuery(window);
			win.resize( function() {

				if ( t.set.resize_timeout ) {

					clearTimeout( t.set.resize_timeout );
				}

				t.set.resize_timeout = setTimeout( function() {

					t.loop_breakpoints();

				}, t.set.resize_treshold );

			});

		}
	};

	t.loop_breakpoints =  function() {

		var window_width = jQuery( window ).width();

		for ( var i in t.set.mediaqueries ) {

			if ( typeof t.set.mediaqueries[ i ].breakpoint === 'number') {

				if ( t.set.mediaqueries[ i ].breakpoint > window_width ) {

					if ( typeof t.set.mediaqueries[ i ].before === 'function' ) {

						t.set.mediaqueries[ i ].before();
					}
				}
				else {

					if ( typeof t.set.mediaqueries[ i ].after === 'function' ) {


						t.set.mediaqueries[ i ].after();
					}
				}
			}
		}
	};

};