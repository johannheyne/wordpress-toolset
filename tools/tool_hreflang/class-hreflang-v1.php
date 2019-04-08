<?php

	class ToolHreflang {

		public $post_types = array();

		public $location = array();

		public function add_post_type( $p = array() ) {

			// DEFAULTS {

				$defaults = array(
					'post_type' => false,
				);

				$p = array_replace_recursive( $defaults, $p );

			// }

			if ( post_type_exists( $p['post_type'] ) ) {

				array_push( $this->post_types, $p['post_type'] );
			}

		}

		function __construct() {

			/* Todo:

				1. [] filter_validate_field()
					Der Ziel Post sollte keine Links zu Sites enthalten, die beim aktuellen Post bereits verlinkt sind.

				2. [ ] action_remove_links_from_target_and_its_relations()
					Durch Punkt 1 ist es dann nicht mehr notwendig, beim Ziel Post und seinen verlinkten Posts die Links zu entfernen.
					Es werden bei action_update_target_links() nur die Sitelinks vom aktuellen Post im Ziel Post hinzugefügt.

				3. [ ] Es sollen bei action_update_target_links() nur die Sitelinks vom aktuellen Post im Ziel Post hinzugefügt werden.

			*/

			add_filter( 'acf/prepare_field/type=select', array( $this, 'filter_adds_field_choices' ), 10, 3 );
			add_action('acf/save_post', array( $this, 'action_remove_links_from_target_and_its_relations' ), 1 ); // before save post
			add_action('acf/save_post', array( $this, 'action_update_target_links' ), 20 ); // after post saved
			add_action( 'before_delete_post', array( $this, 'action_remove_links_from_relations' ) );
			add_filter( 'acf/validate_value', array( $this, 'filter_validate_field' ), 10, 4 );
		}

		public function run(  ) {

			$this->acf_add_local_field_group();
		}

		public function acf_add_local_field_group() {

			if ( ! empty( $this->post_types ) ) {

				foreach ( $this->post_types as $post_type_name ) {

					$this->location[] = array(
						array(
							'param' => 'post_type',
							'operator' => '==',
							'value' => $post_type_name,
						)
					);
				}
			}

			add_action( 'acf/init', array( $this, 'action_acf_add_local_field_group' ) );
		}

		public function action_acf_add_local_field_group() {

			if ( function_exists('acf_add_local_field_group') ) {

				acf_add_local_field_group( array(
					'key' => 'group_clone_hreflang_group',
					'title' => 'Verknüpfungen<br><small>Verknüpfungen zu alternativen Länderversionen dieses Inhaltes.</small>',
					'fields' => get_sites_field_group_fields( array(
						'key' => 'hreflang', // key for get_field( 'hreflang' );
						'label' => 'Country', // not displayed
						'clone' => array( 'group_clone_hreflang' ), // acf-group or acf-field keys
						//'required' => true,
					)),
					'location' => $this->location,
					'menu_order' => 0, // after post_data
					'style' => 'default',
					'position' => 'side',
					'hide_on_screen'=> array(
						//'permalink',
						'the_content',
						'excerpt',
						'discussion',
						'comments',
						//'revisions',
						'slug',
						'author',
						'format',
						'featured_image',
						'categories',
						'tags',
						'send-trackbacks'
					),
				) );
			}
		}

		public function filter_adds_field_choices( $field ) {

			if ( $field['parent'] !== 'group_clone_hreflang_group' ) {

				return $field;
			}

			$site_id = str_replace( 'hreflang_', '', $field['_clone'] );

			switch_to_blog( $site_id );

			$results = get_posts(array(
				'numberposts' => -1,
				'post_status' => 'any',
				'post_type' => 'page',
				'orderby' => 'menu_order',
				'order' => 'ASC',
			));


			$new = array();

			hierachical_order_posts( $results, $new );

			foreach ( $results as $post_item ) {

				$has_hreflang = false;

				foreach ( $GLOBALS['toolset']['sites'] as $item_site_id => $site ) {

					if ( ! empty( get_post_meta( $post_item->ID, 'hreflang_' . $item_site_id . '_post_id', true ) ) ) {

						$has_hreflang = true;
					}

				}

				$field['choices'][ $post_item->ID ] = $post_item->post_title;

				if ( $has_hreflang ) {

					$field['choices'][ $post_item->ID ] .= ' (verlink)';
				}
			}

			restore_current_blog();

			return $field;

		}

		public function action_remove_links_from_target_and_its_relations( $post_id ) {

			// bail early if no ACF data
			if ( empty( $_POST['acf'] ) ) {

				return;
			}

			// GET NEXT SITES ALTERNATES {

				$value_arr = array();
				$value_arr2 = array();

				foreach ( $GLOBALS['toolset']['sites'] as $site_id => $site ) {

					if ( ! empty( $_POST['acf']['hreflang_' . $site_id]['hreflang_' . $site_id . '_field_5ca4f862d07d8'] ) ) {

						$value_arr[ $site_id ] = $_POST['acf']['hreflang_' . $site_id]['hreflang_' . $site_id . '_field_5ca4f862d07d8'];
					}
				}

				if ( ! empty( $value_arr ) ) {

					foreach ( $GLOBALS['toolset']['sites'] as $site_id => $site ) {

						if ( ! empty( $value_arr[ $site_id ] ) ) {

							switch_to_blog( $site_id );

								foreach ( $GLOBALS['toolset']['sites'] as $site_id_2 => $site ) {

									$value_arr2[ $site_id_2 ]['site_id'] = $site_id;
									$value_arr2[ $site_id_2 ]['post_id'] = get_post_meta( $value_arr[ $site_id ], 'hreflang_' . $site_id_2 . '_post_id', true );
								}

							restore_current_blog();
						}
					}
				}

				if ( ! empty( $value_arr2 ) ) {

					foreach ( $GLOBALS['toolset']['sites'] as $site_id => $site ) {

						if ( ! empty( $value_arr2[ $site_id ] ) ) {

							switch_to_blog( $site_id );

								update_post_meta( $value_arr2[ $site_id ]['post_id'], 'hreflang_' . $value_arr2[ $site_id ]['site_id'] . '_post_id', false );

							restore_current_blog();
						}
					}
				}

			// }

			// GET CURRENT SITES ALTERNATES {

				$value_arr = array();

				foreach ( $GLOBALS['toolset']['sites'] as $site_id => $site ) {

					$obj_hreflang = get_field( 'hreflang_' . $site_id, $post_id );

					$alternate_id = false;

					if ( ! empty( $obj_hreflang['post_id'] ) ) {

						$alternate_id = $obj_hreflang['post_id'];
					}

					$value_arr[ $site_id ] = $alternate_id;
				}

			// }

			// REMOVES HREFLANG TO THIS FROM OTHER SITES {

				$base_blog_id = $GLOBALS['toolset']['blog_id'];

				if ( ! empty( $value_arr ) ) {

					foreach ( $GLOBALS['toolset']['sites'] as $site_id => $site ) {

						if ( $value_arr[ $site_id ] !== false ) {

							switch_to_blog( $site_id );

								foreach ( $GLOBALS['toolset']['sites'] as $site_id_2 => $site ) {

									update_post_meta( $value_arr[ $site_id ], 'hreflang_' . $site_id_2 . '_post_id', false );
								}

							restore_current_blog();
						}
					}
				}

				update_post_meta( $post_id, 'hreflang_' . $base_blog_id . '_post_id', false );

			// }

		}

		public function action_update_target_links( $post_id ) {

			// bail early if no ACF data
			if ( empty( $_POST['acf'] ) ) {

				return;
			}

			// GET CURRENT POST ALTERNATES {

				$value_arr = array();
				$has_cont = false;

				foreach ( $GLOBALS['toolset']['sites'] as $site_id => $site ) {

					$obj_hreflang = get_field( 'hreflang_' . $site_id, $post_id );

					$alternate_id = false;

					if ( ! empty( $obj_hreflang['post_id'] ) ) {

						$has_cont = true;
						$alternate_id = $obj_hreflang['post_id'];
					}

					$value_arr[ $site_id ] = $alternate_id;
				}

			// }

			// UPDATES SITES ALTERNATES {

				$base_blog_id = $GLOBALS['toolset']['blog_id'];

				foreach ( $GLOBALS['toolset']['sites'] as $site_id => $site ) {

					$target_post_id = $value_arr[ $site_id ];

					if ( $target_post_id ) {

						switch_to_blog( $site_id );

						foreach ( $value_arr as $site_id_2 => $post_id_2 ) {

							update_post_meta( $target_post_id, 'hreflang_' . $site_id_2 . '_post_id', $post_id_2 );
						}

						update_post_meta( $target_post_id, 'hreflang_' . $base_blog_id . '_post_id', $post_id );

						restore_current_blog();
					}
				}

				if ( $has_cont ) {

					update_post_meta( $post_id, 'hreflang_' . $base_blog_id . '_post_id', $post_id );
				}

			// }

		}

		public function action_remove_links_from_relations( $post_id ) {

			// GET CURRENT POST ALTERNATES {

				$value_arr = array();

				foreach ( $GLOBALS['toolset']['sites'] as $site_id => $site ) {

					$obj_hreflang = get_field( 'hreflang_' . $site_id, $post_id );

					$alternate_id = false;

					if ( ! empty( $obj_hreflang['post_id'] ) ) {

						$alternate_id = $obj_hreflang['post_id'];
					}

					$value_arr[ $site_id ] = $alternate_id;
				}

			// }

			// REMOVES LINKS FROM OTHER SITES {

				$base_blog_id = $GLOBALS['toolset']['blog_id'];

				foreach ( $value_arr as $site_id => $item_post_id ) {

					if ( $item_post_id ) {

						switch_to_blog( $site_id );

							update_post_meta( $item_post_id, 'hreflang_' . $base_blog_id . '_post_id', false );

						restore_current_blog();
					}
				}

			// }

		}

		public function filter_validate_field( $valid, $value, $field, $input ){

			// FILTER {

				if ( $field['parent'] !== 'group_clone_hreflang_group' ) {

					return $valid;
				}

				if ( is_array( $value ) ) {

					return $valid;
				}

			// }

			$post_id = $_POST['post_ID'];

			$name_arr = explode( '_',  $field['name'] );
			$site_id = $name_arr[ 1 ];
			$base_blog_id = $GLOBALS['toolset']['blog_id'];

			if ( $value == get_post_meta( $post_id, 'hreflang_' . $site_id . '_post_id', true ) ) {

				return $valid;
			}

			switch_to_blog( $site_id );

				$test_value = get_post_meta( $value, 'hreflang_' . $base_blog_id . '_post_id', true );

				if (
					! empty( $test_value )
				) {

					$valid = 'Ist bereits mit dieser Site verlinkt!';
				}

			restore_current_blog();

			// return
			return $valid;

		}
	}
