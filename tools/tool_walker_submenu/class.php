<?php

	// SUBMENUWALKER Version (2) {

		/* zeigt die Submenüpunkte des aktuellen Menübaumes ausgehend von Level 0
		ab der per Parameter angegebenen Tiefe.

			Folgender Code zeigt nur die direkten Unterpunkte des aktuellen Menüpunktes.

			wp_nav_menu( array(
				'depth'		   => 2,
				'walker'		  => new SubmenuWalker(1)
			) );

		*/

		class SubmenuWalker extends Walker_Nav_Menu {

			var $start_at_depth = 1;
			var $output_items = FALSE;

			function __construct($start_at_depth = 1)
			{
				$this->start_at_depth = $start_at_depth;
			}

			/**
			 * @see Walker::$tree_type
			 * @since 3.0.0
			 * @var string
			 */
			var $tree_type = array( 'post_type', 'taxonomy', 'custom' );

			/**
			 * @see Walker::$db_fields
			 * @since 3.0.0
			 * @todo Decouple this.
			 * @var array
			 */
			var $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );

			/**
			 * @see Walker::start_lvl()
			 * @since 3.0.0
			 *
			 * @param string $output Passed by reference. Used to append additional content.
			 * @param int $depth Depth of page. Used for padding.
			 */
			function start_lvl( &$output, $depth = 0, $args = array() ) {

				if($depth >= $this->start_at_depth AND $this->output_items === TRUE)
				{
					$indent = str_repeat("\t", $depth + 7);
					$output .= "\n$indent<ul class=\"sub-menu\">\n";
				}
			}

			/**
			 * @see Walker::end_lvl()
			 * @since 3.0.0
			 *
			 * @param string $output Passed by reference. Used to append additional content.
			 * @param int $depth Depth of page. Used for padding.
			 */
			function end_lvl( &$output, $depth = 0, $args = array() ) {

				if($depth >= $this->start_at_depth AND $this->output_items === TRUE)
				{
					$indent = str_repeat("\t", $depth + 7);
					$output .= "$indent</ul>\n$indent";
				}
			}

			/**
			 * @see Walker::start_el()
			 * @since 3.0.0
			 *
			 * @param string $output Passed by reference. Used to append additional content.
			 * @param object $item Menu item data object.
			 * @param int $depth Depth of menu item. Used for padding.
			 * @param int $current_page Menu item ID.
			 * @param object $args
			 */
			function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

				global $wp_query;
				$indent = ( $depth ) ? str_repeat( "\t", $depth + 7 ) : '';

				$class_names = $value = '';

				$classes = empty( $item->classes ) ? array() : (array) $item->classes;
				$classes[] = 'menu-item-' . $item->ID;

				$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
				$class_names = ' class="' . esc_attr( $class_names ) . '"';

				$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
				$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';

				if($depth === 0 AND count(explode('current-menu',$class_names)) > 1)
				{
					$this->output_items = TRUE;

				}

				if($depth === 0 AND count(explode('current-menu',$class_names)) === 1)
				{
					$this->output_items = FALSE;

				}

				if($depth >= $this->start_at_depth AND $this->output_items === TRUE)
				{
					$output .= $indent . '<li' . $id . $value . $class_names .'>';

					$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
					$attributes .= ! empty( $item->target )	 ? ' target="' . esc_attr( $item->target	 ) .'"' : '';
					$attributes .= ! empty( $item->xfn )		? ' rel="'	. esc_attr( $item->xfn		) .'"' : '';
					$attributes .= ! empty( $item->url )		? ' href="'   . esc_attr( $item->url		) .'"' : '';

					$item_output = $args->before;
					$item_output .= '<a'. $attributes .'>';
					$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
					$item_output .= '</a>';
					$item_output .= $args->after;

					$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
				}
			}

			/**
			 * @see Walker::end_el()
			 * @since 3.0.0
			 *
			 * @param string $output Passed by reference. Used to append additional content.
			 * @param object $item Page data object. Not used.
			 * @param int $depth Depth of page. Not Used.
			 */
			function end_el( &$output, $item, $depth = 0, $args = array() ) {
				if($depth >= $this->start_at_depth AND $this->output_items === TRUE)
				{
					$output .= "</li>\n";
				}
			}
		}

		// zeigt nur den Menübaum ausgehend von der vorgegebenen Menüpunkt-ID
		class SelectiveWalker extends Walker_Nav_Menu {

			/* Walker Class for selecting only current nav children.
			* Modified version of Stephen Harris class that adds in support for selecting based on menu_item_id
			*
			* @param int $menu_item_id ID of the menu item you want to select off of (optional)
			*
			* @author Jake Chamberlain
			* @link http://jchamb.com
			* @author Stephen Harris
			* @link http://wp.tutsplus.com/tutorials/creative-coding/understanding-the-walker-class/
			*/

			var $menu_item;
			var $menu_item_current;

			function __construct($menu_item_id = false) {

				$this->menu_item = $menu_item_id;
			}

			// Don't start the top level
			function start_lvl(&$output, $depth=0, $args=array()) {

				if( 0 == $depth )
					return;

				parent::start_lvl($output, $depth,$args);
			}

			// Don't end the top level
			function end_lvl(&$output, $depth=0, $args=array()) {

				if( 0 == $depth )
					return;

				parent::end_lvl($output, $depth,$args);
			}

			// Don't print top-level elements
			function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

				if( 0 == $depth && !$this->menu_item )
					return;

				parent::start_el($output, $item, $depth, $args);
			}

			function end_el(&$output, $item, $depth=0, $args=array()) {

				if( 0 == $depth && !$this->menu_item )
					return;

				parent::end_el($output, $item, $depth, $args);
			}

			// Only follow down one branch
			function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {

				// Check if element as a 'current element' class
				$current_element_markers = array( 'current-menu-item', 'current-menu-parent', 'current-menu-ancestor' );
				$current_class = array_intersect( $current_element_markers, $element->classes );

				if( !$this->menu_item) {

					// If element has a 'current' class, it is an ancestor of the current element
					$ancestor_of_current = !empty($current_class);

					// If this is a top-level link and not the current, or ancestor of the current menu item - stop here.
					if ( 0 == $depth && !$ancestor_of_current)
						return;

					parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
				}
				else {
					if ( $this->menu_item != $element->menu_item_parent )
						return;

					 parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
				 }
			}
		}

	// }
