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
				obj_width = 0,
				data = obj.data( t.set.data_key ),
				fontsize = data.fs,
				blockwidth = t.set.blockwidth,
				fontsize_min = t.set.fontsize_min;
			
			if ( typeof data.w === 'undefined' ) {
			    
				data.w = 'inner'; // inner, padding, border, margin
			}
			
			if ( data.w === 'inner' ) {
			
			    var obj_width = obj.width();
			}
			
			if ( data.w === 'padding' ) {
			
			    var obj_width = obj.innerWidth();
			}
			
			if ( data.w === 'border' ) {
			
			    var obj_width = obj.outherWidth();
			}
			
			if ( data.w === 'margin' ) {
			
			    var obj_width = obj.outherWidth( true );
			}

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
