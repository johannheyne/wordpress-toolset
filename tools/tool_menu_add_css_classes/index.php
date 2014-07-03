<?php

	// ADD MENU-ITEM CLASSES IN SPECIAL CASE {

		add_filter( 'nav_menu_css_class' , 'tool_menu_add_css_classes_filter' , 10 , 2 );
		
		function tool_menu_add_css_classes_filter( $classes, $item ) {
			/* 
				$item-> 

				ID => 46
				post_author => 1
				post_date => 2011-12-08 07:59:49
				post_date_gmt => 2011-12-08 07:59:49
				post_content => Cras justo odio, dapibus ac facilisis in
				post_title =>
				post_excerpt =>
				post_status => publish
				comment_status => open
				ping_status => open
				post_password =>
				post_name => 46
				to_ping =>
				pinged =>
				post_modified => 2011-12-10 09:33:47
				post_modified_gmt => 2011-12-10 09:33:47
				post_content_filtered =>
				post_parent => 0
				guid => http://domain.de/?p=46
				menu_order => 1
				post_type => nav_menu_item
				post_mime_type =>
				comment_count => 0
				filter => raw
				db_id => 46
				menu_item_parent => 0
				object_id => 2
				object => page
				type => post_type
				type_label => Seite
				url => http://wellensittiche.johannheyne.de/
				title => Home
				target =>
				attr_title =>
				description => Cras justo odio, dapibus ac facilisis in
				xfn =>
				current =>
				current_item_ancestor =>
				current_item_parent =>

			*/

			if ( is_array( $GLOBALS['toolset']['inits']['tool_menu_add_css_classes'] ) ) {
			    
			
				foreach ( $GLOBALS['toolset']['inits']['tool_menu_add_css_classes'] as $key => $set ) {
				    
				    // DEFAULTS {

				        $defaults = array(
			            	'menu_item_id' => false,
			            	'is_posttype' => false,
			            	'rules' => false,
			            	'class' => 'current-menu-item',
				        );

				        $set = array_replace_recursive( $defaults, $set );

				    // }
				    
					$check = true;
					
					// menu_item_id {
						
						if ( $set['menu_item_id'] ) {
						
					    	if ( $item->ID != $set['menu_item_id'] ) {

								$check = false;
							}
						}

					// }
					
					// is_posttype {
					    
						if ( $check && $set['is_posttype'] ) {
							
							if ( get_post_type( get_the_ID() ) != $set['is_posttype'] ) {

								$check = false;
							}
						}
					    

					// }
					
					// rules {

					    if ( $check && $set['rules'] ) {
							
							foreach ( $set['rules'] as $key => $rulegroup ) {
							    
								$rulecheck = 'y';
								
								foreach ( $rulegroup as $key => $rule ) {

									$not = stristr( $rule, 'not_' );
									
									if ( $not ) {
									    
										$not = '';
										$rule = str_replace( 'not_', 'is_', $rule );
									}
									else {
										
										$not = '! ';
									}
									if ( $not.$rule() ) {
									    
										$rulecheck = 'n';
									}
								}
								
								
								if ( $rulecheck == 'n' ) {
								   
									$check = false;
								}
							}
						}

					// }
					
					// ADD CLASS {
					
						if ( $check ) {

							$classes[] = $set['class'];
						}
						
					// }
				}
			}
			
			return $classes;
		}

	// }

?>