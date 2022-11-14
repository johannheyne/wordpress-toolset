<?php

	/*if ( ! empty( $GLOBALS['toolset']['inits']['{tool_slug}']['{param_name}'] ) ) {


	}*/

	function tool_model_viewer( $p = array() ) {

		// DEFAULTS {

			$defaults = array(
				'attrs' => array(
					'poster' => false,
					'src' => false, // *.glb
					'environment_image' => false, // *.hdr, usdz
					'ios-src' => false,
					'ar-modes' => array( 'webxr', 'scene-viewer', 'quick-look' ),
					'shadow-intensity' => 1,
					'camera-controls' => true,
					'touch-action' => 'pan-y',
				),
			);

			$p = array_replace_recursive( $defaults, $p );

		// }

		$html = '<model-viewer' . attrs( $p['attrs'] ) . '></model-viewer>';

		return $html;
	}
