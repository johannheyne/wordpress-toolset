<?php

	// GET PREV NEXT OBJECT ( Version 1 ) {

		function tool_get_prev_next( $p ) {

			$p += array(
				'numberposts' => -1,
				'post_status' => 'publish',
				'post_type' => 'post',
				'orderby' => 'post_date',
				'order' => 'ASC',
				'print' => false
			);

			global $post;

			$results = get_posts( array(
				'numberposts' => $p['numberposts'],
				'post_status' => $p['post_status'],
				'post_type' => $p['post_type'],
				'orderby' => $p['orderby'],
				'order' => $p['order']
			) );

			$prev_temp = false;
			$curr_temp = false;
			$ret['prev'] = false;
			$ret['next'] = false;

			foreach ( $results as $key => $item ) {

				if ( $curr_temp ) {
					$curr_temp = false;
					$ret['next']['obj'] = $item;
					$ret['next']['permalink'] = get_permalink( $item->ID );
				}
				if ( $item->ID === $post->ID ) {
					$curr_temp = true;
					if ( $prev_temp ) {
						$ret['prev']['obj'] = $prev_temp;
						$ret['prev']['permalink'] = get_permalink( $prev_temp->ID );
					}
				}
				$prev_temp = $item;
			}

			if ( $p['print'] ) {
				print_o( $ret );
			}

			return $ret;
		}

	// }
