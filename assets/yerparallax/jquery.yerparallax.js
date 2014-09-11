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

    return this.each(function() {

        var tick = 0,
        valStart = settings.valStart,
        valEnd = settings.valEnd, 
        valRange = valEnd - valStart,
        valCurr = 0,
        objParent = jQuery(this).parent(),
        objObject = jQuery(this),
        startFromBottom = settings.startFromBottom,
        yDist = settings.yDist,
        property = settings.property,
        propertySufix = settings.propertySufix,
        windowInnerHeight = jQuery(window).innerHeight();
        
        jQuery(window).bind('scroll', function() {
            
            var objOffset = objParent.offset(),
            valRange = valEnd - valStart,
            yStart = objOffset.top + startFromBottom,
            yCurr = window.pageYOffset + windowInnerHeight;

            if ( yCurr >= yStart && yCurr <= yStart + yDist ) {
                
                tick = yCurr - yStart;
                valCurr = valStart + (valRange / yDist) * tick;
            }

            else if ( yCurr > yStart ) {

                valCurr = valEnd;
            }

            else if ( yCurr < yStart ) {

                valCurr = valStart;
            }
            
            objObject.css( property, valCurr + propertySufix );
        });
    });
};