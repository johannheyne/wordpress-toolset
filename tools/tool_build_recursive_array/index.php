<?php

	// BUILD RECURSIVE ARRAY ( Version 1 ) {

		function tool_build_recursive_array( $p = array() ) {

			/*
				Takes a list 'array' with id´s 'id_name' and parent_id´s 'parent_name'
				and returns an recursive array. The key´s of the returning array
				can be integers or a value from the item 'index_key'. The parent start-id
				can be defined 'parent_id'.

				$return = array(
					0 => array(
						'item' => Object,
						'children => false / array(
							0 => array(
								'item' => Object,
								'children => false / Array,
							),
						),
					),
				)

			*/

			// DEFAULTS {

				$defaults = array(
					'array' => false,
					'id_name' => false,
					'parent_name' => false,
					'index_key' => false,
					'parent_id' => 0,
					'limit_items' => -1, // 1 = first item
					'limit_levels' => -1, // 1 = first level
					'level' => 1,
				);

				$p = array_replace_recursive( $defaults, $p );

			// }

			$r = false;

			// JOB {

				$i = -1;

				foreach ( $p['array'] as $key => $item ) {

					$check = true;

					if ( $p['limit_items'] <= $i + 1 ) $check = false;
					if ( $p['limit_items'] === -1 ) $check = true;
					if ( $p['limit_levels'] < $p['level'] ) $check = false;
					if ( $p['limit_levels'] === -1 ) $check = true;

					if ( $check ) {

						if ( is_object( $item ) ) {

							$item = (array) $item;
						}

						if ( $p['parent_id'] == $item[ $p['parent_name'] ] ) {

							if ( $p['index_key'] ) {

								$i = $item[ $p['index_key'] ];
							}
							else {

								$i = $i + 1;
							}

							$r[ $i ] = array(
								'item' => $item,
								'children' => tool_build_recursive_array( array(
									'array' => $p['array'],
									'id_name' => $p['id_name'],
									'parent_name' => $p['parent_name'],
									'index_key' => $p['index_key'],
									'parent_id' => $item[ $p['id_name'] ],
									'limit_items' => $p['limit_items'],
									'limit_levels' => $p['limit_levels'],
									'level' => $p['level'] + 1,
								) )
							);

						}
					}

				}

			// }

			return $r;
		}

	// }
