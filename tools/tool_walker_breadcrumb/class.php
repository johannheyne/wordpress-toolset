<?php

	// BREADCRUMP WALKER Version (2) {

		class BreadcrumbWalker extends Walker_Nav_Menu {

			private $i_current_page_id;
			private $i_depth;
			private $a_output;
			private $delimiter;

			function __construct( $p = array() ) {

				$p += array(
					'delimiter' => ''
				);
				$this->delimiter = $p['delimiter'];

				// sets our current page so we know when to exit
				$this->i_current_page_id = get_queried_object()->ID;
			}

			function start_lvl(&$output, $depth=0, $args=array()) {
				// increment the depth every time a new ul is entered
				$this->i_depth++;
			}

			function end_lvl(&$output, $depth=0, $args=array()) {
				// decrement the depth when we exit a ul
				$this->i_depth--;
			}

			function start_el(&$output, $item, $depth=0, $args=array()) {
				// if this value is zero, we're starting a new branch
				if($item->menu_item_parent == 0) {
					// reset the output array and depth counters
					$this->a_output = array();
					$this->i_depth = 0;
				}
				// if we haven't set the representative menu item for this depth, do so
				if(!isset($this->a_output[$this->i_depth])) {
					$this->a_output[$this->i_depth] = '<a href="' . get_permalink($item->object_id) . '">' . $item->title . '</a>';
				}
			}

			function end_el(&$output, $item, $depth=0, $args=array()) {
				if($this->i_current_page_id == $item->object_id) {
					// check to see if this is our last item, if so display the breadcrumb
					if($this->i_depth > 0) {
						// but only show it if we actually have a breadcrumb trail
						$this->display_breadcrumb();
					}
				} else {
					// if not, unset the item for this depth since this isn't what we're going to display
					unset($this->a_output[$this->i_depth]);
				}
			}

			function display_breadcrumb() {
				// implode our array into a string
				echo implode($this->delimiter, $this->a_output);
			}
		}

	// }
