YerSlider
==========================================

YerSlider is designed for frontend-developpers, suitable for responsive design and potentially can slide anything.
There are [Demos and Documentation](http://demo.johannheyne.de/yerslider/demo/) and you can play around with a demo on [codepen.io](http://codepen.io/johannheyne/pen/sekGb)

The script was started to personel understand all the limitations and problems I had with other slider-scripts.

Releases & Download
------------------------------------------

You can read [the version history](https://github.com/johannheyne/yerslider/releases/) and [download the latest stable version](https://github.com/johannheyne/yerslider/releases/latest/) here on GitHub.

Properties
------------------------------------------

* fluid slider
* grouping slides depending on breackpoints
* bullets
* thumbs
* touch ready
* css-transition for smooth sliding and javascript fallback

Roadmap
------------------------------------------

You can look at the [enhancement issues](https://github.com/johannheyne/yerslider/issues?labels=enhancement&milestone=&page=1&state=open) for uppcomming features.

Just ask me for your needs at mail@johannheyne.de or create a new issue.

Dependencies
------------------------------------------

There are some libraries, YerSlider depends on. Some are permanently required, others depends on individual functionalities.

permanetly required:

* [modernizr.js](http://modernizr.com/)
* [jQuery](http://jquery.com/)
* [imagesLoaded.js](http://imagesloaded.desandro.com/)

depend on individual functionalities:

* [jquery.touchSwipe.js](https://github.com/mattbryson/TouchSwipe-Jquery-Plugin)
* [YouTube iframe API](https://www.youtube.com/iframe_api)

### Explaination #############################

The "modernizr.js" must be loaded very early in the head section. It detects browser features. YerSlider needs feature detection for js, touch, csstransforms3d, csstransitions and cssanimations.

jQuery can be in latest version of 1.x or 2.x. The YerSlider script was built to use jQuery in the scope of the jQuery variable. 
```javascript
jQuery.noConflict();
jQuery(document).ready(function(){

	// use YerSlider scripts in here

});
```

jquery.touchSwipe.js is required, when the swipe functionality is enabled in a slider.

The YouTube iframe API is required, when youtube videos are used in a slide.

### Autoload #############################

The YerSlider folder comes with an subfolder /dependencies/ that holds all nessesary libraries. All of these libraries exept jQuery and modernizr.js are registered in YerSlider and will be autoloaded as required. So you can, but do not need to manualy embed them in your html. YerSlider checks on runtime, if the dependencies exists. If not, YerSlider tries to load them from the dependencies folder. If you want to embed the dependencies asyncron by yourself, please setup ```dependencies_autoload: false,```. To make autoload working, the absolute path of the yersliderfolder is required.

```javascript
var myslider = new YerSlider();
myslider.init({
	yersliderfolderabsolutepath: '/your_assets_folder/yerslider/',
	dependencies_autoload: true, // default is true
});
```

Setup
------------------------------------------

### Basic HTML ###########################

The basic html of the slider is what you should provide in your code, to make the YerSlider script working.

```html
<div class="yerslider yerslider-wrap mysliderclass">
	<div class="yerslider-viewport">
		<div class="yerslider-mask">
			<ul class="yerslider-slider">
				<li class="yerslider-slide">
					<!-- slide content -->
				</li>
				<li class="yerslider-slide">
					<!-- slide content -->
				</li>
			</ul>
		</div>
		<!-- buttons, bullets, thumbs appears here by …location: 'inside' -->
	</div>
	<!-- buttons, bullets, thumbs  appears here by …location: 'outside' -->
</div>
```

### Loading The Script ###################

Load the yerslider.js from the core folder. The best way is to load the script on dependecy of a slider on the page. The following script does this.

```javascript
if ( jQuery('.yerslider').length > 0 ) {

	jQuery.ajax({
		url: '/assets/yerslider/core/yerslider.min.js',
		dataType: 'script',
		cache: true,
		async: true,
		success: function () {

			/* define a slider here */
		}
	});
}
```

### Define a Slider ######################

```javascript
var myslider = new YerSlider();
myslider.init({
	sliderid: '.mysliderclass'
});
```

### Load the Slider Stylesheet ###########

There is a themefolder with an default theme. Inside there is the stylesheet with basic styles. Just copy the theme and make your additions to the styles. Then load the styles in the head of your html or use a preprozessor to add this to your existing basic stylesheet.

### Adapting Options For Different Enviroments #########

If you use enviroments for developing, staging etc., all options of YerSlider can be adapted via the ```YerSliderGlobals``` object.

```php
<?php

	// development enviroment
	// Notice, "::1" is the IPv6 equivalent of the IPv4 127.0.0.1 address
	
	if ( stristr( $_SERVER['SERVER_ADDR'], '127.0.0.1' ) || stristr( $_SERVER['SERVER_ADDR'], '::1' ) ) {

		$autoplayinterval = 1000;
	}

	// production enviroment
	elseif ( stristr( $_SERVER['SERVER_NAME'], 'production-domain' ) ) {

		$autoplayinterval = 6000;
	}

?>
<script>

	YerSliderGlobals = {
		param: {
			autoplayinterval: <?php echo $autoplayinterval; ?>,
		},
	};

</script>
```

Options
-----------------------------------------

### Slider ID ###########################

```javascript
sliderid: '.mysliderclass',
```

### Loop #################################

```javascript
loop: 'none', // infinite, rollback
```

### Transition Style #################################

```javascript
transitionstyle: 'slide', // slide, fade
animationspeed_adjustheight: 300,
```

### Animation ############################

```javascript
animationtype: 'ease', // ease, ease-in-out, ease-in, ease-out, linear
animationspeed: 1000,
```

### Group Slides #########################

```javascript
slidegroup: 1,
slidegroupresp: {
	0: 1,
	400: 2,
	800: 3
},
autoslidegroup: true,
```

`slidegroup` is the static and `slidegroupresp` the responsive setup for slidegroups. `autoslidegroup` by default limits the `slidegroup` or `slidegroupresp` value to the available slides. 

### Slidegap #############################

```javascript
slidegap: 0,
```

### Sliding Step #############################

Per default, the slider slides the amount of slides in the viewport. With the option ```slidingstep``` you can manually set the amount of slides to slide.

```javascript
slidingstep: 2, // undefined or integer
```

### Previous & Next Button ###############

```javascript
nextbtn: true,
prevbtn: true,
prevnextlocation: 'inside', // inside, outside
nextclassadd: '',
prevclassadd: '',
```

### Bullets

```javascript
bullets: true,
bulletclickable: true,
bulletslocation: 'inside', // inside or outside the sliderviewport

```

### Thumbs ###############################

```html
<li class="yerslider-slide" data-thumb-template-key="1" data-thumb-text="Hello World!">
	<!-- slide content -->
</li>
```

```javascript
thumbs: true,
thumbslocation: 'inside', // inside or outside the sliderviewport
thumbshideiflessthan: 2,
thumbstemplates: {
	'1': {
		'html': '<p>{{thumb-text}}</p>',
		'cssclass': 'my-thumb-template-class'
	}
},
thumbsclickable: true,
thumbsready: function( p ) {

	/** Fires, after thumbs are ready.
		The variable p provides all slider objects
		an some parameters.

		p {
			obj {
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
				thumbsitems: undefined,
				thumbsitem: undefined,
			},
			param {
				touch: false,
			}
		}
	*/

	/** An example from the default theme, 
		that makes the thumbs scrolling 
		by following the mouse */

	var yersliderthumbs = new YerSliderThumbs();
	yersliderthumbs.init({
		obj: p.obj,
		param: p.param
	});
}
```

### Autoplay ##############################

```javascript
autoplay: false,
autoplayinterval: 3000,
autoplaystoponhover: true,
```

Continuosly scrolling like a ticker:

```javascript
autoplaycontinuously: false,
autoplaycontinuouslyspeed: 4000, // milliseconds per slide distance
autoplaycontinuouslystoponhover: true,
```
Falls back to autoplay if css animation is not available.

### Swipe #################################

```javascript
swipe: false,
swipeandprevnextbtn: false,
swipeanimationspeed: 300,
```

### Scroll Top ############################

Scrolls the page to the given distance from top <code>scrolltopval:</code> with the speed of <code>scrolltopspeed:</code>

```javascript
scrolltop: false,
scrolltopval: 0,
scrolltopspeed: 500,
```

### Detaching ############################

Detaching gives the possibility to grab, hide and put slide content outside the slider items and may elsewhere in the document.

```javascript
detach: {
    targets: {
        '1': {
            selector_wrap: '.detach-target', // selector
            selector_item: '.detach-target-item', // selector
            insert_selector: 'this', // 'this' (the current slider wrap element) / 'viewport' / 'bullets' / 'thumbs' / any class / any id
            insert_method: 'after', // 'before', 'after', 'append', 'prepend'
            template_wrap: '<div class="detach-target">{content}</div>', // html
            template_item: '<div class="detach-target-item">{content}</div>', // html
            change: function( p ) {

                /*  This function is called on every slide event and
                    is intended to show the current detachings.

                    p: {
                        items: slider items objects,
                        items_current: current slider items objects
                    }
                */
            },
        }
    },
    sources: {
       '1': {
            target_id: '1', // key of targets object
            selector: '.detach-me p', // selector
            source: 'element', // 'element' / 'content'
            remove: '.detach-me', // selector
        }
    }
}
```

### Images Loaded #########################

```javascript
imagesloaded: [ 'slide', 'thumbs' ], // [ 'slider', 'slide', 'thumbs' ]
loadingmessagedelay: 30,
```

With ```imagesloaded``` slider areas are defined, which are to be checked for loaded images before they are displayed. 
```'slider'``` will display the sliderviewport when all images of all slides are loaded.
```'slide'``` will display the sliderviewport when all images of the first slides in the viewport are loaded.
This also pauses sliding and creates an new element inside the viewport with the class ```.slider-loading``` until the images of the next current slides are loaded.

```'thumbs'``` will display the thumps when all images used in the thumbs are loaded.

The ```loadingmessagedelay``` defines the time in milliseconds to wait, until the element with the class ```.slider-loading``` should be created while checking for loaded images.

### CSS Classes #########################

YerSlider uses some css classes you may could change.

```javascript
// basic slider
sliderclass: '.yerslider',
sliderwrapclass: '.yerslider-wrap',
sliderwrapclasshasbullets: '.yerslider-has-bullets',
sliderviewportclass: '.yerslider-viewport',
slidermaskclass: '.yerslider-mask',
sliderclass: '.yerslider-slider',
slideclass: '.yerslider-slide',
slidecurrentclass: '.current',
slidegroupcurrentclass: '.slidegroup-current',
slidegroupfirstclass: '.slidegroup-first',
slidegrouplastclass: '.slidegroup-last',
slidecloneclass: '.slide-clone',
loadingclass: '.yerslider-loading',

// previous and next buttons
prevnextclass: '.yerslider-prevnext',
nextclass: '.yerslider-next',
prevclass: '.yerslider-prev',
nextinactiveclass: '.yerslider-next-inactive',
previnactiveclass: '.yerslider-prev-inactive',

// bullets
sliderwrapclasshasbullets: '.yerslider-has-bullets',
bulletswrapclass: '.yerslider-bullets-wrap',
bulletclass: '.yerslider-bullet',
bulletcurrentclass: '.yerslider-bullet-current',

// thumbs
sliderwrapclasshasthumbs: '.yerslider-has-thumbs',
thumbswrapclass: '.yerslider-thumbs-wrap',
thumbsmaskclass: '.yerslider-thumbs-mask',
thumbsitemsclass: '.yerslider-thumbs-items',
thumbsitemclass: '.yerslider-thumbs-item',
thumbsitemcurrentclass: '.thumb-current',
thumbsitemgroupcurrentclass: '.thumb-slidegroup-current',

// slide is loading message element
loadingclass: '.yerslider-loading',
```

For styling slides or thumbs there are serveral classes for different states.
`.current` is the current slide. Is the first slide in a slide-group.
`.slidegroup-current` is a slide of the current slide-group.
`.slidegroup-first` is the first slide of the current slide-group.
`.slidegroup-last` is the last slide of the current slide-group.
`.slide-clone` is a cloned slide created and used by YerSlider to do infinity sliding. This class is useful to hide these slides in print styles.

`.thumb-current` is the current thumb. Is the first thumb in the current thumb-slidegroup.
`.thumb-slidegroup-current` is a thumb of the current thumb-slidegroupp.

