// CSSGrid Overlay ( Version 4 ) {

	function GridOverlay() {

		var t = this;

		t.param = {
			use: true,
			showgridonstart: false,
			width: 1200,
			fluid: true,
			cols: 12,
			gap: 20,
			orientation: 'center', // center, left
			zindex: -1,
			zindexinterval: 3000,
			colshtml: '',
			image: false, // '/backend/wp-content/themes/themname/js/gridoverlay/{bild}',
			image_opacity: 0.5,
		};

		t.obj = {
			body: jQuery('html.local body'),
		};

		t.init = function ( p ) {

			t.param = jQuery.extend( true, t.param, p );

			t.job();
		};

		t.job = function () {

			if ( t.param.use ) {

				// BUTTONS {

					t.obj.body.append('<div class="gridoverlay_btns"><div class="gridoverlay_btn_show_grid">grid visibility</div><div class="gridoverlay_btn_change_zindex">grid focus</div></div>');

					var objGridoverlayBtns = t.obj.body.find('.gridoverlay_btns'),
						objGridoverlayBtn = objGridoverlayBtns.find('*');

					objGridoverlayBtns.css({
						'position': 'fixed',
						'z-index': '99999',
						'top': '0',
						'right': '0',
					});

					objGridoverlayBtn.css({
						'display': 'inline-block',
						'margin-left': '1px',
						'padding': '3px 10px',
						'background': 'black',
						'font-size': '12px',
						'line-height': '20px',
						'color': 'white',
						'cursor': 'pointer',
					});

					t.obj.body.find('.gridoverlay_btn_change_zindex').on('click', function () {

						t.changeZIndex();
					});

					t.obj.body.find('.gridoverlay_btn_show_grid').on('click', function () {

						t.showGrid();
					});

				// }

				// GRID {

					for ( var i = 1; i <= t.param.cols; i++ ) {

						t.param.colshtml += '<div class="gridoverlay_col"><div class="gridoverlay_col_innner">x</div></div>';
					}

					if ( t.param.image ) {

					   t.obj.body.append('<div class="gridoverlay_main gridoverlay_image_wrap"><img class="gridoverlay_image" src="' + t.param.image + '"></div>');

					}

					t.obj.body.append('<div class="gridoverlay_main gridoverlay_root"><div class="gridoverlay_wrap"><div class="gridoverlay">' + t.param.colshtml + '</div></div></div>');

					var objGridoverlayRoot = jQuery('.gridoverlay_root'),
						objGridoverlayMain = jQuery('.gridoverlay_main'),
						objGridoverlayWrap = objGridoverlayRoot.find('.gridoverlay_wrap'),
						objGridoverlay = objGridoverlayWrap.find('.gridoverlay'),
						objGridoverlayCol = objGridoverlay.find('.gridoverlay_col'),
						objGridoverlayColInner = objGridoverlayCol.find('.gridoverlay_col_innner'),
						objGridoverlayImageWrap = jQuery('.gridoverlay_image_wrap');
						objGridoverlayImage = objGridoverlayImageWrap.find('.gridoverlay_image');

					var margin = 'auto',
						textalign = 'center';

					if ( t.param.orientation === 'left' ) {

					   margin = '0'; 
					   textalign = 'left';
					}

					objGridoverlayImageWrap.css({
						'position': 'absolute',
						'top': '0',
						'left': '0',
						'right': '0',
						'text-align': textalign,
					});

					objGridoverlayMain.css({
						'z-index': '-1',
						'visibility': 'hidden',
					});

					objGridoverlayRoot.css({
						'opacity': '1',
						'position': 'fixed',
					});

					objGridoverlayColInner.css({
						'background': 'red',
						'height': '100%',
						'width': '100%',
						'opacity': '0.1',
					});

					objGridoverlayCol.css({
						'float': 'left',
						'width': ( 100 / t.param.cols ) + '%',
						'height': '100%',
						'padding-left': ( t.param.gap / 2 ) + 'px',
						'padding-right': ( t.param.gap / 2 ) + 'px',
					});

					objGridoverlay.css({
						'position': 'relative',
						'height': '100%',
						'margin-left': '-' + ( t.param.gap / 2 ) + 'px',
						'margin-right': '-' + ( t.param.gap / 2 ) + 'px',
					});

					objGridoverlayImage.css({
						'opacity': t.param.image_opacity,
					});

					if ( t.param.fluid ) {

						objGridoverlayRoot.css({
							'z-index': t.param.zindex,
							'top': '0',
							'left': '0',
							'right': '0',
							'bottom': '0',
							'overflow': 'hidden',
						});

						objGridoverlayWrap.css({
							'position': 'relative',
							'display': 'block',
							'margin': margin,
							'width': '100%',
							'max-width': t.param.width + 'px',
							'height': '100%',
						});
					}

					else {

						objGridoverlayRoot.css({
							'z-index': t.param.zindex,
							'top': '0',
							'bottom': '0',
							'right': '0',
							'left': '0',
						});

						objGridoverlayWrap.css({
							'position': 'relative',
							'display': 'block',
							'margin': margin,
							'width': t.param.width + 'px',
							'height': '100%',
							'overflow': 'hidden',
						});
					}

					// var intervall = setInterval( changeZIndex, t.param.zindexinterval );

				// }

				// SHOW GRID ON START {

					if ( t.param.showgridonstart ) {

					    t.showGrid();
					}

				// }

			}

		};

		t.changeZIndex = function() {

			var objGridoverlayMain = jQuery('.gridoverlay_main'),
				zindex = objGridoverlayMain.css('z-index');

			if ( zindex === '-1' ) {

				zindex = '9999';
			}
			else {
				zindex = '-1';
			}

			objGridoverlayMain.css({
				'z-index': zindex
			});
		};

		t.showGrid = function () {

			var objGridoverlayMain = jQuery('.gridoverlay_main'),
				visibility = objGridoverlayMain.css('visibility');

			if ( visibility === 'hidden' ) {

				visibility = 'visible';
			}
			else {
				visibility = 'hidden';
			}

			objGridoverlayMain.css({
				'visibility': visibility
			});
		};

		t.helper = {

			getLength: function( o ) {

				var len = o.length ? --o.length : -1;

				for (var k in o) {
					len++;
				}

				return len;
			},
		};

	}

	/* EXAMPLE USAGE

		jQuery.noConflict();
		jQuery(document).ready(function ($) {

			var gridoverlay = new GridOverlay();
			gridoverlay.init({
				use: true,
				width: 1200,
				fluid: true,
				cols: 12,
				gap: 20,
				orientation: 'center', // center, left
				zindex: -1,
				zindexinterval: 3000,
				colshtml: '',
				image: false, // '/backend/wp-content/themes/themname/assets/gridoverlay/{bild}',
				image_opacity: 0.5,
			});

		});
	*/

// }