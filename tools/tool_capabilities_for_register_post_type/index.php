<?php

	// CAPABILITIES FOR REGISTER POSTTYPE ( Version 1 ) {

		function tool_capabilities_for_register_post_type( $p = array() ) {

			/* EXAMPLE

				'capability_type' => 'post',
				'capabilities' => tool_capabilities_for_register_post_type( array(
					'capability_type' => 'post',
					'capability_name' => 'produkt',
					'capabilities' => array(
						'read' => true,
						'publish' => true,
						'edit' => true,
						'delete' => true
					)
				)),

			*/

			$p += array(
				'capability_type' => false,
				'capability_name' => false,
				'capabilities' => false
			);

			$return = false;

			if ( $p['capability_type'] && $p['capability_name'] && $p['capabilities'] ) {

				foreach ( $p['capabilities'] as $key => $value ) {

					if ( 
						$key == 'read' ||
						$key == 'read_private' ||
						$key == 'publish' ||
						$key == 'edit' ||
						$key == 'edit_published' ||
						$key == 'edit_others' ||
						$key == 'edit_private' ||
						$key == 'delete' ||
						$key == 'delete_published' ||
						$key == 'delete_others' ||
						$key == 'delete_private'
					) {
						if ( 
							$key == 'read' ||
							$key == 'publish' ||
							$key == 'edit' ||
							$key == 'delete'
						) {
							$return[ $key . '_' . $p['capability_type'] ] = $key . '_' . $p['capability_name'];
						}

						$return[ $key . '_' . $p['capability_type'] . 's' ] = $key . '_' . $p['capability_name'];
					}

					else {
						$return[ $key ] = $key;
					}
				}
			}

			//print_r($return);
			return $return;
		}

	// }

?>