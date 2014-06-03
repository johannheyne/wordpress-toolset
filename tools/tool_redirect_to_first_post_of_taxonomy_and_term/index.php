<?php
	
	// REDIRECT TO FIRST POST OF TAXONOMY AND TERM ( Version 1 ) {

		function tool_redirect_to_first_post_of_taxonomy_and_term( $p = array() ) {

			// DEFAULTS {

				$defaults = array(
					'post_type' => false,
					'taxonomy_slug' => false,
					'term_slug' => false,
					'orderby' => 'menu_order',
					'order' => 'ASC',
					'wpml' => false,
				);

				$p = array_replace_recursive( $defaults, $p );

			// }

			// VARS {

				$v = array(
					'param' => false,
					'queried_object' => false,
				);

			// }

			// EXTENDING PARAMETERS {

				// SET CURRENT POSTTYPE {

					if ( !$p['post_type'] ) {

						$p['post_type'] = get_post_type();
					}

				// }

				// SET CURRENT TAXONOMY SLUG AND TERM SLUG {

					if ( !$p['taxonomy_slug'] || !$p['term_slug'] ) {

						$v['queried_object'] = get_queried_object();
					}

					if ( !$p['taxonomy_slug'] ) {

						$p['taxonomy_slug'] = $v['queried_object']->taxonomy;
					}

					if ( !$p['term_slug'] ) {

						$p['term_slug'] = $v['queried_object']->slug;
					}

				// }

			// }

			// PREPARE POSTQUERY PARAMETERS {

				$v['param'] = array(
					'numberposts' => 1,
					'post_status' => 'publish',
					'post_type' => $p['post_type'],
					'orderby' => $p['orderby'],
					$p['taxonomy_slug'] => $p['term_slug'],
					'order' => $p['order'],
				);

				if ( $p['wpml'] ) {

					$v['param']['suppress_filters'] = false; // WPML, get only posts from current language
				}

			// }

			// GET POSTQUERY RESULTS {

				$results = get_posts( $v['param'] );

			// }

			// REDIRECT {

				wp_redirect( get_permalink( $results[0]->ID ), 302 );

				exit;

			// }

		}

	// }
	
?>