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

This tool provides a function to insert Adaptive Images in a template.

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

[back to overview](../../README.markdown#initial-functionality)
