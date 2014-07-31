function YerOverlay () {

	var t = this;

	t.param = {
		'close_on_outsideclick': true,
	};

	t.obj = {
		wrap: undefined,
		box: undefined,
		cont: undefined,
	};

	t.init = function ( p ) {

		// GET PARAMETERS {

			t.param = t.helper.setDefaultParam({
				p: p,
				d: t.param
			});

		// }

		// PROVIDE HTML {

			// make sure, only one overlaybox is there

			jQuery( '.yeroverlay-wrap' ).remove();

			// provide HTML

			jQuery( 'body' ).append( '<div class="yeroverlay-wrap"><div class="yeroverlay-box"><div class="yeroverlay-content">' );

		// }

		// REGISTER OBJECTS {

			t.obj.wrap = jQuery( '.yeroverlay-wrap' );
			t.obj.box = jQuery( '.yeroverlay-box' );
			t.obj.cont = jQuery( '.yeroverlay-content' );

		// }

		// EVENT CLOSE ON WRAP CLICK {

			if ( t.param.close_on_outsideclick ) {

				t.obj.wrap.on( 'click', function( event ) {

					event.stopPropagation();
					t.obj.wrap.fadeOut( 100 ); 
				});
			}

			// prevent bubbling up clicks events inside the content element

			t.obj.box.on( 'click', function( event ) {

				event.stopPropagation();
			});

		// }

	};

	t.set = function ( p ) {

		// DEFAULTS {

			var d = {
				'watch': undefined, // css selector
				'event': 'click', // click, mouseover etc.
				'theme': '.yeroverlay-theme-default',
				'content': undefined,
				'content_selector': undefined,
				'content_by_data': undefined,
				'content_by_data_selector': undefined,
				'load_path': undefined, // 'source.html #selector
				'load_href': undefined,
				'close_button': false,
			};

		// }

		// GET PARAMETERS {

			p = t.helper.setDefaultParam({
				p: p,
				d: d,
			});

		// }

		// WATCH {

			if ( p.watch ) {

				jQuery( p.watch ).on( p.event, function ( e ) {

					e.preventDefault();
					t.overlay( jQuery( this ), p );
				} );
			}

			else if ( p.content_selector ) {

				t.overlay( jQuery( p.content_selector ), p );
			}

			else {

				t.overlay( undefined, p );
			}

		// }

		if ( p.close_on_outsideclick ) {

			t.obj.wrap.on( 'click', function( event ) {

				event.stopPropagation();
				t.obj.wrap.fadeOut( 100 ); 
			});
		}
		else {

			t.obj.wrap.unbind( 'click' );
		}

	};

	t.overlay = function ( obj, p ) {

		if ( p.content_selector ) {

		    p.content = jQuery( p.content_selector ).html();
		}

		if ( obj && p.content_by_data ) {

			p.content = obj.data( p.content_by_data );
		} 

		if ( obj && p.content_by_data_selector ) {

			var data_selector = obj.data( p.content_by_data_selector );
		    p.content = jQuery( data_selector ).html();
		}

		if ( p.load_path ) {

			t.obj.cont.load( p.load_path, function() {

				t.fitboxsize();
			});
		}

		if ( p.load_href ) {

			t.obj.cont.load( obj.attr( 'href' ), function() {

				t.fitboxsize();
			});
		}

		if ( p.close_button ) {

			t.closebtn_add();

		} else {

			t.closebtn_remove();
		}

		t.open( p.content );

	};

	t.formsubmit = function ( p ) {

		t.obj.cont.find( 'form' ).submit( function( event ) {

			event.preventDefault();

			jQuery(this).css( 'opacity', 0.3 ).find( 'input[type="submit"]' ).parent().css( 'visibility', 'hidden' );

			var form = jQuery(this),
				url = form.attr( 'action' );

			jQuery.post( url + '?ajax=y', form.serialize(), function( data ) {

				var content = jQuery( data ).find( p.selector );

				t.obj.cont.children().fadeOut( 200, function(){

					jQuery(this).parent().empty().append( content ).children().css( 'opacity', 0 );

					t.obj.cont.children().hide().css( 'opacity', 1 ).fadeIn( 200 );

					t.formsubmit( p );

					t.closebtn();

				});

			});
		});
	};

	t.closebtn_add = function () {

		t.closebtn_remove();

		t.obj.box.append( '<div class="yeroverlay-close">' );
		t.obj.close = t.obj.box.find( '.yeroverlay-close' );

		t.obj.close.on( 'click', function (event) {

			t.obj.wrap.fadeOut( 100 );
			event.stopPropagation();
		});
	};

	t.closebtn_remove = function () {

		jQuery( '.yeroverlay-close' ).remove();
	};

	// HELPER {

		t.fitboxsize = function () {

			window.setTimeout( function () {

				// WAIT FOR IMAGES {

					jQuery( t.obj.cont ).imagesLoaded()
						.always( function( instance ) {

							t.fitboxsize_job();
						});

				// }

				// RESIZE EVENT {

					jQuery( window ).off('resize').resize( function() {

						t.fitboxsize_job();
					});

				// }

			}, 100 );
		};

		t.fitboxsize_job = function () {

			t.obj.cont.height( 'auto' );

			var windowsize = t.windowsize(),
				box_padding_top = parseInt( t.obj.box.css( 'padding-top' ), 10 ),
				box_padding_bottom = parseInt( t.obj.box.css( 'padding-bottom' ), 10 ),
				box_margin_top = parseInt( t.obj.box.css( 'margin-top' ), 10 ),
				box_margin_bottom = parseInt( t.obj.box.css( 'margin-bottom' ), 10 ),
				cont_height = t.obj.box.height(),
				cont_height_max = windowsize.height - box_margin_top - box_margin_bottom - box_padding_top - box_padding_bottom,
				box_vert_space = cont_height + box_padding_top + box_padding_bottom + box_margin_top + box_margin_bottom;

			if ( windowsize.height < box_vert_space ) {

				t.obj.cont.height( cont_height_max );
			}
		};

		t.windowsize = function () {

			var data = {};

			data.width = jQuery( window ).width();
			data.height = jQuery( window ).height();

			return data;
		};

		t.open = function ( content ) {

			t.obj.cont.html( content );
			t.obj.wrap.fadeIn( 200, function() {

				t.fitboxsize();	
			} );

		};

		t.helper = {

			getLength: function( o ) {

				var len = o.length ? --o.length : -1;

				for (var k in o) {
					len++;
				}

				return len;
			},

			setDefaultParam: function ( p ) {

				if ( typeof p === 'undefined' ) {
					p = {};
				}

				if ( typeof p.p === 'undefined' ) {
					p.p = {};
				}

				if ( typeof p.d === 'undefined' ) {
					p.d = {};
				}

				var r = p.p;

				for( var i in p.d ) {

					if ( typeof p.d[ i ] !== 'undefined' && typeof r[ i ] !== typeof p.d[ i ] ) {
						r[ i ] = p.d[ i ];
					}
					else {

						if ( typeof p.d[ i ] !== 'undefined' && t.helper.getLength( r[ i ] ) !== t.helper.getLength( p.d[ i ] ) ) {
							r[ i ] = t.helper.setDefaultParam({ p: r[ i ], d: p.d[ i ] });
						}
					}
				}

				return r;
			}
		};

	// }

};