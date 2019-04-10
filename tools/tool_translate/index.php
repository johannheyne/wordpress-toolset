<?php

	if ( ! empty( $GLOBALS['toolset']['inits']['tool_translate'] ) ) {

		class ToolsetTranslation {

			public $text_list = array();

			public $text_domain = 'tool_translate';

			function __construct() {

				add_action( 'init', array( $this, 'register_posttype' ) );
				add_action( 'acf/init', array( $this, 'adds_acf_fieldgroup' ) );
				add_action( 'current_screen', array( $this, 'updates_posttype_entries' ) );
			}

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

						'capabilities' => array(
							//'create_posts' => 'do_not_allow', // Removes support for the "Add New" function
						),
						// 'map_meta_cap' => true, // Set to false, if users are not allowed to edit/delete existing posts

					)
				);
			}

			public function adds_acf_fieldgroup() {

				if ( function_exists('acf_add_local_field_group') ) {

					tool( array(
						'name' => 'tool_acf_translate',
						'param' => array(
							'strings' => array(
								'Translations' => array(
									'de_DE' => 'Übersetzungen'
								),
							),
						),
					) );

					acf_add_local_field_group( array(
						'key' => 'group_posttype_translate_group',
						'title' => tool_acf_translate_string( 'Translations' ),
						'fields' => array(),
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
					) );

				}

			}

			public function updates_posttype_entries() {

				/*
					(1) Runs only if posttype "translation" archive is viewed
					(2) Compares the $this->text_list against the entries in the posttype list
					(3) Adds missing entries
					(4) Trashes obsolete entries
					(5) Updates option "tool_translate_text"
				*/

				// (1) Runs only if posttype "translation" archive is viewed {

					// CHECK IF IS ADMIN {

						if ( ! is_admin() ) {

							return;
						}

					// }

					// CHECK IF IS POSTTYPE translate ARCHIVE {

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

					// }

				// }

				// (2) Compares the $this->text_list against the entries in the posttype list {

					// GET TRANSLATIONS {

						$posts = get_posts( array(
							'numberposts' => -1,
							'post_status' => 'publish',
							'post_type' => 'translate',

							'orderby' => 'post_name',
							'order' => 'ASC',
						));

					// }

					// ADDS DOMAIN, CONTEXT AND TEXT META DATA TO RESULTS {

						foreach ( $posts as $key => $item ) {

							$posts[ $key ]->translate_text_domain = get_post_meta( $item->ID, 'text_domain', true );
							$posts[ $key ]->translate_context = get_post_meta( $item->ID, 'context', true );
							$posts[ $key ]->translate_text = get_post_meta( $item->ID, 'text', true );
							$posts[ $key ]->translate_status = get_post_meta( $item->ID, 'status', true );
						}

					// }

					// LOOP TEXT LIST AND ADDS MISSING POSTTYPE ENTRIES {

						foreach ( $this->text_list as $text_domain => $text_domain_items ) {

							foreach ( $text_domain_items as $context => $context_items ) {

								foreach ( $context_items as $text => $args ) {

									// CHECK IF TEXT EXISTS AS POST {

										$translation_post_exists = false;

										foreach ( $posts as $post_item ) {

											if (
												$post_item->translate_text_domain == $text_domain AND
												$post_item->translate_context == $context AND
												$post_item->translate_text == $text
											) {

												$translation_post_exists = true;
											}
										}

									// }

									// IF TEXT DO NOT EXISTS AS POST {

										if ( ! $translation_post_exists ) {

											// (3) Adds missing entry {

												// Create post object
												$param = array(
													'post_title' => wp_strip_all_tags( wp_trim_words( $text, 10 ) ),
													'post_type' => 'translate',
													'post_status' => 'publish',
												);

												// Insert the post into the database
												$post_id = wp_insert_post( $param );

												// Adds metas
												update_post_meta( $post_id, 'text_domain', $text_domain );
												update_post_meta( $post_id, 'context', $context );
												update_post_meta( $post_id, 'text', $text );
												update_post_meta( $post_id, 'status', 'untranslated' );

											// }

											// ADDS DEFAULT TRANSLATIONS {

												if ( ! empty( $args['defaults'] ) ) {

													foreach ( $args['defaults'] as $lang => $lang_value ) {

														update_post_meta( $post_id, 'transl_' . $lang, $lang_value );
													}
												}

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

							if ( ! isset( $this->text_list[ $post_item->translate_text_domain ][ $post_item->translate_context ][ $post_item->translate_text ] ) ) {

								wp_trash_post( $post_item->ID );
							}
						}

					// }


				// }
			}

			public function add_text( $p ) {

				// (1) Runs only if posttype "translation" archive is viewed {

					// CHECK IF IS ADMIN {

						if ( ! is_admin() ) {

							return;
						}

					// }

					// CHECK IF IS POSTTYPE translate ARCHIVE {

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

					// }

				// }

				// DEFAULTS {

					$defaults = array(
						'text' => '',
						'param' => array(),
						'context' => '',
						'domain' => $this->text_domain,
					);

					/*
						$param = array(
							'defaults' => array(
								'de_DE' => 'Hallo',
								'en_CA' => 'Hi',
							)
						)
					*/

					$p = array_replace_recursive( $defaults, $p );

				// }

				if ( empty( $this->text_list[ $p['domain'] ][ $p['context'] ][ $p['text'] ] ) ) {

					$this->text_list[ $p['domain'] ][ $p['context'] ][ $p['text'] ] = $p['param'];

					// ADD REWRITE RULES, IF TEXT IS URL SLUG {

						if ( $p['context'] === 'URL Slug' ) {

							$this->add_rewrite_rule( $p );
						}

					// }

					return true;
				}

				return false;
			}

			public function add_rewrite_rule( $p ) {

				add_filter( 'rewrite_rules_array_translation', function( $translations ) {

					// GET TRANSLATIONS FOR THE TEXT FROM POSTTYPE "translation" {

						// get_post, use posttype and text, context meta, both should be disabled acf fields

					// }

					// BUILD TRANSLATIONS ARRAY {

						//$translations['products'] = array(
						//	'en' => 'produkte',
						//);

					// }

					return $translations;
				});
			}

			// [ ] FLUSH REWRITE RULES IF POST IS UPDATED WITH CONTEXT "URL Slug"

			// [ ] ADD filter gettext_with_context PROVIDING $this->text_list TRANSLATIONS

				/*add_filter( 'gettext_with_context', function( $translation, $text, $context, $domain ) {

					// _x( 'kickstart', $context, $domain );

					if (
						$domain === 'toolset' AND
						$context === 'URL slug' AND
						$text === 'kickstart'
					) {

						$translation = translate_standalone( array(
							'default' => 'kickstart',
							'de' => 'kickstart',
							'fr' => 'kickstart',
						));

						//error_log( print_r( $translation, true) );
					}

					return $translation;

				}, 10, 4 );*/

		}

		$GLOBALS['toolset']['classes']['ToolsetTranslation'] = new ToolsetTranslation();
	}


	class ToolsetL10N {

		function __contruct() {

		}

		function translate( $translations, $locale_type = 'auto' ) {

			// DEFAULTS {

				$defaults = array(
					'default' => '',
				);

				$translations = array_replace_recursive( $defaults, $translations );

				$string = '';

				if ( $locale_type === 'user' ) {

					$locale = $GLOBALS['toolset']['user_locale'];
				}

				if ( $locale_type === 'frontend' ) {

					$locale = $GLOBALS['toolset']['frontend_locale'];
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
				));

			*/

			// DEFAULTS {

				$defaults = array(
					'text' => '',
					'translations' => array(),
					'context' => '',
					'domain' => '',
					'locale' => 'user', // user, front
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
		}

	}

	$GLOBALS['toolset']['classes']['ToolsetL10N'] = new ToolsetL10N();
