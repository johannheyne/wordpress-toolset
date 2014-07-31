<!DOCTYPE html>
<html class="no-js">
	<head>
		<title>YerSlider Demo</title>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

<?php

	$path = '';
	include( 'includes/styles.php' );
	include( 'includes/scripts.php' );

?>
	</head>
	<body>

		<!-- header begin -->

		<div class="page-typo">

			<h1>YerSlider</h1>
            
            
			<p>This is the Demopage of the YerSlider-Script hostet on <a href="https://github.com/johannheyne/yerslider" target="_blank">GitHub</a> by Johann Heyne.<br/>
				YerSlider is designed <strong>for developpers</strong>, <strong>high responsive</strong> and potentially <strong>can slide anything</strong>.</p>
			<p class="alert">This page is casually under construction.</p>
            
            <a class="download_btn" href="https://github.com/johannheyne/yerslider/archive/master.zip" target="_self"  title="">Download <span class="version">Version 1.5.4</span></a>
            
            
		</div>

		<nav class="tableofcontents">

			<div class="page-typo">
				<h2>Table of content</h2>
			</div>

			<ul>
				<li><a href="#dependencies">Dependencies</a></li>
				<li><a href="#resp-slidegroups">responsive Slidegroups</a></li>
				<li><a href="#loop">Loop</a>
					<ul>
						<li><a href="#loop-infinite">infinite</a></li>
					</ul>
				</li>
				<li><a href="#bullets">Bullets</a></li>
				<li><a href="#autoplay">Autoplay</a></li>
				<li><a href="#touchswipe">Touch & Swipe</a></li>
				<li><a href="#changelog">Changelog</a></li>
			</ul>

			<div class="page-typo">
				<h3>Single Demos</h3>
			</div>

			<ul>
				<li><a href="single/thumbnails.php">Thumbnails</a></li>
				<li><a href="single/autoplay.php">Autoplay</a></li>
				<li><a href="single/loop-rollback.php">Loop Rollback</a></li>
				<li><a href="single/images.php">Images</a></li>
				<li><a href="single/youtube.php">YouTube</a></li>
				<li><a href="single/detaching.php">Detaching</a></li>
				<!--<li><a href="single/sublimevideo.php">SublimeVideo</a></li>-->
			</ul>

		</nav>

		<div class="page-typo">

			<h2 id="dependencies">Dependencies</h2>

			<ul>
				<li><a href="http://jquery.com/">jQuery</a></li>
				<li><a href="http://modernizr.com/">modernizr.js</a><br/>feature detection of javascript, touch, csstransforms3d and csstransitions</li>
				<li><a href="https://github.com/mattbryson/TouchSwipe-Jquery-Plugin">jquery.touchSwipe.js</a><br/>for touch and swipe functionality</li>
				<li><a href="https://www.youtube.com/iframe_api">YouTube iframe API</a></li>
			</ul>

		</div>

		<!-- end -->

		<!-- responsive Slidegroups begin -->

		<div class="page-typo">

			<h2 id="resp-slidegroups">responsive Slidegroups</h2>

			<p>This slider is able to be fluid and slides can be grouped responding to different slider widths. Change the width of your browser or rotate your device to may see the amount of grouped slides changing.</p>
		</div>

		<div class="yerslider-wrap mysliderclass">
			<div class="yerslider-viewport">
			    <div class="yerslider-mask">
    				<ul class="yerslider-slider">
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner demo-typo">
    							<p>1</p>
    						</div>
    					</li>
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner demo-typo">
    							<p>2</p>
    						</div>
    					</li>
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner demo-typo">
    							<p>3</p>
    						</div>
    					</li>
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner demo-typo">
    							<p>4</p>
    						</div>
    					</li>
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner demo-typo">
    							<p>5</p>
    						</div>
    					</li>
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner demo-typo">
    							<p>6</p>
    						</div>
    					</li>
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner demo-typo">
    							<p>7</p>
    						</div>
    					</li>
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner demo-typo">
    							<p>8</p>
    						</div>
    					</li>
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner demo-typo">
    							<p>9</p>
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
	<div class="code-focus">slidegroupresp: {
		0: 1,
		450: 2,
		900: 3
	}</div>
});</pre>
		</code>

		<script type="text/javascript">

		  jQuery.noConflict();
		  jQuery(document).ready(function(){

				var myslider = new YerSlider();
				myslider.init({
					sliderid: '.mysliderclass',
					slidegap: 10,
					slidegroupresp: {
						0: 1,
						450: 2,
						900: 3
					}
				});
			});

		</script>

		<!-- end -->

		<!-- Loop -->

		<div class="page-typo">
			<h2 id="loop">Loop</h2>
			<h3 id="loop-infinite">infinite</h3>
		</div>

		<div class="yerslider-wrap mysliderclass2">
			<div class="yerslider-viewport">
			    <div class="yerslider-mask">
    				<ul class="yerslider-slider">
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner demo-typo">
    							<p>1</p>
    						</div>
    					</li>
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner demo-typo">
    							<p>2</p>
    						</div>
    					</li>
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner demo-typo">
    							<p>3</p>
    						</div>
    					</li>
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner demo-typo">
    							<p>4</p>
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
		0: 1,
		450: 2,
		900: 3
	},
	<div class="code-focus">loop: 'infinite'</div>
});</pre>
		</code>

		<script type="text/javascript">

		  jQuery.noConflict();
		  jQuery(document).ready(function(){

				var myslider2 = new YerSlider();
				myslider2.init({
					sliderid: '.mysliderclass2',
					slidegap: 10,
					slidegroupresp: {
						0: 1,
						450: 2,
						900: 3
					},
					loop: 'infinite'
				});
			});

		</script>

		<!-- end -->

		<!-- Bullets -->

		<div class="page-typo">
			<h2 id="bullets">Bullets</h2>
		</div>

		<div class="yerslider-wrap mysliderclass3">
			<div class="yerslider-viewport">
			    <div class="yerslider-mask">
    				<ul class="yerslider-slider">
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner demo-typo">
    							<p>1</p>
    						</div>
    					</li>
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner demo-typo">
    							<p>2</p>
    						</div>
    					</li>
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner demo-typo">
    							<p>3</p>
    						</div>
    					</li>
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner demo-typo">
    							<p>4</p>
    						</div>
    					</li>
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner demo-typo">
    							<p>5</p>
    						</div>
    					</li>
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner demo-typo">
    							<p>6</p>
    						</div>
    					</li>
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner demo-typo">
    							<p>7</p>
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
		0: 1,
		450: 2,
		900: 3
	},
	<div class="code-focus">bullets: true</div>
});</pre>
		</code>

		<code class="code-html">
<pre>&#060;div class="yerslider-wrap mysliderclass <div class="code-focus">yerslider-has-bullets</div>"&#062;</pre>
		</code>

		<div class="page-typo">
			<p>If the option <code class="code-inline">bullets:</code> is true, YerSlider automaticly adds the class <span class="code-focus">yerslider-has-bullets</span> to the slider wrapping element.</p>
		</div>

		<script type="text/javascript">

		  jQuery.noConflict();
		  jQuery(document).ready(function(){

				var myslider3 = new YerSlider();
				myslider3.init({
					sliderid: '.mysliderclass3',
					slidegap: 10,
					slidegroupresp: {
						0: 1,
						450: 2,
						900: 3
					},
					bullets: true
				});
			});

		</script>

		<!-- end -->

		<!-- Autoplay -->

		<div class="page-typo">
			<h2 id="autoplay">Autoplay</h2>
		</div>

		<div class="yerslider-wrap mysliderclass4">
			<div class="yerslider-viewport">
			    <div class="yerslider-mask">
    				<ul class="yerslider-slider">
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner demo-typo">
    							<p>1</p>
    						</div>
    					</li>
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner demo-typo">
    							<p>2</p>
    						</div>
    					</li>
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner demo-typo">
    							<p>3</p>
    						</div>
    					</li>
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner demo-typo">
    							<p>4</p>
    						</div>
    					</li>
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner demo-typo">
    							<p>5</p>
    						</div>
    					</li>
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner demo-typo">
    							<p>6</p>
    						</div>
    					</li>
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner demo-typo">
    							<p>7</p>
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
		0: 1,
		450: 2,
		900: 3
	},
	loop: 'infinite',
	animationspeed: 1000,
	bullets: true,
	<div class="code-focus">autoplay: true,
	autoplayinterval: 3000</div>
});</pre>
		</code>

		<script type="text/javascript">

		  jQuery.noConflict();
		  jQuery(document).ready(function(){

				var myslider4 = new YerSlider();
				myslider4.init({
					sliderid: '.mysliderclass4',
					slidegap: 10,
					slidegroupresp: {
						0: 1,
						450: 2,
						900: 3
					},
					loop: 'infinite',
					animationspeed: 1000,
					bullets: true,
					autoplay: true,
					autoplayinterval: 3000
				});
			});

		</script>

		<!-- end -->

		<!-- Touch & Swipe -->

		<div class="page-typo">
			<h2 id="touchswipe">Touch & Swipe</h2>
		</div>

		<div class="yerslider-wrap mysliderclass5">
			<div class="yerslider-viewport">
		    	<div class="yerslider-mask">
    				<ul class="yerslider-slider">
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner demo-typo">
    							<p><a style="margin: auto; color: white; text-decoration: none; background: blue; display: block; font-size: 40px;" href="">Link Test</a></p>
    						</div>
    					</li>
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner demo-typo">
    							<p>2</p>
    						</div>
    					</li>
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner demo-typo">
    							<p>3</p>
    						</div>
    					</li>
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner demo-typo">
    							<p>4</p>
    						</div>
    					</li>
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner demo-typo">
    							<p>5</p>
    						</div>
    					</li>
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner demo-typo">
    							<p>6</p>
    						</div>
    					</li>
    					<li class="yerslider-slide">
    						<div class="yerslider-slide-inner demo-typo">
    							<p>7</p>
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
		0: 1,
		450: 2,
		900: 3
	},
	<div class="code-focus">swipe: true,
	swipeanimationspeed: 300</div>
});</pre>
		</code>

		<script type="text/javascript">

		  jQuery.noConflict();
		  jQuery(document).ready(function(){

				var myslider5 = new YerSlider();
				myslider5.init({
					sliderid: '.mysliderclass5',
					slidegap: 10,
					slidegroupresp: {
						0: 1,
						450: 2,
						900: 3
					},
					bullets: true,
					swipe: true,
					swipeanimationspeed: 300
				});
			});

		</script>

		<!-- end -->

        <div class="page-typo">
			<h2 id="changelog">Changelog</h2>
		</div>

		<ul class="changelog">

			<li>Version 1.5.4 <span class="date">2014-07-24</span>
                <ul>
                	<li>FIX missing slides on autoplaycontinuously on touch devices.</li>
                </ul>
            </li>
			<li>Version 1.5.3 <span class="date">2014-07-24</span>
                <ul>
                	<li>FIX parameter autoplaycontinuouslyspeed calculation.</li>
                </ul>
            </li>
			<li>Version 1.5.2 <span class="date">2014-07-23</span>
                <ul>
                	<li>FIX Removed using indexOf because it is not supported by older IE´s.</li>
                </ul>
            </li>
			<li>Version 1.5.1 <span class="date">2014-07-23</span>
                <ul>
                	<li>FIX Changed object key name 'class' to 'cssclass' in the thumbstemplates setup object because class is a reserved keyword in older IE´s.</li>
                </ul>
            </li>
			<li>Version 1.5.0 <span class="date">2014-06-23</span>
                <ul>
                	<li>NEW Detachings, take some slide content and put it elsewhere.</li>
                </ul>
            </li>
			<li>Version 1.4.4 <span class="date">2014-05-27</span>
                <ul>
                	<li>The YerSliderGlobals object can adapt options for different enviroments.</li>
                </ul>
            </li>
			<li>Version 1.4.3 <span class="date">2014-05-26</span>
                <ul>
                	<li>FIX autoload</li>
                </ul>
            </li>
			<li>Version 1.4.2 <span class="date">2014-05-26</span>
                <ul>
                	<li>New option slidingstep</li>
                </ul>
            </li>
			<li>Version 1.4.1 <span class="date">2014-05-23</span>
                <ul>
                	<li>Autoload of dependencies</li>
                </ul>
            </li>
			<li>Version 1.4.0 <span class="date">2014-05-21</span>
                <ul>
                	<li>Basic support for Images (are images loaded).</li>
                	<li>Image Demo</li>
                </ul>
            </li>
		    <li>Version 1.3.1 <span class="date">2014-05-16</span>
                <ul>
                    <li>Viewport<br/>There is a new html element, that wraps the slidermask element. The navigation elements were previously located physicaly below the slider. Now it is possible to position navigation elements inside and outside the slider viewport.</li>
                </ul>
            </li>
            <li>Version 1.2.0 <span class="date">2014-05-15</span>
                <ul>
                    <li>Thumbs</li>
                </ul>
            </li>
		    <li>Version 1.1.3 <span class="date">2014-04-30</span>
                <ul>
                    <li>Autoplay now stops at end when loop is none.</li>
                    <li>New parameter "autoplaydelaystart".</li>
                    <li>Autoplay now inits after slider finished fading in.</li>
                    <li>Autoplay stops now when mouse enter the sliders wrap element.</li>
                </ul>
            </li>
            <li>Version 1.1.0 <span class="date">2014-04-27</span>
                <ul>
                    <li>Support for rollback looping added.</li>
                </ul>
            </li>
		</ul>
		
	</body>
</html>
