[back to overview](../../README.markdown#initial-functionality)

Tool tool_adaptive_images
===============================

This tool enables to insert Adaptive Images in the WordPress wysiwyg editor.

````php
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
````

[back to overview](../../README.markdown#initial-functionality)
