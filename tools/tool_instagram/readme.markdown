[back to overview](../../README.markdown#initial-functionality)

Tool Instagram
===============================

# tool_instagram_embed

````php

	// parameter
	
	$result = tool( array(
		'name' => 'tool_instagram_embed',
		'param' => array(
			'code' => '8I2ry5JQLI', // instagram image code
			'layout' => array(
				'type' => 'basic', // basic
				'classes-prefix' => '',
				'classes' => array( // defaults
					'wrap' => 'instagram-wrap',
					'img-link' => 'instagram-img-link',
					'img' => 'instagram-img',
					'caption' => 'instagram-caption',
					'title' => 'instagram-title',
					'author' => 'instagram-author',
					'provider' => 'instagram-provider',
				),
			),
			'title_tags_link' => true, // convert tags to instagram tag links
			'title_tags_remove' => false,
		),
	) );


	// example, echo the intagram widget

	echo tool( array(
		'name' => 'tool_instagram_embed',
		'param' => array(
			'code' => '8I2ry5JQLI',
		),
	) )['widget']['html'];


	// example, echo the basic layout

	echo tool( array(
		'name' => 'tool_instagram_embed',
		'param' => array(
			'code' => '8I2ry5JQLI',
			'layout' => array(
				'type' => 'basic',
			),
		),
	) )['layout'];


	// overview of the returning array
	
	array(
		'title' => false,
		'media_id' => false,
		'sizes' => array(
			'thumbnail' => array(
				'url' => false,
				'width' => 150, // 150
				'height' => 150, // 150
			),
			'medium' => array(
				'url' => false,
				'width' => 320, // 320
				'height' => 320, // 320
			),
			'large' => array( // equal to instagram thumbnail
				'url' => false,
				'width' => 640, // 640
				'height' => 640, // 640
			),
			'full' => array(
				'url' => false,
				'width' => false, // 1080 or less
				'height' => false, // 1080 or less
			)
		),
		'author' => array(
			'name' => false,
			'url' => false,
			'id' => false,
		),
		'provider' => array(
			'name' => false,
			'url' => false,
		),
		'layout' => false,
		'widget' => array(
			'html' => false,
		),
		'version' => false,
		'type' => false,
	)
	
````

````html
	
	<!-- the basic layout html -->
	
	<figure class="instagram-wrap">
		<a class="instagram-img-link" href="" target="_blank">
			<img class="instagram-img" src="https://scontent.cdninstagram.com/…">
		</a>
		<figcaption class="instagram-caption">
			<p class="instagram-title">{title}</p>
			<a class="instagram-author" href="https://instagram.com/{autor}" target="_blank">{autor}</a>
			<a class="instagram-provider" href="https://instagram.com/" target="_blank">Instagram</a>
		</figcaption>
	</figure>
	
````

[back to overview](../../README.markdown#initial-functionality)
