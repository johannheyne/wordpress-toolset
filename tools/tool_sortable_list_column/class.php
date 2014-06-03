<?php

	// SORTIERBARE SPALTEN ( Version 1 ) {

		class wpSortableListColumn {

			public $postname;
			public $collabel;
			public $rowlabelfunction;
			public $colid;
			public $posttype = 'posts'; // or pages
			public $position = 2; // or tax
			public $sorttype = 'meta'; // or tax
			public $sortmetakey;
			public $sortmetaorderby = 'meta_value_num';
			public $sorttaxname;

			function __construct( $param )  {
				
				if( isset( $param['postname'])) $this->postname = $param['postname'];
				if( isset( $param['position'])) $this->position = $param['position'];
				if( isset( $param['posttype'])) $this->posttype = $param['posttype'];
				if( isset( $param['colid'])) $this->colid = $param['colid'];
				if( isset( $param['collabel'])) $this->collabel = $param['collabel'];
				if( isset( $param['rowlabelfunction'])) $this->rowlabelfunction = $param['rowlabelfunction'];
				if( isset( $param['sorttype'])) $this->sorttype = $param['sorttype'];
				if( isset( $param['sortmetakey'])) $this->sortmetakey = $param['sortmetakey'];
				if( isset( $param['sortmetaorderby'])) $this->sortmetaorderby = $param['sortmetaorderby'];
				if( isset( $param['sorttaxname'])) $this->sorttaxname = $param['sorttaxname'];

				add_filter( 'manage_edit-' . $this->postname . '_columns', array( $this, 'add_column' ) );
				add_action( 'manage_' . $this->posttype . '_custom_column', array( $this, 'column_content' ), 10, 2 );

				// bei hirarchichen Listen 'manage_pages_custom_column'

				if ( $this->sortmetakey != '' ) {
					
					add_filter( 'manage_edit-' . $this->postname . '_sortable_columns', array( $this, 'column_register_sortable' ) );
					add_filter( 'request', array($this, 'column_orderby') );
				}

				if ( $this->sorttaxname != '' ) {
					
					add_filter( 'posts_clauses', array($this, 'taxonomie_sort' ), 10, 2 );
				}

			}

			function add_column ($columns) {

				$spalte[ $this->colid ] = $this->collabel;

				$an_position = $this->position;
				$result = array_slice( $columns, 0, $an_position );
				$result = array_merge( $result, $spalte );
				$result = array_merge( $result, array_slice( $columns, $an_position ) );
				return $result;
			}

			function column_content( $column_name, $post_id ) {
				
				eval( $this->rowlabelfunction );
			}

			function column_register_sortable( $columns ) {

				$columns[ $this->colid ] = $this->colid; // beide spaltenname klein schreiben!

				return $columns;
			}

			function column_orderby( $vars ) {

				if ( isset( $vars['orderby'] ) && $this->colid == $vars['orderby'] ) {
					
					$vars = array_merge( $vars, array(
						'meta_key' => $this->sortmetakey,
						'orderby' => $this->sortmetaorderby
					) );
				}
				return $vars;
			}

			function taxonomie_sort( $clauses, $wp_query ) {
			
				global $wpdb;

				if ( isset( $wp_query->query['orderby'] ) && $this->sorttaxname == $wp_query->query['orderby'] ) {

					$clauses['join'] .= "LEFT OUTER JOIN {$wpdb->term_relationships} ON {$wpdb->posts}.ID={$wpdb->term_relationships}.object_id LEFT OUTER JOIN {$wpdb->term_taxonomy} USING (term_taxonomy_id) LEFT OUTER JOIN {$wpdb->terms} USING (term_id)";

					$clauses['where'] .= " AND (taxonomy = $this->sorttaxname OR taxonomy IS NULL)";
					$clauses['groupby'] = "object_id";
					$clauses['orderby']  = "GROUP_CONCAT({$wpdb->terms}.name ORDER BY name ASC) ";
					$clauses['orderby'] .= ( 'ASC' == strtoupper( $wp_query->get('order') ) ) ? 'ASC' : 'DESC';
				}

				return $clauses;
			}

		}

	// }

?>