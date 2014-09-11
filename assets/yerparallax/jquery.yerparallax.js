/*
  parallax - a basic jQuery plugin for parallax effects
	by Johann Heyne (@johannheyne)

	Dual licensed under MIT and GPL.
*/

jQuery.fn.parallax = function( options ) {

    var defaults = {
        startFromBottom: 0,
        yDist: 0,
        property: 'left',
        propertySufix: '',
        valStart: 0,
        valEnd: 0
    };

    var settings = jQuery.extend( {}, defaults, options );

	function setCSS( p ) {

	    var objOffset = p.objParent.offset(),
        valRange = p.valEnd - p.valStart,
        yStart = objOffset.top + p.startFromBottom,
        yCurr = window.pageYOffset + p.windowInnerHeight;

        if ( p.yDist !== 0 && yCurr >= yStart && yCurr <= yStart + p.yDist ) {

            p.tick = yCurr - yStart;
			p.valCurr = Number( p.valStart ) + ( ( valRange / Number( p.yDist ) ) * p.tick );
        }

        else if ( yCurr > yStart ) {

            p.valCurr = p.valEnd;
        }

        else if ( yCurr < yStart ) {

            p.valCurr = p.valStart;
        }

        p.objObject.css( p.property, p.valCurr + p.propertySufix );
	};

    return this.each( function() {

        var that = {
			tick: 0,
	        valStart: settings.valStart,
	        valEnd: settings.valEnd, 
	        valRange: settings.valEnd - settings.valStart,
	        valCurr: 0,
	        objParent: jQuery(this).parent(),
	        objObject: jQuery(this),
	        startFromBottom: settings.startFromBottom,
	        yDist: settings.yDist,
	        property: settings.property,
	        propertySufix: settings.propertySufix,
	        windowInnerHeight: jQuery(window).innerHeight(),

		};

		setCSS( that );

        jQuery(window).bind( 'scroll', function() {

            setCSS( that );
        });
    });
};