<?php
	
	function loginpages_get_setup() {
		 
		 if ( config_get_curr_site_id() == 'de' ) $setup['login-page-id'] = 191;
		 if ( config_get_curr_site_id() == 'en' ) $setup['login-page-id'] = 38;
		 
		 $setup['login-page-url'] = get_permalink( $setup['login-page-id'] );
		
		 $setup['post-type'] = 'downloads';
		 $setup['post-name-singular'] = 'Download Seite';
		 $setup['post-name-plural'] = 'Download Seiten';
		 $setup['post-name-new'] = 'neue';
		 
		 $setup['cookie-name'] = 'login-downloads';
		 
		 $setup['login-form-shortcode'] = 'login-downloads';
		 $setup['login-form-fieldname-passwort'] = 'login-passwort';
		 $setup['abmelden-parameter'] = 'abmelden';
		 if ( function_exists('get_field') ) {
			$setup['label'] = get_field( 'opt_downloadform_passwort_label', 'options' );
		 }
		 $setup['acf-passwort-field-label'] = 'Login Passwort';
		 $setup['acf-passwort-field-name'] = 'login-passwort';
		 $setup['acf-passwort-field-key'] = 'field_73';
		 
		 
		 return $setup;
	}
	
	// POSTTYPE {
	
		function loginpage_init() {
		
			$setup = loginpages_get_setup();
		
			$labels = array(
				'name' => $setup['post-name-plural'],
				'singular_name' => $setup['post-name-singular'],
				'add_new' => 'Hinzufügen',
				'add_new_item' => 'Hinzufügen',
				'edit_item' => 'Bearbeiten',
				'new_item' => $setup['post-name-new'] . ' ' . $setup['post-name-singular'],
				'view_item' => 'Zeige ' . $setup['post-name-plural'],
				'search_items' => 'Suche',
				'not_found' =>	'keine ' . $setup['post-name-plural'] . ' gefunden',
				'not_found_in_trash' => 'keine ' . $setup['post-name-plural'] . ' im Papierkorb gefunden', 
				'parent_item_colon' => ''
			);
			$args = array(
				'labels' => $labels,
				'public' => true,
				'publicly_queryable' => true,
				'show_ui' => true, 
				'query_var' => true,
				'rewrite' => array(
					'slug' => $setup['post-type'],
					'with_front' => false
				),
				'capability_type' => 'page',
				'hierarchical' => true,
				'menu_position' => 1,
				'supports' => array('title', 'editor')
			); 
			register_post_type( $setup['post-type'], $args );
		}
		add_action('init', 'loginpage_init');
		
	// }
	
	// ACF {
		
		if ( function_exists( 'register_options_page' ) ) {
			register_options_page('Downloadformular');
		}
		/*
		if( function_exists("register_field_group") ) {
		
			$setup = loginpages_get_setup();
			
			// Passwort Feldbezeichnung
			
			register_field_group(array (
					'id' => '5123670d5a6b8',
					'title' => 'Downloadformular',
					'fields' => 
					array (
						0 => 
						array (
							'key' => 'field_54',
							'label' => 'Passwort Feldbezeichnung',
							'name' => 'opt_downloadform_passwort_label',
							'type' => 'text',
							'order_no' => 0,
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 
							array (
								'status' => 0,
								'rules' => 
								array (
									0 => 
									array (
										'field' => 'null',
										'operator' => '==',
									),
								),
								'allorany' => 'all',
							),
							'default_value' => '',
							'formatting' => 'html',
						),
					),
					'location' => 
					array (
						'rules' => 
						array (
							0 => 
							array (
								'param' => 'options_page',
								'operator' => '==',
								'value' => 'acf-options-downloadformular',
								'order_no' => 0,
							),
						),
						'allorany' => 'all',
					),
					'options' => 
					array (
						'position' => 'normal',
						'layout' => 'default',
						'hide_on_screen' => 
						array (
						),
					),
					'menu_order' => 0,
				));
				
			
			// Passwortfeld
			
			register_field_group(array (
				'id' => '511e12e9485ad',
				'title' => $setup['acf-passwort-field-label'],
				'fields' => 
				array (
					0 => 
					array (
						'key' => 'field_20',
						'label' => $setup['acf-passwort-field-label'],
						'name' => $setup['acf-passwort-field-name'],
						'type' => 'text',
						'order_no' => 0,
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 
						array (
							'status' => 0,
							'rules' => 
							array (
								0 => 
								array (
									'field' => 'null',
									'operator' => '==',
									'value' => '',
								),
							),
							'allorany' => 'all',
						),
						'default_value' => '',
						'formatting' => 'none',
					),
				),
				'location' => 
				array (
					'rules' => 
					array (
						0 => 
						array (
							'param' => 'post_type',
							'operator' => '==',
							'value' => $setup['post-type'],
							'order_no' => 0,
						),
					),
					'allorany' => 'all',
				),
				'options' => 
				array (
					'position' => 'normal',
					'layout' => 'default',
					'hide_on_screen' => 
					array (
					),
				),
				'menu_order' => 0,
			));
			
		}
		*/
	// }
	
	// SHORTCODES {
		
		// LOGINFORM {
		
			function shortcode_loginpages( $atts ) {
		
				/*extract( shortcode_atts( array(
					'code' => false
				), $atts ) );*/

				$setup = loginpages_get_setup();
				
				$return = '';
				
				$return .= '<form class="loginform" action="" method="post" enctype="multipart/form-data" name="form" target="_self">';
						$return .= '<div class="form-field-wrap">';
							$return .= '<div class="label"><label for="passwort">' . $setup['label'] . '</label></div>';
							$return .= '<div class="fields">';
								$return .= '<input class="form-field" type="text" id="' . $setup['login-form-fieldname-passwort'] . '" name="' . $setup['login-form-fieldname-passwort'] . '" value="" size="40" maxlength="99">';
							$return .= '</div>';
						$return .= '</div>';
						$return .= '<div class="form-btn-wrap">';
							$return .= '<input class="form-submit" name="submit" type="submit" value="Login">';
						$return .= '</div>';
				$return .= '</form>';
				
				return $return;
			}
			
			$setup = loginpages_get_setup();
			add_shortcode( $setup['login-form-shortcode'], 'shortcode_loginpages' );
		
		// }
		
	// }
	
	// HEADERS {
	
		function loginpages_page_head() {
			
			global $post;
			
			$setup = loginpages_get_setup();

			$passwort = get_field( $setup['acf-passwort-field-key'] , $post->ID );

			if ( isset( $_COOKIE[ $setup['cookie-name'] ] ) AND $_COOKIE[ $setup['cookie-name'] ] == $passwort ) {
				
			}
			else {
			
				wp_redirect( $setup['login-page-url'] );
				exit();
			}
		}
	
		function loginpages_login_head() {
		
			 $setup = loginpages_get_setup();

			 if (  isset($_GET[ $setup['abmelden-parameter'] ]) ) {

				 setcookie( $setup['cookie-name'] );
				 unset($_COOKIE[ $setup['cookie-name'] ]);
			 }


			 $input['passwort'] = false;

			 if ( isset( $_COOKIE[ $setup['cookie-name'] ] ) ) {

				 $input['passwort'] = $_COOKIE[ $setup['cookie-name'] ];
			 }

			 if ( isset( $_POST[ $setup['login-form-fieldname-passwort'] ] ) ) {

				 $input['passwort'] = tool_sanitize_input( array( 'string' => $_POST[ $setup['login-form-fieldname-passwort'] ] ) );
			 }


			 if ( $input['passwort'] ) {

				 $result = get_posts(array(
					 'numberposts' => -1,
					 'post_status' => 'publish',
					 'post_type' => $setup['post-type'],
					 'meta_query' => array(
						 array(
							 'key' => $setup['acf-passwort-field-name'],
							 'value' => $input['passwort']
						 )
					 )
				 ));
			 
				 if ( count( $result ) === 1 ) {
				 
					 setcookie( $setup['cookie-name'], $input['passwort'], time() + 3600 );
				 
					 wp_redirect( get_permalink( $result[0]->ID ) );
					 exit();
				 }
			 } 
		}
	
	// }
	
	// LOGOUTLINK {
		
		function loginpages_logout_link() {
			
			$setup = loginpages_get_setup();
			
			return '<a class="link solo" href="' . $setup['login-page-url'] . '?' . $setup['abmelden-parameter'] . '=true">abmelden</a>';
		}
		
	// }

?>