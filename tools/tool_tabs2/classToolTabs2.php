<?php

	class ToolsetTabs2 {

		public $tabs = [];

		public $id = false;


		function __construct( $p = array() ) {

			// DEFAULTS {

				$defaults = array(
					'term' => '',
				);

				$p = tool_merge_defaults( $p, $defaults );

			// }

			$this->id = newid();
		}

		public function add_item( $p = array() ) {

			// DEFAULTS {

				$defaults = array(
					'label' => false,
					'callback' => function(){},
				);

				//$p = array_replace_recursive( $defaults, $p );
				$p = tool_merge_defaults( $p, $defaults );

			// }

			$this->tabs[] = array(
				'label' => $p['label'],
				'callback' => $p['callback'],
			);
		}

		public function get_html() {

			if ( empty( $this->tabs ) ) {

				return '';
			}

			$html = '';

			// GET INPUTS {

				foreach ( $this->tabs as $key => $item ) {

					$attrs = array(
						'class' => array( 'hide-accessible' ),
						'style' => array(),
						'type' => 'radio',
						'name' => 'tabs[]',
						'id' => $this->id . '_' . $key,
						'value' => $key,
					);

					if ( 0 == $key ) {

						$attrs['checked'] = 'checked';
					}

					$html .= '<input' . attrs( $attrs ) . '>';
				}

			// }

			// GET TABS {

				$html .= '<ul class="_tabs" data-tabs="' . $this->id . '">';

					foreach ( $this->tabs as $key => $item ) {

						$attrs = array(
							'class' => array( '_tabs-item' ),
							'data-tab-id' => $key,
						);

						$html .= '<li' . attrs( $attrs ) . '><label for="' . $this->id . '_' . $key . '">' . $item['label'] . '</label></li>';
					}

				$html .= '</ul>';

			// }

			// GET CONTENTS {

				$html .= '<div data-tab-cont-wrap="">';

					foreach ( $this->tabs as $key => $item ) {

						$html .= '<div data-tab-cont="' . $key . '">';
							$html .= $item['callback']();
						$html .= '</div>';
					}

				$html .= '</div>';

			// }


			return $html;
		}
	}
