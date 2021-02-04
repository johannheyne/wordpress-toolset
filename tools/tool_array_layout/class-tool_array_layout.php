<?php

	class ToolArrayLayout {

		private $param = array();

		public $html = '';

		function __construct( $p = array() ) {

			// DEFAULTS {

				$defaults = array(
					'data' => false,
					'callback_item' => false,
					'list_elem' => 'ul',
					'item_elem' => 'li',
					'list_attrs' => array(),
					'item_attrs' => array(),
					'echo' => false,
				);

				$p = array_replace_recursive( $defaults, $p );

			// }

			$this->param = $p;

			$this->html = $this->layout( $this->param['data'], 0 );

			if ( $this->param['echo'] ) {

				echo $this->html;
			}
		}

		function get() {

			return $this->html;
		}

		function layout( $data, $depth ) {

			if (
				$data === false OR
				! is_array( $data ) OR
				empty( $data )
			) {

				return '';
			}

			$return = '';

			$list_attrs = $this->param['list_attrs'];

			if ( ! empty( $this->param['list_attrs/depth=' . $depth] ) ) {

				$list_attrs = $this->param['list_attrs/depth=' . $depth];
			}

			$return .= '<' . $this->param['list_elem'] . attrs( $list_attrs ) . '>';

				foreach ( $data as $key => $item ) {

					$item_attrs = $this->param['item_attrs'];

					if ( ! empty( $this->param['item_attrs/depth=' . $depth ] ) ) {

						$item_attrs = $this->param['item_attrs/depth=' . $depth ];
					}

					$return .= '<' . $this->param['item_elem'] . attrs( $item_attrs ) . '>';

						$p = array(
							'key' => $key,
							'depth' => $depth,
							'data' => $item,
						);

						if ( ! empty( $this->param['callback_item/depth=' . $depth ] ) ) {

							$return .= $this->param['callback_item/depth=' . $depth ]( $p );
						}
						else {

							$return .= $this->param['callback_item']( $p );
						}

						if ( ! empty( $item['children'] ) ) {

							$return .= $this->layout( $item['children'], $depth + 1 );
						}

					$return .= '</' . $this->param['item_elem'] . '>';
				}

			$return .= '</' . $this->param['list_elem'] . '>';


			return $return;
		}
	}
