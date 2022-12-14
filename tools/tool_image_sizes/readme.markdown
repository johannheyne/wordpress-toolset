[back to overview](../../README.markdown#initial-functionality)

Tool tool_image_sizes
===============================

### WordPress Default Image Sizes

| Term | Name in the Code | Size in Pixel |
|:---|:---|:---|
| Thumbnail | thumbnail | 150 x 150 px |
| Medium | medium | 300 x 300 px |
| Medium Large | medium_large | 768 x 0 px |
| Large | large | 1024 x 1024 px |
| 2 x Medium Large | 1536×1536 | 1536 x 1536 px |
| 2 x Large | 2048×2048 | 2048 x 2048 px |
| Big | 2560×2560 | 2560 x 2560 px |
| Full Size | – | Upload size |


### Without SRCSET Defined

```php
// config/modules/tool_adaptive_images.php

config_add_init( array(
	'name' => 'tool_adaptive_images',
	'data' => array(
		//'srcset' => true, // on older themes this is not defined
	),
) );
```
Without srcset defined…<br>
##### Toolset removes image sizes:
- "medium"
- "1536x1536"
- "2048x2048"
- "2560×2560"

> Size "thumbnail" is required for archive view of media library<br>
> Size "medium_large" is required for detail view of media library

##### Toolset changes image sizes:
- "thumbnail" sets crop to false


### Methodes

<b>`add_image_sizes`</b>
<br>Uses the WordPress function `add_image_size` to add custom image sizes.

<b>`remove_image_sizes`</b>
<br>Removes image sizes wether they are custom or core sizes. Core sizes should not removed when using "srcset".

<b>`editor_images_remove`</b>
<br>Removes image sizes from the media panel image sizes dropdown.

<b>`editor_images_add`</b>
<br>Adds image sizes for the media panel image sizes dropdown.

````php
	$GLOBALS['toolset'] = array(
		'inits' => array(
			'tool_image_sizes' => array(

				'add_image_sizes' => array(
					'custom_size_name' => array(
						'width' => '550',
						'height' => '0',
						'crop' => false,
						'js' => true, // registers the image size for use in JavaScript like wp.media
					),
				),

				// Removes core image sizes
				'remove_image_sizes' => array(
					//'thumbnail', required for library archive view
					'medium',
					//'medium_large', required for library detail view
					'large',
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
