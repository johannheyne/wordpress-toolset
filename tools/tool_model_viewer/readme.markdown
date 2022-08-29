[back to overview](../../README.markdown#initial-functionality)

Tool Model Viewer
===============================

### Example

````php
/*$GLOBALS['toolset'] = array(
	'inits' => array(
		'tool_model_viewer' => true,
	)
);*/

tool( array(
	'name' => 'model_viewer',
	// Param: https://modelviewer.dev/docs/index.html
	'param' => array(
		'src' => false, // '*.glb'
		'environment-image' => false, // '*.hdr'
		'poster' => false, // '*.jpg'
		'alt' => false,
		'ar' => true,
		'ar-modes' => array( 'webxr', 'scene-viewer', 'quick-look' ),
		'shadow-intensity' => 1,
		'camera-controls' => true,
		'touch-action' => 'pan-y',
	),
);
````
[Parameter Documentation](https://modelviewer.dev/docs/index.html)

[back to overview](../../README.markdown#initial-functionality)
