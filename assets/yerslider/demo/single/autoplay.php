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
	</head>
	<body>

		<!-- header begin -->

		<div class="page-typo">

			<h1><a href="../">YerSlider</a></h1>

		</div>

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
	autoplayinterval: 1000,
	autoplaydelaystart: 0,
	autoplaystoponhover: true</div>
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
					autoplayinterval: 1000,
					autoplaydelaystart: 0,
				    autoplaystoponhover: true
				});
			});

		</script>

		<!-- end -->

	</body>
</html>
