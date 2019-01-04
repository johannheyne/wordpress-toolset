<?php

	// SORTIERBARE SPALTEN ( Version 1 ) {

		class wpSortableListColumn {

			function __construct( $param )  {

				// DEFAULTS {

					$defaults = array(
						'postname' => false,
						'position' => 2,
						'posttype' => 'posts',
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

				if ( $this->param['posttype'] == 'taxonomies' ) {

					$this->param['posttype'] = $this->param['postname'];
				}

				add_filter( 'manage_edit-' . $this->param['postname'] . '_columns', array( $this, 'add_column' ) );
				add_action( 'manage_' . $this->param['posttype'] . '_custom_column', array( $this, 'column_content' ), 10, 3 );
				//add_filter( 'manage_' . $this->param['posttype'] . '_custom_column', array( $this, 'column_content_taxonomy' ), 10, 3 );

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

						echo $this->param['rowlabelfunction']( $arg2, $arg3 );
					}

					if ( empty( $arg3 ) ) {

						echo $this->param['rowlabelfunction']( $arg1, $arg2 );
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

		}

	// }
