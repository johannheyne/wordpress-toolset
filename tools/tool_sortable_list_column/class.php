<?php

	// SORTIERBARE SPALTEN ( Version 1 ) {

		class wpSortableListColumn {

			private $param = array();

			function __construct( $param )  {

				// DEFAULTS {

					$defaults = array(
						'posttype' => 'posts',
						'postname' => false,
						'position' => 2,
						'colid' => false,
						'collabel' => 'Label',
						'rowlabelfunction' => false,
						'sorttype' => 'meta',
						'sortmetakey' => false,
						'sortmetaorderby' => 'meta_value_num',
						'sorttaxname' => false,
					);

					$this->param = array_replace_recursive( $defaults, $param );

				// }

				if ( ! $this->param['postname'] ) {

					$this->param['postname'] = $this->param['posttype'];
				}

				if ( $this->param['posttype'] == 'posts' OR $this->param['posttype'] == 'pages' ) {

					$manage_custom_column_slug = $this->param['posttype'];
				}

				elseif ( $this->param['posttype'] == 'taxonomies' ) {

					$manage_custom_column_slug = $this->param['postname'];
				}

				elseif ( $this->param['posttype'] !== 'posts' OR $this->param['posttype'] !== 'pages' ) {

					$manage_custom_column_slug = $this->param['posttype'] . '_posts';
				}

				add_filter( 'manage_edit-' . $this->param['postname'] . '_columns', array( $this, 'add_column' ) );
				add_action( 'manage_' . $manage_custom_column_slug . '_custom_column', array( $this, 'column_content' ), 10, 3 );

				// bei hirarchichen Listen 'manage_pages_custom_column'

				if ( $this->param['sortmetakey'] != '' ) {

					add_filter( 'manage_edit-' . $this->param['postname'] . '_sortable_columns', array( $this, 'column_register_sortable' ) );
					add_filter( 'request', array($this, 'column_orderby') );
				}

				if ( $this->param['sorttaxname'] != '' ) {

					add_filter( 'posts_clauses', array($this, 'taxonomie_sort' ), 10, 2 );
				}

			}

			function add_column ($columns) {

				$spalte[ $this->param['colid'] ] = $this->param['collabel'];

				$an_position = $this->param['position'];
				$result = array_slice( $columns, 0, $an_position );
				$result = array_merge( $result, $spalte );
				$result = array_merge( $result, array_slice( $columns, $an_position ) );

				return $result;
			}

			function column_content( $arg1, $arg2, $arg3 = false ) {

				if ( is_string( $this->param['rowlabelfunction'] ) ) {

					eval( $this->param['rowlabelfunction'] );
				}

				if ( is_callable( $this->param['rowlabelfunction'] ) ) {

					if ( empty( $arg1 ) ) {

						echo $this->param['rowlabelfunction']( $arg2, $arg3, $this );
					}

					if ( empty( $arg3 ) ) {

						echo $this->param['rowlabelfunction']( $arg1, $arg2, $this );
					}
				}
			}

			/*function column_content_taxonomy( $null, $column_name, $term_id ) {

				if ( is_string( $this->param['rowlabelfunction'] ) ) {

					eval( $this->param['rowlabelfunction'] );
				}

				if ( is_callable( $this->param['rowlabelfunction'] ) ) {

					echo $this->param['rowlabelfunction']( $null, $column_name, $term_id );
				}
			}*/

			function column_register_sortable( $columns ) {

				$columns[ $this->param['colid'] ] = $this->param['colid']; // beide spaltenname klein schreiben!

				return $columns;
			}

			function column_orderby( $vars ) {

				if ( isset( $vars['orderby'] ) && $this->param['colid'] == $vars['orderby'] ) {

					$vars = array_merge( $vars, array(
						'meta_key' => $this->param['sortmetakey'],
						'orderby' => $this->param['sortmetaorderby']
					) );
				}
				return $vars;
			}

			function taxonomie_sort( $clauses, $wp_query ) {

				global $wpdb;

				if ( isset( $wp_query->query['orderby'] ) && $this->param['sorttaxname'] == $wp_query->query['orderby'] ) {

					$clauses['join'] .= "LEFT OUTER JOIN {$wpdb->term_relationships} ON {$wpdb->posts}.ID={$wpdb->term_relationships}.object_id LEFT OUTER JOIN {$wpdb->term_taxonomy} USING (term_taxonomy_id) LEFT OUTER JOIN {$wpdb->terms} USING (term_id)";

					$clauses['where'] .= " AND (taxonomy = $this->param['sorttaxname'] OR taxonomy IS NULL)";
					$clauses['groupby'] = "object_id";
					$clauses['orderby']  = "GROUP_CONCAT({$wpdb->terms}.name ORDER BY name ASC) ";
					$clauses['orderby'] .= ( 'ASC' == strtoupper( $wp_query->get('order') ) ) ? 'ASC' : 'DESC';
				}

				return $clauses;
			}

			// methodes for usage in the row label function

			function get_taxonomy_term_meta_label( $p = array() ) {

				// DEFAULTS {

					$defaults = array(
						'taxonomy_name' => false,
						'meta_post_type' => false,
						'meta_taxonomy_name' => false,
						'meta_key' => false,
						'meta_id' => false,
					);

					$p = array_replace_recursive( $defaults, $p );

					$return = '';

				// }

				$meta_value = get_field( $p['meta_key'], $p['taxonomy_name'] .'_' . $p['meta_id'] );

				if ( ! empty( $meta_value ) ) {

					$results = array();

					if ( is_array( $meta_value ) ) {

						foreach ( $meta_value as $id ) {

							$term = get_term( $id , $p['meta_taxonomy_name'] );
							$term_name = get_lang_field( 'lang_taxonomy/name', $p['meta_taxonomy_name'] . '_' . $term->term_id );
							array_push( $results, '<a href="term.php?taxonomy=' . $p['meta_taxonomy_name'] . '&tag_ID=' . $term->term_id . '&post_type=' . $p['meta_post_type'] . '">' . $term_name . '</a>' );
						}
					}

					elseif ( is_string( $id ) ) {

						$term = get_term( $id , $p['meta_taxonomy_name'] );
						$term_name = get_lang_field( 'lang_taxonomy/name', $p['meta_taxonomy_name'] . '_' . $term->term_id );
						array_push( $results, '<a href="term.php?taxonomy=' . $p['meta_taxonomy_name'] . '&tag_ID=' . $term->term_id . '&post_type=' . $p['meta_post_type'] . '">' . $term_name . '</a>' );
					}

					$return = implode( ', ', $results );
				}

				return $return;
			}

		}

	// }
