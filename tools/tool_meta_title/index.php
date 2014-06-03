<?php

	function tool_meta_title( $p = array() ) {

		// DEFAULTS {

			$defaults = array(
			);

			$p = array_replace_recursive( $defaults, $p );

		// }

		$v = array(
			'title' => '',
			'title_custom_page' => false,
		);

		$v['title'] = get_bloginfo( 'name' ) . ' ' . get_the_title();

		if ( function_exists( 'get_field' ) ) {

			$v['title_custom_page'] = get_field( 'meta_seitentitel' );

			if ( $v['title_custom_page'] ) {

				$v['title'] = $v['title_custom_page'];
			}
		}

		echo '<title>' . $v['title'] . '</title>' . "\n";

	}

?>