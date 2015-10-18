// CSSGrid Overlay ( Version 4 ) {

	/* EXAMPLE USAGE

		jQuery.noConflict();
		jQuery( document ).ready( function ( $ ) {

			var gridoverlay = new GridOverlay();
			gridoverlay.init({
				use: true,
				show_cols: false,
				show_grid: false,
				show_images: false,
				grid_opacity: 0.3,
				width: 1200,
				fluid: true,
				cols: 12,
				gap: 20,
				orientation: 'center', // center, left
				zindex_back: -1,
				zindex_top: 999999,
				images: false, // array [{ 'file': '{file-name}'},]
				image_opacity: 0.5,
				plugin_path: '/wp-content/plugins/',
				image_path: '/wp-content/themes/{theme-name}/assets/gridoverlay/',
			});

		});
	*/

	function GridOverlay() {

		var t = this;

		t.param = {
			use: true,
			show_cols: false,
			show_grid: false,
			show_images: false,
			grid_opacity: 0.1,
			width: 1200,
			fluid: true,
			cols: 12,
			gap: 20,
			orientation: 'center', // center, left
			zindex_back: -1,
			zindex_top: 999999,
			colshtml: '',
			images: false, // array [{ 'file': '{file-name}'},]
			image_opacity: 0.5,
			plugin_path: '/wp-content/plugins/',
			image_path: '/gridoverlay-images/',
		};

		t.sel = {
			'gridoverlay': '.gridoverlay',
			'grid_root': '.gridoverlay_grid_root',
			'cols_root': '.gridoverlay_cols_root',
			'cols_root_inner': '.gridoverlay_cols_root_inner',
			'cols_root_inner_2': '.gridoverlay_cols_root_inner_2',
			'cols_wrap': '.gridoverlay_cols_wrap',
			'cols_inner': '.gridoverlay_cols_inner',
			'image_root': '.gridoverlay_image_root',
			'image_img': '.gridoverlay_image_img',
			'menu': '.gridoverlay_menu',
			'menu_buttons': '.gridoverlay_menu_buttons',
			'menu_button': '.gridoverlay_menu_button',
			'menu_button_grid': '.gridoverlay_menu_button_grid',
			'menu_button_cols': '.gridoverlay_menu_button_cols',
			'menu_button_images': '.gridoverlay_menu_button_images',
			'menu_button_image': '.gridoverlay_menu_button_image',
			'menu_button_zindex': '.gridoverlay_menu_button_zindex',
		}

		t.obj = {
			'html': jQuery( 'html' ),
			'body': jQuery( 'body' ),
			'root': undefined,
			'grid_root': undefined,
			'cols_root': undefined,
			'cols_wrap': undefined,
			'cols_inner': undefined,
			'image_root': undefined,
			'image_img': undefined,
			'menu': undefined,
			'menu_buttons': undefined,
			'menu_button': undefined,
			'menu_button_grid': undefined,
			'menu_button_images': undefined,
			'menu_button_cols': undefined,
		};

		t.stat = {
			'grid_visibility': 'false',
			'cols_visibility': 'false',
			'zindex': 'back', // back, top
		};

		t.init = function ( p ) {

			t.param = jQuery.extend( true, t.param, p );

			if ( t.obj.html.hasClass( 'local' ) ) {

				t.init_gridoverlay();
				t.init_buttons();

				if ( t.param.show_images ) {

					t.init_image();
				}

				if ( t.param.show_cols ) {

					t.init_cols();
				}

				if ( t.param.show_grid ) {

					t.init_grid();
				}

				t.init_zindex_button();
				t.init_styles();
			}

		};

		t.init_zindex_button = function() {

			// BUTTON {

				t.obj.menu_buttons.append( '<div class="' + t.sel.menu_button.replace( '.', '' ) + ' ' + t.sel.menu_button_zindex.replace( '.', '' ) + '">Z</div>' );

				t.obj.menu_button_zindex = t.obj.root.find( t.sel.menu_button_zindex );

			// }

			t.obj.body.on( 'click', t.sel.menu_button_zindex, function() {

				if ( t.stat.zindex === 'back' ) {

					t.stat.zindex = 'top';

					t.obj.grid_root.css( 'z-index', t.param.zindex_top );
					t.obj.cols_root.css( 'z-index', t.param.zindex_top );
					t.obj.image_root.css( 'z-index', t.param.zindex_top );
				}
				else {

					t.stat.zindex = 'back';

					t.obj.grid_root.css( 'z-index', t.param.zindex_back );
					t.obj.cols_root.css( 'z-index', t.param.zindex_back );
					t.obj.image_root.css( 'z-index', t.param.zindex_back);
				}

				t.set_cookie( 'gridoverlay-zindex', t.stat.zindex, 10 );

			} );

			// SET ZINDEX FROM COOCKIE {

				var zindex = t.get_cookie( 'gridoverlay-zindex' );

				if ( zindex === 'top' ) {

					t.stat.zindex = 'top';

					t.obj.grid_root.css( 'z-index', t.param.zindex_top );
					t.obj.cols_root.css( 'z-index', t.param.zindex_top );
					t.obj.image_root.css( 'z-index', t.param.zindex_top );
				}

			// }

		};

		t.init_gridoverlay = function () {

			t.obj.body.append( '<div class="' + t.sel.gridoverlay.replace( '.', '' ) + '"></div>' );

			t.obj.root = t.obj.body.find( t.sel.gridoverlay );
		};

		t.init_buttons = function () {

			// ROOT {

				t.obj.root.append( '<div class="' + t.sel.menu.replace( '.', '' ) + '"></div>' );

				t.obj.menu = t.obj.root.find( t.sel.menu );

				t.obj.menu.css( {
					'position': 'fixed',
					'z-index': t.param.zindex_top + 1,
					'top': '0',
					'right': '0',
					'width': '20px',
					'height': '20px',
					'background-color': 'transparent',
				} );

			// }

			// BUTTONS WRAP {

				t.obj.menu.append( '<div class="' + t.sel.menu_buttons.replace( '.', '' ) + '"></div>' );

				t.obj.menu_buttons = t.obj.root.find( t.sel.menu_buttons );

				t.obj.menu_buttons.css( {
					'position': 'absolute',
					'display': 'none',
					'top': '0',
					'right': '0',
					'white-space': 'nowrap',
				} );

			// }

			// EVENT {

				t.obj.menu.on

				t.obj.root.on( 'mouseover', t.sel.menu, function() {

					t.obj.menu_buttons.show();
				});

				t.obj.root.on( 'mouseleave', t.sel.menu, function() {

					t.obj.menu_buttons.hide();
				});

			// }

		};

		t.init_grid = function () {

			// LAYOUT {

				t.obj.root.append( '<div class="' + t.sel.grid_root.replace( '.', '' ) + '"></div>' );

				t.obj.grid_root = t.obj.body.find( t.sel.grid_root );

				var position = 'top center';

				if ( t.param.orientation === 'left' ) {

					position = '50px 0';
				}

				t.obj.grid_root.css( {
					'position': 'fixed',
					'display': 'none',
					'top': '0',
					'left': '0',
					'right': '0',
					'bottom': '0',
					'background-image': 'url( ' + t.param.plugin_path + 'wordpress-toolset/assets/gridoverlay/grid.png )',
					'background-size': 'auto',
					'background-repeat': 'repeat',
					'background-position': position,
					'opacity': t.param.grid_opacity,
					'z-index': t.param.zindex_back,
				} );

			// }

			// SET GRID VISIBILITY FROM COOCKIE {

				t.stat.grid_visibility = t.get_cookie( 'gridoverlay-grid-visibility' );

				if ( t.stat.grid_visibility === 'true' ) {

					t.obj.grid_root.show();
				}
				else {

					t.obj.grid_root.hide();
				}

			// }

			// BUTTON {

				t.obj.menu_buttons.prepend( '<div class="' + t.sel.menu_button.replace( '.', '' ) + ' ' + t.sel.menu_button_grid.replace( '.', '' ) + '">Grid</div>' );

				t.obj.menu_button_grid = t.obj.root.find( t.sel.menu_button_grid );

			// }

			// EVENT {

				t.obj.body.on( 'click', t.sel.menu_button_grid, function() {

					t.obj.grid_root.toggle();

					if ( t.stat.grid_visibility === 'true' ) {

						t.stat.grid_visibility = 'false';
					}
					else {

						t.stat.grid_visibility = 'true';
					}

					t.set_cookie( 'gridoverlay-grid-visibility', t.stat.grid_visibility, 10 );

				} );

			// }

		};

		t.init_image = function () {

			// LAYOUT {

				t.obj.root.append( '<div class="' + t.sel.image_root.replace( '.', '' ) + '"><img class="' + t.sel.image_img.replace( '.', '' ) + '" src="" style="visibility: hidden;"></div>' );

				t.obj.image_root = t.obj.root.find( t.sel.image_root );
				t.obj.image_img = t.obj.root.find( t.sel.image_img );

				var position = 'top center';

				if ( t.param.orientation === 'left' ) {

					position = 'top left';
				}

				t.obj.image_root.css( {
					'position': 'absolute',
					'top': '0',
					'left': '0',
					'right': '0',
					'overflow-x': 'hidden',
					//'background-image: url(' + t.param.image_path + t.param.images[0]['file'] + ')',
					'background-size': 'auto',
					'background-repeat': 'no-repeat',
					'background-position': position,
					'opacity': t.param.image_opacity,
					'z-index': t.param.zindex_back,
				} );

			// }

			// SET IMAGE FROM COOCKIE {

				var img_index = t.get_cookie( 'gridoverlay-image-index' );

				if ( img_index != 'none' ) {

					t.obj.image_root.show();
					t.obj.image_root.css( 'background-image', 'url(' + t.param.image_path + t.param.images[ img_index ]['file'] + ')' );
					t.obj.image_img.attr( 'src', t.param.image_path + t.param.images[ img_index ]['file'] );
				}

			// }

			// BUTTON {

				var image_buttons = [];

				image_buttons[ 0 ] = '<div class="' + t.sel.menu_button_image.replace( '.', '' ) + '" data-index="none">keines</div>';

				for ( var i in t.param.images  ) {

					image_buttons[ ( parseInt( i ) + 1 ) ] = '<div class="' + t.sel.menu_button_image.replace( '.', '' ) + '" data-index="' + i + '">' + t.param.images[ i ]['file'] + '</div>';
				}

				image_buttons = image_buttons.join( '' );

				t.obj.menu_buttons.prepend( '<div class="' + t.sel.menu_button.replace( '.', '' ) + ' ' + t.sel.menu_button_images.replace( '.', '' ) + '">' + image_buttons + '</div>' );

				t.obj.menu_button_images = t.obj.root.find( t.sel.menu_button_images );
				t.obj.menu_button_image = t.obj.root.find( t.sel.menu_button_image );

			// }

			// EVENT {

				t.obj.body.on( 'click', t.sel.menu_button_image, function() {

					var that = jQuery( this ),
						img_index = that.data( 'index' );

					if ( img_index !== 'none' ) {

						t.obj.image_root.show();
						t.obj.image_root.css( 'background-image', 'url(' + t.param.image_path + t.param.images[ img_index ]['file'] + ')' );
						t.obj.image_img.attr( 'src', t.param.image_path + t.param.images[ img_index ]['file'] );
					}
					else {

						t.obj.image_root.hide();
					}

					t.obj.menu_button_image.css( 'text-decoration', 'none' );
					that.css( 'text-decoration', 'underline' );

					t.set_cookie( 'gridoverlay-image-index', img_index, 10 );

				} );

			// }

		};

		t.init_cols = function () {

			// LAYOUT {

				var cols_html = '';

				for ( var i = 1; i <= t.param.cols; i++ ) {

					cols_html += '<div class="' + t.sel.cols_wrap.replace( '.', '' ) + '"><div class="' + t.sel.cols_inner.replace( '.', '' ) + '">x</div></div>';
				}

				t.obj.root.append( 
					'<div class="' + t.sel.cols_root.replace( '.', '' ) + '">' +
						'<div class="' + t.sel.cols_root_inner.replace( '.', '' ) + '">' +
							'<div class="' + t.sel.cols_root_inner_2.replace( '.', '' ) + '">' +
								cols_html +
							'</div>' +
						'</div>' +
					'</div>' 
				);

				t.obj.cols_root = t.obj.root.find( t.sel.cols_root );
				t.obj.cols_root_inner = t.obj.root.find( t.sel.cols_root_inner );
				t.obj.cols_root_inner_2 = t.obj.root.find( t.sel.cols_root_inner_2 );
				t.obj.cols_wrap = t.obj.root.find( t.sel.cols_wrap );
				t.obj.cols_inner = t.obj.root.find( t.sel.cols_inner );

				var margin = 'auto',
					textalign = 'center';

				if ( t.param.orientation === 'left' ) {

					margin = '0'; 
					textalign = 'left';
				}

				t.obj.cols_root.css({
					'z-index': t.param.zindex_back,
					'opacity': '1',
					'position': 'fixed',
				});

				t.obj.cols_inner.css({
					'background': 'red',
					'height': '100%',
					'width': '100%',
					'opacity': '0.1',
				});

				t.obj.cols_wrap.css({
					'float': 'left',
					'width': ( 100 / t.param.cols ) + '%',
					'height': '100%',
					'padding-left': ( t.param.gap / 2 ) + 'px',
					'padding-right': ( t.param.gap / 2 ) + 'px',
				});

				t.obj.cols_root_inner_2.css({
					'position': 'relative',
					'height': '100%',
					'margin-left': '-' + ( t.param.gap / 2 ) + 'px',
					'margin-right': '-' + ( t.param.gap / 2 ) + 'px',
				});

				if ( t.param.fluid ) {

					t.obj.cols_root.css({
						'z-index': t.param.zindex_back,
						'top': '0',
						'left': '0',
						'right': '0',
						'bottom': '0',
						'overflow': 'hidden',
					});

					t.obj.cols_root_inner.css({
						'position': 'relative',
						'display': 'block',
						'margin': margin,
						'width': '100%',
						'max-width': t.param.width + 'px',
						'height': '100%',
					});
				}

				else {

					t.obj.cols_root.css({
						'z-index': t.param.zindex_back,
						'top': '0',
						'bottom': '0',
						'right': '0',
						'left': '0',
					});

					t.obj.cols_root_inner.css({
						'position': 'relative',
						'display': 'block',
						'margin': margin,
						'width': t.param.width + 'px',
						'height': '100%',
					});
				}

			// }

			// SET COLS VISIBILITY FROM COOCKIE {

				t.stat.cols_visibility = t.get_cookie( 'gridoverlay-cols-visibility' );

				if ( t.stat.cols_visibility === 'true' ) {

					t.obj.cols_root.show();
				}
				else {

					t.obj.cols_root.hide();
				}

			// }

			// BUTTON {

				t.obj.menu_buttons.prepend( '<div class="' + t.sel.menu_button.replace( '.', '' ) + ' ' + t.sel.menu_button_cols.replace( '.', '' ) + '">Cols</div>' );

				t.obj.menu_button_cols = t.obj.root.find( t.sel.menu_button_grid );

			// }

			// EVENT {

				t.obj.body.on( 'click', t.sel.menu_button_cols, function() {

					t.obj.cols_root.toggle();

					if ( t.stat.cols_visibility === 'true' ) {

						t.stat.cols_visibility = 'false';
					}
					else {

						t.stat.cols_visibility = 'true';
					}

					t.set_cookie( 'gridoverlay-cols-visibility', t.stat.cols_visibility, 10 );

				} );

			// }
		};

		t.init_styles = function () {

			t.obj.menu_button = t.obj.menu_buttons.find( t.sel.menu_button );

			t.obj.menu_button.css( {
				'display': 'inline-block',
				'margin-left': '1px',
				'padding': '3px 10px',
				'background': 'grey',
				'font-size': '14px',
				'font-weight': 'normal',
				'line-height': '20px',
				'color': 'white',
				'cursor': 'pointer',
				'white-space': 'nowrap',
				'vertical-align': 'top',
			} );
		};

		t.set_cookie = function( cname, cvalue, exdays ) {

			var d = new Date();

			d.setTime( d.getTime() + ( exdays * 24 * 60 * 60 * 1000 ) );

		    var expires = 'expires=' + d.toUTCString();

			document.cookie = cname + '=' + cvalue + '; ' + expires;
		};

		t.get_cookie = function( cname ) {

			var name = cname + '=';
		    	ca = document.cookie.split( ';' );

			for( var i=0; i < ca.length; i++ ) {

				var c = ca[i];

				while ( c.charAt(0) == ' ' ) {

					c = c.substring( 1 );
				}

				if ( c.indexOf( name ) == 0 ) {

					return c.substring( name.length, c.length );
				}
		    }

		    return '';
		};

	}

// }