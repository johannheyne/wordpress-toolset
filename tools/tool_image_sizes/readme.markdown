[back to overview](../../README.markdown#initial-functionality)

Tool tool_image_sizes
===============================

`add_image_sizes` uses the WordPress function `add_image_size` to add custom image sizes.

`editor_images_remove` removes image sizes from the media panel image sizes dropdown.

`editor_images_add` adds image sizes for the media panel image sizes dropdown.

````php
	$GLOBALS['toolset'] = array(
		'inits' => array(
			'tool_image_sizes' => array(

				'add_image_sizes' => array(
					'custom_size_name' => array(
						'width' => '550',
						'height' => '0',
						'crop' => false,
					),
				),
				
				'editor_images_remove' => array(
					'thumbnail' => array( 'post', 'page' ),
					'medium' => array( 'post', 'page' ),
					'large' => array( 'post', 'page' ),
					'full' => array( 'post', 'page' ),
				),
				
				'editor_images_add' => array(
					'custom_size_name' => array(
						'label' => 'Custom Image',
						'posttypes' => array( 'post', 'page' ),
					),
				),
			),
		)
	);
````

[back to overview](../../README.markdown#initial-functionality)
