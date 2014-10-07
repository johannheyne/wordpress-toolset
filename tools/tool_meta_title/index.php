<?php

	function tool_meta_title( $p = array() ) {

		// DEFAULTS {

			$defaults = array(
				'rules' => array(
					'{sitetitle}' => true,
					'{pagetitle}' => true,
				),
				'delimiter' => ' ',
				'pagetitle_on_hompage' => false
			);

			$p = array_replace_recursive( $defaults, $p );

		// }

		$v = array(
			'title' => '',
			'title_custom_page' => false,
		);

		// RULES {

			foreach ( $p['rules'] as $key => $value ) {

				if ( $key === '{sitetitle}' && $value ) {

					$v['title'] .= get_bloginfo( 'name' );
					$v['title'] .= $p['delimiter'];
				}

				if ( $key === '{pagetitle}' && $value ) {

					if ( ! $p['pagetitle_on_hompage'] && get_the_ID() == get_option('page_on_front' ) ) {

					}
					else {

						$v['title'] .= get_the_title();
						$v['title'] .= $p['delimiter'];
					}
				}
			}

			$v['title'] = trim( $v['title'], $p['delimiter'] );

		// }

		// CUSTOM {

			if ( function_exists( 'get_field' ) ) {

				$v['title_custom_page'] = get_field( 'meta_seitentitel' );

				if ( $v['title_custom_page'] ) {

					$v['title'] = $v['title_custom_page'];
				}
			}

		// }

		echo '<title>' . $v['title'] . '</title>' . "\n";

	}

?>