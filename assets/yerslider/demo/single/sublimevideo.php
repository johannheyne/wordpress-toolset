<!DOCTYPE html>
<html class="no-js">
	<head>
		<title>YerSlider Demo</title>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<?php

	$path = '../';
	include( '../includes/styles.php' );
	include( '../includes/scripts.php' );

?>
		<script src="//cdn.sublimevideo.net/js/3glvwgyi.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/fitvids/1.1.0/jquery.fitvids.js"></script>

		<link href="http://fonts.googleapis.com/css?family=Raleway:200,700" rel="stylesheet" type="text/css"/>
		<link href="../../themes/default/yerslider-styles.css?v=201306122200" rel="stylesheet" type="text/css"/>
		<link href="../demo-styles.css?v=201306122200" rel="stylesheet" type="text/css"/>

	</head>
	<body>

		<!-- header begin -->

		<div class="page-typo">

			<h1><a href="../">YerSlider</a></h1>

		</div>

		<!-- end -->
		
		<!-- Sublime Video -->

		<div class="page-typo">
			<h2 id="sublimevideo">Sublime Video</h2>
		</div>
		
		<div class="yerslider-wrap mysliderclass-sublimevideo">
			<div class="yerslider-viewport">
			    <div class="yerslider-mask">
    				<ul class="yerslider-slider">
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner fitvids">
    							<video id="xQmQeKU25zg" class="sublime" width="640" height="360" data-videotype="sublimevideo" data-uid="xQmQeKU25zg" data-youtube-id="xQmQeKU25zg" data-autoresize="fit" preload="none"></video>
    						</div>
    					</li>
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner fitvids">
    							<video id="RcZn2-bGXqQ" class="sublime" width="640" height="360" data-videotype="sublimevideo" data-uid="RcZn2-bGXqQ" data-youtube-id="RcZn2-bGXqQ" data-autoresize="fit" preload="none"></video>
    						</div>
    					</li>
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner fitvids">
    							<video id="6v2L2UGZJAM" class="sublime" width="640" height="360" data-videotype="sublimevideo" data-uid="6v2L2UGZJAM" data-youtube-id="6v2L2UGZJAM" data-autoresize="fit" preload="none"></video>
    						</div>
    					</li>
    				</ul>
    			</div>
			</div>
		</div>

        <code>
<pre>&#060;div class="yerslider-wrap mysliderclass"&#062;
    &#060;div class="yerslider-mask"&#062;
        &#060;ul class="yerslider-slider"&#062;
            &#060;li class="yerslider-slide"&#062;
                &#060;div class="yerslider-slide-inner <div class="code-focus">fitvids</div>""&#062;
                    <div class="code-focus">&#060;video id="xQmQeKU25zg" class="sublime" width="640" height="360" data-videotype="sublimevideo" data-uid="xQmQeKU25zg" data-youtube-id="xQmQeKU25zg" data-autoresize="fit" preload="none"&#062;&#060;/video&#062;</div>
                &#060;/div&#062;
            &#060;/li&#062;
            &#060;li class="yerslider-slide"&#062;
                &#060;div class="yerslider-slide-inner <div class="code-focus">fitvids</div>"&#062;
                    <div class="code-focus">&#060;video id="RcZn2-bGXqQ" class="sublime" width="640" height="360" data-videotype="sublimevideo" data-uid="RcZn2-bGXqQ" data-youtube-id="RcZn2-bGXqQ" data-autoresize="fit" preload="none"&#062;&#060;/video&#062;</div>
                &#060;/div&#062;
            &#060;/li&#062;
            &#060;li class="yerslider-slide "&#062;
                &#060;div class="yerslider-slide-inner <div class="code-focus">fitvids</div>"&#062;
                    <div class="code-focus">&#060;video id="6v2L2UGZJAM" class="sublime" width="640" height="360" data-videotype="sublimevideo" data-uid="6v2L2UGZJAM" data-youtube-id="6v2L2UGZJAM" data-autoresize="fit" preload="none"&#062;&#060;/video&#062;</div>
                &#060;/div&#062;
            &#060;/li&#062;
        &#060;/ul&#062;
    &#060;/div&#062;
&#060;/div&#062;</pre>
        </code>

		<code>
<pre>jQuery.noConflict();
jQuery(document).ready(function(){

	<div class="code-focus">// fluid width video embeds with fitvidsjs.com
	jQuery('.fitvids').fitVids();</div>

	var myslider = new YerSlider();
	myslider.init({
		sliderid: '.mysliderclass',
		slidegap: 10,
		slidegroupresp: {
			0: 1,
			800: 2
		},
		bullets: true,
		swipe: true,
		swipeanimationspeed: 300,
		<div class="code-focus">sublimevideo: true</div>
	});
});</pre>
		</code>

		<script type="text/javascript">

			jQuery.noConflict();
			jQuery(document).ready(function(){

                <div class="code-focus">jQuery('.fitvids').fitVids();</div>

				var myslider = new YerSlider();
				myslider.init({
					sliderid: '.mysliderclass-sublimevideo',
					slidegap: 10,
					slidegroupresp: {
						0: 1,
						800: 2
					},
					bullets: true,
					swipe: true,
					swipeanimationspeed: 300,
					sublimevideo: true
				});
			});

			/*

			sublime.ready(function(){

				var player = sublime.player('xQmQeKU25zg');
				player.pause();
			});

*/
		</script>

	</body>
</html>
