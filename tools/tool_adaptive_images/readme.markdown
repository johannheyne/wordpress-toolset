[back to overview](../../README.markdown#initial-functionality)

Tool tool_adaptive_images
===============================

## Config of WordPress Image Size Threshold and Adaptive Images max Sizes
````php
add_action( 'toolset_config', function() {

	config_add_init( array(
		'name' => 'tool_adaptive_images',
		'data' => array(
			'big_image_size_threshold' => false, // default, false or number
			'adaptive_image_base_max_width' => 2560, // default, number
			'adaptive_image_base_max_height' => 0, // default, number, zero stands for infinity
		),
	) );

}, 10 );
````

## Enables to insert Adaptive Images in the WordPress wysiwyg editor.

````php
add_action( 'toolset_config', function() {

	config_add_init( array(
		'name' => 'tool_image_sizes',
		'data' => array(

			'editor_imagesizes' => array(
				'adaptive-images-key' => array(
					'width' => '400',
					'height' => '0',
					'crop' => false,
					'label' => 'Custom Size',
					'posttypes' => array( 'post', 'page' )
				),
			),
		),
	));

});

/*
$GLOBALS['toolset'] = array(
	'inits' => array(
		'tool_adaptive_images' => array(

			'editor_imagesizes' => array(
				'adaptive-images-key' => array(
					'width' => '400',
					'height' => '0',
					'crop' => false,
					'label' => 'Custom Size',
					'posttypes' => array( 'post', 'page' )
				),
			),
		)
	)
);
*/
````

## Provides a Function to Insert Adaptive Images in a Template.

````php
	$html .= tool( array(
		'name' => 'get_adaptive_image',
		'param' => array(
			'name' => 'adaptive-images-slug',
			'id' => false, // WordPress Image ID
			'ratio' => false,
			'file' => false,
			'alt' => false,
			'img_class' => '',
			'img_class_resp' => 'resp',
			'img_data' => false,
			'link_image' => false, /* true or size */
			'link_page' => false, /* true or id of page */
			'link_url' => false,
			'link_class' => false,
			'link_target' => false,
			'root_class' => false,
			'link_rel' => false,
			'link_title' => false,
			'link_data' => false,
			'wrap' => false,
			'wrap_class' => false,
			'style' => false,
			'figcaption_cont' => false,
			'figure_class' => 'figure',
			'figcaption_class' => 'figcaption'
		),
	) );
````

## Get Image SRC

```php
$image_src = tool( array(
	'name' => 'get_adaptive_image_src',
	'param' => array(
		'name' => 'my_size', // AI size
		'id' => $image_id, // image ID
	),
) );
```

## Get Image Data

```php
$image_data = tool( array(
	'name' => 'get_adaptive_image_data',
	'param' => array(
		'name' => 'my_size', // AI size
		'id' => $image_id, // image ID
	),
) );

/* returns
$image_data['src'] = (string);
$image_data['w'] = (integer);
$image_data['h'] = (integer);
$image_data['ratio'] = (integer); // like 1.5
$image_data['aspect_ratio'] = (array); // like array(16,9);
$image_data['rel_h'] = (integer); // relative height
$image_data['orig'] = (boolean); // wether is original or not
*/
```

#### Use Relative Height Alias "rel_h"
```php
echo '<div style="padding-top: ' . $image_data['rel_h'] . '%;">';
	// container with aspect ratio of image
echo '</div>';

```

## Get Aspect Ratio

```php
$result = tool_get_aspect_ratio( $w, $h );

/* returns
$result['string'] // like "16:9"
$result['array'] // like array( 16, 9 )
*/
```

[back to overview](../../README.markdown#initial-functionality)
