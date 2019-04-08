<?php

	class ToolHreflang {

		public $post_types = array();

		public $location = array();

		public $site_id = false;

		public $sites = array();

		public $post_id = array();

		public $post_links = false;

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

			$this->set_post_id();

			add_filter( 'acf/prepare_field/type=select', array( $this, 'filter_adds_field_choices' ), 10, 3 );
			add_action( 'before_delete_post', array( $this, 'action_remove_links_from_relations' ) );
			add_filter( 'after_setup_theme', array( $this, 'run' ) );

			if ( ! empty( $this->post_id ) ) {

				$this->set_site_id();
				$this->set_sites();

				add_action('acf/init', array( $this, 'set_post_links' ), 1 ); // before save post
				add_action('acf/save_post', array( $this, 'action_remove_links_from_target_and_its_relations' ), 1 ); // before save post
				add_action('acf/save_post', array( $this, 'action_update_target_links' ), 20 ); // after post saved
				add_filter( 'acf/validate_value', array( $this, 'filter_validate_field' ), 10, 4 );
			}
		}

		public function run() {

			$this->post_types = apply_filters( 'toolset/hreflang/post_types', $this->post_types );

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

			$site_id = $this->get_site_id_from_field( $field );

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

					if ( ! empty( $this->get_post_meta_hreflang( $post_item->ID, $item_site_id ) ) ) {

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

				foreach ( $this->sites as $site_id => $site ) {

					if ( $this->get_link_from_acf_post( $site_id )  ) {

						$value_arr[ $site_id ] = $this->get_link_from_acf_post( $site_id );
					}
				}

				if ( ! empty( $value_arr ) ) {

					foreach ( $this->sites as $site_id => $site ) {

						if ( ! empty( $value_arr[ $site_id ] ) ) {

							switch_to_blog( $site_id );

								foreach ( $this->sites as $site_id_2 => $site ) {

									$value_arr2[ $site_id_2 ]['site_id'] = $site_id;
									$value_arr2[ $site_id_2 ]['post_id'] = $this->get_post_meta_hreflang( $value_arr[ $site_id ], $site_id_2 );
								}

							restore_current_blog();
						}
					}
				}

				if ( ! empty( $value_arr2 ) ) {

					foreach ( $this->sites as $site_id => $site ) {

						if ( ! empty( $value_arr2[ $site_id ] ) ) {

							switch_to_blog( $site_id );

								$this->update_post_meta_hreflang( $value_arr2[ $site_id ]['post_id'], $value_arr2[ $site_id ]['site_id'], false );

							restore_current_blog();
						}
					}
				}

			// }

			// GET CURRENT SITES ALTERNATES {

				$value_arr = array();

				foreach ( $this->sites as $site_id => $site ) {

					$val = $this->get_post_meta_hreflang( $post_id, $site_id );

					$alternate_id = false;

					if ( ! empty( $val ) ) {

						$alternate_id = $val;
					}

					$value_arr[ $site_id ] = $alternate_id;
				}

			// }

			// REMOVES HREFLANG TO THIS FROM OTHER SITES {

				if ( ! empty( $value_arr ) ) {

					foreach ( $this->sites as $site_id => $site ) {

						if ( $value_arr[ $site_id ] !== false ) {

							switch_to_blog( $site_id );

								foreach ( $this->sites as $site_id_2 => $site ) {

									$this->update_post_meta_hreflang( $value_arr[ $site_id ], $site_id_2, false );
								}

							restore_current_blog();
						}
					}
				}

				$this->update_post_meta_hreflang( $post_id, $this->site_id, false );

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

				foreach ( $this->sites as $site_id => $site ) {

					$val = $this->get_post_meta_hreflang( $site_id, $post_id );

					$alternate_id = false;

					if ( ! empty( $val ) ) {

						$has_cont = true;
						$alternate_id = $val;
					}

					$value_arr[ $site_id ] = $alternate_id;
				}

			// }

			// UPDATES SITES ALTERNATES {

				foreach ( $this->sites as $site_id => $site ) {

					$target_post_id = $value_arr[ $site_id ];

					if ( $target_post_id ) {

						switch_to_blog( $site_id );

						foreach ( $value_arr as $site_id_2 => $post_id_2 ) {

							$this->update_post_meta_hreflang( $target_post_id, $site_id_2, $post_id_2 );
						}

						$this->update_post_meta_hreflang( $target_post_id, $this->site_id, $post_id );

						restore_current_blog();
					}
				}

				if ( $has_cont ) {

					$this->update_post_meta_hreflang( $post_id, $this->site_id, $post_id );
				}

			// }

		}

		public function action_remove_links_from_relations( $post_id ) {

			// GET CURRENT POST ALTERNATES {

				$value_arr = array();

				foreach ( $this->sites as $site_id => $site ) {

					$val = $this-get_post_meta_hreflang( $post_id, $site_id );

					$alternate_id = false;

					if ( ! empty( $val ) ) {

						$alternate_id = $val;
					}

					$value_arr[ $site_id ] = $alternate_id;
				}

			// }

			// REMOVES LINKS FROM OTHER SITES {

				foreach ( $value_arr as $site_id => $item_post_id ) {

					if ( $item_post_id ) {

						switch_to_blog( $site_id );

							$this->update_post_meta_hreflang( $item_post_id, $this->site_id, false );

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

			$site_id = $this->get_site_id_from_field( $field );

			// LINK VALUE DID NOT CHANGE {

				if ( $value == $this->get_post_meta_hreflang( $this->post_id, $site_id ) ) {

					return $valid;
				}

			// }

			switch_to_blog( $site_id );

				$target_links = $this->get_post_links( $value );

			restore_current_blog();

			if ( ! $this->can_links_be_merged( $this->post_links, $target_links ) ) {

				$valid = 'Der ausgewählte Inhalt besitzt bereits Verknüpfungen, welche in Konflikt mit denen des aktuellen Inhaltes stehen.';
			}

			// return
			return $valid;
		}


		private function set_site_id() {

			$this->site_id = $GLOBALS['toolset']['blog_id'];
		}

		private function set_post_id() {

			if (
				! empty( $_POST['post_ID'] ) AND
				! is_array( $_POST['post_ID'] )
			) {

				$this->post_id = $_POST['post_ID'];
			}
		}

		private function set_sites() {

			$this->sites = $GLOBALS['toolset']['sites'];
		}

		public function set_post_links() {

			foreach ( $this->sites as $site_id => $site ) {

				$id = get_post_meta( $this->post_id, 'hreflang_' . $site_id . '_post_id', true );

				$link_id = false;

				if ( ! empty( $id ) ) {

					$link_id = $id;
				}

				$this->post_links[ $site_id ] = $link_id;
			}
		}


		public function get_post_links( $post_id ) {

			$links = array();

			foreach ( $this->sites as $site_id => $site ) {

				$id = $this->get_post_meta_hreflang( $post_id, $site_id );

				$link_id = false;

				if ( ! empty( $id ) ) {

					$link_id = $id;
				}

				$links[ $site_id ] = $link_id;
			}

			return $links;
		}

		private function get_site_id_from_field( $field ) {

			$name_arr = explode( '_',  $field['key'] );
			$site_id = $name_arr[ 1 ];

			return $site_id;
		}

		public function get_post_meta_hreflang( $post_id = false, $site_id = 0, $single = true ) {

			return get_post_meta( $post_id, 'hreflang_' . $site_id . '_post_id', $single );
		}

		public function get_link_from_acf_post( $site_id ) {

			if ( ! empty( $_POST['acf']['hreflang_' . $site_id]['hreflang_' . $site_id . '_field_5ca4f862d07d8'] ) ) {

				return $_POST['acf']['hreflang_' . $site_id]['hreflang_' . $site_id . '_field_5ca4f862d07d8'];
			}

			return false;
		}

		public function update_post_meta_hreflang( $post_id = false, $site_id = 0, $value = false ) {

			return update_post_meta( $post_id, 'hreflang_' . $site_id . '_post_id', $value );
		}

		public function can_links_be_merged( $links_1 = array(), $links_2 = array() ) {

			foreach ( $links_1 as $key => $value ) {

				if ( ! empty( $links_2[ $key ] ) AND ! empty( $value ) ) {

					return false;
				}
			}

			return true;
		}
	}
