function YerRespFontSize() {

	var t = this;

	t.defaults = {
		'data_key': 'rfs',
		'blockwidth': 960,
		'fontsize_min': 16,
		'resize_timeout': 250,
	}

	t.set = undefined;

	t.init =  function( p ) {

		t.set = jQuery.extend( true, t.defaults, p );
		t.job();

	};

	t.job = function() {

		jQuery( '[data-' + t.set.data_key + ']' ).each( function() {

			var factor = 1,
				obj = jQuery( this ),
				obj_width = obj.width(),
				data = obj.data( t.set.data_key ),
				fontsize = data,
				blockwidth = t.set.blockwidth,
				fontsize_min = t.set.fontsize_min;

			factor = obj_width / blockwidth;

			var fontsize_px = Math.round( fontsize * factor );

			if ( fontsize_px < fontsize_min ) {

			  fontsize_px = fontsize_min;
			}

			obj.css({
			  'font-size': fontsize_px + 'px'
			});

		  });

		var timeout;

	};

	t.resize = function() {

		jQuery( window ).resize(function() {

			clearTimeout( timeout );
			timeout = setTimeout( t.job() , t.set.resize_timeout );

		});
	};
};
