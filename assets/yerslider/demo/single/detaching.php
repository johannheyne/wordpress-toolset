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
			<h2 id="autoplay">Detaching</h2>
		</div>
		
		<p>Detaching gives us the possibility to grab, hide and put slide content outside the slider items and may elsewhere in the document.</p>
		
		<div class="yerslider-wrap mysliderclass">
			<div class="yerslider-viewport">
		    	<div class="yerslider-mask">
    				<ul class="yerslider-slider">
						<?php

							$content = array(
								0 => 'Quis aute iure reprehenderit in voluptate velit esse.',
								1 => 'Tityre, tu patulae recubans sub tegmine fagi  dolor. Curabitur blandit tempus ardua ridiculus sed magna.',
								2 => 'Mercedem aut nummos unde unde extricat, amaras. Praeterea iter est quasdam res quas ex communi. Quo usque tandem abutere, Catilina, patientia nostra?',
								3 => 'Vivamus sagittis lacus vel augue laoreet rutrum faucibus. Pellentesque habitant morbi tristique senectus et netus. Mercedem aut nummos unde unde extricat, amaras. Morbi odio eros, volutpat ut pharetra vitae, lobortis sed nibh.',
								4 => 'Sed haec quis possit intrepidus aestimare tellus. Nihilne te nocturnum praesidium Palati, nihil urbis vigiliae. Sed haec quis possit intrepidus aestimare tellus. Etiam habebis sem dicantur magna mollis euismod. Petierunt uti sibi concilium totius Galliae in diem certam indicere.',
							);

							for ( $i = 0; $i <= 10; $i++ ) {

								shuffle( $content );

								echo '<li class="yerslider-slide">';
									echo '<div class="yerslider-slide-inner demo-typo">';
										echo '<p>' . $i . '</p>';
										echo '<div class="detach-me"><p>( ' . $i . ' ) ' . $content[ 0 ] . '</p></div>';
									echo '</div>';
								echo '</li>';
							}

						?>
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
	autoplay: true,
	autoplayinterval: 2000,
	autoplaydelaystart: 0,
	autoplaystoponhover: true,
	<div class="code-focus">detach: {
		<div class="code-comment">// targets inserting new empty detaching containers outside the slider</div>
		targets: { 
			'1': {
				selector_wrap: '.detach-target',
				selector_item: '.detach-target-item',
				insert_selector: 'viewport', <div class="code-comment">// 'wrap' (the current slider wrap) / 'viewport' / 'bullets' / 'thumbs' / class / id</div>
				insert_method: 'after', <div class="code-comment">// before, after, append, prepend</div>
				template_wrap: '&#060;div class="detach-target"&#062;{content}&#060;/div&#062;', <div class="code-comment">// html</div>
				template_item: '&#060;div class="detach-target-item"&#062;{content}&#060;/div&#062;', <div class="code-comment">// html</div>
				change: function( p ) {
					
					<div class="code-comment">/**
						This function is called on every slide-change and
						is intended to show the current detachings.
						
						p: {
							items: objects,
							items_current: objects
						}
					*/</div>
					
					<div class="code-comment">// example of the demo showing only detachings of the current slides</div>
					
					var root = p.items.parents( '.detach-target' ),
						height = root.height();

					root.css( 'height', height + 'px' );
					
					window.setTimeout( function () {
					
						p.items.fadeOut( 90 );
					}, 600 );
					
					window.setTimeout( function () {

						p.items_current.fadeIn( 300 );

						window.setTimeout( function () {
							
							root.css( 'height', 'auto' );
						}, 100 );

					}, 700 );
				},
			}
		},
		<div class="code-comment">// sources defining some slide content and put them into an target</div>
		sources: { 
			'1': {
				target_id: '1',
				selector: '.detach-me p',
				source: 'element', <div class="code-comment">// element / content</div>
				remove: '.detach-me', <div class="code-comment">// selector</div>
			},
		},
	},</div>
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
						900: 3,
						1200: 4
					},
					loop: 'infinite',
					animationspeed: 1000,
					bullets: true,
					autoplay: true,
					autoplayinterval: 2000,
					autoplaydelaystart: 0,
					autoplaystoponhover: true,
					detach: {
						targets: {
							'1': {
								selector_wrap: '.detach-target',
								selector_item: '.detach-target-item',
								insert_selector: 'viewport', // this (the current slider) / class / id
								insert_method: 'after', // before, after, append, prepend
								template_wrap: '<div class="detach-target"><div class="detach-target-table">{content}</div></div>', // html
								template_item: '<div class="detach-target-item"><div class="detach-target-item-inner">{content}</div></div>', // html
								change: function( p ) {

									var root = p.items.parents( '.detach-target' ),
										height = root.height();

									root.css( 'height', height + 'px' );
									
									window.setTimeout( function () {
									
										p.items.fadeOut( 90 );
									}, 600 );
									
									window.setTimeout( function () {

										p.items_current.fadeIn( 300 );

										window.setTimeout( function () {
											
											root.css( 'height', 'auto' );
										}, 100 );

									}, 700 );
								},
							}
						},
						sources: {
							'1': {
								target_id: '1',
								selector: '.detach-me p',
								source: 'element', // element / content
								remove: '.detach-me', // selector
							},
						},
					},
				});
			});

		</script>

		<!-- end -->

	</body>
</html>
