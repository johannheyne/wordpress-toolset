<?php

	if ( ! empty( $GLOBALS['toolset']['inits']['tool_translate'] ) ) {

		class ToolsetTranslation {

			public $add_text_list = array();

			public $option_text_list = array();

			public $text_domain = 'tool_translate';

			public $admin_list_cols = array();

			function __construct() {

				add_action( 'current_screen', array( $this, 'adds_acf_fieldgroup' ) );
				add_action( 'setup_theme', array( $this, 'get_option_text_list' ) );
				add_action( 'setup_theme', array( $this, 'add_rewrite_rules' ) );
				add_action( 'init', array( $this, 'check_for_update' ) );
				add_action( 'init', array( $this, 'register_posttype' ) );
				add_action( 'init', array( $this, 'removes_obsolte_post_editing_functionalities' ) );
				add_action( 'init', array( $this, 'adds_admin_list_colums' ) );
				add_action( 'init', array( $this, 'extend_admin_search' ) );
				add_action( 'current_screen', array( $this, 'updates_posttype_entries' ) ); // $this->add_text to post metas, runs on admin posttype lis only
				add_filter( 'gettext_with_context', array( $this, 'gettext_with_context' ), 10, 4 );
				add_action( 'save_post', array( $this, 'save_post' ), 100, 3 );
				add_action( 'localize_theme_script', array( $this, 'javascript_translation' ) );
				add_action( 'init', array( $this, 'filter_admin_list_bulk_actions' ) );
				add_action( 'init', array( $this, 'adds_gettext_filter_for_none_tool_translate_text_domains' ) );
			}

			// Public

			public function filter_admin_list_bulk_actions() {

				add_filter( 'bulk_actions-edit-translate', function( $actions ) {

					// prevents recovering of an deleted tranbslations which would add a copies of the autogenerates translation
					unset( $actions['untrash'] );

					return $actions;
				}, 10, 1 );
			}

			public function add_text( $p ) {

				// (1) Runs only if posttype "translation" archive is viewed {

					// CHECK IF IS ADMIN {

						if ( empty( $GLOBALS['toolset']['updated'] ) ) {

							if ( ! is_admin() ) {

								return;
							}
						}

					// }

					// CHECK IF IS POSTTYPE translate ARCHIVE {

						if ( empty( $GLOBALS['toolset']['updated'] ) ) {

							$current_posttype = tool( array(
								'name' => 'tool_get_admin_current_post_type',
								'param' => array(
									'is_archive' => true,
									'is_single' => false,
								)
							) );

							if ( $current_posttype != 'translate' ) {

								return;
							}
						}

					// }

				// }

				// DEFAULTS {

					$defaults = array(
						'domain' => $this->text_domain,
						'context' => '',
						'text' => '',
						'param' => array(
							'text_default' => '',
							'type' => 'text',
							'description' => '',
							'default_transl' => array(
								/*'de_DE' => 'produkt',
								'fr_CA' => 'produit',*/
							),
							'js' => false,
						),
					);

					$p = array_replace_recursive( $defaults, $p );

				// }

				if ( ! isset( $this->add_text_list[ $p['domain'] ][ $p['context'] ][ $p['text'] ] ) ) {

					$this->add_text_list[ $p['domain'] ][ $p['context'] ][ $p['text'] ] = $p['param'];

					return true;
				}

				return false;
			}


			// On Update

			public function check_for_update() {

				if ( ! empty( $GLOBALS['toolset']['updated'] ) ) {

					// Takes over changed provided by $this-add_text()

					$this->updates_posttype_entries();
					$this->updates_option_text_list();
				}
			}


			// Posttype

			public function register_posttype() {

				$GLOBALS['toolset']['classes']['ToolsetL10N']->_x( array(
					'text' => 'Translations',
					'translations' => array(
						'default' => 'Translations',
						'de' =>  'Übersetzungen'
					),
					'context' => 'posttype_translation',
					'domain' => 'toolset',
					'locale' => 'user',
				));

				$GLOBALS['toolset']['classes']['ToolsetL10N']->_x( array(
					'text' => 'Translation',
					'translations' => array(
						'default' => 'Translation',
						'de' =>  'Übersetzung'
					),
					'context' => 'posttype_translation',
					'domain' => 'toolset',
					'locale' => 'user',
				));

				register_post_type( 'translate',

					array(

						'labels' => array(
							'name' => _x( 'Translations', 'posttype_translation', 'toolset' ),
							'singular_name' => _x( 'Translation', 'posttype_translation', 'toolset' ),
							'menu_name' => _x( 'Translations', 'posttype_translation', 'toolset' ),
							'add_new' => _x( 'Add New', 'posttype_label', 'toolset' ),
							'add_new_item' => _x( 'Add', 'posttype_label', 'toolset' ),
							'edit_item' => _x( 'Edit', 'posttype_label', 'toolset' ),
							'new_item' => _x( 'New', 'posttype_label', 'toolset' ),
							'view_item' => _x( 'Show', 'posttype_label', 'toolset' ),
							'search_items' => _x( 'Search', 'posttype_label', 'toolset' ),
							'not_found' =>  _x( 'Nothing found', 'posttype_label', 'toolset' ),
							'not_found_in_trash' => _x( 'Nothing in Trash', 'posttype_label', 'toolset' )
						),

						'public' => false,
						'publicly_queryable' => false,
						'show_ui' => true,
						'query_var' => true,
						'capability_type' => 'page',
						'hierarchical' => false,
						//'show_in_menu' => 'edit.php?post_type=name', // as a submenu of a posttype
						//'show_in_menu' => 'global-contents.php', // as global contents submenu
						//'show_in_menu' => 'global-contents.php', // as global contents submenu

						//'rewrite' => array(
						//	'slug' => 'translation',
						//	'with_front' => false // prevents "/blog/" on mainsite of multisites
						//),

						'menu_position' => 100,
							// 5 - below Posts
							// 10 - below Media
							// 15 - below Links
							// 20 - below Pages
							// 25 - below comments
							// 60 - below first separator
							// 65 - below Plugins
							// 70 - below Users
							// 75 - below Tools
							// 80 - below Settings
							// 100 - below second separator

						'supports' => array( 'title', 'editor', 'page-attributes' ),  // editor needed for ACF WYSIWYG fields, page-attributes needed for orderby: menu_order
							// supports
							//	  'title'
							//	  'editor' (content)
							//	  'author'
							//	  'thumbnail' (featured image, current theme must also support post-thumbnails)
							//	  'excerpt'
							//	  'trackbacks'
							//	  'custom-fields'
							//	  'comments' (also will see comment count balloon on edit screen)
							//	  'revisions' (will store revisions)
							//	  'page-attributes' (menu order, hierarchical must be true to show Parent option)
							//	  'post-formats' add post formats, see http://codex.wordpress.org/Post_Formats

						//'has_archive' => 'translations', // domain.com/translations/
							// http://mark.mcwilliams.me/wordpress-3-1-introduces-custom-post-type-archives/

						//'taxonomies' => array( 'category', 'post_tag', 'demos_kategorien' )
							// category = default post category
							// post_tag = default post tags
							// translation = custom taxomonie name
						//'capability_type' => 'translate',
						'map_meta_cap' => true,
						'capabilities' => array(
							//'create_posts' => 'do_not_allow', // Removes support for the "Add New" function
							//'delete_posts' => 'do_not_allow',
						),
						//'map_meta_cap' => false, // Set to false, if users are not allowed to edit/delete existing posts

					)
				);

			}

			public function updates_posttype_entries() {

				/*
					(1) Runs only if posttype "translation" archive is viewed or on $GLOBALS['toolset']['updated']
					(2) Compares the $this->add_text_list against the entries in the posttype list
					(3) Adds missing entries
					(4) Trashes obsolete entries
					(5) Updates option "tool_translate_text"
				*/

				// (1) Runs only if posttype "translation" archive is viewed {

					// CHECK IF IS ADMIN {

						if ( empty( $GLOBALS['toolset']['updated'] ) ) {

							if ( ! is_admin() ) {

								return;
							}
						}

					// }

					// CHECK IF IS POSTTYPE translate ARCHIVE {

						if ( empty( $GLOBALS['toolset']['updated'] ) ) {

							$current_posttype = tool( array(
								'name' => 'tool_get_admin_current_post_type',
								'param' => array(
									'is_archive' => true,
									'is_single' => false,
								)
							) );

							if ( $current_posttype != 'translate' ) {

								return;
							}
						}

					// }

				// }

				// (2) Compares the $this->add_text_list against the entries in the posttype list {

					// GET TRANSLATIONS {

						$posts = $this->get_posts();

					// }

					// ADDS DOMAIN, CONTEXT AND TEXT META DATA TO RESULTS {

						foreach ( $posts as $key => $item ) {

							$posts[ $key ]->translate_text_domain = get_post_meta( $item->ID, 'text_domain', true );
							$posts[ $key ]->translate_context = get_post_meta( $item->ID, 'context', true );
							$posts[ $key ]->translate_text = get_post_meta( $item->ID, 'text', true );
							//$posts[ $key ]->translate_text_default = get_post_meta( $item->ID, 'text_default', true );
							//$posts[ $key ]->translate_status = get_post_meta( $item->ID, 'status', true );
							//$posts[ $key ]->translate_type = get_post_meta( $item->ID, 'type', true );
						}

					// }

					// LOOP TEXT LIST AND ADDS MISSING POSTTYPE ENTRIES {

						foreach ( $this->add_text_list as $text_domain => $text_domain_items ) {

							foreach ( $text_domain_items as $context => $context_items ) {

								foreach ( $context_items as $text => $transl_param ) {

									$translation_post_exists = false;

									// CHECK IF TEXT EXISTS AS POST {

										foreach ( $posts as $post_item ) {

											if (
												$post_item->translate_text_domain == $text_domain AND
												$post_item->translate_context == $context AND
												$post_item->translate_text == $text
											) {

												// POST EXISTS {

													// UPDATE ARGS
													foreach ( $transl_param as $transl_param_key => $transl_param_value ) {

														update_post_meta( $post_item->ID, $transl_param_key, $transl_param_value );
													}

													if ( ! empty( $transl_param['default_transl'] ) ) {

														foreach ( $GLOBALS['toolset']['language_array'] as $lang => $item ) {

															$value = false;

															if ( ! empty( $transl_param['default_transl'][ $lang ] ) ) {

																$value = $transl_param['default_transl'][ $lang ];
															}

															// USES DEFAULT TRANSL like "en" FOR "en_US", "en_GB" etc. {

																if ( empty( $value ) ) {

																	$lang_primary = locale_get_primary_language( $lang );

																	if ( ! empty( $transl_param['default_transl'][ $lang_primary ] ) ) {

																		$value = $transl_param['default_transl'][ $lang_primary ];
																	}
																}

															// }

															if ( ! empty( $value ) ) {

																$meta_value = get_post_meta( $post_item->ID, 'transl_' . $lang, true );

																if ( empty( $meta_value ) ) {

																	update_post_meta( $post_item->ID, 'transl_' . $lang, $value );
																}
															}
														}
													}

													$translation_post_exists = true;

												// }
											}
										}

									// }

									// IF TEXT DO NOT EXISTS AS POST {

										if ( ! $translation_post_exists ) {

											// (3) Adds missing entry {

												// Create post object
												$post_param = array(
													'post_title' => wp_strip_all_tags( wp_trim_words( $text, 10 ) ),
													'post_type' => 'translate',
													'post_status' => 'publish',
												);

												// Insert the post into the database
												$post_id = wp_insert_post( $post_param );

												// ADDS POST METAS {

													update_post_meta( $post_id, 'text_domain', $text_domain );
													update_post_meta( $post_id, 'context', $context );
													update_post_meta( $post_id, 'text', $text );
													update_post_meta( $post_id, 'status', 'untranslated' );

													foreach ( $transl_param as $transl_param_key => $transl_param_value ) {

														update_post_meta( $post_id, $transl_param_key, $transl_param_value );
													}

													// ADD TRANSLATIONS METAS BY DEFAULTS {

														if ( ! empty( $transl_param['default_transl'] ) ) {

															foreach ( $GLOBALS['toolset']['language_array'] as $lang => $item ) {

																$value = false;

																if ( ! empty( $transl_param['default_transl'][ $lang ] ) ) {

																	$value = $transl_param['default_transl'][ $lang ];
																}

																// USES DEFAULT TRANSL like "en" FOR "en_US", "en_GB" etc. {

																	if ( empty( $value ) ) {

																		$lang_primary = locale_get_primary_language( $lang );

																		if ( ! empty( $transl_param['default_transl'][ $lang_primary ] ) ) {

																			$value = $transl_param['default_transl'][ $lang_primary ];
																		}
																	}

																// }

																if ( ! empty( $value ) ) {

																	update_post_meta( $post_id, 'transl_' . $lang, $value );
																}
															}
														}

													// }

												// }

											// }
										}

									// }
								}
							}
						}

					// }

				// }

				// (4) Trashes obsolete entries {

					// LOOP POSTTYPE ENTRIES AND TRASHES ENTRIES MISSING IN TEXT LIST {

						foreach ( $posts as $post_item ) {

							if (
								$post_item->translate_text_domain === 'tool_translate' AND
								! isset( $this->add_text_list[ $post_item->translate_text_domain ][ $post_item->translate_context ][ $post_item->translate_text ] )
							) {

								wp_trash_post( $post_item->ID );
							}
						}

					// }

				// }

				$this->updates_option_text_list();
			}

			public function save_post( $post_id, $post, $update ) {

				if ( $post->post_type != 'translate' ) {

					return;
				}

				if ( ! $update ) {

					return;
				}

				$this->updates_option_text_list();

				// FLUSH REWRITE RULES {

					$context = get_post_meta( $post_id, 'context', true );

					if ( $context === 'URL Slug' ) {

						$this->update_rewrite_rules();
					}

				// }
			}

			public function get_posts() {

				$posts = get_posts( array(
					'numberposts' => -1,
					'post_status' => 'publish',
					'post_type' => 'translate',
					'orderby' => 'post_name',
					'order' => 'ASC',
				));

				return $posts;
			}


			// Fieldgroup

			public function adds_acf_fieldgroup() {

				$screen = get_current_screen();

				if ( function_exists('acf_add_local_field_group') ) {

					$field_type = 'text';
					$text_default = '';
					$text_domain = 'tool_translate';
					$description = '';
					$field_type = 'text';

					// IS SCREEN ADD NEW TRANSLATION {

						if (
							$screen->post_type === 'translate' AND
							$screen->action === 'add'
						) {

							$text_domain = '';
						}

					// }

					if ( ! empty( $_REQUEST['post'] ) ) {

						$post_id = sanitize_text_field( (int) $_REQUEST['post'] );

						$temp = get_post_meta( $post_id, 'type', true );

						if ( ! empty( $temp ) ) {

							$field_type = $temp;
						}

						$temp = get_post_meta( $post_id, 'text_default', true );

						if ( ! empty( $temp ) ) {

							$text_default = $temp;
						}

						$temp = get_post_meta( $post_id, 'description', true );

						if ( ! empty( $temp ) ) {

							$description = $temp;
						}

						$temp = get_post_meta( $post_id, 'text_domain', true );

						if ( ! empty( $temp ) ) {

							$text_domain = $temp;
						}
					}

					$GLOBALS['toolset']['classes']['ToolsetL10N']->_x( array(
						'text' => 'Translations',
						'translations' => array(
							'default' => 'Translations',
							'de' =>  'Übersetzungen',
						),
						'context' => 'tool_translate',
						'domain' => 'toolset',
					));

					$GLOBALS['toolset']['classes']['ToolsetL10N']->_x( array(
						'text' => 'Text Translation',
						'translations' => array(
							'default' => 'Text Translation',
							'de' =>  'Text Übersetzung',
						),
						'context' => 'tool_translate',
						'domain' => 'toolset',
					));

					$GLOBALS['toolset']['classes']['ToolsetL10N']->_x( array(
						'text' => 'Text (Default)',
						'translations' => array(
							'default' => '(Text Default)',
							'de' =>  'Text (Standard)',
						),
						'context' => 'tool_translate',
						'domain' => 'toolset',
					));

					// GENERATE LANG FIELDS {

						$fields = array();

						// META FIELDS {

							// TEXT DOMAIN {

								$args = array(
									'key' => 'text_domain',
									'label' => _x( 'Text Domain', 'tool_translate', 'toolset' ),
									'name' => 'text_domain',
									'type' => 'text',
									//'instructions' => $description,
									'default_value' => $text_domain,
								);

								if ( $text_domain === 'tool_translate' ) {

									$args['readonly'] = 1;
								}


								$fields[] = $args;

							// }

							// CONTEXT {

								$args = array(
									'key' => 'context',
									'label' => _x( 'Context', 'tool_translate', 'toolset' ),
									'name' => 'context',
									'type' => 'text',
									'instructions' => $description,
									'default_value' => '',
								);

								if ( $text_domain === 'tool_translate' ) {

									$args['readonly'] = 1;
								}

								$fields[] = $args;

							// }

							// DEFAULT TEXT {

								$args = array(
									'key' => 'text_default',
									'label' => _x( 'Text (Default)', 'tool_translate', 'toolset' ),
									'name' => 'text_default',
									'type' => $field_type,
									'default_value' => $text_default,
									'rows' => 2,
								);

								if ( $text_domain === 'tool_translate' ) {

									$args['readonly'] = 1;
								}

								$fields[] = $args;

							// }

						// }

						// TRANSLATION FIELDS {

							if ( ! empty( $GLOBALS['toolset']['language_array'] ) ) {

								foreach ( $GLOBALS['toolset']['language_array'] as $lang_code => $item ) {

									$label_lang = tool( array(
										'name' => 'tool_multilanguage_get_locale_label',
										'param' => array(
											'langcode' => $lang_code,
											'locale' => $GLOBALS['toolset']['user_locale'],
										)
									));

									$fields[] = array(
										'key' => 'transl_' . $lang_code,
										'label' => _x( 'Text Translation', 'tool_translate', 'toolset' ) . ' <span class="tooltip tooltip-style-small tooltip-inline">' . $lang_code . '<span class="tooltiptext">' . $label_lang . '</span></span>',
										'name' => 'transl_' . $lang_code,
										'type' => $field_type,
										'rows' => 2,
										'instructions' => '',
										'required' => 0,
										'conditional_logic' => 0,
										'wrapper' => array(
											'width' => '',
											'class' => '',
											'id' => ''
										),
										'default_value' => '',
										'placeholder' => '',
										'prepend' => '',
										'append' => '',
										'maxlength' => ''
									);
								}
							}

						// }

					// }

					acf_add_local_field_group( array(
						'key' => 'group_posttype_translate_group',
						'title' => _x( 'Translations', 'tool_translate', 'toolset' ),
						'fields' => $fields,
						'location' => array (
							array (
								array (
									'param' => 'post_type',
									'operator' => '==',
									'value' => 'translate',
								),
							),
						),
						'menu_order' => 0,
						'style' => 'seamless',
						'hide_on_screen' => [
							'permalink',
							'the_content',
							'excerpt',
							'discussion',
							'comments',
							'revisions',
							'slug',
							'author',
							'format',
							'page_attributes',
							'featured_image',
							'categories',
							'tags',
							'send-trackbacks'
						],
						'label_placement' => 'left',
					) );

				}

			}


			// Frontend Customisation

			public function removes_obsolte_post_editing_functionalities() {

				// REMOVES OBSOLTES EDITING FUNCTIONALITIES {

					// removes post list actions
					add_filter( 'post_row_actions', function( $actions, $post ) {

						if ( $post->post_type !== 'translate' ) {

							return $actions;
						}

						unset( $actions['trash'] );
						unset( $actions['clone'] );

						// hides QickEdit
						if ( isset( $actions['inline hide-if-no-js'] ) ) {

							unset( $actions['inline hide-if-no-js'] );
						}

						return $actions;

					}, 10, 2 );

					// removes post edit actions
					add_action( 'admin_head', function () {

						$current_posttype = tool( array(
							'name' => 'tool_get_admin_current_post_type',
							'param' => array(
								'is_archive' => false,
								'is_single' => true,
							)
						) );

						if ( $current_posttype !== 'translate' ) {

							return;
						}

						echo '<style>#delete-action, #misc-publishing-actions, #minor-publishing, .row-actions, .tablenav.top { display: none; }</style>';

					} );

				// }

			}

			public function adds_admin_list_colums() {

				$col_index = 1;

				$cols = array();

				// SETUP COL "TEXT DOMAIN" {

					$func = function( $col_id, $post_id ) {

						if ( $col_id != 'text_domain' ) {

							return;
						}

						$text_domain = get_post_meta( $post_id, 'text_domain', true );

						echo '<a href="edit.php?s=' . $text_domain . '&post_status=all&post_type=translate">' . $text_domain . '</a>';
					};

					$cols[] = array(
						'sorttype' => 'meta',
						'sortmetakey' => 'text_domain',
						'sortmetaorderby' => 'meta_value',
						'colid' => 'text_domain',
						'collabel' => 'Text Domain',
						'rowlabelfunction' => $func,
					);

				// }

				// SETUP COL "CONTEXT" {

					$func = function( $col_id, $post_id ) {

						if ( $col_id != 'context' ) {

							return;
						}

						echo get_post_meta( $post_id, 'context', true );

						$description = get_post_meta( $post_id, 'description', true );

						if ( $description ) {

							echo '<br><small>' . $description . '</small>';
						}
					};

					$cols[] = array(
						'sorttype' => 'meta',
						'sortmetakey' => 'context',
						'sortmetaorderby' => 'meta_value',
						'colid' => 'context',
						'collabel' => 'Context',
						'rowlabelfunction' => $func,
					);

				// }

				// SETUP COL "TEXT" {

					$func = function( $col_id, $post_id ) {

						if ( $col_id != 'text' ) {

							return;
						}

						echo get_post_meta( $post_id, 'text_default', true );
					};

					$cols[] = array(
						'sorttype' => 'meta',
						'sortmetakey' => 'text',
						'sortmetaorderby' => 'meta_value',
						'colid' => 'text',
						'collabel' => 'Default Text',
						'rowlabelfunction' => $func,
					);

				// }

				// SETUP COLS LANGS {

					foreach ( $GLOBALS['toolset']['language_array'] as $lang_code => $value ) {

						$func = function( $col_id, $post_id ) use ( $lang_code ) {

							if ( $col_id != 'transl_' . $lang_code ) {

								return;
							}

							echo get_post_meta( $post_id, 'transl_' . $lang_code, true );
						};

						$cols[] = array(
							'sorttype' => 'meta',
							'sortmetakey' => 'transl_' . $lang_code,
							'sortmetaorderby' => 'meta_value',
							'colid' => 'transl_' . $lang_code,
							'collabel' => 'Translation: ' . $lang_code,
							'rowlabelfunction' => $func,
						);
					}

				// }

				// INIT COLS {

					foreach ( $cols as $item ) {

						$default = array(
							'posttype' => 'translate',
							'postname' => false,
							'position' => ++$col_index,
						);

						$args = array_replace_recursive( $default, $item );

						new wpSortableListColumn( $args );

						$this->admin_list_cols[] = $item['colid']; // required for admin $this->extend_admin_search()
					}

				// }
			}

			public function extend_admin_search( $query ) {

				$current_posttype = tool( array(
					'name' => 'tool_get_admin_current_post_type',
					'param' => array(
						'is_archive' => false,
						'is_single' => true,
					)
				) );

				if ( $current_posttype !== 'translate' ) {

					return;
				}

				add_filter( 'posts_join', function( $join ){

					global $pagenow, $wpdb;

					if (
						is_admin() &&
						$pagenow == 'edit.php' &&
						! empty( $_GET['post_type'] ) &&
						$_GET['post_type'] == 'translate' &&
						! empty( $_GET['s'] )
					) {

						$join .= 'LEFT JOIN ' . $wpdb->postmeta . ' ON ' . $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
					}

					return $join;
				} );


				add_filter( 'posts_where', function( $where ) {

					global $pagenow, $wpdb;

					if (
						is_admin() &&
						$pagenow == 'edit.php' &&
						! empty( $_GET['post_type'] ) &&
						$_GET['post_type'] == 'translate' &&
						! empty( $_GET['s'] )
					) {

						$cols_where = array();

						foreach ( $this->admin_list_cols as $col_id ) {

							$cols_where[] = "(" . $wpdb->postmeta . ".meta_value LIKE $1 AND " . $wpdb->postmeta . ".meta_key = '" . $col_id . "' )";
						}

						$where = preg_replace(
							"/\(\s*" . $wpdb->posts . ".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
							"(" . $wpdb->posts . ".post_title LIKE $1) OR " . implode( 'OR', $cols_where ),
						$where );
					}

					return $where;
				} );


				add_filter( 'posts_distinct', function( $where ) {

					global $pagenow, $wpdb;

					if (
						is_admin() &&
						$pagenow == 'edit.php' &&
						! empty( $_GET['post_type'] ) &&
						$_GET['post_type'] == 'translate' &&
						! empty( $_GET['s'] )
					) {

						return "DISTINCT";
					}

					return $where;
				} );

			}


			// Option List

			public function updates_option_text_list() {

				$this->option_text_list = array();

				$posts = $this->get_posts();

				// LOOP POSTS, GET METAS, BUILD OPTION ARRAY {

					foreach ( $posts as $post ) {

						$metas = get_post_meta( $post->ID );

						// $metas['text_domain'][0]
						// $metas['context'][0]
						// $metas['text'][0]
						// $metas['status'][0]
						// $metas['type'][0]
						// $metas['description'][0]
						// $metas['default_transl'][0]
						// $metas['text_default'][0]

						if ( ! isset( $metas['status']) ) {

							$metas['status'][] = 'untranslated';
						}

						if ( ! isset( $metas['type']) ) {

							$metas['type'][] = 'text';
						}

						if ( ! isset( $metas['description']) ) {

							$metas['description'][] = '';
						}

						if ( ! isset( $metas['default_transl']) ) {

							$metas['default_transl'][] = serialize( $metas['text_default'][0] );
						}

						if ( ! isset( $metas['js']) ) {

							$metas['js'][] = false;
						}

						if ( ! isset( $metas['text']) ) {

							$metas['text'][] = $metas['text_default'][0];
						}

						/*if ( $metas['context'][0] === 'global' ) {

							if ( ! isset( $metas['status']) ) {

								$metas['status'][] = 'untranslated';
							}

							$metas['text_domain'][] = 'tool_translate';
							$metas['type'][] = 'text';
							$metas['description'][] = '';
							$metas['default_transl'][] = serialize( $metas['text_default'][0] );
							$metas['js'][] = false;
							$metas['text'][0] = $metas['text_default'][0];
						}*/

						$param = array(
							'status' => $metas['status'][0],
							'type' => $metas['type'][0],
							'description' => $metas['description'][0],
							'text_default' => $metas['text_default'][0],
							'default_transl' => unserialize( $metas['default_transl'][0] ),
							'transl' => array(),
							'js' => $metas['js'][0],
						);

						foreach ( $GLOBALS['toolset']['language_array'] as $lang_code => $value ) {

							$transl_value = '';

							// translation meta field
							if ( ! empty( $metas[ 'transl_' . $lang_code ] ) ) {

								$transl_value = $metas[ 'transl_' . $lang_code ][0];
							}
							// default_transl meta field
							elseif ( ! empty( $param['default_transl'][ $lang_code ] ) ) {

								$transl_value = $param['default_transl'][ $lang_code ];
							}
							// text_default meta field
							elseif ( ! empty( $metas['text_default'][0] ) ) {

								$transl_value = $metas['text_default'][0];
							}
							// text meta field
							else {

								$transl_value = $metas['text'][0];
							}

							$param['transl'][ $lang_code ] = $transl_value;
						}

						$this->option_text_list[ $metas['text_domain'][0] ][ $metas['context'][0] ][ $metas['text'][0] ] = $param;
					}

				// }

				update_option( 'tool_translate_translations', $this->option_text_list );
			}

			public function get_option_text_list() {

				$this->option_text_list = get_option( 'tool_translate_translations' );
			}


			// GetText Filter

			public function gettext_with_context( $translation, $text, $context, $domain ) {

				if (
					! empty( $this->option_text_list[ $domain ][ $context ][ $text ]['transl'] )
				) {

					$transl = $this->option_text_list[ $domain ][ $context ][ $text ]['transl'];
					$transl['default'] = $this->option_text_list[ $domain ][ $context ][ $text ]['text_default'];

					$translation = $GLOBALS['toolset']['classes']['ToolsetL10N']->translate( $transl );
				}

				return $translation;
			}


			// Rewrite Rules

			public function add_rewrite_rules() {

				if ( empty( $this->option_text_list ) ) {

					return;
				}

				foreach (  $this->option_text_list as $text_domain => $text_domain_items ) {

					foreach ( $text_domain_items as $context => $context_items ) {

						foreach ( $context_items as $text => $transl_param ) {

							if ( $context === 'URL Slug' ) {

								$text = $text;
								$transl = $this->option_text_list[ $text_domain ][ $context ][ $text ]['transl'];

								add_filter( 'rewrite_rules_array_translation', function( $translations ) use ( $text, $transl ) {

									$translations[ $text ] = $transl;

									return $translations;

								}, 1, 123 ); // this priority "123" is used to remove only these 'rewrite_rules_array_translation' filters in $this->update_rewrite_rules()
							}
						}
					}
				}
			}

			public function update_rewrite_rules() {

				remove_all_filters( 'rewrite_rules_array_translation', 123 ); // The priority "123" removes only the filters added in $this->add_rewrite_rules()

				$this->add_rewrite_rules();

				flush_rewrite_rules();
			}


			// ADDS GETTEXT FILTER

			public function adds_gettext_filter_for_none_tool_translate_text_domains() {

				foreach ( $this->option_text_list as $text_domain => $text_domain_group ) {

					if ( $text_domain === 'tool_translate' ) {

						continue;
					}

					foreach ( $text_domain_group as $context => $context_group ) {

						foreach ( $context_group as $text => $args) {

							$p = array(
								'text' => $text,
								'context' => $context,
								'domain' => $text_domain,
								'translations' => $args['transl'],
								'locale' => 'frontend',
							);

							if ( $context !== '' ) {

								add_filter( 'gettext_with_context', function( $translation, $text, $context, $domain ) use( $p )  {

									if (
										$domain == $p['domain'] AND
										$context == $p['context'] AND
										$text == $p['text']
									) {

										$translation = $GLOBALS['toolset']['classes']['ToolsetL10N']->translate( $p['translations'], $p['locale'] );
									}

									return $translation;

								}, 10, 4 );
							}
							else {

								add_filter( 'gettext', function( $translation, $text, $domain ) use( $p )  {

									if (
										$domain == $p['domain'] AND
										$text == $p['text']
									) {

										$translation = $GLOBALS['toolset']['classes']['ToolsetL10N']->translate( $p['translations'], $p['locale'] );
									}

									return $translation;

								}, 10, 3 );
							}
						}
					}
				}

			}

			// JavaScript translations

			public function javascript_translation() {

				$translations = array();

				if ( ! empty( $this->option_text_list ) ) {

					foreach ( $this->option_text_list  as $domain => $item ) {

						foreach ( $item  as $context => $item2 ) {

							foreach ( $item2  as $text => $item3 ) {

								if ( ! empty(  $item3['js'] ) ) {

									if ( $item3['js'] == 'current' ) {

										$data = $GLOBALS['toolset']['classes']['ToolsetL10N']->translate( $item3['transl'], 'auto', 'data' );
										$translations[ $domain ][ $context ][ $text ][ $data['locale'] ] = $data['string'];
									}

									if ( $item3['js'] == 'all' ) {

										$translations[ $domain ][ $context ][ $text ]= $item3['transl'];
									}
								}

							}
						}
					}
				}

				wp_localize_script( 'theme_script', 'AppTranslData', $translations );
			}
		}

		$GLOBALS['toolset']['classes']['ToolsetTranslation'] = new ToolsetTranslation();
	}

	class ToolsetL10N {

		public $transl_list = array();

		function __contruct() {

			add_action( 'localize_theme_script', array( $this, 'javascript_translation' ) );

		}

		function translate( $translations, $locale_type = 'auto', $return = 'value' ) {

			// DEFAULTS {

				$defaults = array(
					'default' => '',
				);

				$translations = array_replace_recursive( $defaults, $translations );

				$string = '';

				if (
					$locale_type === 'user' AND
					! empty( $GLOBALS['toolset']['user_locale'] )
				) {

					$locale = $GLOBALS['toolset']['user_locale'];
				}
				else {

					$locale_type = 'auto';
				}

				if (
					$locale_type === 'frontend' AND
					! empty( $GLOBALS['toolset']['frontend_locale'] )
				) {

					$locale = $GLOBALS['toolset']['frontend_locale'];
				}
				else {

					$locale_type = 'auto';
				}

				if ( $locale_type === 'auto' ) {

					if ( is_admin() && ! wp_doing_ajax() ) {

						$locale = $GLOBALS['toolset']['user_locale'];

					}
					else {

						$locale = $GLOBALS['toolset']['frontend_locale'];
					}
				}

				// IF LOCALE DOES NOT MATCH TRANSLATIONS, REMOVE COUNTRY CODE FROM LOCALE {

					if (
						empty( $translations[ $locale ] ) AND
						strpos( $locale, '_' ) !== false
					) {

						$locale = explode( '_', $locale )[0];
					}

				// }

			// }

			if ( ! empty( $translations[ $locale ] ) ) {

				$string = $translations[ $locale ];
			}
			else {

				$string = $translations['default'];
			}

			if ( $return === 'data' ) {

				$ret = array(
					'locale' => $locale,
					'string' => $string,
				);

				return $ret;
			}

			return $string;
		}

		function _x( $p = array() ) {

			/* EXAMPLE

				$GLOBALS['toolset']['classes']['ToolsetL10N']->_x( array(
					'text' => 'Add New',
					'translations' => array(
						'default' => 'Add New',
						'de' =>  'Neu hinzufügen' // de will also translate user locales like de_AU, de_SW
					),
					'context' => 'posttype_label',
					'domain' => 'toolset',
					'locale' => 'user', // user, front
					'js' => false, // false, 'all', 'current' // translation accessable with App.ln.get( string, context, domain );
				));

			*/

			// DEFAULTS {

				$defaults = array(
					'text' => '',
					'translations' => array(),
					'context' => '',
					'domain' => '',
					'locale' => 'auto', // 'auto' detetects locale wether from admin user or frontend, 'user' translates by user locale, 'front' translates by frontend locale
					'js' => false, // false, 'all', 'current'
				);

				$p = array_replace_recursive( $defaults, $p );

			// }

			if ( empty( $p['text'] ) ) {

				return false;
			}

			if ( empty( $p['translations'] ) ) {

				return false;
			}

			add_filter( 'gettext_with_context', function( $translation, $text, $context, $domain ) use( $p )  {

				if (
					$domain == $p['domain'] AND
					$context == $p['context'] AND
					$text == $p['text']
				) {

					$translation = $this->translate( $p['translations'], $p['locale'] );
				}

				return $translation;

			}, 10, 4 );

			// JAVASCRIPT {

				if ( $p['js'] === 'all' ) {

					$this->transl_list[ $p['domain'] ][ $p['context'] ][ $p['text'] ] = $p['translations'];
				}

				if ( $p['js'] === 'current' ) {

					$data = $this->translate( $item3['transl'], 'auto', 'data' );

					$this->transl_list[ $p['domain'] ][ $p['context'] ][ $p['text'] ][ $data['locale'] ] = $data['string'];
				}

			// }
		}

		// JavaScript translations

		public function javascript_translation() {

			$translations = array();

			if ( ! empty( $this->transl_list ) ) {

				foreach ( $this->transl_list   as $domain => $item ) {

					foreach ( $item  as $context => $item2 ) {

						foreach ( $item2  as $text => $item3 ) {


						}
					}
				}
			}

			wp_localize_script( 'theme_script', 'AppLnData', $this->transl_list );
		}
	}

	$GLOBALS['toolset']['classes']['ToolsetL10N'] = new ToolsetL10N();
