<?php

	class ToolArrayPos {

		private $array = array();

		private $array_pos = array();

		private $array_return = array();

		private $param = array();

		function __construct( $p = array() ) {

			// DEFAULTS {

				$defaults = array(
					'array' => false, // array to sort
					'param' => array(
						'pos_key' => 'pos_key', // item array key used for positioning by pos_before and pos_after
						'parent_pos_key' => 'parent_pos_key', // item array key used for positioning by pos_before and pos_after
					),
				);

				$p = array_replace_recursive( $defaults, $p );

			// }

			$this->param = $p['param'];
			$this->array = $p['array'];

			//$this->get();
		}

		function get() {

			$this->adds_pos();
			$this->adds_pos_top();
			$this->adds_pos_bottom();
			$this->builds_array();

			return $this->array_return;
		}

		function adds_pos() {

			foreach ( $this->array as $key => $item ) {

				if ( isset( $item['pos'] ) ) {

					// INITS NEW SUB ARRAY {

						if ( ! isset( $this->array_pos[ $item['pos'] ] ) ) {

							$this->array_pos[ $item['pos'] ] = array();
						}

					// }

					// CHECK pos_before {

						$this->adds_pos_before( $item, $item['pos'], $this->array_pos );

					// }

					// CHECK without pos_before and pos_after {

						if (
							! isset( $item['pos_before'] ) AND
							! isset( $item['pos_after'] )
						) {

							$this->array_pos[ $item['pos'] ][] = $item;
							unset( $this->array[ $key ] );
						}

					// }

					// CHECK pos_after {

						$this->adds_pos_after( $item, $item['pos'], $this->array_pos );

					// }
				}
			}

			ksort( $this->array_pos );
		}

		function adds_pos_before( $current_item, $pos, &$array ) {

			foreach ( $this->array as $key => $item ) {

				if (
					isset( $item['pos_before'] ) AND
					isset( $current_item[ $this->param['pos_key'] ] ) AND
					$current_item[ $this->param['pos_key'] ] === $item['pos_before']
				) {

					$this->adds_pos_before( $item, $pos, $array );
					$array[ $pos ][] = $item;
					$this->adds_pos_after( $item, $pos, $array );

					unset( $this->array[ $key ] );
				}
			}
		}

		function adds_pos_after( $current_item, $pos, &$array ) {

			foreach ( $this->array as $key => $item ) {

				if (
					isset( $item['pos_after'] ) AND
					isset( $current_item[ $this->param['pos_key'] ] ) AND
					$current_item[ $this->param['pos_key'] ] === $item['pos_after']
				) {

					$this->adds_pos_before( $item, $pos, $array );
					$array[ $pos ][] = $item;
					$this->adds_pos_after( $item, $pos, $array );

					unset( $this->array[ $key ] );
				}
			}
		}

		function adds_pos_top() {

			$array = array();

			foreach ( $this->array as $key => $item ) {

				if ( isset( $item['pos_top'] ) ) {

					// INITS NEW SUB ARRAY {

						if ( ! isset( $array[ $item['pos_top'] ] ) ) {

							$array[ $item['pos_top'] ] = array();
						}

					// }

					$this->adds_pos_before( $item, $item['pos_top'], $array );
					$array[ $item['pos_top'] ][] = $item;
					$this->adds_pos_after( $item, $item['pos_top'], $array );

					unset( $this->array[ $key ] );
				}
			}

			krsort( $array );

			foreach ( $array as $key => $item ) {

				array_unshift( $this->array_pos, $item );
			}
		}

		function adds_pos_bottom() {

			$array = array();

			foreach ( $this->array as $key => $item ) {

				if ( isset( $item['pos_bottom'] ) ) {

					// INITS NEW SUB ARRAY {

						if ( ! isset( $array[ $item['pos_bottom'] ] ) ) {

							$array[ $item['pos_bottom'] ] = array();
						}

					// }

					$this->adds_pos_before( $item, $item['pos_bottom'], $array );
					$array[ $item['pos_bottom'] ][] = $item;
					$this->adds_pos_after( $item, $item['pos_bottom'], $array );

					unset( $this->array[ $key ] );
				}
			}

			ksort( $array );

			foreach ( $array as $key => $item ) {

				array_push( $this->array_pos, $item );
			}
		}

		function builds_array() {

			foreach ( $this->array_pos as $item ) {

				foreach ( $item as $item_2 ) {

					if ( empty( $item_2[ $this->param['parent_pos_key'] ] ) ) {

						$item_2['children'] = $this->get_level( $item_2[ $this->param['pos_key'] ] );
						$this->array_return[] = $item_2;
						continue;
					}
				}
			}
		}

		function get_level( $parent_pos_id ) {

			$return = array();

			foreach ( $this->array_pos as $item ) {

				foreach ( $item as $item_2 ) {

					if ( empty( $item_2[ $this->param['parent_pos_key'] ] ) ) {

						continue;
					}

					if ( $item_2[ $this->param['parent_pos_key'] ] ==  $parent_pos_id ) {

						$return[] = $item_2;
						continue;
					}
				}
			}

			return $return;
		}
	}
