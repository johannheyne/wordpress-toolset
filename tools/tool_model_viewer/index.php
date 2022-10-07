<?php

	if ( ! empty( $GLOBALS['toolset']['inits']['{tool_slug}']['{param_name}'] ) ) {


	}

	function tool_model_viewer( $p = array() ) {

		// DEFAULTS {

			$defaults = array(
				'src' => false, // *.glb
				'environment_image' => false, // *.hdr, usdz
				'poster' => false,
				'alt' => false,
				'ar' => true,
				'ar-modes' => array( 'webxr', 'scene-viewer', 'quick-look' ),
				'shadow-intensity' => 1,
				'camera-controls' => true,
				'touch-action' => 'pan-y',
			);

			$p = array_replace_recursive( $defaults, $p );

		// }

		$attrs = array();

		if ( $p['alt'] ) {

			$attrs['alt'] = $p['alt'];
		}

		if ( $p['src'] ) {

			$attrs['src'] = $p['src'];
		}

		if ( $p['ar'] ) {

			$attrs['ar'] = $p['ar'];
		}

		if ( $p['ar-modes'] ) {

			$attrs['ar-modes'] = $p['ar-modes'];
		}

		if ( $p['environment_image'] ) {

			$attrs['environment_image'] = $p['environment_image'];
		}

		if ( $p['poster'] ) {

			$attrs['poster'] = $p['poster'];
		}

		if ( $p['shadow-intensity'] ) {

			$attrs['shadow-intensity'] = $p['shadow-intensity'];
		}

		if ( $p['camera-controls'] ) {

			$attrs['camera-controls'] = $p['camera-controls'];
		}

		if ( $p['touch-action'] ) {

			$attrs['touch-action'] = $p['touch-action'];
		}

		$html = '<model-viewer' . attrs( $attrs ) . '></model-viewer>';

		return $html;
	}
