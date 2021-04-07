(function(){

	jQuery.noConflict();
	jQuery( document ).ready( function( $ ) {

		function ToolCountdown() {

			var t = this;

			t.ids = {
				data_event_time: 'countdown-event-time',
				table: 'js-countdown-table',
				template: 'js-countdown-expire-template',
				d: 'js-countdown-d',
				h: 'js-countdown-h',
				m: 'js-countdown-m',
				s: 'js-countdown-s',
			};

			t.sel = {
				data_event_time: '[data-' + t.ids.data_event_time + ']',
				template: '.' + t.ids.template,
				table: '.' + t.ids.table,
				d: '.' + t.ids.d,
				h: '.' + t.ids.h,
				m: '.' + t.ids.m,
				s: '.' + t.ids.s,
			};

			t.obj = {
				data_event_time: $( t.sel.data_event_time ),
			};

			t.param = {
				//name: false,
			};

			t.priv = {
				//name: false,
			};

			t.vars = {
				count_downs: {},
			};

			t.init =  function() {

				// INITS {



				// }

				// ADDS ACTIONS {



				// }

				// ADDS FILTERS {



				// }

				// ADDS EVENTS {



				// }

			};

			t.run = function() {

				t.init_countdowns();
			};

			t.init_countdowns = function() {

				if ( t.obj.data_event_time.length > 0 ) {

					t.obj.data_event_time.each( function( i ) {

						var that = $( this );

						t.vars.count_downs[ i ] = {
							event_time: that.data( t.ids.data_event_time ),
							obj_root: that,
							obj_d: that.find( t.sel.d ),
							obj_h: that.find( t.sel.h ),
							obj_m: that.find( t.sel.m ),
							obj_s: that.find( t.sel.s ),
						};

						t.countdown( i );
					});
				}
			};

			t.expired = function( i ) {

				var data = t.vars.count_downs[ i ],
					template = data.obj_root.find( t.sel.template );

				if ( template.length > 0 ) {

					data.obj_root.find( t.sel.table ).remove();

					data.obj_root.append( template.html() );
				}

				App.Actions.do( 'ToolCountdown', 'expired', data );
			};

			t.countdown = function( i ) {

				var data = t.vars.count_downs[ i ],
					now = new Date(),
					event_date = new Date( data.event_time ),
					current_time = now.getTime(),
					event_time = event_date.getTime(),
					rem_time = event_time - current_time,
					s = Math.floor( rem_time / 1000 ),
					m = Math.floor( s / 60 ),
					h = Math.floor( m / 60 ),
					d = Math.floor( h / 24 );

				if ( rem_time < 0 ) {

					t.expired( i );

					return;
				}

				h %= 24;
				m %= 60;
				s %= 60;

				if ( h < 10 ) {

					h = '0' + h;
				}

				if ( m < 10 ) {

					m = '0' + m;
				}

				if ( s < 10 ) {

					s = '0' + s;
				}

				//console.log( d, h, m, s );
				data.obj_d.text( d );
				data.obj_h.text( h );
				data.obj_m.text( m );
				data.obj_s.text( s );

				setTimeout( function() {

					t.countdown( i );
				}, 1000 );

			};

		};

		App.ToolCountdown = new ToolCountdown();
		App.Modules.add( 'ToolCountdown' ); // or simply App.ToolCountdown.init();

	});

}());
