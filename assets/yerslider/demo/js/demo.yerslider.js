/*!
 * imagesLoaded PACKAGED v3.1.6
 * JavaScript is all like "You images are done yet or what?"
 * MIT License
 */

(function(){function e(){}function t(e,t){for(var n=e.length;n--;)if(e[n].listener===t)return n;return-1}function n(e){return function(){return this[e].apply(this,arguments)}}var i=e.prototype,r=this,o=r.EventEmitter;i.getListeners=function(e){var t,n,i=this._getEvents();if("object"==typeof e){t={};for(n in i)i.hasOwnProperty(n)&&e.test(n)&&(t[n]=i[n])}else t=i[e]||(i[e]=[]);return t},i.flattenListeners=function(e){var t,n=[];for(t=0;e.length>t;t+=1)n.push(e[t].listener);return n},i.getListenersAsObject=function(e){var t,n=this.getListeners(e);return n instanceof Array&&(t={},t[e]=n),t||n},i.addListener=function(e,n){var i,r=this.getListenersAsObject(e),o="object"==typeof n;for(i in r)r.hasOwnProperty(i)&&-1===t(r[i],n)&&r[i].push(o?n:{listener:n,once:!1});return this},i.on=n("addListener"),i.addOnceListener=function(e,t){return this.addListener(e,{listener:t,once:!0})},i.once=n("addOnceListener"),i.defineEvent=function(e){return this.getListeners(e),this},i.defineEvents=function(e){for(var t=0;e.length>t;t+=1)this.defineEvent(e[t]);return this},i.removeListener=function(e,n){var i,r,o=this.getListenersAsObject(e);for(r in o)o.hasOwnProperty(r)&&(i=t(o[r],n),-1!==i&&o[r].splice(i,1));return this},i.off=n("removeListener"),i.addListeners=function(e,t){return this.manipulateListeners(!1,e,t)},i.removeListeners=function(e,t){return this.manipulateListeners(!0,e,t)},i.manipulateListeners=function(e,t,n){var i,r,o=e?this.removeListener:this.addListener,s=e?this.removeListeners:this.addListeners;if("object"!=typeof t||t instanceof RegExp)for(i=n.length;i--;)o.call(this,t,n[i]);else for(i in t)t.hasOwnProperty(i)&&(r=t[i])&&("function"==typeof r?o.call(this,i,r):s.call(this,i,r));return this},i.removeEvent=function(e){var t,n=typeof e,i=this._getEvents();if("string"===n)delete i[e];else if("object"===n)for(t in i)i.hasOwnProperty(t)&&e.test(t)&&delete i[t];else delete this._events;return this},i.removeAllListeners=n("removeEvent"),i.emitEvent=function(e,t){var n,i,r,o,s=this.getListenersAsObject(e);for(r in s)if(s.hasOwnProperty(r))for(i=s[r].length;i--;)n=s[r][i],n.once===!0&&this.removeListener(e,n.listener),o=n.listener.apply(this,t||[]),o===this._getOnceReturnValue()&&this.removeListener(e,n.listener);return this},i.trigger=n("emitEvent"),i.emit=function(e){var t=Array.prototype.slice.call(arguments,1);return this.emitEvent(e,t)},i.setOnceReturnValue=function(e){return this._onceReturnValue=e,this},i._getOnceReturnValue=function(){return this.hasOwnProperty("_onceReturnValue")?this._onceReturnValue:!0},i._getEvents=function(){return this._events||(this._events={})},e.noConflict=function(){return r.EventEmitter=o,e},"function"==typeof define&&define.amd?define("eventEmitter/EventEmitter",[],function(){return e}):"object"==typeof module&&module.exports?module.exports=e:this.EventEmitter=e}).call(this),function(e){function t(t){var n=e.event;return n.target=n.target||n.srcElement||t,n}var n=document.documentElement,i=function(){};n.addEventListener?i=function(e,t,n){e.addEventListener(t,n,!1)}:n.attachEvent&&(i=function(e,n,i){e[n+i]=i.handleEvent?function(){var n=t(e);i.handleEvent.call(i,n)}:function(){var n=t(e);i.call(e,n)},e.attachEvent("on"+n,e[n+i])});var r=function(){};n.removeEventListener?r=function(e,t,n){e.removeEventListener(t,n,!1)}:n.detachEvent&&(r=function(e,t,n){e.detachEvent("on"+t,e[t+n]);try{delete e[t+n]}catch(i){e[t+n]=void 0}});var o={bind:i,unbind:r};"function"==typeof define&&define.amd?define("eventie/eventie",o):e.eventie=o}(this),function(e,t){"function"==typeof define&&define.amd?define(["eventEmitter/EventEmitter","eventie/eventie"],function(n,i){return t(e,n,i)}):"object"==typeof exports?module.exports=t(e,require("eventEmitter"),require("eventie")):e.imagesLoaded=t(e,e.EventEmitter,e.eventie)}(this,function(e,t,n){function i(e,t){for(var n in t)e[n]=t[n];return e}function r(e){return"[object Array]"===d.call(e)}function o(e){var t=[];if(r(e))t=e;else if("number"==typeof e.length)for(var n=0,i=e.length;i>n;n++)t.push(e[n]);else t.push(e);return t}function s(e,t,n){if(!(this instanceof s))return new s(e,t);"string"==typeof e&&(e=document.querySelectorAll(e)),this.elements=o(e),this.options=i({},this.options),"function"==typeof t?n=t:i(this.options,t),n&&this.on("always",n),this.getImages(),a&&(this.jqDeferred=new a.Deferred);var r=this;setTimeout(function(){r.check()})}function c(e){this.img=e}function f(e){this.src=e,v[e]=this}var a=e.jQuery,u=e.console,h=u!==void 0,d=Object.prototype.toString;s.prototype=new t,s.prototype.options={},s.prototype.getImages=function(){this.images=[];for(var e=0,t=this.elements.length;t>e;e++){var n=this.elements[e];"IMG"===n.nodeName&&this.addImage(n);var i=n.nodeType;if(i&&(1===i||9===i||11===i))for(var r=n.querySelectorAll("img"),o=0,s=r.length;s>o;o++){var c=r[o];this.addImage(c)}}},s.prototype.addImage=function(e){var t=new c(e);this.images.push(t)},s.prototype.check=function(){function e(e,r){return t.options.debug&&h&&u.log("confirm",e,r),t.progress(e),n++,n===i&&t.complete(),!0}var t=this,n=0,i=this.images.length;if(this.hasAnyBroken=!1,!i)return this.complete(),void 0;for(var r=0;i>r;r++){var o=this.images[r];o.on("confirm",e),o.check()}},s.prototype.progress=function(e){this.hasAnyBroken=this.hasAnyBroken||!e.isLoaded;var t=this;setTimeout(function(){t.emit("progress",t,e),t.jqDeferred&&t.jqDeferred.notify&&t.jqDeferred.notify(t,e)})},s.prototype.complete=function(){var e=this.hasAnyBroken?"fail":"done";this.isComplete=!0;var t=this;setTimeout(function(){if(t.emit(e,t),t.emit("always",t),t.jqDeferred){var n=t.hasAnyBroken?"reject":"resolve";t.jqDeferred[n](t)}})},a&&(a.fn.imagesLoaded=function(e,t){var n=new s(this,e,t);return n.jqDeferred.promise(a(this))}),c.prototype=new t,c.prototype.check=function(){var e=v[this.img.src]||new f(this.img.src);if(e.isConfirmed)return this.confirm(e.isLoaded,"cached was confirmed"),void 0;if(this.img.complete&&void 0!==this.img.naturalWidth)return this.confirm(0!==this.img.naturalWidth,"naturalWidth"),void 0;var t=this;e.on("confirm",function(e,n){return t.confirm(e.isLoaded,n),!0}),e.check()},c.prototype.confirm=function(e,t){this.isLoaded=e,this.emit("confirm",this,t)};var v={};return f.prototype=new t,f.prototype.check=function(){if(!this.isChecked){var e=new Image;n.bind(e,"load",this),n.bind(e,"error",this),e.src=this.src,this.isChecked=!0}},f.prototype.handleEvent=function(e){var t="on"+e.type;this[t]&&this[t](e)},f.prototype.onload=function(e){this.confirm(!0,"onload"),this.unbindProxyEvents(e)},f.prototype.onerror=function(e){this.confirm(!1,"onerror"),this.unbindProxyEvents(e)},f.prototype.confirm=function(e,t){this.isConfirmed=!0,this.isLoaded=e,this.emit("confirm",this,t)},f.prototype.unbindProxyEvents=function(e){n.unbind(e.target,"load",this),n.unbind(e.target,"error",this)},s});

/*
* @fileOverview TouchSwipe - jQuery Plugin
* @version 1.6.3
*
* @author Matt Bryson http://www.github.com/mattbryson
* @see https://github.com/mattbryson/TouchSwipe-Jquery-Plugin
* @see http://labs.skinkers.com/touchSwipe/
* @see http://plugins.jquery.com/project/touchSwipe
*
* Copyright (c) 2010 Matt Bryson
* Dual licensed under the MIT or GPL Version 2 licenses.
*
*
* Changelog
* $Date: 2010-12-12 (Wed, 12 Dec 2010) $
* $version: 1.0.0
* $version: 1.0.1 - removed multibyte comments
*
* $Date: 2011-21-02 (Mon, 21 Feb 2011) $
* $version: 1.1.0 	- added allowPageScroll property to allow swiping and scrolling of page
*					- changed handler signatures so one handler can be used for multiple events
* $Date: 2011-23-02 (Wed, 23 Feb 2011) $
* $version: 1.2.0 	- added click handler. This is fired if the user simply clicks and does not swipe. The event object and click target are passed to handler.
*					- If you use the http://code.google.com/p/jquery-ui-for-ipad-and-iphone/ plugin, you can also assign jQuery mouse events to children of a touchSwipe object.
* $version: 1.2.1 	- removed console log!
*
* $version: 1.2.2 	- Fixed bug where scope was not preserved in callback methods.
*
* $Date: 2011-28-04 (Thurs, 28 April 2011) $
* $version: 1.2.4 	- Changed licence terms to be MIT or GPL inline with jQuery. Added check for support of touch events to stop non compatible browsers erroring.
*
* $Date: 2011-27-09 (Tues, 27 September 2011) $
* $version: 1.2.5 	- Added support for testing swipes with mouse on desktop browser (thanks to https://github.com/joelhy)
*
* $Date: 2012-14-05 (Mon, 14 May 2012) $
* $version: 1.2.6 	- Added timeThreshold between start and end touch, so user can ignore slow swipes (thanks to Mark Chase). Default is null, all swipes are detected
*
* $Date: 2012-05-06 (Tues, 05 June 2012) $
* $version: 1.2.7 	- Changed time threshold to have null default for backwards compatibility. Added duration param passed back in events, and refactored how time is handled.
*
* $Date: 2012-05-06 (Tues, 05 June 2012) $
* $version: 1.2.8 	- Added the possibility to return a value like null or false in the trigger callback. In that way we can control when the touch start/move should take effect or not (simply by returning in some cases return null; or return false;) This effects the ontouchstart/ontouchmove event.
*
* $Date: 2012-06-06 (Wed, 06 June 2012) $
* $version: 1.3.0 	- Refactored whole plugin to allow for methods to be executed, as well as exposed defaults for user override. Added 'enable', 'disable', and 'destroy' methods
*
* $Date: 2012-05-06 (Fri, 05 June 2012) $
* $version: 1.3.1 	- Bug fixes  - bind() with false as last argument is no longer supported in jQuery 1.6, also, if you just click, the duration is now returned correctly.
*
* $Date: 2012-29-07 (Sun, 29 July 2012) $
* $version: 1.3.2	- Added fallbackToMouseEvents option to NOT capture mouse events on non touch devices.
* 			- Added "all" fingers value to the fingers property, so any combinatin of fingers triggers the swipe, allowing event handlers to check the finger count
*
* $Date: 2012-09-08 (Thurs, 9 Aug 2012) $
* $version: 1.3.3	- Code tidy prep for minified version
*
* $Date: 2012-04-10 (wed, 4 Oct 2012) $
* $version: 1.4.0	- Added pinch support, pinchIn and pinchOut
*
* $Date: 2012-11-10 (Thurs, 11 Oct 2012) $
* $version: 1.5.0	- Added excludedElements, a jquery selector that specifies child elements that do NOT trigger swipes. By default, this is one select that removes all form, input select, button and anchor elements.
*
* $Date: 2012-22-10 (Mon, 22 Oct 2012) $
* $version: 1.5.1	- Fixed bug with jQuery 1.8 and trailing comma in excludedElements
*					- Fixed bug with IE and eventPreventDefault()
* $Date: 2013-01-12 (Fri, 12 Jan 2013) $
* $version: 1.6.0	- Fixed bugs with pinching, mainly when both pinch and swipe enabled, as well as adding time threshold for multifinger gestures, so releasing one finger beofre the other doesnt trigger as single finger gesture.
*					- made the demo site all static local HTML pages so they can be run locally by a developer
*					- added jsDoc comments and added documentation for the plugin	
*					- code tidy
*					- added triggerOnTouchLeave property that will end the event when the user swipes off the element.
* $Date: 2013-03-23 (Sat, 23 Mar 2013) $
* $version: 1.6.1	- Added support for ie8 touch events
* $version: 1.6.2	- Added support for events binding with on / off / bind in jQ for all callback names.
*                   - Deprecated the 'click' handler in favour of tap.
*                   - added cancelThreshold property
*                   - added option method to update init options at runtime
*
* $version 1.6.3    - added doubletap, longtap events and longTapThreshold, doubleTapThreshold property
* $Date: 2013-04-04 (Thurs, 04 April 2013) $
* $version 1.6.4    - Fixed bug with cancelThreshold introduced in 1.6.3, where swipe status no longer fired start event, and stopped once swiping back.
*/
 
/**
 * See (http://jquery.com/).
 * @name $
 * @class 
 * See the jQuery Library  (http://jquery.com/) for full details.  This just
 * documents the function and classes that are added to jQuery by this plug-in.
 */
 
/**
 * See (http://jquery.com/)
 * @name fn
 * @class 
 * See the jQuery Library  (http://jquery.com/) for full details.  This just
 * documents the function and classes that are added to jQuery by this plug-in.
 * @memberOf $
 */
 
 
 
(function ($) {
	"use strict";
 
	//Constants
	var LEFT = "left",
		RIGHT = "right",
		UP = "up",
		DOWN = "down",
		IN = "in",
		OUT = "out",
 
		NONE = "none",
		AUTO = "auto",
		
		SWIPE = "swipe",
		PINCH = "pinch",
		TAP = "tap",
		DOUBLE_TAP = "doubletap",
		LONG_TAP = "longtap",
		
		HORIZONTAL = "horizontal",
		VERTICAL = "vertical",
 
		ALL_FINGERS = "all",
		
		DOUBLE_TAP_THRESHOLD = 10,
 
		PHASE_START = "start",
		PHASE_MOVE = "move",
		PHASE_END = "end",
		PHASE_CANCEL = "cancel",
 
		SUPPORTS_TOUCH = 'ontouchstart' in window,
 
		PLUGIN_NS = 'TouchSwipe';
 
 
 
	/**
	* The default configuration, and available options to configure touch swipe with.
	* You can set the default values by updating any of the properties prior to instantiation.
	* @name $.fn.swipe.defaults
	* @namespace
	* @property {int} [fingers=1] The number of fingers to detect in a swipe. Any swipes that do not meet this requirement will NOT trigger swipe handlers.
	* @property {int} [threshold=75] The number of pixels that the user must move their finger by before it is considered a swipe. 
	* @property {int} [cancelThreshold=null] The number of pixels that the user must move their finger back from the original swipe direction to cancel the gesture.
	* @property {int} [pinchThreshold=20] The number of pixels that the user must pinch their finger by before it is considered a pinch. 
	* @property {int} [maxTimeThreshold=null] Time, in milliseconds, between touchStart and touchEnd must NOT exceed in order to be considered a swipe. 
	* @property {int} [fingerReleaseThreshold=250] Time in milliseconds between releasing multiple fingers.  If 2 fingers are down, and are released one after the other, if they are within this threshold, it counts as a simultaneous release. 
	* @property {int} [longTapThreshold=500] Time in milliseconds between tap and release for a long tap
    * @property {int} [doubleTapThreshold=200] Time in milliseconds between 2 taps to count as a doubletap
	* @property {function} [swipe=null] A handler to catch all swipes. See {@link $.fn.swipe#event:swipe}
	* @property {function} [swipeLeft=null] A handler that is triggered for "left" swipes. See {@link $.fn.swipe#event:swipeLeft}
	* @property {function} [swipeRight=null] A handler that is triggered for "right" swipes. See {@link $.fn.swipe#event:swipeRight}
	* @property {function} [swipeUp=null] A handler that is triggered for "up" swipes. See {@link $.fn.swipe#event:swipeUp}
	* @property {function} [swipeDown=null] A handler that is triggered for "down" swipes. See {@link $.fn.swipe#event:swipeDown}
	* @property {function} [swipeStatus=null] A handler triggered for every phase of the swipe. See {@link $.fn.swipe#event:swipeStatus}
	* @property {function} [pinchIn=null] A handler triggered for pinch in events. See {@link $.fn.swipe#event:pinchIn}
	* @property {function} [pinchOut=null] A handler triggered for pinch out events. See {@link $.fn.swipe#event:pinchOut}
	* @property {function} [pinchStatus=null] A handler triggered for every phase of a pinch. See {@link $.fn.swipe#event:pinchStatus}
	* @property {function} [tap=null] A handler triggered when a user just taps on the item, rather than swipes it. If they do not move, tap is triggered, if they do move, it is not. 
	* @property {function} [doubleTap=null] A handler triggered when a user double taps on the item. The delay between taps can be set with the doubleTapThreshold property. See {@link $.fn.swipe.defaults#doubleTapThreshold}
	* @property {function} [longTap=null] A handler triggered when a user long taps on the item. The delay between start and end can be set with the longTapThreshold property. See {@link $.fn.swipe.defaults#doubleTapThreshold}
	* @property {boolean} [triggerOnTouchEnd=true] If true, the swipe events are triggered when the touch end event is received (user releases finger).  If false, it will be triggered on reaching the threshold, and then cancel the touch event automatically. 
	* @property {boolean} [triggerOnTouchLeave=false] If true, then when the user leaves the swipe object, the swipe will end and trigger appropriate handlers. 
	* @property {string} [allowPageScroll='auto'] How the browser handles page scrolls when the user is swiping on a touchSwipe object. See {@link $.fn.swipe.pageScroll}.  <br/><br/>
										<code>"auto"</code> : all undefined swipes will cause the page to scroll in that direction. <br/>
										<code>"none"</code> : the page will not scroll when user swipes. <br/>
										<code>"horizontal"</code> : will force page to scroll on horizontal swipes. <br/>
										<code>"vertical"</code> : will force page to scroll on vertical swipes. <br/>
	* @property {boolean} [fallbackToMouseEvents=true] If true mouse events are used when run on a non touch device, false will stop swipes being triggered by mouse events on non tocuh devices. 
	* @property {string} [excludedElements="button, input, select, textarea, a, .noSwipe"] A jquery selector that specifies child elements that do NOT trigger swipes. By default this excludes all form, input, select, button, anchor and .noSwipe elements. 
	
	*/
	var defaults = {
		fingers: 1, 		
		threshold: 75, 	
		cancelThreshold:null,	
		pinchThreshold:20,
		maxTimeThreshold: null, 
		fingerReleaseThreshold:250, 
		longTapThreshold:500,
		doubleTapThreshold:200,
		swipe: null, 		
		swipeLeft: null, 	
		swipeRight: null, 	
		swipeUp: null, 		
		swipeDown: null, 	
		swipeStatus: null, 	
		pinchIn:null,		
		pinchOut:null,		
		pinchStatus:null,	
		click:null, //Deprecated since 1.6.2
		tap:null,
		doubleTap:null,
		longTap:null, 		
		triggerOnTouchEnd: true, 
		triggerOnTouchLeave:false, 
		allowPageScroll: "auto", 
		fallbackToMouseEvents: true,	
		excludedElements:""//"button, input, select, textarea, a, .noSwipe"
	};
 
 
 
	/**
	* Applies TouchSwipe behaviour to one or more jQuery objects.
	* The TouchSwipe plugin can be instantiated via this method, or methods within 
	* TouchSwipe can be executed via this method as per jQuery plugin architecture.
	* @see TouchSwipe
	* @class
	* @param {Mixed} method If the current DOMNode is a TouchSwipe object, and <code>method</code> is a TouchSwipe method, then
	* the <code>method</code> is executed, and any following arguments are passed to the TouchSwipe method.
	* If <code>method</code> is an object, then the TouchSwipe class is instantiated on the current DOMNode, passing the 
	* configuration properties defined in the object. See TouchSwipe
	*
	*/
	$.fn.swipe = function (method) {
		var $this = $(this),
			plugin = $this.data(PLUGIN_NS);
 
		//Check if we are already instantiated and trying to execute a method	
		if (plugin && typeof method === 'string') {
			if (plugin[method]) {
				return plugin[method].apply(this, Array.prototype.slice.call(arguments, 1));
			} else {
				$.error('Method ' + method + ' does not exist on jQuery.swipe');
			}
		}
		//Else not instantiated and trying to pass init object (or nothing)
		else if (!plugin && (typeof method === 'object' || !method)) {
			return init.apply(this, arguments);
		}
 
		return $this;
	};
 
	//Expose our defaults so a user could override the plugin defaults
	$.fn.swipe.defaults = defaults;
 
	/**
	* The phases that a touch event goes through.  The <code>phase</code> is passed to the event handlers. 
	* These properties are read only, attempting to change them will not alter the values passed to the event handlers.
	* @namespace
	* @readonly
	* @property {string} PHASE_START Constant indicating the start phase of the touch event. Value is <code>"start"</code>.
	* @property {string} PHASE_MOVE Constant indicating the move phase of the touch event. Value is <code>"move"</code>.
	* @property {string} PHASE_END Constant indicating the end phase of the touch event. Value is <code>"end"</code>.
	* @property {string} PHASE_CANCEL Constant indicating the cancel phase of the touch event. Value is <code>"cancel"</code>.
	*/
	$.fn.swipe.phases = {
		PHASE_START: PHASE_START,
		PHASE_MOVE: PHASE_MOVE,
		PHASE_END: PHASE_END,
		PHASE_CANCEL: PHASE_CANCEL
	};
 
	/**
	* The direction constants that are passed to the event handlers. 
	* These properties are read only, attempting to change them will not alter the values passed to the event handlers.
	* @namespace
	* @readonly
	* @property {string} LEFT Constant indicating the left direction. Value is <code>"left"</code>.
	* @property {string} RIGHT Constant indicating the right direction. Value is <code>"right"</code>.
	* @property {string} UP Constant indicating the up direction. Value is <code>"up"</code>.
	* @property {string} DOWN Constant indicating the down direction. Value is <code>"cancel"</code>.
	* @property {string} IN Constant indicating the in direction. Value is <code>"in"</code>.
	* @property {string} OUT Constant indicating the out direction. Value is <code>"out"</code>.
	*/
	$.fn.swipe.directions = {
		LEFT: LEFT,
		RIGHT: RIGHT,
		UP: UP,
		DOWN: DOWN,
		IN : IN,
		OUT: OUT
	};
	
	/**
	* The page scroll constants that can be used to set the value of <code>allowPageScroll</code> option
	* These properties are read only
	* @namespace
	* @readonly
	* @see $.fn.swipe.defaults#allowPageScroll
	* @property {string} NONE Constant indicating no page scrolling is allowed. Value is <code>"none"</code>.
	* @property {string} HORIZONTAL Constant indicating horizontal page scrolling is allowed. Value is <code>"horizontal"</code>.
	* @property {string} VERTICAL Constant indicating vertical page scrolling is allowed. Value is <code>"vertical"</code>.
	* @property {string} AUTO Constant indicating either horizontal or vertical will be allowed, depending on the swipe handlers registered. Value is <code>"auto"</code>.
	*/
	$.fn.swipe.pageScroll = {
		NONE: NONE,
		HORIZONTAL: HORIZONTAL,
		VERTICAL: VERTICAL,
		AUTO: AUTO
	};
 
	/**
	* Constants representing the number of fingers used in a swipe.  These are used to set both the value of <code>fingers</code> in the 
	* options object, as well as the value of the <code>fingers</code> event property.
	* These properties are read only, attempting to change them will not alter the values passed to the event handlers.
	* @namespace
	* @readonly
	* @see $.fn.swipe.defaults#fingers
	* @property {string} ONE Constant indicating 1 finger is to be detected / was detected. Value is <code>1</code>.
	* @property {string} TWO Constant indicating 2 fingers are to be detected / were detected. Value is <code>1</code>.
	* @property {string} THREE Constant indicating 3 finger are to be detected / were detected. Value is <code>1</code>.
	* @property {string} ALL Constant indicating any combination of finger are to be detected.  Value is <code>"all"</code>.
	*/
	$.fn.swipe.fingers = {
		ONE: 1,
		TWO: 2,
		THREE: 3,
		ALL: ALL_FINGERS
	};
 
	/**
	* Initialise the plugin for each DOM element matched
	* This creates a new instance of the main TouchSwipe class for each DOM element, and then
	* saves a reference to that instance in the elements data property.
	* @internal
	*/
	function init(options) {
		//Prep and extend the options
		if (options && (options.allowPageScroll === undefined && (options.swipe !== undefined || options.swipeStatus !== undefined))) {
			options.allowPageScroll = NONE;
		}
		
        //Check for deprecated options
		//Ensure that any old click handlers are assigned to the new tap, unless we have a tap
		if(options.click!==undefined && options.tap===undefined) {
		    options.tap = options.click;
		}
 
		if (!options) {
			options = {};
		}
		
        //pass empty object so we dont modify the defaults
		options = $.extend({}, $.fn.swipe.defaults, options);
 
		//For each element instantiate the plugin
		return this.each(function () {
			var $this = $(this);
 
			//Check we havent already initialised the plugin
			var plugin = $this.data(PLUGIN_NS);
 
			if (!plugin) {
				plugin = new TouchSwipe(this, options);
				$this.data(PLUGIN_NS, plugin);
			}
		});
	}
 
	/**
	* Main TouchSwipe Plugin Class.
	* Do not use this to construct your TouchSwipe object, use the jQuery plugin method $.fn.swipe(); {@link $.fn.swipe}
	* @private
	* @name TouchSwipe
	* @param {DOMNode} element The HTML DOM object to apply to plugin to
	* @param {Object} options The options to configure the plugin with.  @link {$.fn.swipe.defaults}
	* @see $.fh.swipe.defaults
	* @see $.fh.swipe
    * @class
	*/
	function TouchSwipe(element, options) {
		var useTouchEvents = (SUPPORTS_TOUCH || !options.fallbackToMouseEvents),
			START_EV = useTouchEvents ? 'touchstart' : 'mousedown',
			MOVE_EV = useTouchEvents ? 'touchmove' : 'mousemove',
			END_EV = useTouchEvents ? 'touchend' : 'mouseup',
			LEAVE_EV = useTouchEvents ? null : 'mouseleave', //we manually detect leave on touch devices, so null event here
			CANCEL_EV = 'touchcancel';
 
 
 
		//touch properties
		var distance = 0,
			direction = null,
			duration = 0,
			startTouchesDistance = 0,
			endTouchesDistance = 0,
			pinchZoom = 1,
			pinchDistance = 0,
			pinchDirection = 0,
			maximumsMap=null;
 
		
		
		//jQuery wrapped element for this instance
		var $element = $(element);
		
		//Current phase of th touch cycle
		var phase = "start";
 
		// the current number of fingers being used.
		var fingerCount = 0; 			
 
		//track mouse points / delta
		var fingerData=null;
 
		//track times
		var startTime = 0,
			endTime = 0,
			previousTouchEndTime=0,
			previousTouchFingerCount=0,
			doubleTapStartTime=0;
 
        //Timeouts
        var singleTapTimeout=null;
        
		// Add gestures to all swipable areas if supported
		try {
			$element.bind(START_EV, touchStart);
			$element.bind(CANCEL_EV, touchCancel);
		}
		catch (e) {
			$.error('events not supported ' + START_EV + ',' + CANCEL_EV + ' on jQuery.swipe');
		}
 
		//
		//Public methods
		//
		
		/**
		* re-enables the swipe plugin with the previous configuration
		* @function
		* @name $.fn.swipe#enable
		* @return {DOMNode} The Dom element that was registered with TouchSwipe 
		* @example $("#element").swipe("enable");
		*/
		this.enable = function () {
			$element.bind(START_EV, touchStart);
			$element.bind(CANCEL_EV, touchCancel);
			return $element;
		};
 
		/**
		* disables the swipe plugin
		* @function
		* @name $.fn.swipe#disable
		* @return {DOMNode} The Dom element that is now registered with TouchSwipe
	    * @example $("#element").swipe("disable");
		*/
		this.disable = function () {
			removeListeners();
			return $element;
		};
 
		/**
		* Destroy the swipe plugin completely. To use any swipe methods, you must re initialise the plugin.
		* @function
		* @name $.fn.swipe#destroy
		* @return {DOMNode} The Dom element that was registered with TouchSwipe 
		* @example $("#element").swipe("destroy");
		*/
		this.destroy = function () {
			removeListeners();
			$element.data(PLUGIN_NS, null);
			return $element;
		};
 
 
        /**
         * Allows run time updating of the swipe configuration options.
         * @function
    	 * @name $.fn.swipe#option
    	 * @param {String} property The option property to get or set
         * @param {Object} [value] The value to set the property to
		 * @return {Object} If only a property name is passed, then that property value is returned.
		 * @example $("#element").swipe("option", "threshold"); // return the threshold
         * @example $("#element").swipe("option", "threshold", 100); // set the threshold after init
         * @see $.fn.swipe.defaults
         *
         */
        this.option = function (property, value) {
            if(options[property]!==undefined) {
                if(value===undefined) {
                    return options[property];
                } else {
                    options[property] = value;
                }
            } else {
                $.error('Option ' + property + ' does not exist on jQuery.swipe.options');
            }
        }
 
		//
		// Private methods
		//
		
		//
		// EVENTS
		//
		/**
		* Event handler for a touch start event.
		* Stops the default click event from triggering and stores where we touched
		* @inner
		* @param {object} jqEvent The normalised jQuery event object.
		*/
		function touchStart(jqEvent) {
			//If we already in a touch event (a finger already in use) then ignore subsequent ones..
			if( getTouchInProgress() )
				return;
			
			//Check if this element matches any in the excluded elements selectors,  or its parent is excluded, if so, DONT swipe
			if( $(jqEvent.target).closest( options.excludedElements, $element ).length>0 ) 
				return;
				
			//As we use Jquery bind for events, we need to target the original event object
			//If these events are being programatically triggered, we dont have an orignal event object, so use the Jq one.
			var event = jqEvent.originalEvent ? jqEvent.originalEvent : jqEvent;
			
			var ret,
				evt = SUPPORTS_TOUCH ? event.touches[0] : event;
 
			phase = PHASE_START;
 
			//If we support touches, get the finger count
			if (SUPPORTS_TOUCH) {
				// get the total number of fingers touching the screen
				fingerCount = event.touches.length;
			}
			//Else this is the desktop, so stop the browser from dragging the image
			else {
				jqEvent.preventDefault(); //call this on jq event so we are cross browser
			}
 
			//clear vars..
			distance = 0;
			direction = null;
			pinchDirection=null;
			duration = 0;
			startTouchesDistance=0;
			endTouchesDistance=0;
			pinchZoom = 1;
			pinchDistance = 0;
			fingerData=createAllFingerData();
			maximumsMap=createMaximumsData();
			cancelMultiFingerRelease();
 
			
			// check the number of fingers is what we are looking for, or we are capturing pinches
			if (!SUPPORTS_TOUCH || (fingerCount === options.fingers || options.fingers === ALL_FINGERS) || hasPinches()) {
				// get the coordinates of the touch
				createFingerData( 0, evt );
				startTime = getTimeStamp();
				
				if(fingerCount==2) {
					//Keep track of the initial pinch distance, so we can calculate the diff later
					//Store second finger data as start
					createFingerData( 1, event.touches[1] );
					startTouchesDistance = endTouchesDistance = calculateTouchesDistance(fingerData[0].start, fingerData[1].start);
				}
				
				if (options.swipeStatus || options.pinchStatus) {
					ret = triggerHandler(event, phase);
				}
			}
			else {
				//A touch with more or less than the fingers we are looking for, so cancel
				ret = false; 
			}
 
			//If we have a return value from the users handler, then return and cancel
			if (ret === false) {
				phase = PHASE_CANCEL;
				triggerHandler(event, phase);
				return ret;
			}
			else {
				setTouchInProgress(true);
			}
		};
		
		
		
		/**
		* Event handler for a touch move event. 
		* If we change fingers during move, then cancel the event
		* @inner
		* @param {object} jqEvent The normalised jQuery event object.
		*/
		function touchMove(jqEvent) {
			
			//As we use Jquery bind for events, we need to target the original event object
			//If these events are being programatically triggered, we dont have an orignal event object, so use the Jq one.
			var event = jqEvent.originalEvent ? jqEvent.originalEvent : jqEvent;
			
			//If we are ending, cancelling, or within the threshold of 2 fingers being released, dont track anything..
			if (phase === PHASE_END || phase === PHASE_CANCEL || inMultiFingerRelease())
				return;
 
			var ret,
				evt = SUPPORTS_TOUCH ? event.touches[0] : event;
			
 
			//Update the  finger data 
			var currentFinger = updateFingerData(evt);
			endTime = getTimeStamp();
			
			if (SUPPORTS_TOUCH) {
				fingerCount = event.touches.length;
			}
 
			phase = PHASE_MOVE;
 
			//If we have 2 fingers get Touches distance as well
			if(fingerCount==2) {
				
				//Keep track of the initial pinch distance, so we can calculate the diff later
				//We do this here as well as the start event, incase they start with 1 finger, and the press 2 fingers
				if(startTouchesDistance==0) {
					//Create second finger if this is the first time...
					createFingerData( 1, event.touches[1] );
					
					startTouchesDistance = endTouchesDistance = calculateTouchesDistance(fingerData[0].start, fingerData[1].start);
				} else {
					//Else just update the second finger
					updateFingerData(event.touches[1]);
				
					endTouchesDistance = calculateTouchesDistance(fingerData[0].end, fingerData[1].end);
					pinchDirection = calculatePinchDirection(fingerData[0].end, fingerData[1].end);
				}
				
				
				pinchZoom = calculatePinchZoom(startTouchesDistance, endTouchesDistance);
				pinchDistance = Math.abs(startTouchesDistance - endTouchesDistance);
			}
			
			
			if ( (fingerCount === options.fingers || options.fingers === ALL_FINGERS) || !SUPPORTS_TOUCH || hasPinches() ) {
				
				direction = calculateDirection(currentFinger.start, currentFinger.end);
				
				//Check if we need to prevent default evnet (page scroll / pinch zoom) or not
				validateDefaultEvent(jqEvent, direction);
 
				//Distance and duration are all off the main finger
				distance = calculateDistance(currentFinger.start, currentFinger.end);
				duration = calculateDuration();
 
                //Cache the maximum distance we made in this direction
                setMaxDistance(direction, distance);
 
 
				if (options.swipeStatus || options.pinchStatus) {
					ret = triggerHandler(event, phase);
				}
				
				
				//If we trigger end events when threshold are met, or trigger events when touch leves element
				if(!options.triggerOnTouchEnd || options.triggerOnTouchLeave) {
					
					var inBounds = true;
					
					//If checking if we leave the element, run the bounds check (we can use touchleave as its not supported on webkit)
					if(options.triggerOnTouchLeave) {
						var bounds = getbounds( this );
						inBounds = isInBounds( currentFinger.end, bounds );
					}
					
					//Trigger end handles as we swipe if thresholds met or if we have left the element if the user has asked to check these..
					if(!options.triggerOnTouchEnd && inBounds) {
						phase = getNextPhase( PHASE_MOVE );
					} 
					//We end if out of bounds here, so set current phase to END, and check if its modified 
					else if(options.triggerOnTouchLeave && !inBounds ) {
						phase = getNextPhase( PHASE_END );
					}
						
					if(phase==PHASE_CANCEL || phase==PHASE_END)	{
						triggerHandler(event, phase);
					}				
				}
			}
			else {
				phase = PHASE_CANCEL;
				triggerHandler(event, phase);
			}
 
			if (ret === false) {
				phase = PHASE_CANCEL;
				triggerHandler(event, phase);
			}
		}
 
 
 
		/**
		* Event handler for a touch end event. 
		* Calculate the direction and trigger events
		* @inner
		* @param {object} jqEvent The normalised jQuery event object.
		*/
		function touchEnd(jqEvent) {
			//As we use Jquery bind for events, we need to target the original event object
			var event = jqEvent.originalEvent;
				
 
			//If we are still in a touch with another finger return
			//This allows us to wait a fraction and see if the other finger comes up, if it does within the threshold, then we treat it as a multi release, not a single release.
			if (SUPPORTS_TOUCH) {
				if(event.touches.length>0) {
					startMultiFingerRelease();
					return true;
				}
			}
			
			//If a previous finger has been released, check how long ago, if within the threshold, then assume it was a multifinger release.
			//This is used to allow 2 fingers to release fractionally after each other, whilst maintainig the event as containg 2 fingers, not 1
			if(inMultiFingerRelease()) {	
				fingerCount=previousTouchFingerCount;
			}	
				 
			//call this on jq event so we are cross browser 
			jqEvent.preventDefault(); 
			
			//Set end of swipe
			endTime = getTimeStamp();
			
			//Get duration incase move was never fired
			duration = calculateDuration();
			
			//If we trigger handlers at end of swipe OR, we trigger during, but they didnt trigger and we are still in the move phase
			if(didSwipeBackToCancel()) {
			    phase = PHASE_CANCEL;
                triggerHandler(event, phase);
			} else if (options.triggerOnTouchEnd || (options.triggerOnTouchEnd == false && phase === PHASE_MOVE)) {
				phase = PHASE_END;
                triggerHandler(event, phase);
			}
			//Special cases - A tap should always fire on touch end regardless,
			//So here we manually trigger the tap end handler by itself
			//We dont run trigger handler as it will re-trigger events that may have fired already
			else if (!options.triggerOnTouchEnd && hasTap()) {
                //Trigger the pinch events...
			    phase = PHASE_END;
			    triggerHandlerForGesture(event, phase, TAP);
			}
			else if (phase === PHASE_MOVE) {
				phase = PHASE_CANCEL;
				triggerHandler(event, phase);
			}
 
			setTouchInProgress(false);
		}
 
 
 
		/**
		* Event handler for a touch cancel event. 
		* Clears current vars
		* @inner
		*/
		function touchCancel() {
			// reset the variables back to default values
			fingerCount = 0;
			endTime = 0;
			startTime = 0;
			startTouchesDistance=0;
			endTouchesDistance=0;
			pinchZoom=1;
			
			//If we were in progress of tracking a possible multi touch end, then re set it.
			cancelMultiFingerRelease();
			
			setTouchInProgress(false);
		}
		
		
		/**
		* Event handler for a touch leave event. 
		* This is only triggered on desktops, in touch we work this out manually
		* as the touchleave event is not supported in webkit
		* @inner
		*/
		function touchLeave(jqEvent) {
			var event = jqEvent.originalEvent;
			
			//If we have the trigger on leve property set....
			if(options.triggerOnTouchLeave) {
				phase = getNextPhase( PHASE_END );
				triggerHandler(event, phase);
			}
		}
		
		/**
		* Removes all listeners that were associated with the plugin
		* @inner
		*/
		function removeListeners() {
			$element.unbind(START_EV, touchStart);
			$element.unbind(CANCEL_EV, touchCancel);
			$element.unbind(MOVE_EV, touchMove);
			$element.unbind(END_EV, touchEnd);
			
			//we only have leave events on desktop, we manually calcuate leave on touch as its not supported in webkit
			if(LEAVE_EV) { 
				$element.unbind(LEAVE_EV, touchLeave);
			}
			
			setTouchInProgress(false);
		}
 
		
		/**
		 * Checks if the time and distance thresholds have been met, and if so then the appropriate handlers are fired.
		 */
		function getNextPhase(currentPhase) {
			
			var nextPhase = currentPhase;
			
			// Ensure we have valid swipe (under time and over distance  and check if we are out of bound...)
			var validTime = validateSwipeTime();
			var validDistance = validateSwipeDistance();
			var didCancel = didSwipeBackToCancel();
						
			//If we have exceeded our time, then cancel	
			if(!validTime || didCancel) {
				nextPhase = PHASE_CANCEL;
			}
			//Else if we are moving, and have reached distance then end
			else if (validDistance && currentPhase == PHASE_MOVE && (!options.triggerOnTouchEnd || options.triggerOnTouchLeave) ) {
				nextPhase = PHASE_END;
			} 
			//Else if we have ended by leaving and didnt reach distance, then cancel
			else if (!validDistance && currentPhase==PHASE_END && options.triggerOnTouchLeave) {
				nextPhase = PHASE_CANCEL;
			}
			
			return nextPhase;
		}
		
		
		/**
		* Trigger the relevant event handler
		* The handlers are passed the original event, the element that was swiped, and in the case of the catch all handler, the direction that was swiped, "left", "right", "up", or "down"
		* @param {object} event the original event object
		* @param {string} phase the phase of the swipe (start, end cancel etc) {@link $.fn.swipe.phases}
		* @inner
		*/
		function triggerHandler(event, phase) {
			
			var ret = undefined;
			
			// SWIPE GESTURES
			if(didSwipe() || hasSwipes()) { //hasSwipes as status needs to fire even if swipe is invalid
				//Trigger the swipe events...
				ret = triggerHandlerForGesture(event, phase, SWIPE);
			} 
			
			// PINCH GESTURES (if the above didnt cancel)
			else if((didPinch() || hasPinches()) && ret!==false) {
				//Trigger the pinch events...
				ret = triggerHandlerForGesture(event, phase, PINCH);
			}
			
			// CLICK / TAP (if the above didnt cancel)
			if(didDoubleTap() && ret!==false) {
				//Trigger the tap events...
				ret = triggerHandlerForGesture(event, phase, DOUBLE_TAP);
			}
			
			// CLICK / TAP (if the above didnt cancel)
			else if(didLongTap() && ret!==false) {
				//Trigger the tap events...
				ret = triggerHandlerForGesture(event, phase, LONG_TAP);
			}
 
			// CLICK / TAP (if the above didnt cancel)
			else if(didTap() && ret!==false) {
				//Trigger the tap event..
				ret = triggerHandlerForGesture(event, phase, TAP);
	    	}
			
			
			
			// If we are cancelling the gesture, then manually trigger the reset handler
			if (phase === PHASE_CANCEL) {
				touchCancel(event);
			}
			
			// If we are ending the gesture, then manually trigger the reset handler IF all fingers are off
			if(phase === PHASE_END) {
				//If we support touch, then check that all fingers are off before we cancel
				if (SUPPORTS_TOUCH) {
					if(event.touches.length==0) {
						touchCancel(event);	
					}
				} 
				else {
					touchCancel(event);
				}
			}
					
			return ret;
		}
		
		
		
		/**
		* Trigger the relevant event handler
		* The handlers are passed the original event, the element that was swiped, and in the case of the catch all handler, the direction that was swiped, "left", "right", "up", or "down"
		* @param {object} event the original event object
		* @param {string} phase the phase of the swipe (start, end cancel etc) {@link $.fn.swipe.phases}
		* @param {string} gesture the gesture to triger a handler for : PINCH or SWIPE {@link $.fn.swipe.gestures}
		* @return Boolean False, to indicate that the event should stop propagation, or void.
		* @inner
		*/
		function triggerHandlerForGesture(event, phase, gesture) {	
			
			var ret=undefined;
			
			//SWIPES....
			if(gesture==SWIPE) {
				//Trigger status every time..
				
				//Trigger the event...
				$element.trigger('swipeStatus', [phase, direction || null, distance || 0, duration || 0, fingerCount]);
				
				//Fire the callback
				if (options.swipeStatus) {
					ret = options.swipeStatus.call($element, event, phase, direction || null, distance || 0, duration || 0, fingerCount);
					//If the status cancels, then dont run the subsequent event handlers..
					if(ret===false) return false;
				}
				
				
				
				
				if (phase == PHASE_END && validateSwipe()) {
					//Fire the catch all event
					$element.trigger('swipe', [direction, distance, duration, fingerCount]);
					
					//Fire catch all callback
					if (options.swipe) {
						ret = options.swipe.call($element, event, direction, distance, duration, fingerCount);
						//If the status cancels, then dont run the subsequent event handlers..
						if(ret===false) return false;
					}
					
					//trigger direction specific event handlers	
					switch (direction) {
						case LEFT:
							//Trigger the event
							$element.trigger('swipeLeft', [direction, distance, duration, fingerCount]);
					
					        //Fire the callback
							if (options.swipeLeft) {
								ret = options.swipeLeft.call($element, event, direction, distance, duration, fingerCount);
							}
							break;
	
						case RIGHT:
							//Trigger the event
					        $element.trigger('swipeRight', [direction, distance, duration, fingerCount]);
					
					        //Fire the callback
							if (options.swipeRight) {
								ret = options.swipeRight.call($element, event, direction, distance, duration, fingerCount);
							}
							break;
	
						case UP:
							//Trigger the event
					        $element.trigger('swipeUp', [direction, distance, duration, fingerCount]);
					
					        //Fire the callback
							if (options.swipeUp) {
								ret = options.swipeUp.call($element, event, direction, distance, duration, fingerCount);
							}
							break;
	
						case DOWN:
							//Trigger the event
					        $element.trigger('swipeDown', [direction, distance, duration, fingerCount]);
					
					        //Fire the callback
							if (options.swipeDown) {
								ret = options.swipeDown.call($element, event, direction, distance, duration, fingerCount);
							}
							break;
					}
				}
			}
			
			
			//PINCHES....
			if(gesture==PINCH) {
				//Trigger the event
			     $element.trigger('pinchStatus', [phase, pinchDirection || null, pinchDistance || 0, duration || 0, fingerCount, pinchZoom]);
					
                //Fire the callback
				if (options.pinchStatus) {
					ret = options.pinchStatus.call($element, event, phase, pinchDirection || null, pinchDistance || 0, duration || 0, fingerCount, pinchZoom);
					//If the status cancels, then dont run the subsequent event handlers..
					if(ret===false) return false;
				}
				
				if(phase==PHASE_END && validatePinch()) {
					
					switch (pinchDirection) {
						case IN:
							//Trigger the event
                            $element.trigger('pinchIn', [pinchDirection || null, pinchDistance || 0, duration || 0, fingerCount, pinchZoom]);
                    
                            //Fire the callback
                            if (options.pinchIn) {
								ret = options.pinchIn.call($element, event, pinchDirection || null, pinchDistance || 0, duration || 0, fingerCount, pinchZoom);
							}
							break;
						
						case OUT:
							//Trigger the event
                            $element.trigger('pinchOut', [pinchDirection || null, pinchDistance || 0, duration || 0, fingerCount, pinchZoom]);
                    
                            //Fire the callback
                            if (options.pinchOut) {
								ret = options.pinchOut.call($element, event, pinchDirection || null, pinchDistance || 0, duration || 0, fingerCount, pinchZoom);
							}
							break;	
					}
				}
			}
			
 
 
                
	    		
			if(gesture==TAP) {
				if(phase === PHASE_CANCEL || phase === PHASE_END) {
					
    			    
    			    //Cancel any existing double tap
				    clearTimeout(singleTapTimeout);
				           
					//If we are also looking for doubelTaps, wait incase this is one...
				    if(hasDoubleTap() && !inDoubleTap()) {
				        //Cache the time of this tap
                        doubleTapStartTime = getTimeStamp();
                       
				        //Now wait for the double tap timeout, and trigger this single tap
				        //if its not cancelled by a double tap
				        singleTapTimeout = setTimeout($.proxy(function() {
        			        doubleTapStartTime=null;
        			        //Trigger the event
                            $element.trigger('tap', [event.target]);
 
                        
                            //Fire the callback
                            if(options.tap) {
                                ret = options.tap.call($element, event, event.target);
                            }
    			        }, this), options.doubleTapThreshold );
    			    	
    			    } else {
                        doubleTapStartTime=null;
                        
                        //Trigger the event
                        $element.trigger('tap', [event.target]);
 
                        
                        //Fire the callback
                        if(options.tap) {
                            ret = options.tap.call($element, event, event.target);
                        }
	    		    }
	    		}
			}
			
			else if (gesture==DOUBLE_TAP) {
				if(phase === PHASE_CANCEL || phase === PHASE_END) {
					//Cancel any pending singletap 
				    clearTimeout(singleTapTimeout);
				    doubleTapStartTime=null;
				        
                    //Trigger the event
                    $element.trigger('doubletap', [event.target]);
                
                    //Fire the callback
                    if(options.doubleTap) {
                        ret = options.doubleTap.call($element, event, event.target);
                    }
	    		}
			}
			
			else if (gesture==LONG_TAP) {
				if(phase === PHASE_CANCEL || phase === PHASE_END) {
					//Cancel any pending singletap (shouldnt be one)
				    clearTimeout(singleTapTimeout);
				    doubleTapStartTime=null;
				        
                    //Trigger the event
                    $element.trigger('longtap', [event.target]);
                
                    //Fire the callback
                    if(options.longTap) {
                        ret = options.longTap.call($element, event, event.target);
                    }
	    		}
			}				
				
			return ret;
		}
 
 
 
		
		//
		// GESTURE VALIDATION
		//
		
		/**
		* Checks the user has swipe far enough
		* @return Boolean if <code>threshold</code> has been set, return true if the threshold was met, else false.
		* If no threshold was set, then we return true.
		* @inner
		*/
		function validateSwipeDistance() {
			var valid = true;
			//If we made it past the min swipe distance..
			if (options.threshold !== null) {
				valid = distance >= options.threshold;
			}
			
            return valid;
		}
		
		/**
		* Checks the user has swiped back to cancel.
		* @return Boolean if <code>cancelThreshold</code> has been set, return true if the cancelThreshold was met, else false.
		* If no cancelThreshold was set, then we return true.
		* @inner
		*/
		function didSwipeBackToCancel() {
            var cancelled = false;
    		if(options.cancelThreshold !== null && direction !==null)  {
    		    cancelled =  (getMaxDistance( direction ) - distance) >= options.cancelThreshold;
			}
			
			return cancelled;
		}
 
		/**
		* Checks the user has pinched far enough
		* @return Boolean if <code>pinchThreshold</code> has been set, return true if the threshold was met, else false.
		* If no threshold was set, then we return true.
		* @inner
		*/
		function validatePinchDistance() {
			if (options.pinchThreshold !== null) {
				return pinchDistance >= options.pinchThreshold;
			}
			return true;
		}
 
		/**
		* Checks that the time taken to swipe meets the minimum / maximum requirements
		* @return Boolean
		* @inner
		*/
		function validateSwipeTime() {
			var result;
			//If no time set, then return true
 
			if (options.maxTimeThreshold) {
				if (duration >= options.maxTimeThreshold) {
					result = false;
				} else {
					result = true;
				}
			}
			else {
				result = true;
			}
 
			return result;
		}
 
 
		/**
		* Checks direction of the swipe and the value allowPageScroll to see if we should allow or prevent the default behaviour from occurring.
		* This will essentially allow page scrolling or not when the user is swiping on a touchSwipe object.
		* @param {object} jqEvent The normalised jQuery representation of the event object.
		* @param {string} direction The direction of the event. See {@link $.fn.swipe.directions}
		* @see $.fn.swipe.directions
		* @inner
		*/
		function validateDefaultEvent(jqEvent, direction) {
			if (options.allowPageScroll === NONE || hasPinches()) {
				jqEvent.preventDefault();
			} else {
				var auto = options.allowPageScroll === AUTO;
 
				switch (direction) {
					case LEFT:
						if ((options.swipeLeft && auto) || (!auto && options.allowPageScroll != HORIZONTAL)) {
							jqEvent.preventDefault();
						}
						break;
 
					case RIGHT:
						if ((options.swipeRight && auto) || (!auto && options.allowPageScroll != HORIZONTAL)) {
							jqEvent.preventDefault();
						}
						break;
 
					case UP:
						if ((options.swipeUp && auto) || (!auto && options.allowPageScroll != VERTICAL)) {
							jqEvent.preventDefault();
						}
						break;
 
					case DOWN:
						if ((options.swipeDown && auto) || (!auto && options.allowPageScroll != VERTICAL)) {
							jqEvent.preventDefault();
						}
						break;
				}
			}
 
		}
 
 
		// PINCHES
		/**
		 * Returns true of the current pinch meets the thresholds
		 * @return Boolean
		 * @inner
		*/
		function validatePinch() {
		    var hasCorrectFingerCount = validateFingers();
		    var hasEndPoint = validateEndPoint();
			var hasCorrectDistance = validatePinchDistance();
			return hasCorrectFingerCount && hasEndPoint && hasCorrectDistance;
			
		}
		
		/**
		 * Returns true if any Pinch events have been registered
		 * @return Boolean
		 * @inner
		*/
		function hasPinches() {
			//Enure we dont return 0 or null for false values
			return !!(options.pinchStatus || options.pinchIn || options.pinchOut);
		}
		
		/**
		 * Returns true if we are detecting pinches, and have one
		 * @return Boolean
		 * @inner
		 */
		function didPinch() {
			//Enure we dont return 0 or null for false values
			return !!(validatePinch() && hasPinches());
		}
 
 
 
 
		// SWIPES
		/**
		 * Returns true if the current swipe meets the thresholds
		 * @return Boolean
		 * @inner
		*/
		function validateSwipe() {
			//Check validity of swipe
			var hasValidTime = validateSwipeTime();
			var hasValidDistance = validateSwipeDistance();	
			var hasCorrectFingerCount = validateFingers();
		    var hasEndPoint = validateEndPoint();
		    var didCancel = didSwipeBackToCancel();	
		    
			// if the user swiped more than the minimum length, perform the appropriate action
			// hasValidDistance is null when no distance is set 
			var valid =  !didCancel && hasEndPoint && hasCorrectFingerCount && hasValidDistance && hasValidTime;
			
			return valid;
		}
		
		/**
		 * Returns true if any Swipe events have been registered
		 * @return Boolean
		 * @inner
		*/
		function hasSwipes() {
			//Enure we dont return 0 or null for false values
			return !!(options.swipe || options.swipeStatus || options.swipeLeft || options.swipeRight || options.swipeUp || options.swipeDown);
		}
		
		
		/**
		 * Returns true if we are detecting swipes and have one
		 * @return Boolean
		 * @inner
		*/
		function didSwipe() {
			//Enure we dont return 0 or null for false values
			return !!(validateSwipe() && hasSwipes());
		}
 
        /**
		 * Returns true if we have matched the number of fingers we are looking for
		 * @return Boolean
		 * @inner
		*/
        function validateFingers() {
            //The number of fingers we want were matched, or on desktop we ignore
    		return ((fingerCount === options.fingers || options.fingers === ALL_FINGERS) || !SUPPORTS_TOUCH);
    	}
        
        /**
		 * Returns true if we have an end point for the swipe
		 * @return Boolean
		 * @inner
		*/
        function validateEndPoint() {
            //We have an end value for the finger
		    return fingerData[0].end.x !== 0;
        }
 
		// TAP / CLICK
		/**
		 * Returns true if a click / tap events have been registered
		 * @return Boolean
		 * @inner
		*/
		function hasTap() {
			//Enure we dont return 0 or null for false values
			return !!(options.tap) ;
		}
		
		/**
		 * Returns true if a double tap events have been registered
		 * @return Boolean
		 * @inner
		*/
		function hasDoubleTap() {
			//Enure we dont return 0 or null for false values
			return !!(options.doubleTap) ;
		}
		
		/**
		 * Returns true if any long tap events have been registered
		 * @return Boolean
		 * @inner
		*/
		function hasLongTap() {
			//Enure we dont return 0 or null for false values
			return !!(options.longTap) ;
		}
		
		/**
		 * Returns true if we could be in the process of a double tap (one tap has occurred, we are listening for double taps, and the threshold hasn't past.
		 * @return Boolean
		 * @inner
		*/
		function validateDoubleTap() {
		    if(doubleTapStartTime==null){
		        return false;
		    }
		    var now = getTimeStamp();
		    return (hasDoubleTap() && ((now-doubleTapStartTime) <= options.doubleTapThreshold));
		}
		
		/**
		 * Returns true if we could be in the process of a double tap (one tap has occurred, we are listening for double taps, and the threshold hasn't past.
		 * @return Boolean
		 * @inner
		*/
		function inDoubleTap() {
		    return validateDoubleTap();
		}
		
		
		/**
		 * Returns true if we have a valid tap
		 * @return Boolean
		 * @inner
		*/
		function validateTap() {
		    return ((fingerCount === 1 || !SUPPORTS_TOUCH) && (isNaN(distance) || distance === 0));
		}
		
		/**
		 * Returns true if we have a valid long tap
		 * @return Boolean
		 * @inner
		*/
		function validateLongTap() {
		    //slight threshold on moving finger
            return ((duration > options.longTapThreshold) && (distance < DOUBLE_TAP_THRESHOLD)); 
		}
		
		/**
		 * Returns true if we are detecting taps and have one
		 * @return Boolean
		 * @inner
		*/
		function didTap() {
		    //Enure we dont return 0 or null for false values
			return !!(validateTap() && hasTap());
		}
		
		
		/**
		 * Returns true if we are detecting double taps and have one
		 * @return Boolean
		 * @inner
		*/
		function didDoubleTap() {
		    //Enure we dont return 0 or null for false values
			return !!(validateDoubleTap() && hasDoubleTap());
		}
		
		/**
		 * Returns true if we are detecting long taps and have one
		 * @return Boolean
		 * @inner
		*/
		function didLongTap() {
		    //Enure we dont return 0 or null for false values
			return !!(validateLongTap() && hasLongTap());
		}
		
		
		
		
		// MULTI FINGER TOUCH
		/**
		 * Starts tracking the time between 2 finger releases, and keeps track of how many fingers we initially had up
		 * @inner
		*/
		function startMultiFingerRelease() {
			previousTouchEndTime = getTimeStamp();
			previousTouchFingerCount = event.touches.length+1;
		}
		
		/**
		 * Cancels the tracking of time between 2 finger releases, and resets counters
		 * @inner
		*/
		function cancelMultiFingerRelease() {
			previousTouchEndTime = 0;
			previousTouchFingerCount = 0;
		}
		
		/**
		 * Checks if we are in the threshold between 2 fingers being released 
		 * @return Boolean
		 * @inner
		*/
		function inMultiFingerRelease() {
			
			var withinThreshold = false;
			
			if(previousTouchEndTime) {	
				var diff = getTimeStamp() - previousTouchEndTime	
				if( diff<=options.fingerReleaseThreshold ) {
					withinThreshold = true;
				}
			}
			
			return withinThreshold;	
		}
		
 
		/**
		* gets a data flag to indicate that a touch is in progress
		* @return Boolean
		* @inner
		*/
		function getTouchInProgress() {
			//strict equality to ensure only true and false are returned
			return !!($element.data(PLUGIN_NS+'_intouch') === true);
		}
		
		/**
		* Sets a data flag to indicate that a touch is in progress
		* @param {boolean} val The value to set the property to
		* @inner
		*/
		function setTouchInProgress(val) {
			
			//Add or remove event listeners depending on touch status
			if(val===true) {
				$element.bind(MOVE_EV, touchMove);
				$element.bind(END_EV, touchEnd);
				
				//we only have leave events on desktop, we manually calcuate leave on touch as its not supported in webkit
				if(LEAVE_EV) { 
					$element.bind(LEAVE_EV, touchLeave);
				}
			} else {
				$element.unbind(MOVE_EV, touchMove, false);
				$element.unbind(END_EV, touchEnd, false);
			
				//we only have leave events on desktop, we manually calcuate leave on touch as its not supported in webkit
				if(LEAVE_EV) { 
					$element.unbind(LEAVE_EV, touchLeave, false);
				}
			}
			
		
			//strict equality to ensure only true and false can update the value
			$element.data(PLUGIN_NS+'_intouch', val === true);
		}
		
		
		/**
		 * Creates the finger data for the touch/finger in the event object.
		 * @param {int} index The index in the array to store the finger data (usually the order the fingers were pressed)
		 * @param {object} evt The event object containing finger data
		 * @return finger data object
		 * @inner
		*/
		function createFingerData( index, evt ) {
			var id = evt.identifier!==undefined ? evt.identifier : 0; 
			
			fingerData[index].identifier = id;
			fingerData[index].start.x = fingerData[index].end.x = evt.pageX||evt.clientX;
			fingerData[index].start.y = fingerData[index].end.y = evt.pageY||evt.clientY;
			
			return fingerData[index];
		}
		
		/**
		 * Updates the finger data for a particular event object
		 * @param {object} evt The event object containing the touch/finger data to upadte
		 * @return a finger data object.
		 * @inner
		*/
		function updateFingerData(evt) {
			
			var id = evt.identifier!==undefined ? evt.identifier : 0; 
			var f = getFingerData( id );
			
			f.end.x = evt.pageX||evt.clientX;
			f.end.y = evt.pageY||evt.clientY;
			
			return f;
		}
		
		/**
		 * Returns a finger data object by its event ID.
		 * Each touch event has an identifier property, which is used 
		 * to track repeat touches
		 * @param {int} id The unique id of the finger in the sequence of touch events.
		 * @return a finger data object.
		 * @inner
		*/
		function getFingerData( id ) {
			for(var i=0; i<fingerData.length; i++) {
				if(fingerData[i].identifier == id) {
					return fingerData[i];	
				}
			}
		}
		
		/**
		 * Creats all the finger onjects and returns an array of finger data
		 * @return Array of finger objects
		 * @inner
		*/
		function createAllFingerData() {
			var fingerData=[];
			for (var i=0; i<=5; i++) {
				fingerData.push({
					start:{ x: 0, y: 0 },
					end:{ x: 0, y: 0 },
					identifier:0
				});
			}
			
			return fingerData;
		}
		
		/**
		 * Sets the maximum distance swiped in the given direction. 
		 * If the new value is lower than the current value, the max value is not changed.
		 * @param {string}  direction The direction of the swipe
		 * @param {int}  distance The distance of the swipe
		 * @inner
		*/
		function setMaxDistance(direction, distance) {
    		distance = Math.max(distance, getMaxDistance(direction) );
    		maximumsMap[direction].distance = distance;
		}
        
        /**
		 * gets the maximum distance swiped in the given direction. 
		 * @param {string}  direction The direction of the swipe
		 * @return int  The distance of the swipe
		 * @inner
		*/        
        function getMaxDistance(direction) {
            return maximumsMap[direction].distance;
        }
		
		/**
		 * Creats a map of directions to maximum swiped values.
		 * @return Object A dictionary of maximum values, indexed by direction.
		 * @inner
		*/
		function createMaximumsData() {
			var maxData={};
			maxData[LEFT]=createMaximumVO(LEFT);
			maxData[RIGHT]=createMaximumVO(RIGHT);
			maxData[UP]=createMaximumVO(UP);
			maxData[DOWN]=createMaximumVO(DOWN);
			
			return maxData;
		}
		
		/**
		 * Creates a map maximum swiped values for a given swipe direction
		 * @param {string} The direction that these values will be associated with
		 * @return Object Maximum values
		 * @inner
		*/
		function createMaximumVO(dir) {
		    return { 
		        direction:dir, 
		        distance:0
		    }
		}
		
		
		//
		// MATHS / UTILS
		//
 
		/**
		* Calculate the duration of the swipe
		* @return int
		* @inner
		*/
		function calculateDuration() {
			return endTime - startTime;
		}
		
		/**
		* Calculate the distance between 2 touches (pinch)
		* @param {point} startPoint A point object containing x and y co-ordinates
	    * @param {point} endPoint A point object containing x and y co-ordinates
	    * @return int;
		* @inner
		*/
		function calculateTouchesDistance(startPoint, endPoint) {
			var diffX = Math.abs(startPoint.x - endPoint.x);
			var diffY = Math.abs(startPoint.y - endPoint.y);
				
			return Math.round(Math.sqrt(diffX*diffX+diffY*diffY));
		}
		
		/**
		* Calculate the zoom factor between the start and end distances
		* @param {int} startDistance Distance (between 2 fingers) the user started pinching at
	    * @param {int} endDistance Distance (between 2 fingers) the user ended pinching at
	    * @return float The zoom value from 0 to 1.
		* @inner
		*/
		function calculatePinchZoom(startDistance, endDistance) {
			var percent = (endDistance/startDistance) * 1;
			return percent.toFixed(2);
		}
		
		
		/**
		* Returns the pinch direction, either IN or OUT for the given points
		* @return string Either {@link $.fn.swipe.directions.IN} or {@link $.fn.swipe.directions.OUT}
		* @see $.fn.swipe.directions
		* @inner
		*/
		function calculatePinchDirection() {
			if(pinchZoom<1) {
				return OUT;
			}
			else {
				return IN;
			}
		}
		
		
		/**
		* Calculate the length / distance of the swipe
		* @param {point} startPoint A point object containing x and y co-ordinates
	    * @param {point} endPoint A point object containing x and y co-ordinates
	    * @return int
		* @inner
		*/
		function calculateDistance(startPoint, endPoint) {
			return Math.round(Math.sqrt(Math.pow(endPoint.x - startPoint.x, 2) + Math.pow(endPoint.y - startPoint.y, 2)));
		}
 
		/**
		* Calculate the angle of the swipe
		* @param {point} startPoint A point object containing x and y co-ordinates
	    * @param {point} endPoint A point object containing x and y co-ordinates
	    * @return int
		* @inner
		*/
		function calculateAngle(startPoint, endPoint) {
			var x = startPoint.x - endPoint.x;
			var y = endPoint.y - startPoint.y;
			var r = Math.atan2(y, x); //radians
			var angle = Math.round(r * 180 / Math.PI); //degrees
 
			//ensure value is positive
			if (angle < 0) {
				angle = 360 - Math.abs(angle);
			}
 
			return angle;
		}
 
		/**
		* Calculate the direction of the swipe
		* This will also call calculateAngle to get the latest angle of swipe
		* @param {point} startPoint A point object containing x and y co-ordinates
	    * @param {point} endPoint A point object containing x and y co-ordinates
	    * @return string Either {@link $.fn.swipe.directions.LEFT} / {@link $.fn.swipe.directions.RIGHT} / {@link $.fn.swipe.directions.DOWN} / {@link $.fn.swipe.directions.UP}
		* @see $.fn.swipe.directions
		* @inner
		*/
		function calculateDirection(startPoint, endPoint ) {
			var angle = calculateAngle(startPoint, endPoint);
 
			if ((angle <= 45) && (angle >= 0)) {
				return LEFT;
			} else if ((angle <= 360) && (angle >= 315)) {
				return LEFT;
			} else if ((angle >= 135) && (angle <= 225)) {
				return RIGHT;
			} else if ((angle > 45) && (angle < 135)) {
				return DOWN;
			} else {
				return UP;
			}
		}
		
 
		/**
		* Returns a MS time stamp of the current time
		* @return int
		* @inner
		*/
		function getTimeStamp() {
			var now = new Date();
			return now.getTime();
		}
		
		
		
		/**
		 * Returns a bounds object with left, right, top and bottom properties for the element specified.
		 * @param {DomNode} The DOM node to get the bounds for.
		 */
		function getbounds( el ) {
			el = $(el);
			var offset = el.offset();
			
			var bounds = {	
					left:offset.left,
					right:offset.left+el.outerWidth(),
					top:offset.top,
					bottom:offset.top+el.outerHeight()
					}
			
			return bounds;	
		}
		
		
		/**
		 * Checks if the point object is in the bounds object.
		 * @param {object} point A point object.
		 * @param {int} point.x The x value of the point.
		 * @param {int} point.y The x value of the point.
		 * @param {object} bounds The bounds object to test
		 * @param {int} bounds.left The leftmost value
		 * @param {int} bounds.right The righttmost value
		 * @param {int} bounds.top The topmost value
		* @param {int} bounds.bottom The bottommost value
		 */
		function isInBounds(point, bounds) {
			return (point.x > bounds.left && point.x < bounds.right && point.y > bounds.top && point.y < bounds.bottom);
		};
	
	
	}
	
	
 
 
/**
 * A catch all handler that is triggered for all swipe directions. 
 * @name $.fn.swipe#swipe
 * @event
 * @default null
 * @param {EventObject} event The original event object
 * @param {int} direction The direction the user swiped in. See {@link $.fn.swipe.directions}
 * @param {int} distance The distance the user swiped
 * @param {int} duration The duration of the swipe in milliseconds
 * @param {int} fingerCount The number of fingers used. See {@link $.fn.swipe.fingers}
 */
 
 
 
 
/**
 * A handler that is triggered for "left" swipes.
 * @name $.fn.swipe#swipeLeft
 * @event
 * @default null
 * @param {EventObject} event The original event object
 * @param {int} direction The direction the user swiped in. See {@link $.fn.swipe.directions}
 * @param {int} distance The distance the user swiped
 * @param {int} duration The duration of the swipe in milliseconds
 * @param {int} fingerCount The number of fingers used. See {@link $.fn.swipe.fingers}
 */
 
/**
 * A handler that is triggered for "right" swipes.
 * @name $.fn.swipe#swipeRight
 * @event
 * @default null
 * @param {EventObject} event The original event object
 * @param {int} direction The direction the user swiped in. See {@link $.fn.swipe.directions}
 * @param {int} distance The distance the user swiped
 * @param {int} duration The duration of the swipe in milliseconds
 * @param {int} fingerCount The number of fingers used. See {@link $.fn.swipe.fingers}
 */
 
/**
 * A handler that is triggered for "up" swipes.
 * @name $.fn.swipe#swipeUp
 * @event
 * @default null
 * @param {EventObject} event The original event object
 * @param {int} direction The direction the user swiped in. See {@link $.fn.swipe.directions}
 * @param {int} distance The distance the user swiped
 * @param {int} duration The duration of the swipe in milliseconds
 * @param {int} fingerCount The number of fingers used. See {@link $.fn.swipe.fingers}
 */
 
/**
 * A handler that is triggered for "down" swipes.
 * @name $.fn.swipe#swipeDown
 * @event
 * @default null
 * @param {EventObject} event The original event object
 * @param {int} direction The direction the user swiped in. See {@link $.fn.swipe.directions}
 * @param {int} distance The distance the user swiped
 * @param {int} duration The duration of the swipe in milliseconds
 * @param {int} fingerCount The number of fingers used. See {@link $.fn.swipe.fingers}
 */
 
/**
 * A handler triggered for every phase of the swipe. This handler is constantly fired for the duration of the pinch.
 * This is triggered regardless of swipe thresholds.
 * @name $.fn.swipe#swipeStatus
 * @event
 * @default null
 * @param {EventObject} event The original event object
 * @param {string} phase The phase of the swipe event. See {@link $.fn.swipe.phases}
 * @param {string} direction The direction the user swiped in. This is null if the user has yet to move. See {@link $.fn.swipe.directions}
 * @param {int} distance The distance the user swiped. This is 0 if the user has yet to move.
 * @param {int} duration The duration of the swipe in milliseconds
 * @param {int} fingerCount The number of fingers used. See {@link $.fn.swipe.fingers}
 */
 
/**
 * A handler triggered for pinch in events.
 * @name $.fn.swipe#pinchIn
 * @event
 * @default null
 * @param {EventObject} event The original event object
 * @param {int} direction The direction the user pinched in. See {@link $.fn.swipe.directions}
 * @param {int} distance The distance the user pinched
 * @param {int} duration The duration of the swipe in milliseconds
 * @param {int} fingerCount The number of fingers used. See {@link $.fn.swipe.fingers}
 * @param {int} zoom The zoom/scale level the user pinched too, 0-1.
 */
 
/**
 * A handler triggered for pinch out events.
 * @name $.fn.swipe#pinchOut
 * @event
 * @default null
 * @param {EventObject} event The original event object
 * @param {int} direction The direction the user pinched in. See {@link $.fn.swipe.directions}
 * @param {int} distance The distance the user pinched
 * @param {int} duration The duration of the swipe in milliseconds
 * @param {int} fingerCount The number of fingers used. See {@link $.fn.swipe.fingers}
 * @param {int} zoom The zoom/scale level the user pinched too, 0-1.
 */ 
 
/**
 * A handler triggered for all pinch events. This handler is constantly fired for the duration of the pinch. This is triggered regardless of thresholds.
 * @name $.fn.swipe#pinchStatus
 * @event
 * @default null
 * @param {EventObject} event The original event object
 * @param {int} direction The direction the user pinched in. See {@link $.fn.swipe.directions}
 * @param {int} distance The distance the user pinched
 * @param {int} duration The duration of the swipe in milliseconds
 * @param {int} fingerCount The number of fingers used. See {@link $.fn.swipe.fingers}
 * @param {int} zoom The zoom/scale level the user pinched too, 0-1.
 */
 
/**
 * A click handler triggered when a user simply clicks, rather than swipes on an element.
 * This is deprecated since version 1.6.2, any assignment to click will be assigned to the tap handler.
 * You cannot use <code>on</code> to bind to this event as the default jQ <code>click</code> event will be triggered.
 * Use the <code>tap</code> event instead.
 * @name $.fn.swipe#click
 * @event
 * @deprecated since version 1.6.2, please use {@link $.fn.swipe#tap} instead 
 * @default null
 * @param {EventObject} event The original event object
 * @param {DomObject} target The element clicked on.
 */
 
 /**
 * A click / tap handler triggered when a user simply clicks or taps, rather than swipes on an element.
 * @name $.fn.swipe#tap
 * @event
 * @default null
 * @param {EventObject} event The original event object
 * @param {DomObject} target The element clicked on.
 */
 
/**
 * A double tap handler triggered when a user double clicks or taps on an element.
 * You can set the time delay for a double tap with the {@link $.fn.swipe.defaults#doubleTapThreshold} property. 
 * Note: If you set both <code>doubleTap</code> and <code>tap</code> handlers, the <code>tap</code> event will be delayed by the <code>doubleTapThreshold</code>
 * as the script needs to check if its a double tap.
 * @name $.fn.swipe#doubleTap
 * @see  $.fn.swipe.defaults#doubleTapThreshold
 * @event
 * @default null
 * @param {EventObject} event The original event object
 * @param {DomObject} target The element clicked on.
 */
 
 /**
 * A long tap handler triggered when a user long clicks or taps on an element.
 * You can set the time delay for a long tap with the {@link $.fn.swipe.defaults#longTapThreshold} property. 
 * @name $.fn.swipe#longTap
 * @see  $.fn.swipe.defaults#longTapThreshold
 * @event
 * @default null
 * @param {EventObject} event The original event object
 * @param {DomObject} target The element clicked on.
 */
 
})(jQuery);

/*
 * @package		YerSlider
 * @version		1.6.1
 * @date		2014-09-17
 * @time		20:46:32
 * @license		MIT or GPL
 * @repository	https://github.com/johannheyne/yerslider.git
 * @homepage	http://demo.johannheyne.de/yerslider/demo/
 */
function YerSliderThumbs(){var a=this;a.param={thumbswrapclass:".yerslider-thumbs-wrap",thumbsmaskclass:".yerslider-thumbs-mask",thumbsitemsclass:".yerslider-thumbs-items",thumbsitemclass:".yerslider-thumbs-item",hasthumbsclass:"yerslider-has-thumbs",moveoffset:60,touch:!1},a.stat={itemspos_x:0,itemswidth:!1,maskwidth:!1,maskleft:0,diff:0,mousepos_x:!1,is_animating:!1,isresizing:!1},a.obj={thumbswrap:void 0,thumbsitems:void 0,thumbsitem:void 0},a.init=function(b){b&&(a.obj=a.helper.setDefaultParam({p:b.obj,d:a.obj}),a.param=a.helper.setDefaultParam({p:b.param,d:a.param})),a.param.touch||(window.setTimeout(function(){a.process()},500),jQuery(window).resize(function(){window.setTimeout(function(){a.stat.isresizing||(a.stat.isresizing=!0,a.obj.thumbsviewport.unbind("mouseenter"),a.obj.thumbsviewport.unbind("mousemove"),a.obj.thumbsitems.css("left","0px"),a.process(),a.stat.isresizing=!1)},100)}))},a.process=function(){a.obj.thumbsitems.imagesLoaded().always(function(){a.stat.itemspos_x=0,a.stat.maskwidth=a.obj.thumbsmask.innerWidth(),a.stat.itemswidth=a.obj.thumbsitem.last().position().left+a.obj.thumbsitem.last().outerWidth(),a.stat.diff=a.stat.itemswidth-a.stat.maskwidth,a.stat.maskleft=a.obj.thumbsmask.offset().left,a.obj.thumbsviewport.on("mouseenter",function(b){a.set_itemspos(b),a.stat.is_animating=!0,a.obj.thumbsitems.animate({left:"-"+a.stat.itemspos_x+"px"},200,function(){a.stat.is_animating=!1})}),a.obj.thumbsviewport.on("mousemove",function(b){a.stat.is_animating||(a.set_itemspos(b),a.obj.thumbsitems.css("left","-"+a.stat.itemspos_x+"px"))}).mouseover()})},a.set_itemspos=function(b){var c=b.pageX-a.param.moveoffset-a.stat.maskleft;0>c&&(c=0),c>a.stat.maskwidth-2*a.param.moveoffset&&(c=a.stat.maskwidth-2*a.param.moveoffset);var d=100/(a.stat.maskwidth-2*a.param.moveoffset)*c;a.stat.itemspos_x=a.stat.diff/100*d},a.helper={getLength:function(a){var b=a.length?--a.length:-1;for(var c in a)b++;return b},setDefaultParam:function(b){"undefined"==typeof b&&(b={}),"undefined"==typeof b.p&&(b.p={}),"undefined"==typeof b.d&&(b.d={});var c=b.p;for(var d in b.d)"undefined"!=typeof b.d[d]&&typeof c[d]!=typeof b.d[d]?c[d]=b.d[d]:"undefined"!=typeof b.d[d]&&a.helper.getLength(c[d])!==a.helper.getLength(b.d[d])&&(c[d]=a.helper.setDefaultParam({p:c[d],d:b.d[d]}));return c}}}

/*
 * @package		YerSlider
 * @version		1.6.1
 * @date		2014-09-17
 * @time		20:46:30
 * @license		MIT or GPL
 * @repository	https://github.com/johannheyne/yerslider.git
 * @homepage	http://demo.johannheyne.de/yerslider/demo/
 */
function onYouTubeIframeAPIReady(){yerslider.youtubeready=!0}function YerSlider(){var a=this;a.param={yersliderfolderabsolutepath:"/",sliderid:".yerslider",imagesloaded:["slide","thumbs"],loadingmessagedelay:30,slidegap:0,slidingstep:void 0,slidegroup:1,slidegroupresp:{},swipe:!1,swipeandprevnextbtn:!1,swipeanimationspeed:300,animationtype:"ease",animationspeed:1e3,loop:"none",loopswipe:"none",autoplay:!1,autoplayinterval:3e3,autoplaybreakmin:10,autoplaybreak:!1,autoplaydelaystart:0,autoplaystoponhover:!0,autoplaycontinuously:!1,autoplaycontinuouslyspeed:1e4,autoplaycontinuouslystoponhover:!0,sliderclass:".yerslider",sliderwrapclass:".yerslider-wrap",sliderviewportclass:".yerslider-viewport",slidermaskclass:".yerslider-mask",sliderclass:".yerslider-slider",slideclass:".yerslider-slide",loadingclass:".yerslider-loading",nextbtn:!0,prevbtn:!0,prevnextlocation:"inside",prevnextclass:".yerslider-prevnext",nextclass:".yerslider-next",prevclass:".yerslider-prev",nextinactiveclass:".yerslider-next-inactive",previnactiveclass:".yerslider-prev-inactive",nextclassadd:"",prevclassadd:"",bullets:!1,bulletslocation:"outside",bulletclickable:!0,sliderwrapclasshasbullets:".yerslider-has-bullets",bulletswrapclass:".yerslider-bullets-wrap",bulletclass:".yerslider-bullet",bulletcurrentclass:".yerslider-bullet-current",thumbs:!1,thumbshideiflessthan:2,thumbslocation:"outside",thumbstemplates:{},thumbsclickable:!0,sliderwrapclasshasthumbs:".yerslider-has-thumbs",thumbswrapclass:".yerslider-thumbs-wrap",thumbsviewportclass:".yerslider-thumbs-viewport",thumbsmaskclass:".yerslider-thumbs-mask",thumbsitemsclass:".yerslider-thumbs-items",thumbsitemclass:".yerslider-thumbs-item",thumbsready:void 0,sublimevideo:!1,autoloadyoutubeiframeapi:!0,videoplayercloseafterend:!0,scrolltop:!1,scrolltopval:0,scrolltopspeed:500,dependencies_autoload:!0,sliderready:void 0,detach:void 0},a.stat={slidegroupmax:1,slidegroup:1,currentslideindex:0,currentslideposition:0,slidecount:0,slidermaskwidth:0,slidewidth:0,isanimating:!1,isswiping:!1,lasteventtype:!1,nextbtnclickable:!1,prevbtnclickable:!1,bulletscount:0,bulletscountcache:0,bulletcurrent:0,bulletschanged:!1,slidingleft:!1,slidingright:!1,resizing:!1,cssanimation:!1,isevent:!1,touch:!1,clicktype:"click",isios:!1,isresizing:!1,slidesinviewportindexbegin:!1,slidesinviewportindexend:!1,slidesinviewportindexes:!1,autoplayinterval:!1,autoplayison:!1,videoidindex:0,videoplayerindex:0,lastplayedvideo:!1,videoisplaying:!1,loop:!1,dependencies_loaded:0,dependencies_count:0,browser_features:[],loadingtimeout:!1,isloading:!1},a.obj={sliderid:void 0,sliderwrap:void 0,sliderviewport:void 0,slider:void 0,slide:void 0,bulletswrap:void 0,bullets:void 0,prevbtn:void 0,nextbtn:void 0,videoplayers:{},slides_videoplayers:{},thumbswrap:void 0,thumbsviewport:void 0,thumbsitems:void 0,thumbsitem:void 0,slidesinviewport:void 0},a.init=function(b){a.init_getdefaultparam(b),a.init_get_browser_features(),a.init_touch(),a.init_param_changin(),jQuery(a.param.sliderid).length>0&&(a.init_dependencies(),a.helper.when(a.stat,{key:"dependencies_loaded",val:a.stat.dependencies_count,callback_if:a.init_workflow}))},a.init_get_browser_features=function(){a.stat.browser_features=jQuery("html").attr("class").split(/\s+/)},a.init_dependencies=function(){a.param.dependencies_autoload&&("undefined"==typeof YT&&a.param.autoloadyoutubeiframeapi&&!yerslider.youtubeloaded?jQuery.getScript("https://www.youtube.com/iframe_api",function(){a.stat.dependencies_loaded++,yerslider.youtubeloaded=!0}):a.stat.dependencies_loaded++,"undefined"==typeof jQuery.fn.imagesLoaded?a.init_dependencies_path({file:a.param.yersliderfolderabsolutepath+"dependencies/imagesloaded.js"}):a.stat.dependencies_loaded++,"undefined"==typeof jQuery.fn.swipe&&-1!==jQuery.inArray("touch",a.stat.browser_features)&&a.param.swipe===!0?a.init_dependencies_path({file:a.param.yersliderfolderabsolutepath+"dependencies/jquery.touchSwipe.js"}):a.stat.dependencies_loaded++,a.stat.dependencies_count=3)},a.init_dependencies_path=function(b){jQuery.ajax({url:b.file,dataType:"script",cache:!0,async:!0,success:function(){a.stat.dependencies_loaded++}})},a.init_workflow=function(){a.init_animation(),a.init_isios(),a.init_ojects(),a.init_css(),a.obj.sliderwrap.css({opacity:"0"}),a.set_slidermaskwidth(),a.set_slidecount(),a.set_slidegroup(),a.set_slidegroupmax(),a.set_slidesinviewport(),a.set_currentslidesindexs(),a.set_slidewidth(),a.set_slideheight(),a.clon_slides(),a.set_prevnext(),a.thumbs(),a.bullets(),a.init_detach(),a.init_touchswipe(),a.init_iosresizeclickbug(),a.init_video(),a.slides_equal_height(),a.init_showslider()},a.init_slider_ready=function(){"function"==typeof a.param.sliderready&&a.obj.slider.imagesLoaded().always(function(){var b={obj:a.obj,param:a.param};a.param.sliderready(b)})},a.init_getdefaultparam=function(b){a.param=a.helper.setDefaultParam({p:b,d:a.param}),"undefined"!=typeof YerSliderGlobals&&jQuery.extend(!0,a.param,YerSliderGlobals.param),a.param.autoplaybreak&&(a.param.autoplayinterval=a.param.animationspeed+a.param.autoplaybreak),a.param.autoplayinterval<=a.param.animationspeed&&(a.param.autoplayinterval=a.param.animationspeed+a.param.autoplaybreakmin)},a.init_animation=function(){var b=jQuery("html");b.hasClass("csstransitions")&&b.hasClass("csstransforms3d")&&b.hasClass("cssanimations")&&a.param.slidegroup>0&&(a.stat.cssanimation=!0)},a.init_touch=function(){jQuery("html").hasClass("touch")&&(a.stat.touch=!0,a.stat.clicktype="touchend"),a.stat.touch&&a.param.swipe&&!a.param.swipeandprevnextbtn&&(a.param.nextbtn=!1,a.param.prevbtn=!1)},a.init_param_changin=function(){a.stat.loop=a.param.loop,a.stat.touch&&a.param.swipe&&(a.stat.loop=a.param.loopswipe),"object"==typeof a.param.dependencies_autoload&&(a.stat.dependencies_autoload=a.param.dependencies_autoload)},a.init_isios=function(){navigator.userAgent.match(/(iPod|iPhone|iPad)/)&&(a.stat.isios=!0)},a.init_ojects=function(){a.obj.sliderid=jQuery(a.param.sliderid),a.obj.sliderwrap=jQuery(a.param.sliderid),a.obj.sliderviewport=jQuery(a.param.sliderid+" "+a.param.sliderviewportclass),a.obj.slidermask=jQuery(a.param.sliderid+" "+a.param.slidermaskclass),a.obj.slider=jQuery(a.param.sliderid+" "+a.param.sliderclass),a.obj.slide=jQuery(a.param.sliderid+" "+a.param.slideclass)},a.init_css=function(){a.obj.sliderwrap.css({position:"relative"}),a.obj.slidermask.css({position:"relative",width:"100%",overflow:"hidden"}),a.obj.slider.css({"white-space":"nowrap",position:"relative","list-style-type":"none",padding:0,margin:0,"line-height":0,"font-size":0}),a.obj.slide.css({display:"inline-block","white-space":"normal"})},a.init_touchswipe=function(){a.stat.touch&&a.param.swipe&&a.touchswipe()},a.init_iosresizeclickbug=function(){jQuery(window).resize(function(){window.setTimeout(function(){a.stat.isresizing||a.stat.isevent||a.resize()},100)});var b;jQuery(window).resize(function(){clearTimeout(b),b=setTimeout(function(){a.resize_end()},300)})},a.init_video=function(){jQuery(".yerslider-video-play").wrapInner('<div style="visibility: hidden;">');var b,c=0;a.obj.slide.each(function(){b=jQuery(this),b.data("slideindex",++c)}),a.obj.slider.find(".yerslider-video").each(function(){var c={video:{},play:{},player:{},preview:{}},d={videotype:!1,videoid:!1,previewimg:!1,autoplay:1,showinfo:0,rel:1,ratio:"16:9"};c.video=jQuery(this),jQuery.extend(d,c.video.data()),"auto"===d.previewimg&&("youtube"===d.videotype&&("16:9"===d.ratio&&c.video.append('<img class="yerslider-video-preview" src="http://img.youtube.com/vi/'+d.videoid+'/mqdefault.jpg"/>'),"4:3"===d.ratio&&c.video.append('<img class="yerslider-video-preview" class="" src="http://img.youtube.com/vi/'+d.videoid+'/0.jpg"/>')),c.preview=c.video.find(".yerslider-video-preview")),"youtube"===d.videotype?(c.play=c.video.find(".yerslider-video-play"),c.play.on("click",function(e){e.preventDefault(),c.preview.hide(),c.play.hide();var f=0,g=setInterval(function(){yerslider.youtubeready&&(c.video.append('<div id="'+d.videoid+'" class="yerslider-video-player">'),c.player=c.video.find(".yerslider-video-player"),a.obj.videoplayers[d.videoid]={type:"youtube",id:d.videoid,slide:c.video.parents(".yerslider-slide").data("slideindex"),api:new YT.Player(d.videoid,{videoId:d.videoid,playerVars:{rel:d.rel,autoplay:d.autoplay,showinfo:d.showinfo,wmode:"opaque",events:{onReady:a.player_fix_ratio(d.videoid)}}}),status:!1},a.obj.videoplayers[d.videoid].status="started",a.obj.videoplayers[d.videoid].api.addEventListener("onStateChange",a.player_youtube_statechange),1===d.autoplay&&(a.stat.videoisplaying=!0,a.autoplayclear()),a.stat.lastplayedvideo=d.videoid,"undefined"==typeof a.obj.slides_videoplayers[b.data("slideindex")]&&(a.obj.slides_videoplayers[b.data("slideindex")]={}),a.obj.slides_videoplayers[b.data("slideindex")][d.videoid]=!0,clearInterval(g)),++f>600&&clearInterval(g)},100);a.stat.videoidindex++})):"vimeo"===param.videotype||"sublimevideo"===param.videotype})},a.init_showslider=function(){if(-1!==jQuery.inArray("slider",a.param.imagesloaded))a.obj.slider.imagesLoaded().always(function(){a.init_showslider_job()});else if(-1!==jQuery.inArray("slide",a.param.imagesloaded)){var b=0,c=a.stat.slidegroup;for(var d in a.stat.slidesinviewportindexes)jQuery(a.obj.slide[a.stat.slidesinviewportindexes[d]-1]).imagesLoaded().always(function(){b+=1,b===c&&a.init_showslider_job()})}else a.init_showslider_job()},a.init_showslider_job=function(){a.obj.sliderwrap.hide().css({opacity:"1",position:"relative",left:"0"}).fadeIn("slow",function(){a.init_slider_ready(),a.autoplayinit()}),a.init_detach_show()},a.init_showslides=function(){},a.init_detach=function(){if("object"==typeof a.param.detach){if("object"==typeof a.param.detach.targets)for(var b in a.param.detach.targets){var c=a.obj.sliderwrap;"string"==typeof a.param.detach.targets[b].insert_selector&&(-1===a.param.detach.targets[b].insert_selector.search(/\./)&&-1==a.param.detach.targets[b].insert_selector.search(/\#/)?("wrap"==a.param.detach.targets[b].insert_selector&&(c=a.obj.sliderwrap),"viewport"==a.param.detach.targets[b].insert_selector&&(c=a.obj.sliderviewport),"bullets"==a.param.detach.targets[b].insert_selector&&(c=a.obj.bulletswrap),"thumbs"==a.param.detach.targets[b].insert_selector&&(c=a.obj.thumbswrap)):c=jQuery(a.param.detach.targets[b].insert_selector)),"string"==typeof a.param.detach.targets[b].insert_method&&"string"==typeof a.param.detach.targets[b].template_wrap&&"string"==typeof a.param.detach.targets[b].template_item&&("before"===a.param.detach.targets[b].insert_method&&c.before(a.param.detach.targets[b].template_wrap),"after"===a.param.detach.targets[b].insert_method&&c.after(a.param.detach.targets[b].template_wrap),"append"===a.param.detach.targets[b].insert_method&&c.append(a.param.detach.targets[b].template_wrap),"prepend"===a.param.detach.targets[b].insert_method&&c.prepend(a.param.detach.targets[b].template_wrap))}if("object"==typeof a.param.detach.sources){var d={};a.obj.slide.each(function(){var b=jQuery(this),c={};for(var e in a.param.detach.sources){var f=a.param.detach.sources[e].target_id,g=a.param.detach.targets[f];"undefined"==typeof c[f]&&(c[f]=""),c[f]+="element"===a.param.detach.sources[e].source?b.find(a.param.detach.sources[e].selector).clone().wrap("<div>").parent().html():b.find(a.param.detach.sources[e].selector).html,"string"==typeof a.param.detach.sources[e].remove&&b.find(a.param.detach.sources[e].remove).remove()}for(var e in a.param.detach.sources){var f=a.param.detach.sources[e].target_id,g=a.param.detach.targets[f],h=g.template_item;"undefined"==typeof d[f]&&(d[f]=""),d[f]+=h.replace("{content}",c[f])}});for(var e in d){var f=a.param.detach.targets[e],g=jQuery(f.selector_wrap).html();jQuery(f.selector_wrap).html(g.replace("{content}",d[e])).hide().find(f.selector_item).hide()}}}},a.init_detach_show=function(){if("object"==typeof a.param.detach&&"object"==typeof a.param.detach.targets)for(var b in a.param.detach.targets){var c=a.param.detach.targets[b],d=jQuery(c.selector_wrap),e=(d.find(c.selector_item),void 0);e=a.init_detach_get_obj_slides_in_viewport(),e.show(),d.fadeIn("slow")}},a.init_detach_change_item=function(){if("object"==typeof a.param.detach&&"object"==typeof a.param.detach.targets)for(var b in a.param.detach.targets){var c=a.param.detach.targets[b],d=jQuery(c.selector_wrap),e=d.find(c.selector_item);obj_currents=void 0,obj_currents=a.init_detach_get_obj_slides_in_viewport();var f={items:e,items_current:obj_currents};c.change(f)}},a.init_detach_resize=function(){if("object"==typeof a.param.detach&&"object"==typeof a.param.detach.targets)for(var b in a.param.detach.targets){var c=a.param.detach.targets[b],d=jQuery(c.selector_wrap),e=d.find(c.selector_item);obj_currents=void 0,obj_currents=a.init_detach_get_obj_slides_in_viewport();var f=e.parents(".detach-target"),g=f.height();f.height(g),e.hide(),obj_currents.show(),f.height("auto")}},a.init_detach_get_obj_slides_in_viewport=function(){if("object"==typeof a.param.detach&&"object"==typeof a.param.detach.targets){for(var b in a.param.detach.targets){var c=a.param.detach.targets[b],d=(jQuery(c.selector_wrap),[]),e="";for(var f in a.stat.currentslidesindexes)d.push(c.selector_item+":nth-child("+a.stat.currentslidesindexes[f]+")");e=d.join(","),obj_currents=jQuery(e)}return jQuery(e)}},a.player_youtube_play=function(b){a.obj.videoplayers[b].api.playVideo(),a.stat.videoisplaying=!0},a.player_youtube_pause=function(b){a.obj.videoplayers[b].api.pauseVideo(),a.stat.videoisplaying=!1},a.player_youtube_statechange=function(b){0===b.data&&a.param.videoplayercloseafterend&&(a.player_remove(),a.param.autoplay&&a.stat.touch&&a.param.swipe&&a.autoplayset()),1===b.data&&-1===b.data&&a.param.autoplay&&a.autoplayclear(),2===b.data&&(a.stat.videoisplaying=!1,a.stat.touch&&a.param.autoplay&&a.autoplayset())},a.player_fix_ratio=function(a){if("undefined"!=typeof a){var b=jQuery("#"+a);b.height(b.width()/16*9)}else jQuery(".yerslider-video-player").each(function(){var a=jQuery(this);jQuery(a).height(jQuery(a).width()/16*9)})},a.player_remove=function(b){a.stat.videoisplaying=!1,"undefined"!=typeof b?jQuery("#"+b).parents(".yerslider-video").find(".yerslider-video-preview, .yerslider-video-play").show().find("#"+b).remove():(jQuery(".yerslider-video-player").remove(),jQuery(".yerslider-video-preview, .yerslider-video-play").show())},a.set_slidermaskwidth=function(){a.param.insidetablecellfix&&(a.obj.slider.hide(),a.obj.slidermask.css("width","100%")),a.stat.slidermaskwidth=a.obj.slidermask.innerWidth(),a.param.insidetablecellfix&&(a.obj.slidermask.css("width",a.obj.slidermask.width()+"px"),a.obj.slider.show())},a.set_slidecount=function(){a.stat.slidecount=a.obj.slide.size()},a.set_slidegroup=function(){var b=a.param.slidegroup;if(a.helper.getLength(a.param.slidegroupresp)>0)for(var c in a.param.slidegroupresp)c<=a.stat.slidermaskwidth&&(b=a.param.slidegroupresp[c]);b>=a.stat.slidecount&&(b=a.stat.slidecount,a.stat.currentslideindex=0,a.move_slider_to_current_index()),a.stat.slidegroup=b},a.set_slidegroupmax=function(){a.stat.slidegroupmax=a.stat.slidegroup;for(var b in a.param.slidegroupresp)a.stat.slidegroupmax<a.param.slidegroupresp[b]&&(a.stat.slidegroupmax=a.param.slidegroupresp[b])},a.set_slidewidth=function(){if(a.stat.slidegroup>0){a.stat.slidewidth=Math.floor((a.stat.slidermaskwidth-a.param.slidegap*(a.stat.slidegroup-1))/a.stat.slidegroup);var b=a.stat.slidermaskwidth-(a.stat.slidewidth*a.stat.slidegroup+a.param.slidegap*(a.stat.slidegroup-1));if(a.obj.slide.width(a.stat.slidewidth).css("margin-right",a.param.slidegap+"px"),b>0)for(var c=0;b>c;c++)jQuery(a.param.sliderid+" "+a.param.slideclass+":nth-child("+(1+c)+"n-"+(a.stat.slidegroup-1)+")").css("margin-right",a.param.slidegap+1+"px")}0===a.stat.slidegroup&&a.obj.slide.css({width:"auto","margin-right":a.param.slidegap+"px"})},a.set_slideheight=function(){},a.set_slidesinviewport=function(){for(a.stat.slidesinviewportindexbegin=a.stat.currentslideindex,a.stat.slidesinviewportindexend=a.stat.currentslideindex+(a.stat.slidegroup-1),a.stat.slidesinviewportindexes=[],i=0;i<a.stat.slidecount+2*a.stat.slidegroup;i++)if(i>=a.stat.slidesinviewportindexbegin&&i<=a.stat.slidesinviewportindexend){var b=i;b>=a.stat.slidecount&&(b-=a.stat.slidecount),a.stat.slidesinviewportindexes.push(b+1)}for(a.stat.currentslidesindexes=[],i=0;i<a.stat.slidecount+2*a.stat.slidegroup;i++)if(i>=a.stat.slidesinviewportindexbegin&&i<=a.stat.slidesinviewportindexend){var b=i;a.stat.currentslidesindexes.push(b+1)}},a.set_currentslidesindexs=function(){for(a.stat.currentslidesindexes=[],i=0;i<a.stat.slidecount+2*a.stat.slidegroup;i++)if(i>=a.stat.slidesinviewportindexbegin&&i<=a.stat.slidesinviewportindexend){var b=i;a.stat.currentslidesindexes.push(b+1)}},a.set_prevnext=function(){if(a.stat.slidecount>a.stat.slidegroup){var b="",c="";if("object"!=typeof a.obj.nextbtn&&a.param.nextbtn){""!==a.param.nextclassadd&&(b=" "+a.param.nextclassadd.replace(".",""));var d='<div class="js-yerslider-next '+a.param.prevnextclass.replace(".","")+" "+a.param.nextclass.replace(".","")+b+'">';"inside"===a.param.prevnextlocation&&a.obj.sliderviewport.append(d),"outside"===a.param.prevnextlocation&&a.obj.sliderwrap.append(d),a.obj.nextbtn=jQuery(a.param.sliderid+" "+a.param.nextclass)}if("object"!=typeof a.obj.prevbtn&&a.param.prevbtn){""!==a.param.prevclassadd&&(c=" "+a.param.prevclassadd.replace(".",""));var d='<div class="js-yerslider-prev '+a.param.prevnextclass.replace(".","")+" "+a.param.prevclass.replace(".","")+c+'">';"inside"===a.param.prevnextlocation&&a.obj.sliderviewport.append(d),"outside"===a.param.prevnextlocation&&a.obj.sliderwrap.append(d),a.obj.prevbtn=jQuery(a.param.sliderid+" "+a.param.prevclass)}a.refresh_prevnext()}else"object"==typeof a.obj.nextbtn&&(a.obj.nextbtn.remove(),a.obj.nextbtn=void 0,a.stat.nextbtnclickable=!1),"object"==typeof a.obj.prevbtn&&(a.obj.prevbtn.remove(),a.obj.prevbtn=void 0,a.stat.prevbtnclickable=!1)},a.next_slide=function(){a.stat.currentslideindex=a.param.slidingstep?a.stat.currentslideindex+a.param.slidingstep:a.stat.currentslideindex+a.stat.slidegroup},a.prev_slide=function(){a.stat.currentslideindex=a.param.slidingstep?a.stat.currentslideindex-a.param.slidingstep:a.stat.currentslideindex-a.stat.slidegroup},a.next_job=function(){a.stat.isanimating||(a.stat.isanimating=!0,a.stat.slidingright=!0,a.next_slide(),a.task_slide())},a.prev_job=function(){a.stat.isanimating||(a.stat.isanimating=!0,a.stat.slidingleft=!0,a.prev_slide(),a.task_slide())},a.nextbtn_click=function(){a.obj.nextbtn&&!a.stat.nextbtnclickable&&(a.obj.nextbtn.on(a.stat.clicktype,function(){a.stat.lasteventtype="click-next",a.next_job()}),a.stat.nextbtnclickable=!0)},a.prevbtn_click=function(){a.obj.prevbtn&&!a.stat.prevbtnclickable&&(a.obj.prevbtn.on(a.stat.clicktype,function(){a.stat.lasteventtype="click-prev",a.prev_job()}),a.stat.prevbtnclickable=!0)},a.nextbtn_click_unbind=function(){a.obj.nextbtn.unbind("click").addClass(a.param.nextinactiveclass.replace(".","")),a.stat.nextbtnclickable=!1},a.prevbtn_click_unbind=function(){a.obj.prevbtn.unbind("click").addClass(a.param.previnactiveclass.replace(".","")),a.stat.prevbtnclickable=!1},a.refresh_prevnext=function(){a.stat.nextbtnclickable||a.nextbtn_click(),a.stat.prevbtnclickable||a.prevbtn_click(),a.obj.nextbtn&&a.obj.nextbtn.removeClass(a.param.nextinactiveclass.replace(".","")),a.obj.prevbtn&&a.obj.prevbtn.removeClass(a.param.previnactiveclass.replace(".","")),"none"===a.stat.loop&&(a.obj.nextbtn&&a.stat.currentslideindex>=a.stat.slidecount-a.stat.slidegroup&&a.nextbtn_click_unbind(),a.obj.prevbtn&&a.stat.currentslideindex<=0&&a.prevbtn_click_unbind())},a.set_slide_current_class=function(){a.obj.slide.removeClass("current"),jQuery(a.obj.slide[a.stat.currentslideindex]).addClass("current")},a.autoplayinit=function(){a.param.autoplay&&(a.autoplayset(),a.param.autoplaystoponhover&&a.autoplayhover())},a.autoplayset=function(){a.stat.autoplayison===!1&&(a.param.autoplaycontinuously&&a.stat.cssanimation&&(a.css_animation(a.obj.slider,"slideshow "+Math.round(a.param.autoplaycontinuouslyspeed*a.stat.slidecount/1e3)+"s linear infinite"),a.css_transform(a.obj.slider,"translate3d(0, 0, 0)"),a.obj.sliderwrap.prev("style").remove(),a.obj.sliderwrap.before("<style>"+a.css_keyframes(a.stat.slidewidth*a.stat.slidecount)+"</style>"),a.param.autoplaycontinuouslystoponhover&&(a.obj.sliderwrap.on("mouseenter",function(){a.css_animation_play_state(a.obj.slider,"paused")}),a.obj.sliderwrap.on("mouseleave",function(){a.css_animation_play_state(a.obj.slider,"running")}))),a.param.autoplaycontinuously&&a.stat.cssanimation||(a.stat.autoplayison=!0,window.setTimeout(function(){a.stat.autoplayison&&(a.stat.autoplayinterval=window.setInterval(function(){a.stat.isanimating||(a.stat.isanimating=!0,a.stat.slidingright=!0,a.stat.lasteventtype="autoplay",a.next_slide(),a.task_slide())},a.param.autoplayinterval))},a.param.autoplaydelaystart)))},a.autoplayclear=function(){a.stat.autoplayison=!1,a.stat.autoplayinterval=clearInterval(a.stat.autoplayinterval)},a.autoplayhover=function(){jQuery(a.obj.slider).on("mouseenter",function(){a.stat.videoisplaying||a.autoplayclear()}).on("mouseleave",function(){a.stat.videoisplaying||a.autoplayset()}),jQuery(a.obj.sliderwrap.find(a.param.prevnextclass)).mouseenter(function(){a.stat.videoisplaying||a.autoplayclear()}).on("mouseleave",function(){a.stat.videoisplaying||a.autoplayset()}),"object"==typeof a.obj.bulletswrap&&a.obj.bulletswrap.mouseenter(function(){a.stat.videoisplaying||a.autoplayclear()}).on("mouseleave",function(){a.stat.videoisplaying||a.autoplayset()})},a.bullets=function(){if(a.param.bullets){if(a.obj.sliderwrap.addClass(a.param.sliderwrapclasshasbullets.replace(".","")),"object"!=typeof a.obj.bulletswrap){var b='<div class="'+a.param.bulletswrapclass.replace(".","")+'"></div>';"inside"===a.param.bulletslocation&&a.obj.sliderviewport.append(b),"outside"===a.param.bulletslocation&&a.obj.sliderwrap.append(b),a.obj.bulletswrap=a.obj.sliderwrap.find(a.param.bulletswrapclass)}a.stat.bulletscount=Math.ceil(a.stat.slidecount/a.stat.slidegroup),a.set_bullet_current(),a.bullet_items(),a.set_bullet_current_class(),a.bullet_click()}},a.bullet_items=function(){if(a.param.bullets){if(a.stat.bulletscountcache!==a.stat.bulletscount){for(var b="",c=1;c<=a.stat.bulletscount;c++)b+='<div class="'+a.param.bulletclass.replace(".","")+'" data-index="'+c+'"></div>';a.obj.bulletswrap.empty(),a.stat.bulletscount>1&&a.obj.bulletswrap.append(b),a.stat.bulletscountcache=a.stat.bulletscount}a.obj.bullets=a.obj.bulletswrap.find(a.param.bulletclass),a.set_bullet_current_class()}},a.set_bullet_current=function(){if(a.param.bullets){var b=a.stat.currentslideindex;b+1>a.stat.slidecount&&(b-=a.stat.slidecount),"none"===a.stat.loop?a.stat.bulletcurrent=Math.ceil(b/a.stat.slidegroup)+1:(a.stat.bulletcurrent=Math.round(b/a.stat.slidegroup)+1,a.stat.bulletcurrent>a.stat.bulletscount&&(a.stat.bulletcurrent=a.stat.bulletscount))}},a.set_bullet_current_class=function(){a.param.bullets&&(a.obj.bullets.removeClass(a.param.bulletcurrentclass.replace(".","")),a.obj.bulletswrap.find('[data-index="'+a.stat.bulletcurrent+'"]').addClass(a.param.bulletcurrentclass.replace(".","")))},a.bullet_click=function(){a.param.bullets&&a.obj.bullets.on("click",function(){if(!a.stat.isanimating){a.stat.isanimating=!0,a.stat.slidingright=!0;var b=jQuery(this).data("index");a.stat.currentslideindex=(b-1)*a.stat.slidegroup,a.task_slide()}})},a.thumbs=function(){var b=!0;if(a.obj.slide.length<a.param.thumbshideiflessthan&&(b=!1),a.param.thumbs&&b){if(a.obj.sliderwrap.addClass(a.param.sliderwrapclasshasthumbs.replace(".","")),"object"!=typeof a.obj.thumbswrap){var c='<div class="'+a.param.thumbswrapclass.replace(".","")+'"><div class="'+a.param.thumbsviewportclass.replace(".","")+'"><div class="'+a.param.thumbsmaskclass.replace(".","")+'"><div class="'+a.param.thumbsitemsclass.replace(".","")+'"></div></div></div></div>';"inside"===a.param.thumbslocation&&a.obj.sliderviewport.append(c),"outside"===a.param.thumbslocation&&a.obj.sliderwrap.append(c),a.obj.thumbswrap=a.obj.sliderwrap.find(a.param.thumbswrapclass),a.obj.thumbsviewport=a.obj.sliderwrap.find(a.param.thumbsviewportclass),a.obj.thumbsmask=a.obj.sliderwrap.find(a.param.thumbsmaskclass),a.obj.thumbsitems=a.obj.sliderwrap.find(a.param.thumbsitemsclass)}a.thumbs_items(),a.set_thumbs_current_class(),a.thumbs_click(),a.obj.thumbsviewport.hide(),-1!==jQuery.inArray("thumbs",a.param.imagesloaded)?a.obj.thumbsviewport.imagesLoaded().always(function(){a.obj.thumbsviewport.fadeIn("slow",function(){a.thumbs_script(),a.thumbs_autoplay()})}):a.obj.thumbsviewport.fadeIn("slow",function(){a.thumbs_script(),a.thumbs_autoplay()})}},a.thumbs_items=function(){if(a.param.thumbs){var b=0;a.obj.slide.each(function(){if(b++<a.stat.slidecount){var c=jQuery(this),d=c.data("thumb-template-key"),e="",f="",g="";d&&"object"==typeof a.param.thumbstemplates[d]&&"undefined"!=typeof a.param.thumbstemplates[d].html&&(f="",g="",thumb_="",placeholder_arr=!1,a.param.thumbstemplates[d].html&&(e=a.param.thumbstemplates[d].html,a.param.thumbstemplates[d].cssclass&&(g=" "+a.param.thumbstemplates[d].cssclass),placeholder_arr=a.get_placeholder_of_string(e),placeholder_arr.length>0&&placeholder_arr.map(function(a){var b=c.data(a);b||(b=""),e=e.replace("{{"+a+"}}",b)}),f+='<div class="'+a.param.thumbsitemclass.replace(".","")+g+'">',f+=e,f+="</div>",a.obj.thumbsitems.append(f),a.obj.thumbsitem=a.obj.sliderwrap.find(a.param.thumbsitemclass)))}})}},a.set_thumbs_current_class=function(){if(a.param.thumbs&&"object"==typeof a.obj.thumbsitem){a.obj.thumbsitem.removeClass("thumb-slidegroup-current"),a.obj.thumbsitem.removeClass("thumb-current");for(var b in a.stat.slidesinviewportindexes)jQuery(a.obj.thumbsitem[a.stat.slidesinviewportindexes[b]-1]).addClass("thumb-slidegroup-current");jQuery(a.obj.thumbsitem[a.stat.currentslideindex]).addClass("thumb-current")}},a.thumbs_click=function(){a.param.thumbs&&a.obj.thumbswrap.on("click",a.param.thumbsitemclass,function(){a.stat.lasteventtype="thumb-click";var b=jQuery(this),c=b.index();a.stat.isanimating||(a.stat.isanimating=!0,a.stat.slidingright=!0,a.stat.currentslideindex=c,a.task_slide())})},a.thumbs_script=function(){if(a.param.thumbs&&a.param.thumbsready){var b={};b.obj=a.obj,b.param={touch:a.stat.touch},a.param.thumbsready(b)}},a.thumbs_autoplay=function(){a.param.autoplay&&"object"==typeof a.obj.thumbswrap&&a.obj.thumbsmask.on("mouseenter",function(b){a.stat.videoisplaying||"undefined"==typeof b.originalEvent||a.autoplayclear()}).on("mouseleave",function(){a.stat.videoisplaying||a.autoplayset()})},a.move_slider_to_current_index=function(){a.stat.slidecount>1&&(a.stat.cssanimation?(a.css_transitionduration(a.obj.slider,0),a.css_transform(a.obj.slider,-1*a.get_sliderposition())):a.obj.slider.css({"margin-left":"-"+a.get_sliderposition()+"px"}))},a.animate_slider_to_current_position=function(b){a.stat.cssanimation?a.animate_slider_to_current_position_css(b):a.animate_slider_to_current_position_js(b)},a.animate_slider_to_current_position_js=function(b){a.obj.slider.animate({"margin-left":"-"+a.get_sliderposition()+"px"},b,a.translate_easing(a.param.animationtype,"jquery"),function(){a.get_sliderposition(),a.stat.isanimating=!1})},a.animate_slider_to_current_position_css=function(b){var c=-1*a.get_sliderposition();a.css_transitionduration(a.obj.slider,b),a.css_transform(a.obj.slider,c),a.css_transitiontiming(a.obj.slider,a.param.animationtype),window.setTimeout(function(){a.get_sliderposition(),a.stat.isanimating=!1,a.animation_finshed()},b)},a.animation_finshed=function(){},a.video_load=function(){},a.scroll_slider=function(b,c){var d=-1*a.stat.currentslideposition,e=d;"left"===c&&(e=d-Math.abs(b)),"right"===c&&(e=d+Math.abs(b)),a.css_transitionduration(a.obj.slider),a.css_transform(a.obj.slider,e),a.css_transitiontiming(a.obj.slider,a.param.animationtype)},a.task_slide=function(){if(a.player_remove(),a.param.scrolltop&&jQuery("body").animate({scrollTop:a.param.scrolltopval},a.param.scrolltopspeed),a.check_slider_current_index(),a.set_slidesinviewport(),-1!==jQuery.inArray("slide",a.param.imagesloaded)){a.stat.loadingtimeout=window.setTimeout(function(){a.obj.sliderviewport.append('<div class="'+a.param.loadingclass.replace(".","")+'"></div>'),a.stat.isloading=!0},a.param.loadingmessagedelay);var b=0,c=a.stat.slidegroup;for(var d in a.stat.slidesinviewportindexes)jQuery(a.obj.slide[a.stat.slidesinviewportindexes[d]-1]).imagesLoaded().always(function(){b+=1,b===c&&(a.task_slide_job(),clearTimeout(a.stat.loadingtimeout),a.stat.isloading&&(a.obj.sliderviewport.find(a.param.loadingclass).remove(),a.stat.isloading=!1))})}else a.task_slide_job()},a.task_slide_job=function(){a.animate_slider_to_current_position(a.get_animationspeed()),a.stat.prevbtnclickable||a.prevbtn_click(),a.stat.slidingright=!1,a.stat.slidingleft=!1,a.refresh_prevnext(),a.param.bullets&&(a.set_bullet_current(),a.set_bullet_current_class()),a.set_thumbs_current_class(),a.init_detach_change_item()},a.clon_slides=function(){if(!a.stat.touch||a.stat.touch&&"infinite"===a.stat.loop||a.stat.touch&&a.param.autoplaycontinuously===!0){var b=0,c=0;for(clones=a.stat.slidegroupmax,a.param.slidingstep,a.stat.slidegroup>0&&(clones=2*a.stat.slidegroupmax),a.param.slidingstep>a.stat.slidegroup&&(clones=2*a.param.slidingstep+2*a.stat.slidegroupmax),c=0;clones>c;c++)b>a.stat.slidecount&&(b=0),a.obj.slider.append(jQuery(a.obj.slide[b]).clone()),b++;a.obj.slide=jQuery(a.param.sliderid+" "+a.param.slideclass)}},a.get_sliderposition=function(){var b=jQuery(a.obj.slide[a.stat.currentslideindex]).position().left;return a.stat.currentslideposition=b,b},a.get_animationspeed=function(){var b=a.param.animationspeed;return a.stat.isswiping&&(b=a.param.swipeanimationspeed),b},a.proof_slider_current_index=function(){a.stat.slidecount-a.stat.slidegroup>0&&a.stat.currentslideindex>=a.stat.slidecount-a.stat.slidegroup&&(a.stat.currentslideindex=a.stat.slidecount-a.stat.slidegroup,a.param.nextbtnclickable&&a.nextbtn_click_unbind())},a.check_slider_current_index=function(){if("thumb-click"===a.stat.lasteventtype&&"infinite"!==a.stat.loop&&a.stat.currentslideindex>=a.stat.slidecount-a.stat.slidegroup&&(a.stat.currentslideindex=a.stat.slidecount-a.stat.slidegroup),"click-next"===a.stat.lasteventtype||"autoplay"===a.stat.lasteventtype||"swipe-left"===a.stat.lasteventtype){if("none"===a.stat.loop&&(a.stat.currentslideindex>=a.stat.slidecount-a.stat.slidegroup&&(a.stat.currentslideindex=a.stat.slidecount-a.stat.slidegroup),a.param.autoplay&&a.autoplayclear()),"rollback"===a.stat.loop)if(a.param.slidingstep){if("click-next"===a.stat.lasteventtype||"autoplay"===a.stat.lasteventtype||"swipe-right"===a.stat.lasteventtype){(a.stat.currentslideindex===a.stat.slidecount||a.stat.currentslideindex===a.stat.slidecount-a.stat.slidegroup+a.param.slidingstep)&&(a.stat.currentslideindex=0);var b=0;a.stat.slidegroup>a.param.slidingstep&&(b=a.stat.slidegroup-a.param.slidingstep+1),a.stat.currentslideindex>=a.stat.slidecount-b&&(a.stat.currentslideindex=a.stat.slidecount-a.stat.slidegroup)}}else("click-next"===a.stat.lasteventtype||"autoplay"===a.stat.lasteventtype||"swipe-right"===a.stat.lasteventtype)&&(a.stat.currentslideindex==a.stat.slidecount&&(a.stat.currentslideindex=0),a.stat.currentslideindex>=a.stat.slidecount-a.stat.slidegroup&&(a.stat.currentslideindex=a.stat.slidecount-a.stat.slidegroup));if("infinite"===a.stat.loop&&("click-next"===a.stat.lasteventtype||"autoplay"===a.stat.lasteventtype||"swipe-right"===a.stat.lasteventtype)){var c=0;c=a.param.slidingstep?a.param.slidingstep:a.stat.slidegroup,a.stat.currentslideindex-c>a.stat.slidecount-1&&(a.stat.currentslideindex=a.stat.currentslideindex-Math.floor((a.stat.currentslideindex-c)/a.stat.slidecount)*a.stat.slidecount-c,a.move_slider_to_current_index(),a.stat.currentslideindex=a.stat.currentslideindex+c)}}if(("click-prev"===a.stat.lasteventtype||"swipe-right"===a.stat.lasteventtype)&&("none"===a.stat.loop&&a.stat.currentslideindex<=0&&(a.stat.currentslideindex=0),"rollback"===a.stat.loop&&(a.param.slidingstep?a.stat.slidingleft&&a.stat.currentslideindex==0-a.param.slidingstep&&(a.stat.currentslideindex=a.stat.slidecount-a.stat.slidegroup):a.stat.slidingleft&&a.stat.currentslideindex==0-a.stat.slidegroup&&(a.stat.currentslideindex=a.stat.slidecount-a.stat.slidegroup),a.stat.currentslideindex<0&&(a.stat.currentslideindex=0)),"infinite"===a.stat.loop&&a.stat.currentslideindex<0)){var c=0;
c=a.param.slidingstep?a.param.slidingstep:a.stat.slidegroup,a.stat.currentslideindex<0&&(a.stat.currentslideindex=a.stat.currentslideindex+Math.ceil(c/a.stat.slidecount)*a.stat.slidecount+c,a.move_slider_to_current_index(),a.stat.currentslideindex=a.stat.currentslideindex-c)}},a.resize=function(){a.stat.resizing=!0,a.stat.isios&&(a.stat.isresizing=!0),a.set_slidermaskwidth(),a.set_slidegroup(),a.set_slidesinviewport(),a.set_slidewidth(),a.set_slideheight(),a.proof_slider_current_index(),a.animate_slider_to_current_position(0),a.set_prevnext(),a.param.bullets&&a.bullets(),a.player_fix_ratio(),a.slides_equal_height(),a.set_thumbs_current_class(),a.stat.resizing=!1,a.stat.isios&&(a.stat.isresizing=!1),a.init_detach_resize()},a.resize_end=function(){},a.touchswipe=function(){function b(b,c,d,e){a.stat.isswiping=!0,"move"!==c||"left"!==d&&"right"!==d?"cancel"===c?(a.animate_slider_to_current_position(a.get_animationspeed()),a.param.autoplay&&!a.stat.autoplayison&&a.autoplayset()):"end"===c&&("right"===d?(a.stat.lasteventtype="swipe-right",a.prev_job()):"left"===d&&(a.stat.lasteventtype="swipe-left",a.next_job()),a.param.autoplay&&!a.stat.autoplayison&&a.autoplayset()):("left"===d?a.scroll_slider(e,d):"right"===d&&a.scroll_slider(e,d),a.param.autoplay&&a.stat.autoplayison&&a.autoplayclear()),a.stat.isswiping=!1}a.obj.slide.swipe({triggerOnTouchEnd:!0,swipeStatus:b,allowPageScroll:"vertical",tap:function(a,b){var c=jQuery(b).attr("href");"undefined"==typeof c&&(c=jQuery(b).parents("a").attr("href")),"undefined"!=typeof c&&(window.location=c)}})},a.slides_equal_height=function(){a.obj.slider.imagesLoaded().progress(function(){a.obj.slide.css("height","auto"),a.obj.slide.height(a.obj.slider.height())})},a.css_transform=function(b,c){var d="none";"undefined"!=typeof c&&(d="translate3d("+c.toString()+"px,0px,0px)"),a.stat.cssanimation&&b.css({"-webkit-transform":d,"-ms-transform":d,"-o-transform":d,"-moz-transform":d,transform:d})},a.css_animation=function(b,c){var d="none";"undefined"!=typeof c&&(d=c),a.stat.cssanimation&&b.css({"-webkit-animation":d,"-ms-animation":d,"-o-animation":d,"-moz-animation":d,animation:d})},a.css_animation_play_state=function(b,c){var d="none";"undefined"!=typeof c&&(d=c),a.stat.cssanimation&&b.css({"-webkit-animation-play-state":d,"-ms-animation-play-state":d,"-o-animation-play-state":d,"-moz-animation-play-state":d,"animation-play-state":d})},a.css_transitiontiming=function(b,c){"undefined"==typeof c&&(c="none"),a.stat.cssanimation&&b.css({"-webkit-transition-timing-function":c,"-ms-transition-timing-function":c,"-o-transition-timing-function":c,"-moz-transition-timing-function":c,"transition-timing-function":c})},a.css_transitionduration=function(b,c){"undefined"==typeof c&&(c=0);var d=(c/1e3).toFixed(1)+"s";a.stat.cssanimation&&b.css({"-webkit-transition-duration":d,"-ms-transition-duration":d,"-o-transition-duration":d,"-moz-transition-duration":d,"transition-duration":d})},a.css_keyframes=function(b){if("undefined"!=typeof b&&a.stat.cssanimation){var c="@-webkit-keyframes slideshow {0%{ -webkit-transform: translateX(0);}100%{-webkit-transform: translateX(-"+b+"px);}}@-ms-keyframes slideshow {0%{ -ms-transform: translateX(0);}100%{-ms-transform: translateX(-"+b+"px);}}@-o-keyframes slideshow {0%{ -o-transform: translateX(0);}100%{-o-transform: translateX(-"+b+"px);}}@-moz-keyframes slideshow {0%{ -moz-transform: translateX(0);}100%{-moz-transform: translateX(-"+b+"px);}}@keyframes slideshow {0%{ transform: translateX(0);}100%{transform: translateX(-"+b+"px);}}";return c}},a.css_marginleft=function(a,b){"undefined"==typeof b&&(b=0),a.css({"margin-left":b})},a.translate_easing=function(a,b){var c="linear";return"jquery"===b&&("linear"===a&&(c="linear"),"ease"===a&&(c="swing")),"css"===b&&(c=a),c},a.helper={getLength:function(a){var b=a.length?--a.length:0;for(var c in a)b++;return b},setDefaultParam:function(b){"undefined"==typeof b&&(b={}),"undefined"==typeof b.p&&(b.p={}),"undefined"==typeof b.d&&(b.d={});var c=b.p;for(var d in b.d)"undefined"!=typeof b.d[d]&&typeof c[d]!=typeof b.d[d]?c[d]=b.d[d]:"undefined"!=typeof b.d[d]&&a.helper.getLength(c[d])!==a.helper.getLength(b.d[d])&&(c[d]=a.helper.setDefaultParam({p:c[d],d:b.d[d]}));return c},when:function(a,b){var c=0;"undefined"==typeof b.timeout&&(b.timeout=10),"undefined"==typeof b.val&&(b.val=!0);var d=window.setInterval(function(){c++,a[b.key]===b.val&&(b.callback_if(),window.clearInterval(d)),c/10===b.timeout&&(window.clearInterval(d),"function"==typeof b.callback_timeout&&b.callback_timeout())},100)}},a.get_placeholder_of_string=function(a){for(var b=/\{\{([\w-]+)\}\}/g,c=[];match=b.exec(a);)c.push(match[1]);return c},a.goto_slide=function(b){window.setTimeout(function(){var c={id:!1,align:!1},d=0,e=!1;b=jQuery.extend(!0,c,b),a.obj.slide.each(function(){var c=jQuery(this).data("yersliderid").toString();b.id.toString()===c&&(e=d,"center"===b.align&&(e=d-(Math.ceil(a.stat.slidegroup/2)-1))),d++}),e&&(a.stat.currentslideindex=e,a.check_slider_current_index(),a.set_slide_current_class(),a.set_slidesinviewport(),a.refresh_prevnext(),a.move_slider_to_current_index(),a.param.bullets&&(a.set_bullet_current(),a.set_bullet_current_class()))},250)}}var yerslider={};yerslider.youtubeready=!1,yerslider.youtubeloaded=!1;