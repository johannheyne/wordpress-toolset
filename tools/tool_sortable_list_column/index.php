<?php

	// SPALTEN HINZUFÜGEN {

		function tool_sortable_list_column( $p = array() ) {

			// DEFAULTS {

				$defaults = array(
					'posttype' => 'pages', // pages / posts
					'postname' => 'page',
					'colid' => 'meta_{colid}',
					'collabel' => 'Label',
					'rowlabelfunction' => function( $column_name, $post_id ) {

						if ( 'meta_{colid}' != $column_name ) {

							return;
						}

						$post_meta = get_post_meta( $post_id, '{meta_name}', true );
						$value = $post_meta;

						//if( $post_meta ) $value = '<a href="/backend/wp-admin/post.php?post=' . $post_meta . '&action=edit">' . get_the_title( $post_meta ) . '</a>';
						//if ( ! $post_meta ) $value = '';

						return $value;
					},
					'position' => 2,
					'sortmetakey' => '{meta_name}',
					'sortmetaorderby' => 'meta_value' // meta_value / meta_value_num
				);

				$p = array_replace_recursive( $defaults, $p );

			// }

			$sort_function = new wpSortableListColumn( $p );

		}

	// }
