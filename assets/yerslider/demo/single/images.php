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
            <p>This is a demopage of the YerSlider-Script hostet on <a href="https://github.com/johannheyne/yerslider" target="_blank">GitHub</a> by Johann Heyne.</p>

		</div>

		<!-- end -->

		<!-- body begin -->

		<div class="page-typo">
			<h2 id="images">Images</h2>
		</div>

		<div class="yerslider-wrap mysliderclass1">
			<div class="yerslider-viewport">
    			<div class="yerslider-mask">
				    <ul class="yerslider-slider">

<?php

	$path = array( 'landscape', 'portrait' );

	for ( $i = 1; $i <= 18; $i++ ) {

		//shuffle( $path );
?>
    					<li class="yerslider-slide" data-thumb-template-key="1" data-thumb-img-src="../images/landscape/<?php echo str_pad( $i, 2, '0', STR_PAD_LEFT ); ?>-thumb.jpg">

    						<div class="yerslider-slide-inner demo-typo">
    							<img src="../images/<?php echo $path[0]; ?>/<?php echo str_pad( $i, 2, '0', STR_PAD_LEFT ); ?>-large.jpg">
    						</div>
    					</li>
<?php

	}

?>
						<!--<li class="yerslider-slide" data-thumb-template-key="2" data-thumb-text="Text">
    						<div class="yerslider-slide-inner demo-typo">
    							<p>Qui<br/>Ipsum<br/>Lingua<br/>Celtae<br/>Nostra<br/>Galli<br/>Antur</p>
    						</div>
    					</li>-->
    				</ul>
    			</div>
			</div>
		</div>

		<script type="text/javascript">

		  jQuery.noConflict();
		  jQuery( document ).ready( function ( $ ) {

				var myslider4 = new YerSlider();
				myslider4.init({
					sliderid: '.mysliderclass1',
					slidegap: 10,
					slidegroupresp: {
						0: 1,
						420: 2,
						800: 3,
						1000: 4,
						1200: 5,
					},
					loop: 'infinite',
					loopswipe: 'rollback',
					animationspeed: 2000,
					bullets: false,
                    autoplay: true,
					autoplayinterval: 2000,
					autoplaydelaystart: 0,
					autoplaystoponhover: true,
                    thumbs: true,
					thumbshideiflessthan: 2,
					thumbstemplates: {
						'1': {
							'html': '<img src="{{thumb-img-src}}">',
							'class': 'thumb-template-1'
						},
						'2': {
							'html': '<p>{{thumb-text}}</p>',
							'class': 'thumb-text'
						}
					},
					thumbsclickable: true,
					thumbsready: function( p ) {

        				var yersliderthumbs = new YerSliderThumbs();
        				yersliderthumbs.init({
        					obj: p.obj,
        					param: p.param
        				});
        			},
        			swipe: true,
					swipeanimationspeed: 300,
				});
			});

		</script>

		<!-- end -->

	</body>
</html>
