<?php

	// ADD MENU-ITEM CLASSES IN SPECIAL CASE ( Version 3 ) {

		add_filter( 'nav_menu_css_class' , 'tool_menu_add_css_classes_filter' , 10 , 2 );

		function tool_menu_add_css_classes_filter( $classes, $item ) {

			global $post;

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
							'menu_item_object_id_by_acf_field' => false,
							'menu_item_object_id' => false,
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

					// menu_item_object_id {

						if ( $set['menu_item_object_id'] ) {

							if ( $item->object_id != $set['menu_item_object_id'] ) {

								$check = false;
							}
						}

					// }

					// menu_item_object_id_by_acf_field {

						if ( $set['menu_item_object_id_by_acf_field'] ) {

							if ( function_exists( 'get_field' ) ) {

								$id = get_field( $set['menu_item_object_id_by_acf_field'][0], $set['menu_item_object_id_by_acf_field'][1] );

								if ( $item->object_id != $id ) {

									$check = false;
								}
							}
							else {

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

								foreach ( $rulegroup as $key => $value ) {

									if ( ! is_string( $key ) ) {

										$rule = $value;
										$rule_param = '';
									}
									else {

										$rule = $key;
										$rule_param = $value;
									}

									if ( stristr( $rule, 'not_' ) OR stristr( $rule, 'is_' ) ) {

										$not = stristr( $rule, 'not_' );
										$rule = str_replace( 'not_', 'is_', $rule );

										if ( $not ) {

											if ( $rule( $rule_param )  ) {

												$rulecheck = 'n';
											}
										}
										else {

											if ( ! $rule( $rule_param )  ) {

												$rulecheck = 'n';
											}
										}
									}

									elseif ( stristr( $rule, '$post->' ) ) {

										$rule = str_replace( '$post->', '', $rule );
										$arr = explode( ' ', $rule );
										$check = true;

										if ( ! preg_match( '/^[a-z_]*$/i', $arr[0] ) ) {

										  	$check = false;
										}

									  	if ( ! preg_match( '/^[' . preg_quote( '=!<>' ) . ']*$/', $arr[1] ) ) {

										  	$check = false;
										}

										if ( ! preg_match( '/^[a-z_0-9 \'\"\-]*$/i', $arr[2] ) ) {

										  	$check = false;
										}

										if ( $check ) {

											$rule = implode( ' ', $arr );
											$string = 'if ( $post->' . $rule . ' ) { } else { $rulecheck = "n"; }';
											eval( $string );
										}

									}
									else {

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
