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
		<style rel="stylesheet" type="text/css">

			.yerslider-slider {

			}

			.yerslider-slide {

			}

			.center {
				position: relative;
				margin: auto;
			}

			.small {
				position: relative;
				max-width: 400px;
				margin-top: 0;
				margin-bottom: 20px;
			}

			.center-text {
				text-align: center;
				margin: 0;
				line-height: 30px;
			}

		</style>

		<!--

			YouTube Thumbnails:

				http://img.youtube.com/vi/[ID]/…

					0.jpg ( 4:3 large )
					1.jpg ( 4:3 small )
					2.jpg ( 4:3 small )
					3.jpg ( 4:3 small )
					mqdefault.jpg ( 16:9 large )
		-->
	</head>
	<body>

		<!-- header begin -->

		<div class="page-typo">

			<h1><a href="../">YerSlider</a></h1>

		</div>

		<!-- end -->

		<!-- YouTube begin -->

		<div class="page-typo">
			<h2 id="youtube">YouTube</h2>
		</div>

		<!-- Standalone Player Test

		<div class="fitvids">
			<a id="player1" href="">YouTube Watch</a>
		</div>

		<script>

			jQuery.noConflict();
			jQuery(document).ready(function(){

				var player;
					player = {};
					player['1'] = false;

				/* youtubeready begin */

				var count = 0,
					timer = setInterval( function() {

						if ( yerslider.youtubeready ) {

							/* youtubeready body begin */

							player['1'] = new YT.Player('player1', {
								videoId: 'lMtXfwk7PXg',
								playerVars: {
									width: 800,
									height: 450,
									rel: 1,
									autoplay: 0,
									showinfo: 0,
									wmode: 'opaque'
								}
							});

							/* youtubeready body end */

							clearInterval( timer );
						}

						if ( ++count > 600 ) {

							clearInterval( timer );
						}
					}, 100 );

				/* youtubeready end */

			});

		</script>
		 -->

		<div class="yerslider-wrap mysliderclass-youtube">
			<div class="yerslider-viewport">
			    <div class="yerslider-mask">
    				<ul class="yerslider-slider">
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner">

    							<p class="center-text">fix width</p>
    							<div class="yerslider-video small center" data-videotype="youtube" data-videoid="Lxn1-pKY-9I" data-previewimg="auto">
    								<a class="yerslider-video-play" href="http://www.youtube.com/watch?v=Lxn1-pKY-9I">Play</a>
    							</div>

    						</div>
    					</li>
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner">

    							<p class="center-text">fluid width</p>
    							<div class="yerslider-video" data-videotype="youtube" data-videoid="W3OQgh_h4U4" data-previewimg="auto">
    								<a class="yerslider-video-play" href="http://www.youtube.com/watch?v=W3OQgh_h4U4">Play</a>
    							</div>

    						</div>
    					</li>
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner">

    							<p class="center-text">fix video</p>
    							<div class="yerslider-video small center" data-videotype="youtube" data-videoid="k3VevYjjwQk" data-previewimg="auto">
    								<a class="yerslider-video-play" href="http://www.youtube.com/watch?v=k3VevYjjwQkE">Play</a>
    							</div>

    						</div>
    					</li>
    				</ul>
    			</div>
			</div>
		</div>

		<code>
<pre>var myslider = new YerSlider();
myslider.init({
	sliderid: '.mysliderclass',
	slidegap: 10,
	slidegroupresp: {
		0: 1
	},
	bullets: true,
	swipe: true,
	swipeandprevnextbtn: true,
	swipeanimationspeed: 300,
	autoplay: true,
	autoplayinterval: 3000,
	loop: 'infinite',
	<div class="code-focus">autoloadyoutubeiframeapi: true,
	videoplayercloseafterend: true</div>
});</pre>
		</code>

		<script type="text/javascript">

			jQuery.noConflict();
			jQuery(document).ready(function(){

				var myslider5 = new YerSlider();
				myslider5.init({
					sliderid: '.mysliderclass-youtube',
					slidegap: 10,
					slidegroupresp: {
						0: 1
					},
					bullets: true,
					swipe: true,
					swipeandprevnextbtn: true,
					swipeanimationspeed: 300,
					autoplay: true,
					autoplayinterval: 3000,
					loop: 'infinite',
					autoloadyoutubeiframeapi: true,
					videoplayercloseafterend: true
				});

			});

		</script>

		<!-- end -->

	</body>
</html>
