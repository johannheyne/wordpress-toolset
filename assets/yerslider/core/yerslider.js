/*
 * YerSlider
 * A javascript object for slided content
 *
 * Copyright (c) 2014 Johann Heyne
 *
 * Terms of use:
 * Dual licensed under the MIT or GPL Version 2 licenses.
 *
 */

var yerslider = {};
	yerslider.youtubeready = false;
	yerslider.youtubeloaded = false;

function onYouTubeIframeAPIReady() {

	yerslider.youtubeready = true;
}

function YerSlider() {

	var t = this;

	t.param = {

		// path
		yersliderfolderabsolutepath: '/',

		// sliderid
		sliderid: '.yerslider',

		// images loaded
		imagesloaded: [ 'slide', 'thumbs' ], /* [ 'slider', 'slide', 'thumbs' ] */
		loadingmessagedelay: 30,

		// slidegap
		slidegap: 0,

		// slidestep
		slidingstep: undefined,

		// slidegroups
		slidegroup: 1,
		slidegroupresp: {},

		// swipe		
		swipe: false,
		swipemouse: false,
		swipeandprevnextbtn: false,
		swipeanimationspeed: 300,

		// animation
		animationtype: 'ease', /* ease, ease-in-out, ease-in, ease-out, linear */
		animationspeed: 1000,

		// loop
		loop: 'none', /* infinite, rollback */
		loopswipe: 'none', /* infinite */

		// autoplay
		autoplay: false, /* true */
		autoplayinterval: 3000, /* integer sec, 0 */
		autoplaybreakmin: 10, /* integer millisec, 0 */
		autoplaybreak: false, /* integer millisec, 0 */
		autoplaydelaystart: 0, /* integer sec, 0 */
		autoplaystoponhover: true,

		autoplaycontinuously: false, /* true */
		autoplaycontinuouslyspeed: 10000,
		autoplaycontinuouslystoponhover: true,

		// slider classes
		sliderclass: '.yerslider',
		sliderwrapclass: '.yerslider-wrap',
		sliderviewportclass: '.yerslider-viewport',
		slidermaskclass: '.yerslider-mask',
		sliderclass: '.yerslider-slider',
		slideclass: '.yerslider-slide',
		loadingclass: '.yerslider-loading',

		// buttons
		nextbtn: true,
		prevbtn: true,
		prevnextlocation: 'inside', // inside, outside,
		prevnextclass: '.yerslider-prevnext',
		nextclass: '.yerslider-next',
		prevclass: '.yerslider-prev',
		nextinactiveclass: '.yerslider-next-inactive',
		previnactiveclass: '.yerslider-prev-inactive',
		nextclassadd: '',
		prevclassadd: '',

		// keys
		nextkey: 13,
		prevkey: 14,
		
		// bullets
		bullets: false,
		bulletslocation: 'outside', // inside, outside,
		bulletclickable: true,
		sliderwrapclasshasbullets: '.yerslider-has-bullets',
		bulletswrapclass: '.yerslider-bullets-wrap',
		bulletclass: '.yerslider-bullet',
		bulletcurrentclass: '.yerslider-bullet-current',

		// thumbs
		thumbs: false,
		thumbshideiflessthan: 2,
		thumbslocation: 'outside', // inside, outside,
		thumbstemplates: {},
		thumbsclickable: true,
		sliderwrapclasshasthumbs: '.yerslider-has-thumbs',
		thumbswrapclass: '.yerslider-thumbs-wrap',
		thumbsviewportclass: '.yerslider-thumbs-viewport',
		thumbsmaskclass: '.yerslider-thumbs-mask',
		thumbsitemsclass: '.yerslider-thumbs-items',
		thumbsitemclass: '.yerslider-thumbs-item',
		thumbsready: undefined, // a function that will call after thumbs are ready

		// video
		sublimevideo: false,
		autoloadyoutubeiframeapi: true,
		videoplayercloseafterend: true,

		// scroll
		scrolltop: false,
		scrolltopval: 0,
		scrolltopspeed: 500,

		// showslidestime: 500,
		dependencies_autoload: true,

		// callbacks
		sliderready: undefined,

		// detachning
		detach: undefined,
		
		sliderfadeinspeed: 'slow'
	};

	t.stat = {
		slidegroupmax: 1,
		slidegroup: 1,
		currentslideindex: 0,
		currentslideposition: 0,
		slidecount: 0,
		slidermaskwidth: 0,
		slidewidth: 0,
		isanimating: false,
		isswiping: false,
		lasteventtype: false,
		nextbtnclickable: false,
		prevbtnclickable: false,
		bulletscount: 0,
		bulletscountcache: 0,
		bulletcurrent: 0,
		bulletschanged: false,
		slidingleft: false,
		slidingright: false,
		resizing: false,
		cssanimation: false,
		isevent: false,
		touch: false,
		clicktype: 'click',
		isios: false,
		isresizing: false,
		slidesinviewportindexbegin: false,
		slidesinviewportindexend: false,
		slidesinviewportindexes: false,
		autoplayinterval: false,
		autoplayison: false,
		videoidindex: 0,
		videoplayerindex: 0,
		lastplayedvideo: false,
		videoisplaying: false,
		loop: false,
		dependencies_loaded: 0,
		dependencies_count: 0,
		browser_features: [],
		loadingtimeout: false,
		isloading: false,
	};

	t.obj = {
		sliderid: undefined,
		sliderwrap: undefined,
		sliderviewport: undefined,
		slider: undefined,
		slide: undefined,
		bulletswrap: undefined,
		bullets: undefined,
		prevbtn: undefined,
		nextbtn: undefined,
		videoplayers: {},
		slides_videoplayers: {},
		thumbswrap: undefined,
		thumbsviewport: undefined,
		thumbsitems: undefined,
		thumbsitem: undefined,
		slidesinviewport: undefined,
	};

	// init {

	t.init = function ( p ) {

		t.init_getdefaultparam( p );

		t.init_get_browser_features();

		t.init_touch();

		t.init_param_changin();

		// is there a slider element
		if ( jQuery( t.param.sliderid ).length > 0 ) {

			t.init_dependencies();

			// WHEN DEPENDENCIES LOADED, CALL WORKFLOW {

				t.helper.when( t.stat, {
					key: 'dependencies_loaded',
					val: t.stat.dependencies_count,
					callback_if: t.init_workflow,
				} );

			// }
		}
	};

	t.init_get_browser_features = function () {

		t.stat.browser_features = jQuery( 'html' ).attr('class').split(/\s+/);
	},

	t.init_dependencies = function () {

		// LOAD DEPENDECIES IF NOT EXISTS {

			if ( t.param.dependencies_autoload ) {

				// YouTube iframe API {

					if ( typeof YT == 'undefined' && t.param.autoloadyoutubeiframeapi && !yerslider.youtubeloaded ) {

						jQuery.getScript( 'https://www.youtube.com/iframe_api', function() {

							t.stat.dependencies_loaded++;
							yerslider.youtubeloaded = true;
						} );
					}
					else {

						t.stat.dependencies_loaded++;
					}

				// }

				// imagesLoaded {

					if ( typeof jQuery.fn.imagesLoaded == 'undefined' ) {

							t.init_dependencies_path( {

								file: t.param.yersliderfolderabsolutepath + 'dependencies/imagesloaded.js',
							} );
					}
					else {

						t.stat.dependencies_loaded++;
					}

				// }

				// TouchSwipe {

					if ( typeof jQuery.fn.swipe == 'undefined' 
					&& ( jQuery.inArray( 'touch', t.stat.browser_features ) !== -1 || t.param.swipemouse === true )
					&& t.param.swipe === true ) {

						t.init_dependencies_path( {

							file: t.param.yersliderfolderabsolutepath + 'dependencies/jquery.touchSwipe.js',
						} );
					}
					else {

						t.stat.dependencies_loaded++;
					}

				// }

				t.stat.dependencies_count = 3;
			}

		// }

	};

	t.init_dependencies_path = function ( p ) {

		jQuery.ajax( {
			url: p.file,
			dataType: 'script',
			cache: true,
			async: true,
			success: function () {

				t.stat.dependencies_loaded++;
			}
		});
	};

	t.init_workflow = function () {

		t.init_animation();

		t.init_isios();

		t.init_ojects();

		t.init_css();

		t.obj.sliderwrap.css( {
			'opacity': '0',
		} );

		t.set_slidermaskwidth();
		t.set_slidecount();
		t.set_slidegroup();
		t.set_slidegroupmax();
		t.set_slidesinviewport();
		t.set_currentslidesindexs();
		t.set_slidewidth();
		t.set_slideheight();

		t.clon_slides();

		t.set_prevnext();
		
		t.keynav();
		
		t.thumbs();

		t.bullets();

		t.init_detach();

		t.init_touchswipe();

		t.init_iosresizeclickbug();

		t.init_showslides();

		t.init_video();

		t.slides_equal_height();

		t.init_showslider();

		// }
	};

	t.init_slider_ready = function () {

		if ( typeof t.param.sliderready === 'function' ) {

			t.obj.slider.imagesLoaded()
				.always( function( instance ) {

					var p = {
						obj: t.obj,
						param: t.param,
					};

					t.param.sliderready( p );
				});
		}

	};

	t.init_getdefaultparam = function ( p ) {

		/* get default parameters */

		t.param = t.helper.setDefaultParam( {
			p: p,
			d: t.param
		} );

		// get global parameters {

			if ( typeof YerSliderGlobals !== 'undefined' ) {

				jQuery.extend( true, t.param, YerSliderGlobals.param );
			}

		// }

		// VALIDATE AUTOPLAYINTERVAL {

			if ( t.param.autoplaybreak ) {

				t.param.autoplayinterval = t.param.animationspeed + t.param.autoplaybreak;
			}

			if ( t.param.autoplayinterval <= t.param.animationspeed ) {

				t.param.autoplayinterval = t.param.animationspeed + t.param.autoplaybreakmin;
			}

		// }

	};

	t.init_animation = function () {

		/* css animation */

		var obj_html = jQuery('html');

		if ( obj_html.hasClass( 'csstransitions' ) && obj_html.hasClass( 'csstransforms3d' ) && obj_html.hasClass( 'cssanimations' ) && t.param.slidegroup > 0 ) {

			t.stat.cssanimation = true;
		}

	};

	t.init_touch = function () {

		/* touch */

		if ( jQuery('html').hasClass('touch') || t.param.swipemouse === true ) {

			t.stat.touch = true;
			t.stat.clicktype = 'touchend';
		}

		if ( t.stat.touch && t.param.swipe && ! t.param.swipeandprevnextbtn ) {

			t.param.nextbtn = false;
			t.param.prevbtn = false;
		}
	};

	t.init_param_changin = function () {

		// set t.stat.loop {

			t.stat.loop = t.param.loop;

			if ( t.stat.touch && t.param.swipe ) {

				t.stat.loop = t.param.loopswipe;
			}

		// }

		// autoload {

			if ( typeof t.param.dependencies_autoload === 'object' ) {

				t.stat.dependencies_autoload = t.param.dependencies_autoload;
			}

		// }
	};

	t.init_isios = function () {

		/* is iOS */

		if ( navigator.userAgent.match(/(iPod|iPhone|iPad)/) ) {

			t.stat.isios = true;
		}
	};

	t.init_ojects = function () {

		/* define slider objects */

		t.obj.sliderid = jQuery( t.param.sliderid );
		t.obj.sliderwrap = jQuery( t.param.sliderid );
		t.obj.sliderviewport = jQuery( t.param.sliderid + ' ' + t.param.sliderviewportclass );
		t.obj.slidermask = jQuery( t.param.sliderid + ' ' + t.param.slidermaskclass );
		t.obj.slider = jQuery( t.param.sliderid + ' ' + t.param.sliderclass );
		t.obj.slide = jQuery( t.param.sliderid + ' ' + t.param.slideclass );
	};

	t.init_css = function () {

		/* layout slider */

		t.obj.sliderwrap.css({
			position: 'relative',
			//width: '100%'
		});

		t.obj.slidermask.css({
			position: 'relative',
			width: '100%',
			overflow: 'hidden',
		});

		/* remember font-size and line-height for the slides because
		the font-size and line-height of the slider needs to be zero

		var obj_slide_css = [];

			obj_slide_css.fontsize = t.obj.slide.css('font-size');
			obj_slide_css.lineheight = t.obj.slide.css('line-height'); */

		t.obj.slider.css({
			'white-space': 'nowrap',
			position: 'relative',
			'list-style-type': 'none',
			padding: 0,
			margin: 0,
			'line-height': 0,
			'font-size': 0
		});

		t.obj.slide.css({
			display: 'inline-block',
			'white-space': 'normal'/*,
			'font-size': obj_slide_css.fontsize,
			'line-height': obj_slide_css.lineheight*/
		});
	};

	t.init_touchswipe = function () {

		/* touch swipe */

		if ( t.stat.touch && t.param.swipe ) {

			t.touchswipe();
		}
	};

	t.init_iosresizeclickbug = function () {

		/* Resize Click Bug iOS: http://boagworld.com/dev/ios-safari-resizing-issues/ */

		/*t.obj.sliderwrap.on( t.stat.clicktype, function () {

			t.stat.isevent	= true;

			window.setTimeout(function(){

				t.stat.isevent= false;
			}, 200);
		});
		*/
		jQuery(window).resize( function() {

			window.setTimeout(function(){

				if ( !t.stat.isresizing && !t.stat.isevent ) {

					t.resize();
				}
			}, 100);

		});

		var doit;
		jQuery(window).resize( function(){

			clearTimeout(doit);

			doit = setTimeout( function() {

				t.resize_end();

			}, 300 );
		});
	};

	t.init_video = function () {

		// hide play button text {

			jQuery('.yerslider-video-play').wrapInner('<div style="visibility: hidden;">');

		// }

		// set slide index {

		var slide,
			slideindex = 0;

		t.obj.slide.each( function () {

			slide = jQuery( this );
			slide.data('slideindex', ++slideindex );
		});

		// }

		// each video {

			t.obj.slider.find('.yerslider-video').each( function() {

				// default video data {

					var obj = {
							video: {},
							play: {},
							player: {},
							preview: {}
						},
						data = {
							videotype: false,
							videoid: false,
							previewimg: false,
							autoplay: 1,
							showinfo: 0,
							rel: 1,
							ratio: '16:9'
						};

				// }

				// extend data from video element {

					obj.video = jQuery( this );
					jQuery.extend( data, obj.video.data() );

				// }

				// load auto preview image {

				if ( data.previewimg === 'auto' ) {

					if ( data.videotype === 'youtube' ) {

						if ( data.ratio === '16:9' ) {

							obj.video.append('<img class="yerslider-video-preview" src="http://img.youtube.com/vi/' + data.videoid + '/mqdefault.jpg"/>');
						}

						if ( data.ratio === '4:3' ) {

							obj.video.append('<img class="yerslider-video-preview" class="" src="http://img.youtube.com/vi/' + data.videoid + '/0.jpg"/>');
						}
					}

					obj.preview = obj.video.find( '.yerslider-video-preview' );
				}

				// }

				// set play event {

					if ( data.videotype === 'youtube' ) {

						obj.play = obj.video.find( '.yerslider-video-play' );

						obj.play.on('click', function( event ) {

							event.preventDefault();

							// hide preview {

							obj.preview.hide();
							obj.play.hide();

							// }

							// ini player {

								// youtubeready {

									var count = 0,
									timer = setInterval( function() {

										if ( yerslider.youtubeready ) {

											// body {

												// player html {

													obj.video.append('<div id="' + data.videoid + '" class="yerslider-video-player">');
													obj.player = obj.video.find('.yerslider-video-player');

												// }

												// player object {

													t.obj.videoplayers[ data.videoid ] = {
														'type': 'youtube',
														'id': data.videoid,
														'slide': obj.video.parents('.yerslider-slide').data('slideindex'),
														'api': new YT.Player( data.videoid, {
															videoId: data.videoid,
															playerVars: {
																rel: data.rel,
																autoplay: data.autoplay,
																showinfo: data.showinfo,
																wmode: 'opaque',
																events: {
																	'onReady': t.player_fix_ratio( data.videoid )
																}
															}
														}),
														'status': false
													};

													t.obj.videoplayers[ data.videoid ].status = 'started'; 
													t.obj.videoplayers[ data.videoid ].api.addEventListener( 'onStateChange', t.player_youtube_statechange );
												// }

												// set stat video is playing if autoplay = 1 {

													if ( data.autoplay === 1 ) {

														t.stat.videoisplaying = true;
														t.autoplayclear();
													}

												// }

												t.stat.lastplayedvideo = data.videoid;

												// player is in slide {

													if ( typeof t.obj.slides_videoplayers[ slide.data('slideindex') ] === 'undefined' ) {

														t.obj.slides_videoplayers[ slide.data('slideindex') ] = {};
													}

													t.obj.slides_videoplayers[ slide.data('slideindex') ][ data.videoid ] = true;

												// }

											// }

											clearInterval( timer );
										}

										if ( ++count > 600 ) {

											clearInterval( timer );
										}
									}, 100 );

								// }

								t.stat.videoidindex++;

							// }
						});
					}

					else if ( param.videotype === 'vimeo' ) {

					}

					else if ( param.videotype === 'sublimevideo' ) {

					}

				// }
			});

		// }

		/* old stuff {

		// t.obj.videoplayers

				if ( param.videotype === 'youtube' ) {

					var count = 0,
					timer = setInterval( function() {

						if ( yerslider.youtubeready ) {

							// youtubeready body begin

							var intervalvalue = 1,
							playerid = 'playerid' + t.stat.videoidindex++;

							videoobj.attr('id', playerid );

							t.obj.videoplayers[ id ] = {
								'type': 'youtube',
								'id': playerid,
								'slide': slide.data('slideindex'),
								'api': new YT.Player( playerid, {
									videoId: param.youtubeid,
									playerVars: {
										height: '100%',
										width: '100%',
										rel: param.rel,
										autoplay: param.autoplay,
										showinfo: param.showinfo,
										wmode: 'opaque'
									}
								}),
								'status': false
							};

							// youtubeready body end

							clearInterval( timer );
						}

						if ( ++count > 600 ) {

							clearInterval( timer );
						}
					}, 100 );

					t.stat.videoidindex++;
				}

				else if ( param.videotype === 'vimeo' ) {

				}

				else if ( param.videotype === 'sublimevideo' ) {

					sublime.ready(function(){

						// t.obj.videoplayers

						t.obj.videoplayers[ id ] = {
							'type': 'sublimevideo',
							'id': id,
							'slide': slide.data('slideindex'),
							'api': sublime.player( id ),
							'status': false
						};

						t.obj.videoplayers[ id ].api.on({
							start: function(player) {
								t.obj.videoplayers[ id ].status = 'started'; 
								t.stat.lastplayedvideo = id;
							},
							pause: function(player) {
								t.obj.videoplayers[ id ].status = 'paused';
								t.stat.lastplayedvideo = id;
							},
							end:   function(player) {
								t.obj.videoplayers[ id ].status = 'ended';
								t.stat.lastplayedvideo = id;
							}
						});

						t.stat.videoplayerindex++;

						// t.obj.slides_videoplayers

						if ( typeof t.obj.slides_videoplayers[ slide.data('slideindex') ] === 'undefined' ) {

							t.obj.slides_videoplayers[ slide.data('slideindex') ] = {};
						}

						t.obj.slides_videoplayers[ slide.data('slideindex') ][ id ] = true; 
					});
				}

		} */
	};

	t.init_showslider = function () {

		if ( jQuery.inArray( 'slider', t.param.imagesloaded ) !== -1 ) {

			t.obj.slider.imagesLoaded()
				.always( function( instance ) {

					t.init_showslider_job();
				});
		}
		else if ( jQuery.inArray( 'slide', t.param.imagesloaded ) !== -1 ) {

			var count = 0,
				countmax = t.stat.slidegroup;

			for ( var i in t.stat.slidesinviewportindexes ) {

				jQuery( t.obj.slide[ t.stat.slidesinviewportindexes[ i ] - 1 ] ).imagesLoaded()
					.always( function( instance ) {

						count = count + 1;

						if ( count === countmax ) {

							t.init_showslider_job();
						}

					});
			}
		}
		else {

			t.init_showslider_job();
		}

	};

	t.init_showslider_job = function () {

		t.obj.sliderwrap.hide().css( {
			'opacity': '1',
			'position': 'relative',
			'left': '0',
		} ).fadeIn( t.param.sliderfadeinspeed , function() {

			t.init_slider_ready();

			t.autoplayinit();
		});

		t.init_detach_show();

	};

	t.init_showslides = function () {

		/*window.setTimeout(function(){

			t.obj.slider.show();

		}, 200);*/

		/*
		t.stat.isanimating = true;

		window.setTimeout(function(){

			if ( t.stat.cssanimation ) {

				t.css_transitionduration( t.obj.slider, t.param.showslidestime );

				t.obj.slider.fadeIn( 1000 );

				t.css_transitionduration( t.obj.slider );
			}

			else {

				t.obj.slider.fadeIn(t.param.showslidestime);
			}

		}, 5000);

		window.setTimeout(function(){

			t.stat.isanimating = false;

		}, t.param.showslidestime + 100);
		*/
	};

	t.init_detach = function() {

		if ( typeof t.param.detach === 'object' ) {

			// TARGETS {

				if ( typeof t.param.detach.targets === 'object' ) {

					for ( var i in t.param.detach.targets ) {

						/*
						t.param.detach.targets[ i ].selector_wrap
						t.param.detach.targets[ i ].selector_item
						t.param.detach.targets[ i ].insert_selector
						t.param.detach.targets[ i ].insert_method
						t.param.detach.targets[ i ].template_wrap
						t.param.detach.targets[ i ].template_item
						*/

						// DEFAULT INSERT OBJEKT {

							var object = t.obj.sliderwrap;

						// }

						// CUSTOM INSERT OBJECT {

							if ( typeof t.param.detach.targets[ i ].insert_selector === 'string' ) {

								if (
									t.param.detach.targets[ i ].insert_selector.search( /\./ ) === -1
									&& t.param.detach.targets[ i ].insert_selector.search( /\#/ ) == -1
								) {

									if ( t.param.detach.targets[ i ].insert_selector == 'wrap' ) {

										object = t.obj.sliderwrap;
									}

									if ( t.param.detach.targets[ i ].insert_selector == 'viewport' ) {

										object = t.obj.sliderviewport;
									}

									if ( t.param.detach.targets[ i ].insert_selector == 'bullets' ) {

										object = t.obj.bulletswrap;
									}

									if ( t.param.detach.targets[ i ].insert_selector == 'thumbs' ) {

										object = t.obj.thumbswrap;
									}

								}
								else {

									object = jQuery( t.param.detach.targets[ i ].insert_selector );
								}
							}

						// }

						// INSERT TARGET WRAP HTML {

							if (
								typeof t.param.detach.targets[ i ].insert_method === 'string'
								&& typeof t.param.detach.targets[ i ].template_wrap === 'string'
								&& typeof t.param.detach.targets[ i ].template_item === 'string'
							) {

								if ( t.param.detach.targets[ i ].insert_method === 'before' ) {

									object.before( t.param.detach.targets[ i ].template_wrap );
								}

								if ( t.param.detach.targets[ i ].insert_method === 'after' ) {

									object.after( t.param.detach.targets[ i ].template_wrap );
								}

								if ( t.param.detach.targets[ i ].insert_method === 'append' ) {

									object.append( t.param.detach.targets[ i ].template_wrap );
								}

								if ( t.param.detach.targets[ i ].insert_method === 'prepend' ) {

									object.prepend( t.param.detach.targets[ i ].template_wrap );
								}
							}

						// }

					}
				}

			// }

			// SOURCES {

				if ( typeof t.param.detach.sources === 'object' ) {

					var html = {};

					// EACH SLIDE {

						var targets = {};

						t.obj.slide.each( function() {

							var that = jQuery( this ),
								targets_temp = {};

							// SOURCES {

								for ( var i in t.param.detach.sources ) {

									/*
									t.param.detach.sources[ i ].target
									t.param.detach.sources[ i ].selector
									t.param.detach.sources[ i ].source
									t.param.detach.sources[ i ].remove
									t.param.detach.sources[ i ].show
									t.param.detach.sources[ i ].hide
									*/

									// GET TARGET DATA {

										var target_id = t.param.detach.sources[ i ].target_id,
											target = t.param.detach.targets[ target_id ];

									// }

									// DEFINE VARIABLE FOR TARGET HTML  {

										if ( typeof targets_temp[ target_id ] === 'undefined' ) {

											targets_temp[ target_id ] = '';
										}

									// }

									// SET TARGET HTML {

										if ( t.param.detach.sources[ i ].source === 'element' ) {

											targets_temp[ target_id ] += that.find( t.param.detach.sources[ i ].selector ).clone().wrap( '<div>' ).parent().html();
										}
										else {

											targets_temp[ target_id ] += that.find( t.param.detach.sources[ i ].selector ).html;
										}

									// }

									// REMOVE {

										if ( typeof t.param.detach.sources[ i ].remove === 'string' ) {

											that.find( t.param.detach.sources[ i ].remove ).remove();
										}

									// }
								}

							// }

							// TEMPLATE {

								for ( var i in t.param.detach.sources ) {

									var target_id = t.param.detach.sources[ i ].target_id,
										target = t.param.detach.targets[ target_id ];

									var template = target.template_item;

									if ( typeof targets[ target_id ] === 'undefined' ) {

										targets[ target_id  ] = '';
									}

									targets[ target_id  ] += template.replace( '{content}', targets_temp[ target_id ] );  
								}

							// }

						} );

						// INSERT TARGETS HTML {

							for ( var target_id in targets ) {

								var target = t.param.detach.targets[ target_id ],
									obj_html = jQuery( target.selector_wrap ).html();

								jQuery( target.selector_wrap )
									.html( obj_html.replace( '{content}', targets[ target_id ] ) )
									.hide()
									.find( target.selector_item ) 
									.hide();
							}

						// }

					// }

				}

			// }
		}
	};

	t.init_detach_show = function() {

		if ( typeof t.param.detach === 'object' && typeof t.param.detach.targets === 'object' ) {

			for ( var target_id in t.param.detach.targets ) {

				var target = t.param.detach.targets[ target_id ],
					obj_target_wrap = jQuery( target.selector_wrap ),
					obj_target_item = obj_target_wrap.find( target.selector_item ),
					obj_currents = undefined;

				obj_currents = t.init_detach_get_obj_slides_in_viewport();

				obj_currents.show();

				obj_target_wrap.fadeIn( 'slow' );
			}
		}

	};

	t.init_detach_change_item = function() {

		if ( typeof t.param.detach === 'object' && typeof t.param.detach.targets === 'object' ) {

			for ( var target_id in t.param.detach.targets ) {

				var target = t.param.detach.targets[ target_id ],
					obj_target_wrap = jQuery( target.selector_wrap ),
					obj_target_item = obj_target_wrap.find( target.selector_item )
					obj_currents = undefined;

				obj_currents = t.init_detach_get_obj_slides_in_viewport();

				var p = {
					items: obj_target_item,
					items_current: obj_currents
				};

				target.change( p );
			}
		}

	};

	t.init_detach_resize = function() {

		if ( typeof t.param.detach === 'object' && typeof t.param.detach.targets === 'object' ) {

			for ( var target_id in t.param.detach.targets ) {

				var target = t.param.detach.targets[ target_id ],
					obj_target_wrap = jQuery( target.selector_wrap ),
					obj_target_item = obj_target_wrap.find( target.selector_item )
					obj_currents = undefined;

				obj_currents = t.init_detach_get_obj_slides_in_viewport();

				var root = obj_target_item.parents( '.detach-target' ),
					height = root.height();

				root.height( height );
				obj_target_item.hide();
				obj_currents.show();
				root.height( 'auto' );

			}
		}

	};

	t.init_detach_get_obj_slides_in_viewport = function() {

		if ( typeof t.param.detach === 'object' && typeof t.param.detach.targets === 'object' ) {

			for ( var target_id in t.param.detach.targets ) {

				var target = t.param.detach.targets[ target_id ],
					obj_target_wrap = jQuery( target.selector_wrap ),
					query_array = [],
					query = '';

				for ( var i in t.stat.currentslidesindexes ) {

					query_array.push( target.selector_item + ':nth-child(' + t.stat.currentslidesindexes[ i ] + ')' );
				}

				query = query_array.join( ',');
				obj_currents = jQuery( query );

			}

			return jQuery( query );
		}
	};

	// }

	// video players {

	t.player_youtube_play = function ( id ) {

		t.obj.videoplayers[ id ].api.playVideo();
		t.stat.videoisplaying = true;
	};

	t.player_youtube_pause = function ( id ) {

		t.obj.videoplayers[ id ].api.pauseVideo();
		t.stat.videoisplaying = false;
	};

	t.player_youtube_statechange = function ( event ) {

		if ( event.data === 0 ) {

			if ( t.param.videoplayercloseafterend ) {

				t.player_remove();

				if ( t.param.autoplay && t.stat.touch && t.param.swipe ) {

					t.autoplayset();
				}
			}
		}

		if ( event.data === 1 && event.data === -1 ) {

			if ( t.param.autoplay ) {

				t.autoplayclear();
			}
		}

		if ( event.data === 2 ) {

			t.stat.videoisplaying = false;

			if ( t.stat.touch && t.param.autoplay ) {

				t.autoplayset();
			}
		}
	};

	t.player_fix_ratio = function ( id ) {

		if ( typeof id !== 'undefined' ) {

			var obj = jQuery( '#' + id );

			obj.height( obj.width() / 16 * 9 ); 
		}
		else {

			jQuery('.yerslider-video-player').each( function () {

					var obj = jQuery( this );

				jQuery( obj ).height( jQuery( obj ).width() / 16 * 9 ); 
			});
		}
	};

	t.player_remove = function ( id ) {

		t.stat.videoisplaying = false;

		if ( typeof id !== 'undefined' ) {

			jQuery( '#' + id ).parents('.yerslider-video').find('.yerslider-video-preview, .yerslider-video-play').show().find('#' + id).remove();
		}
		else {

			jQuery('.yerslider-video-player').remove();
			jQuery('.yerslider-video-preview, .yerslider-video-play').show();
		}
	};

	// }

	// set something {

	t.set_slidermaskwidth = function () {

		if ( t.param.insidetablecellfix ) {
			t.obj.slider.hide();
			t.obj.slidermask.css('width','100%');
		}

		t.stat.slidermaskwidth = t.obj.slidermask.innerWidth();

		if ( t.param.insidetablecellfix ) {
			t.obj.slidermask.css('width',t.obj.slidermask.width() + 'px');
			t.obj.slider.show();
		}
	};

	t.set_slidecount = function () {

		t.stat.slidecount = t.obj.slide.size();
	};

	t.set_slidegroup = function () {

		// var slidermaskwidth = t.obj.slidermask.innerWidth();

		var temp = t.param.slidegroup;

		if ( t.helper.getLength( t.param.slidegroupresp ) > 0 ) {

			for ( var i in t.param.slidegroupresp ) {

				if ( i <= t.stat.slidermaskwidth ) {

					temp = t.param.slidegroupresp[ i ];
				}
			}
		}

		if ( temp >= t.stat.slidecount ) {

			temp = t.stat.slidecount;
			t.stat.currentslideindex = 0;
			t.move_slider_to_current_index();
		}

		t.stat.slidegroup = temp;

	};

	t.set_slidegroupmax = function () {

		t.stat.slidegroupmax = t.stat.slidegroup;

		for ( var i in t.param.slidegroupresp ) {

			if ( t.stat.slidegroupmax < t.param.slidegroupresp[ i ] ) {

				t.stat.slidegroupmax = t.param.slidegroupresp[ i ];
			}
		}
	};

	t.set_slidewidth = function () {

		/** Not dodo. This commented code calculating the slidewidth including slide padding (border should added).
			But i think it is saver to left the styles of the slide element untouched because the calculation 
			is allways save and correct. Use a inner div and margin to simmulate slide-padding.

			var slidepadding = {};
			slidepadding.left = parseInt( t.obj.slide.css('padding-left'), 10 );
			slidepadding.right = parseInt( t.obj.slide.css('padding-right'), 10 );
			t.stat.slidewidth = Math.floor( ( t.stat.slidermaskwidth - ( ( t.param.slidegap * ( t.stat.slidegroup - 1 ) ) + ( ( slidepadding.left + slidepadding.left ) * t.stat.slidegroup ) ) ) / t.stat.slidegroup );
		*/

		if ( t.stat.slidegroup > 0 ) {

			t.stat.slidewidth = Math.floor( ( t.stat.slidermaskwidth - ( t.param.slidegap * ( t.stat.slidegroup - 1 ) ) ) / t.stat.slidegroup );

			var diff = t.stat.slidermaskwidth - ( ( t.stat.slidewidth * t.stat.slidegroup ) + ( t.param.slidegap * ( t.stat.slidegroup - 1 ) ) );

			t.obj.slide
				.width( t.stat.slidewidth )
				.css( 'margin-right', t.param.slidegap + 'px' );
				//.last().css( 'margin-right', '0' );

			/* streching the width of some slides by 1 pixel to fit the sliderwrapwidth */

			if ( diff > 0 ) {

				for ( var i = 0; i < diff; i++ ) {
					jQuery( t.param.sliderid + ' ' + t.param.slideclass + ':nth-child(' + ( 1 + i )	+ 'n-' + (t.stat.slidegroup - 1) + ')' ).css( 'margin-right', ( t.param.slidegap + 1 ) + 'px' );

				}
			}
		}

		if ( t.stat.slidegroup === 0 ) {

			t.obj.slide.css({
				'width': 'auto',
				'margin-right': t.param.slidegap + 'px'
			});
		}
	};

	t.set_slideheight = function () {

		/** Fix fitVids and slider height
			If the jQuery('.fitvids').fitVids(); is placed after the t.init than the 
			timeout is needet to result the fitted video height in the slider height. 
			The jQuery('.fitvids').fitVids(); has to be inside the jQuery(document).ready…

			window.setTimeout( function () {

				t.obj.slide.height('auto');

				var height = t.obj.slider.height();
				t.obj.slide.height( height );

			}, 0);
		*/
	};

	t.set_slidesinviewport = function () {

		t.stat.slidesinviewportindexbegin = t.stat.currentslideindex;
		t.stat.slidesinviewportindexend = t.stat.currentslideindex + ( t.stat.slidegroup - 1 );

		t.stat.slidesinviewportindexes = [];

		for ( i = 0; i < ( t.stat.slidecount + ( t.stat.slidegroup * 2 ) ); i++ ) {

			if ( i >= t.stat.slidesinviewportindexbegin && i <= t.stat.slidesinviewportindexend ) {

				var ii = i;

				if ( ii >= t.stat.slidecount ) {

					ii = ii - t.stat.slidecount;
				}

				t.stat.slidesinviewportindexes.push( ii + 1 );
			}
		}

		t.stat.currentslidesindexes = [];

		for ( i = 0; i < ( t.stat.slidecount + ( t.stat.slidegroup * 2 ) ); i++ ) {

			if ( i >= t.stat.slidesinviewportindexbegin && i <= t.stat.slidesinviewportindexend ) {

				var ii = i;

				t.stat.currentslidesindexes.push( ii + 1 );
			}
		}
	};

	t.set_currentslidesindexs = function () {

		t.stat.currentslidesindexes = [];

		for ( i = 0; i < ( t.stat.slidecount + ( t.stat.slidegroup * 2 ) ); i++ ) {

			if ( i >= t.stat.slidesinviewportindexbegin && i <= t.stat.slidesinviewportindexend ) {

				var ii = i;

				t.stat.currentslidesindexes.push( ii + 1 );
			}
		}
	};
	// }

	// prev next {

	t.set_prevnext = function () {

		/* init */
		if ( t.stat.slidecount > t.stat.slidegroup ) {

			var nextclassadd = '',
				prevclassadd = '';

			if ( typeof t.obj.nextbtn !== 'object' && t.param.nextbtn ) {

				if ( t.param.nextclassadd !== '' ) {

					nextclassadd = ' ' + t.param.nextclassadd.replace( '.', '' );
				}

				var html = '<div class="js-yerslider-next ' + t.param.prevnextclass.replace( '.', '' ) + ' ' + t.param.nextclass.replace( '.', '' ) + nextclassadd + '">';

				if ( t.param.prevnextlocation === 'inside' ) {

					t.obj.sliderviewport.append( html );
				}

				if ( t.param.prevnextlocation === 'outside' ) {

					t.obj.sliderwrap.append( html );
				}

				t.obj.nextbtn = jQuery( t.param.sliderid + ' ' + t.param.nextclass );
			}

			if ( typeof t.obj.prevbtn !== 'object' && t.param.prevbtn ) {

				if ( t.param.prevclassadd !== '' ) {

					prevclassadd = ' ' + t.param.prevclassadd.replace( '.', '' );
				}

				var html = '<div class="js-yerslider-prev ' + t.param.prevnextclass.replace( '.', '' ) + ' ' + t.param.prevclass.replace( '.', '' ) + prevclassadd + '">';

				if ( t.param.prevnextlocation === 'inside' ) {

					t.obj.sliderviewport.append( html );
				}

				if ( t.param.prevnextlocation === 'outside' ) {

					t.obj.sliderwrap.append( html );
				}

				t.obj.prevbtn = jQuery( t.param.sliderid + ' ' + t.param.prevclass );
			}

			t.refresh_prevnext();
		}

		/* remove */
		else {

			if ( typeof t.obj.nextbtn === 'object' ) {
				t.obj.nextbtn.remove();
				t.obj.nextbtn = undefined;
				t.stat.nextbtnclickable = false;
			}
			if ( typeof t.obj.prevbtn === 'object' ) {
				t.obj.prevbtn.remove();
				t.obj.prevbtn = undefined;
				t.stat.prevbtnclickable = false;
			}
		}

	};

	t.next_slide = function () {

		if ( t.param.slidingstep ) {

			t.stat.currentslideindex = t.stat.currentslideindex + t.param.slidingstep;
		}
		else {

			t.stat.currentslideindex = t.stat.currentslideindex + t.stat.slidegroup;
		}

	};

	t.prev_slide = function () {

		if ( t.param.slidingstep ) {

			t.stat.currentslideindex = t.stat.currentslideindex - t.param.slidingstep;
		}
		else {

			t.stat.currentslideindex = t.stat.currentslideindex - t.stat.slidegroup;
		}
	};

	t.next_job = function () {

		if ( ! t.stat.isanimating ) {

			t.stat.isanimating = true;
			t.stat.slidingright = true;

			t.next_slide();

			// TASK {

				t.task_slide();

			// }

		}
	};

	t.prev_job = function () {

		if ( ! t.stat.isanimating ) {

			t.stat.isanimating = true;
			t.stat.slidingleft = true;

			t.prev_slide();

			// JOB {

				t.task_slide();

			// }

		}
	};

	t.nextbtn_click = function () {

		if ( t.obj.nextbtn && ! t.stat.nextbtnclickable ) {

			t.obj.nextbtn.on( t.stat.clicktype, function () {

				t.stat.lasteventtype = 'click-next';
				t.next_job();
			});

			t.stat.nextbtnclickable = true;
		}
	};

	t.prevbtn_click = function () {

		if ( t.obj.prevbtn && !t.stat.prevbtnclickable ) {

			t.obj.prevbtn.on( t.stat.clicktype, function () {

				t.stat.lasteventtype = 'click-prev';
				t.prev_job();
			});

			t.stat.prevbtnclickable = true;
		}

	};

	t.nextbtn_click_unbind = function () {

		t.obj.nextbtn.unbind( 'click' )
			.addClass( t.param.nextinactiveclass.replace( '.', '' ) );

		t.stat.nextbtnclickable = false;
	};

	t.prevbtn_click_unbind = function () {

		t.obj.prevbtn.unbind( 'click' )
			.addClass( t.param.previnactiveclass.replace( '.', '' ) );

		t.stat.prevbtnclickable = false;
	};

	t.refresh_prevnext = function () {

		/* bind click events if unbinded in general */

		if ( !t.stat.nextbtnclickable ) {

			t.nextbtn_click();
		}

		if ( !t.stat.prevbtnclickable ) {

			t.prevbtn_click();
		}

		/* remove inactive classes in general */

		if ( t.obj.nextbtn ) {

			t.obj.nextbtn.removeClass( t.param.nextinactiveclass.replace( '.', '' ) );
		}

		if ( t.obj.prevbtn ) {

			t.obj.prevbtn.removeClass( t.param.previnactiveclass.replace( '.', '' ) );
		}

		/* then unbind in some kind of slider situation */

		if ( t.stat.loop === 'none' ) {

			if ( t.obj.nextbtn && t.stat.currentslideindex >= ( t.stat.slidecount - t.stat.slidegroup ) ) {

				t.nextbtn_click_unbind();
			}

			if ( t.obj.prevbtn && t.stat.currentslideindex <= 0 ) {

				t.prevbtn_click_unbind();
			}
		}
	};

	t.set_slide_current_class = function () {

		t.obj.slide.removeClass('current');
		jQuery( t.obj.slide[ t.stat.currentslideindex ] ).addClass('current');

	};
	
	t.keynav = function () {

	    jQuery( document ).on( 'keyup', function ( event ) {

			if ( event.keyCode === 39 ) {
				
				t.stat.lasteventtype = 'click-next';
				t.next_job();
			}
			
			if ( event.keyCode === 37 ) {
				
				t.stat.lasteventtype = 'click-prev';
				t.prev_job();
			}
		});
	};

	/*
	t.pausevideosofcurrentslide = function () {

		for ( var i in t.obj.videoplayers ) {

			if ( jQuery.inArray( t.obj.videoplayers[ i ].slide, t.stat.slidesinviewportindexes ) !== -1 ) {

				if ( t.obj.videoplayers[ i ].type === 'youtube' ) {

					t.player_youtube_pause( i );
				}

				if ( t.obj.videoplayers[ i ].type === 'sublimevideo' ) {

					t.obj.videoplayers[ i ].api.pause();
				}
			}
		}
	};

	t.playlastplayedvideoofcurrentslide = function () {

		for ( var i in t.obj.videoplayers ) {

			if ( jQuery.inArray( t.obj.videoplayers[ i ].slide, t.stat.slidesinviewportindexes ) !== -1 && i === t.stat.lastplayedvideo ) {

				if ( t.obj.videoplayers[ i ].type === 'youtube' ) {

					t.player_youtube_play( i );
				}

				if ( t.obj.videoplayers[ i ].type === 'sublimevideo' ) {

					t.obj.videoplayers[ i ].api.play();
				}
			}
		}

		t.stat.lastplayedvideo = false;
	};
	*/

	// }

	// autoplay {

	t.autoplayinit = function () {

		if ( t.param.autoplay ) {

			t.autoplayset();

			if ( t.param.autoplaystoponhover ) {

				t.autoplayhover();
			}
		}

	};

	t.autoplayset = function () {

		if ( t.stat.autoplayison === false ) {

			// if autoplay continuously and CSS animation is possible
			if ( t.param.autoplaycontinuously && t.stat.cssanimation ) {

				t.css_animation( t.obj.slider, 'slideshow ' + Math.round( ( t.param.autoplaycontinuouslyspeed * t.stat.slidecount ) / 1000 ) + 's linear infinite' );
				t.css_transform( t.obj.slider, 'translate3d(0, 0, 0)' );

				t.obj.sliderwrap.prev('style').remove();
				t.obj.sliderwrap.before('<style>' + t.css_keyframes( ( t.stat.slidewidth * t.stat.slidecount ) ) + '</style>');

				if ( t.param.autoplaycontinuouslystoponhover ) {

					t.obj.sliderwrap.on( 'mouseenter', function() {

						t.css_animation_play_state( t.obj.slider, 'paused' );
					});

					t.obj.sliderwrap.on( 'mouseleave', function() {

						t.css_animation_play_state( t.obj.slider, 'running' );
					});
				}

			}

			// if not autoplay continuously or autoplay continuously fallback if CSS animation is not possible
			if ( !t.param.autoplaycontinuously || !t.stat.cssanimation ) {

				t.stat.autoplayison = true;

				window.setTimeout( function() {

					if ( t.stat.autoplayison ) { // could be set to false while timeout by t.autoplayclear()

						t.stat.autoplayinterval = window.setInterval( function () {

							if ( ! t.stat.isanimating ) {
								t.stat.isanimating = true;
								t.stat.slidingright = true;
								t.stat.lasteventtype = 'autoplay';
								t.next_slide();

								t.task_slide();
							}

						}, t.param.autoplayinterval );
					}

				}, t.param.autoplaydelaystart );
			}
		}
	};

	t.autoplayclear = function () {

		t.stat.autoplayison = false;

		t.stat.autoplayinterval = clearInterval( t.stat.autoplayinterval );
	};

	t.autoplayhover = function () {

		// slidermask

		jQuery( t.obj.slider ).on( 'mouseenter', function() {

			if ( ! t.stat.videoisplaying ) {

				t.autoplayclear();
			}

		}).on( 'mouseleave', function() {

			if ( ! t.stat.videoisplaying ) {

				t.autoplayset();
			}
		});

		// prevnextclass

		jQuery( t.obj.sliderwrap.find( t.param.prevnextclass ) ).mouseenter(function() {

			if ( ! t.stat.videoisplaying ) {

				t.autoplayclear();
			}

		}).on( 'mouseleave', function() {

			if ( ! t.stat.videoisplaying ) {

				t.autoplayset();
			}
		});

		// bulletclass

		if ( typeof t.obj.bulletswrap === 'object' ) {

			t.obj.bulletswrap.mouseenter(function() {

				if ( ! t.stat.videoisplaying ) {

					t.autoplayclear();
				}

			}).on( 'mouseleave', function() {

				if ( ! t.stat.videoisplaying ) {

					t.autoplayset();
				}
			});
		}

	};

	// }

	// bullets {

	t.bullets = function () {

		if ( t.param.bullets ) {

			//window.setTimeout(function(){

				/* add bullet class to wrap */

				t.obj.sliderwrap.addClass( t.param.sliderwrapclasshasbullets.replace( '.', '' ) );

				/* do bullets-wrap html and object */

				if ( typeof t.obj.bulletswrap !== 'object' ) {

					var html = '<div class="' + t.param.bulletswrapclass.replace( '.', '' ) + '"></div>';

					if ( t.param.bulletslocation === 'inside' ) {

						t.obj.sliderviewport.append( html );
					}

					if ( t.param.bulletslocation === 'outside' ) {

						t.obj.sliderwrap.append( html );
					}

					t.obj.bulletswrap = t.obj.sliderwrap.find( t.param.bulletswrapclass );
				}

				/* get amount of bullets */

				t.stat.bulletscount = Math.ceil( t.stat.slidecount / t.stat.slidegroup );

				/* current bullet index */

				t.set_bullet_current();

				/* bullet items */

				t.bullet_items();

				/* bullet current class */

				t.set_bullet_current_class();

				/* bullets click */

				t.bullet_click();

			//}, 200);

		}
	};

	t.bullet_items = function () {

		if ( t.param.bullets ) {

			/* do bullets html and object */

			if ( t.stat.bulletscountcache !== t.stat.bulletscount ) {

				var bullets = '';

				for ( var i = 1; i <= t.stat.bulletscount; i++ ) {

					bullets += '<div class="' + t.param.bulletclass.replace( '.', '' ) + '" data-index="' + i + '"></div>';
				}

				t.obj.bulletswrap.empty();

				if ( t.stat.bulletscount > 1 ) {

					t.obj.bulletswrap.append( bullets );
				}

				t.stat.bulletscountcache = t.stat.bulletscount;
			}

			t.obj.bullets = t.obj.bulletswrap.find( t.param.bulletclass );

			t.set_bullet_current_class();

		}
	};

	t.set_bullet_current = function () {

		if ( t.param.bullets ) {

			var currentslideindex = t.stat.currentslideindex;

			/* translate clone current slide index into original index */

			if ( currentslideindex + 1 > t.stat.slidecount ) {

				currentslideindex = currentslideindex - t.stat.slidecount;
			}

			/* current bullet index */

			if ( t.stat.loop === 'none' ) {

				t.stat.bulletcurrent = Math.ceil( currentslideindex / t.stat.slidegroup ) + 1;
			}
			else {

				t.stat.bulletcurrent = Math.round( currentslideindex / t.stat.slidegroup ) + 1;

				if ( t.stat.bulletcurrent > t.stat.bulletscount ) {

					t.stat.bulletcurrent = t.stat.bulletscount;
				}
			}
		}
	};

	t.set_bullet_current_class = function () {

		if ( t.param.bullets ) {

			/* current bullet class */

			t.obj.bullets.removeClass( t.param.bulletcurrentclass.replace( '.', '' ) );

			t.obj.bulletswrap.find('[data-index="' + t.stat.bulletcurrent + '"]').addClass( t.param.bulletcurrentclass.replace( '.', '' ) );
		}

	};

	t.bullet_click = function () {

		if ( t.param.bullets ) {

			t.obj.bullets.on( 'click', function () {

				if ( ! t.stat.isanimating ) {

					t.stat.isanimating = true;
					t.stat.slidingright = true;

					var currentbullet = jQuery(this).data('index');

					t.stat.currentslideindex = ( currentbullet - 1 ) * t.stat.slidegroup;

					// JOB {

						t.task_slide();

					// }

				}

			});
		}
	};

	// }

	// thumbs {

	t.thumbs = function () {

		var check = true;

		if ( t.obj.slide.length < t.param.thumbshideiflessthan ) {

			check = false;
		}

		if ( t.param.thumbs && check ) {

			//window.setTimeout(function(){

				/* add thumbs class to wrap */

				t.obj.sliderwrap.addClass( t.param.sliderwrapclasshasthumbs.replace( '.', '' ) );

				/* do thumbs-wrap html and object */

				if ( typeof t.obj.thumbswrap !== 'object' ) {

					var html = '<div class="' + t.param.thumbswrapclass.replace( '.', '' ) + '"><div class="' + t.param.thumbsviewportclass.replace( '.', '' ) + '"><div class="' + t.param.thumbsmaskclass.replace( '.', '' ) + '"><div class="' + t.param.thumbsitemsclass.replace( '.', '' ) + '"></div></div></div></div>';

					if ( t.param.thumbslocation === 'inside' ) {

						t.obj.sliderviewport.append( html );
					}

					if ( t.param.thumbslocation === 'outside' ) {

						t.obj.sliderwrap.append( html );
					}

					t.obj.thumbswrap = t.obj.sliderwrap.find( t.param.thumbswrapclass );
					t.obj.thumbsviewport = t.obj.sliderwrap.find( t.param.thumbsviewportclass );
					t.obj.thumbsmask = t.obj.sliderwrap.find( t.param.thumbsmaskclass );
					t.obj.thumbsitems = t.obj.sliderwrap.find( t.param.thumbsitemsclass );

				}

				/* thumbs items */

				t.thumbs_items();

				t.set_thumbs_current_class();

				/* thumbs click */

				t.thumbs_click();

				/* thumbs hide and fade in and check images loaded */

				t.obj.thumbsviewport.hide();

				if ( jQuery.inArray( 'thumbs', t.param.imagesloaded ) !== -1 ) {

					t.obj.thumbsviewport.imagesLoaded()
						.always( function( instance ) {

							t.obj.thumbsviewport.fadeIn( 'slow', function() {

								/* thumbs script */

								t.thumbs_script();

								/* thumbs autoplay */

								t.thumbs_autoplay();

							} );
						})
				}
				else {

					t.obj.thumbsviewport.fadeIn( 'slow', function() {

						/* thumbs script */

						t.thumbs_script();

						/* thumbs autoplay */

						t.thumbs_autoplay();

					} );
				}

			//}, 200);

		}
	};

	t.thumbs_items = function () {

		if ( t.param.thumbs ) {

			var i = 0;

			t.obj.slide.each( function() {

				if ( i++ < t.stat.slidecount ) {

					var obj_slide = jQuery( this ),
						template_key = obj_slide.data( 'thumb-template-key' ),
						template_html = '',
						thumb_html = '',
						thumb_class = '';

					// be sure, there is a themplate_key and an belonging object of thumbtemplate
					if (
						template_key
						&& typeof t.param.thumbstemplates[ template_key ] === 'object'
						&& typeof t.param.thumbstemplates[ template_key ].html !== 'undefined'
					) {

						thumb_html = '';
						thumb_class = '';
						thumb_ = '';
						placeholder_arr = false;

						if ( t.param.thumbstemplates[ template_key ].html ) {

							template_html = t.param.thumbstemplates[ template_key ].html;

							// if class
							if ( t.param.thumbstemplates[ template_key ].cssclass ) {

								thumb_class = ' ' + t.param.thumbstemplates[ template_key ].cssclass;
							}

							// get the placeholders from the template in an array
							placeholder_arr = t.get_placeholder_of_string( template_html );

							// replace placeholders with data
							if ( placeholder_arr.length > 0 ) {

								placeholder_arr.map( function( placeholder ) {

									var value = obj_slide.data( placeholder );

									if ( ! value ) {

										value = '';
									}

									template_html = template_html.replace( '{{' + placeholder + '}}', value );
								});
							}

							// build thumb html
							thumb_html += '<div class="' + t.param.thumbsitemclass.replace( '.', '' ) + thumb_class + '">';
							thumb_html += template_html;
							thumb_html += '</div>';

							t.obj.thumbsitems.append( thumb_html );

							t.obj.thumbsitem = t.obj.sliderwrap.find( t.param.thumbsitemclass );
						}
					}
				}

			});

		}
	};

	t.set_thumbs_current_class = function () {

		if ( t.param.thumbs && typeof t.obj.thumbsitem == 'object' ) {

			t.obj.thumbsitem.removeClass( 'thumb-slidegroup-current' );
			t.obj.thumbsitem.removeClass( 'thumb-current' );

			for ( var i in t.stat.slidesinviewportindexes ) {

				jQuery( t.obj.thumbsitem[ ( t.stat.slidesinviewportindexes[ i ] - 1 ) ] ).addClass( 'thumb-slidegroup-current' );
			}

			jQuery( t.obj.thumbsitem[ t.stat.currentslideindex ] ).addClass( 'thumb-current' );
		}
	};

	t.thumbs_click = function () {

		if ( t.param.thumbs ) {

			// clickevent {

				t.obj.thumbswrap.on( 'click', t.param.thumbsitemclass, function ( ) {

					t.stat.lasteventtype = 'thumb-click';

					// setup {

						var thumb_obj = jQuery( this );

					// }

					// get index of thumb {

						var thumb_index = thumb_obj.index();

					// }

					// get slide object {

						// var slide_obj = t.obj.sliderwrap.find( t.param.slideclass + ':nth-child( ' + ( thumb_index + 1 ) + ')' );

					// }

					// get slide position {

						// var slide_offset_left = slide_obj[ 0 ].offsetLeft;

					// }

					// move slide in slider viewort {

						if ( ! t.stat.isanimating ) {

							t.stat.isanimating = true;
							t.stat.slidingright = true;

							t.stat.currentslideindex = thumb_index;

							// JOB {

								t.task_slide();

							// }

						}

					// }

				} );

			// }

		}

	};

	t.thumbs_script = function () {

		if ( t.param.thumbs ) {

			if ( t.param.thumbsready ) {

				var p = {};
					p.obj = t.obj;
					p.param = {
						touch: t.stat.touch
					};

				t.param.thumbsready( p );

			}
		}
	};

	t.thumbs_autoplay = function () {

		if ( t.param.autoplay && typeof t.obj.thumbswrap === 'object' ) {

			t.obj.thumbsmask.on( 'mouseenter', function( e ) {

				// e.originalEvent !== 'undefined' prevents triggering this event by
				//  other event used in t.param.thumbsready( p )
				if ( ! t.stat.videoisplaying && typeof e.originalEvent !== 'undefined' ) {

					t.autoplayclear();
				}

			}).on( 'mouseleave', function() {

				if ( ! t.stat.videoisplaying ) {

					t.autoplayset();
				}
			});
		}
	};

	// }

	// animation {

	t.move_slider_to_current_index = function () {

		if ( t.stat.slidecount > 1 ) {

			if ( t.stat.cssanimation ) {

				t.css_transitionduration( t.obj.slider, 0 );
				t.css_transform( t.obj.slider, t.get_sliderposition() * -1 );
			}
			else {

				t.obj.slider.css({
					'margin-left': '-' + t.get_sliderposition() + 'px'
				});
			}
		}
	};

	t.animate_slider_to_current_position = function ( duration) {

		if ( t.stat.cssanimation ) {

			t.animate_slider_to_current_position_css( duration );
		}
		else {

			t.animate_slider_to_current_position_js( duration );
		}
	};

	t.animate_slider_to_current_position_js = function ( duration ) {

		t.obj.slider.animate( {
			'margin-left': '-' + t.get_sliderposition() + 'px'
		}, duration, t.translate_easing( t.param.animationtype, 'jquery' ), function () {

			t.get_sliderposition();
			t.stat.isanimating = false;
		});

	};

	t.animate_slider_to_current_position_css = function( duration ) {

		var sliderposition = t.get_sliderposition() * -1;

		t.css_transitionduration( t.obj.slider, duration );
		t.css_transform( t.obj.slider, sliderposition );
		t.css_transitiontiming( t.obj.slider, t.param.animationtype );
		//t.css_marginleft( t.obj.slider );

		window.setTimeout( function () {

			t.get_sliderposition();
			t.stat.isanimating = false;
			t.animation_finshed();

		}, duration );
	};

	t.animation_finshed = function () {

	};

	t.video_load = function () {

	};

	t.scroll_slider = function ( distance, direction ) {

		var sliderposition = t.stat.currentslideposition * -1,
			gotopos = sliderposition;

		if ( direction === 'left' ) {

			gotopos = ( sliderposition - Math.abs(distance) );
		}

		if ( direction === 'right' ) {

			gotopos = ( sliderposition + Math.abs(distance) );
		}

		t.css_transitionduration( t.obj.slider );

		t.css_transform( t.obj.slider, gotopos );

		t.css_transitiontiming( t.obj.slider, t.param.animationtype );

	};

	t.task_slide = function () {

		// VIDEO {

			t.player_remove();

			// t.pausevideosofcurrentslide();

			// t.playlastplayedvideoofcurrentslide();

			// t.playlastplayedvideoofcurrentslide();

		// }

		// SCROLLTOP {

			if ( t.param.scrolltop ) {

				jQuery('body').animate({

					scrollTop: t.param.scrolltopval
				}, t.param.scrolltopspeed );
			}

		// }

		// CHECK CURRENT SLIDE INDEX {

			t.check_slider_current_index();

		// }

		// SET SLIDES IN VIEWPORT {

			t.set_slidesinviewport();

		// }

		// SLIDE & CHECK IMAGES LOADED {

			if ( jQuery.inArray( 'slide', t.param.imagesloaded ) !== -1 ) {

				// check if images loaded in slides of viewport

				t.stat.loadingtimeout = window.setTimeout( function () {

					t.obj.sliderviewport.append( '<div class="' + t.param.loadingclass.replace( '.', '' ) + '"></div>' );
					t.stat.isloading = true;

				}, t.param.loadingmessagedelay );

				var count = 0,
					countmax = t.stat.slidegroup;

				for ( var i in t.stat.slidesinviewportindexes ) {

					jQuery( t.obj.slide[ t.stat.slidesinviewportindexes[ i ] - 1 ] )
					.imagesLoaded()
					.always( function( instance ) {

						count = count + 1;

						if ( count === countmax ) {

							t.task_slide_job();

							clearTimeout( t.stat.loadingtimeout );

							if ( t.stat.isloading ) {

								t.obj.sliderviewport.find( t.param.loadingclass ).remove();

								t.stat.isloading = false;
							}

						}
					});
				}
			}
			else {

				t.task_slide_job();
			}

		// }

	};

	t.task_slide_job = function () {

		// ANIMATION {

			t.animate_slider_to_current_position( t.get_animationspeed() ); // OK

		// }

		// PREV NEXT CLICKABLE {

			if ( ! t.stat.prevbtnclickable ) {

				t.prevbtn_click();
			}

		// }

		// RESET STATS {

			t.stat.slidingright = false;
			t.stat.slidingleft = false;

		// }

		// PREV NEXT REFRESH {

			t.refresh_prevnext();

		// }

		// BULLETS {

			if ( t.param.bullets ) {

				t.set_bullet_current();
				t.set_bullet_current_class();
			}

		// }

		// THUMBS {

			t.set_thumbs_current_class();

		// }

		// DETACH {

			t.init_detach_change_item();

		// }
	};

	// }

	// misc {

	t.clon_slides = function () {

		if ( ! t.stat.touch || ( t.stat.touch && t.stat.loop === 'infinite' ) || ( t.stat.touch && t.param.autoplaycontinuously === true ) ) {

			var index = 0,
				i = 0
				clones = t.stat.slidegroupmax;

				t.param.slidingstep

			if ( t.stat.slidegroup > 0 ) {

				clones = t.stat.slidegroupmax * 2;
			}

			if ( t.param.slidingstep > t.stat.slidegroup ) {

				clones = ( t.param.slidingstep * 2 ) + ( t.stat.slidegroupmax * 2 );
			}

			for ( i = 0; i < clones; i++ ) {

				// start from first slide when last slide passed
				if ( index > t.stat.slidecount ) {

					index = 0;
				}

				t.obj.slider.append( jQuery( t.obj.slide[ index ] ).clone() );

				index++;
			}

			t.obj.slide = jQuery( t.param.sliderid + ' ' + t.param.slideclass );
		}
	};

	t.get_sliderposition = function () {

		//var pos = ( parseInt( t.stat.currentslideindex * t.stat.slidewidth, 10 ) + parseInt( t.param.slidegap * t.stat.currentslideindex, 10 ) );		
		var pos = jQuery( t.obj.slide[ t.stat.currentslideindex ] ).position().left;

		t.stat.currentslideposition = pos;

		return pos;
	};

	t.get_animationspeed = function () {

		var speed = t.param.animationspeed;

		if ( t.stat.isswiping ) {

			speed = t.param.swipeanimationspeed;
		}

		return speed;
	};

	t.proof_slider_current_index = function () {

		if ( t.stat.slidecount - t.stat.slidegroup > 0 && t.stat.currentslideindex >= t.stat.slidecount - t.stat.slidegroup ) {

			t.stat.currentslideindex = t.stat.slidecount - t.stat.slidegroup;

			if ( t.param.nextbtnclickable ) {

				t.nextbtn_click_unbind();
			}
		}

	};

	t.check_slider_current_index = function () {

		// THUMB CLICK {

			if ( t.stat.lasteventtype === 'thumb-click' ) {

				if ( t.stat.loop !== 'infinite' ) {

					if ( t.stat.currentslideindex >= t.stat.slidecount - t.stat.slidegroup ) {

						t.stat.currentslideindex = t.stat.slidecount - t.stat.slidegroup;
					}
				}
			}

		// }

		// NEXT {

			if ( t.stat.lasteventtype === 'click-next'
			|| t.stat.lasteventtype === 'autoplay'
			|| t.stat.lasteventtype === 'swipe-left' ) {

				if ( t.stat.loop === 'none' ) {

					if ( t.stat.currentslideindex >= t.stat.slidecount - t.stat.slidegroup ) {

						t.stat.currentslideindex = t.stat.slidecount - t.stat.slidegroup;

						if ( t.param.autoplay ) {

							t.autoplayclear();
						}
					}

				}

				if ( t.stat.loop === 'rollback' ) {

					if ( t.param.slidingstep ) {

						if ( t.stat.lasteventtype === 'click-next'
						|| t.stat.lasteventtype === 'autoplay'
						|| t.stat.lasteventtype === 'swipe-right' ) {

							if ( t.stat.currentslideindex === t.stat.slidecount
							|| t.stat.currentslideindex === t.stat.slidecount - t.stat.slidegroup + t.param.slidingstep ) {

								t.stat.currentslideindex = 0;
							}

							var leftover = 0;

							if ( t.stat.slidegroup > t.param.slidingstep ) {

								leftover = t.stat.slidegroup - t.param.slidingstep + 1;
							}

							if ( t.stat.currentslideindex >= t.stat.slidecount - leftover ) {

								t.stat.currentslideindex = t.stat.slidecount - t.stat.slidegroup;
							}

						}

					}
					else {

						if ( t.stat.lasteventtype === 'click-next'
						|| t.stat.lasteventtype === 'autoplay' 
						|| t.stat.lasteventtype === 'swipe-right' ) {

							if ( t.stat.currentslideindex == t.stat.slidecount ) {

								t.stat.currentslideindex = 0;
							}

							if ( t.stat.currentslideindex >= t.stat.slidecount - t.stat.slidegroup ) {

								t.stat.currentslideindex = t.stat.slidecount - t.stat.slidegroup;
							}
						}
					}

				}

				if ( t.stat.loop === 'infinite' ) {

					if ( t.stat.lasteventtype === 'click-next'
					|| t.stat.lasteventtype === 'autoplay'
					|| t.stat.lasteventtype === 'swipe-right' ) {

						/* step is the amount of slides to move */
						var steps = 0;

						if (  t.param.slidingstep ) {

							steps = t.param.slidingstep;
						}
						else {

							steps = t.stat.slidegroup;
						}

						/* if the last slide index was a clone, you can jump back */
						if ( t.stat.currentslideindex - steps > t.stat.slidecount - 1 ) {

							t.stat.currentslideindex = t.stat.currentslideindex - ( Math.floor( ( t.stat.currentslideindex - steps ) / t.stat.slidecount ) * t.stat.slidecount ) - steps;

							t.move_slider_to_current_index();

							t.stat.currentslideindex = t.stat.currentslideindex + steps;
						}

					}

				}
			}

		// }

		// PREV {

			if ( t.stat.lasteventtype === 'click-prev'
			|| t.stat.lasteventtype === 'swipe-right' ) {

				if ( t.stat.loop === 'none' ) {

					if ( t.stat.currentslideindex <= 0 ) {

						t.stat.currentslideindex = 0;
					}
				}

				if ( t.stat.loop === 'rollback' ) {

					if ( t.param.slidingstep ) {

						if ( t.stat.slidingleft && t.stat.currentslideindex == ( 0 - t.param.slidingstep ) ) {

							t.stat.currentslideindex = t.stat.slidecount - t.stat.slidegroup;
						}
					}
					else {

						if ( t.stat.slidingleft && t.stat.currentslideindex == ( 0 - t.stat.slidegroup ) ) {

							t.stat.currentslideindex = t.stat.slidecount - t.stat.slidegroup;
						}
					}

					if ( t.stat.currentslideindex < 0 ) {

						t.stat.currentslideindex = 0;
					}

				}

				if ( t.stat.loop === 'infinite' && t.stat.currentslideindex < 0 ) {

					var steps = 0;

					if (  t.param.slidingstep ) {

						steps = t.param.slidingstep;
					}
					else {

						steps = t.stat.slidegroup;
					}
					if ( t.stat.currentslideindex < 0 ) {

					t.stat.currentslideindex = t.stat.currentslideindex + ( Math.ceil( steps / t.stat.slidecount ) * t.stat.slidecount ) + steps;

						t.move_slider_to_current_index();

						t.stat.currentslideindex = t.stat.currentslideindex - steps;
					}
				}
			}

		// }

	};

	t.resize = function () {

		t.stat.resizing = true;

			if ( t.stat.isios ) {

				t.stat.isresizing = true;
				//t.obj.slider.fadeOut();
			}

			t.set_slidermaskwidth();
			t.set_slidegroup();
			t.set_slidesinviewport();
			t.set_slidewidth();
			t.set_slideheight();
			t.proof_slider_current_index();
			t.animate_slider_to_current_position( 0 );

			t.set_prevnext();

			if ( t.param.bullets ) {

				t.bullets();
			}

			t.player_fix_ratio();

			t.slides_equal_height();

			t.set_thumbs_current_class();

		t.stat.resizing = false;

		if ( t.stat.isios ) {

			/*t.obj.slider.fadeIn( 'fast', function () {

			});*/
			t.stat.isresizing = false;
		}

		t.init_detach_resize();

	};

	t.resize_end = function () {

	};

	t.touchswipe = function () {

		/**
		* Catch each phase of the swipe.
		* move : we drag the div.
		* cancel : we animate back to where we were
		* end : we animate to the next image
		*/
		function swipeStatus( event, phase, direction, distance/*, fingers*/ ) {

			if ( t.stat.isswiping === false && t.stat.isanimating === false ) {
				
				t.stat.isswiping = true;
				
				if ( phase === 'start' ) {

					event.preventDefault();
				}
				
				//If we are moving before swipe, and we are going L or R, then manually drag the images

				else if ( phase === 'move' && ( direction === 'left' || direction === 'right' ) ) {

					if ( direction === 'left' ) {

						t.scroll_slider( distance, direction );
					}
					else if ( direction === 'right' ) {

						t.scroll_slider( distance, direction );
					}

					if ( t.param.autoplay && t.stat.autoplayison ) {

						t.autoplayclear();
					}
				}

				//Else, cancel means snap back to the begining

				else if ( phase === 'cancel' ) {

					t.animate_slider_to_current_position( t.get_animationspeed() );

					if ( t.param.autoplay && !t.stat.autoplayison ) {

						t.autoplayset();
					}
				}

				//Else end means the swipe was completed, so move to the next image

				else if ( phase === 'end' ) {

					if ( direction === 'right' ) {

						t.stat.lasteventtype = 'swipe-right';
						t.prev_job();
					}
					else if ( direction === 'left' ) {

						t.stat.lasteventtype = 'swipe-left';
						t.next_job();
					}

					if ( t.param.autoplay && !t.stat.autoplayison ) {

						t.autoplayset();
					}
					
				}

				t.stat.isswiping = false;
			}
			
		}

		// init touch swipe
		t.obj.slider.swipe( {
			triggerOnTouchEnd: true,
			swipeStatus: swipeStatus,
			//allowPageScroll: 'vertical',
			tap:function(event, target) {
				/* this was a tab event */

				var href = jQuery(target).attr('href');

				if ( typeof href === 'undefined' ) {
					href = jQuery(target).parents('a').attr('href');
				}

				if ( typeof href !== 'undefined' ) {
					window.location = href;
				}
			},
		});

	};

	t.slides_equal_height = function () {

		t.obj.slider.imagesLoaded()

			.progress( function( /*instance, image*/ ) {

				t.obj.slide.css( 'height', 'auto' );
				t.obj.slide.height( t.obj.slider.height() );
			});

	};

	// }

	// css {

	// disable CSS3 styles: https://github.com/chriscoyier/CSS3-StripTease/blob/master/striptease.js

	t.css_transform = function ( obj, value ) {

		var transform = 'none';

		if ( typeof(value) !== 'undefined' ) {

			transform = 'translate3d(' + value.toString() + 'px,0px,0px)';
		}

		if ( t.stat.cssanimation ) {

			obj.css({
				'-webkit-transform': transform,
				'-ms-transform': transform,
				'-o-transform': transform,
				'-moz-transform': transform,
				'transform': transform
			});
		}
	};

	t.css_animation = function ( obj, value ) {

		var animation = 'none';

		if ( typeof(value) !== 'undefined' ) {

			animation = value;
		}

		if ( t.stat.cssanimation ) {

			obj.css({
				'-webkit-animation': animation,
				'-ms-animation': animation,
				'-o-animation': animation,
				'-moz-animation': animation,
				'animation': animation
			});
		}
	};

	t.css_animation_play_state = function ( obj, value ) {

		var state = 'none';

		if ( typeof(value) !== 'undefined' ) {

			state = value;
		}

		if ( t.stat.cssanimation ) {

			obj.css({
				'-webkit-animation-play-state': state,
				'-ms-animation-play-state': state,
				'-o-animation-play-state': state,
				'-moz-animation-play-state': state,
				'animation-play-state': state
			});
		}
	};

	t.css_transitiontiming = function ( obj, value ) {

		if ( typeof(value) === 'undefined' ) {

			value = 'none';
		}

		if ( t.stat.cssanimation ) {

			obj.css({
				'-webkit-transition-timing-function': value,
				'-ms-transition-timing-function': value,
				'-o-transition-timing-function': value,
				'-moz-transition-timing-function': value,
				'transition-timing-function': value
			});
		}
	};

	t.css_transitionduration = function ( obj, value ) {

		if ( typeof(value) === 'undefined' ) {

			value = 0;
		}

		var duration = ( ( value / 1000 ).toFixed(1) + 's' );

		if ( t.stat.cssanimation ) {

			obj.css({
				'-webkit-transition-duration': duration,
				'-ms-transition-duration': duration,
				'-o-transition-duration': duration,
				'-moz-transition-duration': duration,
				'transition-duration': duration
			});
		}
	};

	t.css_keyframes = function ( value ) {

		if ( typeof(value) !== 'undefined' && t.stat.cssanimation ) {

			var ret = '@-webkit-keyframes slideshow {0%{ -webkit-transform: translateX(0);}100%{-webkit-transform: translateX(-' + value + 'px);}}' +
						'@-ms-keyframes slideshow {0%{ -ms-transform: translateX(0);}100%{-ms-transform: translateX(-' + value + 'px);}}' +
						'@-o-keyframes slideshow {0%{ -o-transform: translateX(0);}100%{-o-transform: translateX(-' + value + 'px);}}' +
						'@-moz-keyframes slideshow {0%{ -moz-transform: translateX(0);}100%{-moz-transform: translateX(-' + value + 'px);}}' +
						'@keyframes slideshow {0%{ transform: translateX(0);}100%{transform: translateX(-' + value + 'px);}}';
			return ret;
		}
	};

	t.css_marginleft = function ( obj, value ) {

		if ( typeof(value) === 'undefined' ) {

			value = 0;
		}

		obj.css({
			'margin-left': value
		});
	};

	// }

	// helper {

	t.translate_easing = function ( name, type ) {

		var ret = 'linear';

		if ( type === 'jquery' ) {

			if ( name === 'linear' ) { ret = 'linear'; }
			if ( name === 'ease' ) { ret = 'swing'; }
		}

		if ( type === 'css' ) {

			ret = name;
		}

		return ret;
	};

	t.helper = {

		getLength: function( o ) {

			var len = o.length ? --o.length : 0;

			for (var k in o) {

				len++;
			}

			return len;
		},

		setDefaultParam: function ( p ) {

			var r = jQuery.extend( true, p.d, p.p );
			

			return r;
		},

		when: function( obj, p ) {

			/*
				t.helper.when( obj, {
					timeout: 60,
					key: 'index',
					callback_if: t.function,
					callback_timeout: t.function,
				} );
			*/

			var i = 0;

			if ( typeof p.timeout == 'undefined' ) {

				p.timeout = 10;
			}

			if ( typeof p.val == 'undefined' ) {

				p.val = true;
			}

			var interval = window.setInterval( function() {

				i++;

				if ( obj[ p.key ] === p.val ) {

					p.callback_if();
					window.clearInterval( interval );
				}

				if ( ( i / 10 ) === p.timeout ) {

					window.clearInterval( interval );

					if ( typeof p.callback_timeout === 'function' ) {

						p.callback_timeout(); 
					}
				}

			}, 100 );

		},
	};

	t.get_placeholder_of_string = function ( string ) {

		// placeholder pattern {{placeholder}}, you can use alphanumeric characters, underscore and hyphen

		var regex = /\{\{([\w-]+)\}\}/g,
			arr = [];

		while ( match = regex.exec( string ) ) {

			arr.push( match[1] );	
		}

		return arr;
	},

	// }

	// public {

		t.goto_slide = function ( p ) {

			window.setTimeout(function(){

				var d = {
						id: false,
						align: false // false, center
					},
					i = 0,
					curr_index = false;

				p = jQuery.extend ( true, d, p );

				t.obj.slide.each( function () {

					var yersliderid = jQuery( this ).data('yersliderid').toString();

					if ( p.id.toString() === yersliderid ) {

						curr_index = i;

						if ( p.align === 'center' ) {

							curr_index = i - ( Math.ceil( t.stat.slidegroup / 2 ) - 1 );
						}
					}

					i++;
				});

				if ( curr_index ) {

					t.stat.currentslideindex = curr_index;

					t.check_slider_current_index();
					t.set_slide_current_class();
					t.set_slidesinviewport();
					t.refresh_prevnext();

					t.move_slider_to_current_index();

					if ( t.param.bullets ) {

						t.set_bullet_current();
						t.set_bullet_current_class();
					}
				}

			}, 250);
		};

	// }
};
