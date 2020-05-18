<?php

	if ( ! empty( $GLOBALS['toolset']['inits']['tool_menu_item_types'] ) ) {

		if ( is_admin() ) {

			add_action( 'admin_enqueue_scripts', function() {

				wp_enqueue_script( 'tool_menu_item_types', $GLOBALS['toolset']['plugins_url'] . '/wordpress-toolset/tools/tool_menu_item_types/admin-script.js', array( 'jquery' ), config_get_theme_version() );
				wp_enqueue_style( 'tool_menu_item_types', $GLOBALS['toolset']['plugins_url'] . '/wordpress-toolset/tools/tool_menu_item_types/admin-styles.css', array(), config_get_theme_version() );
			});
		}

		add_filter( 'nav_menu_css_class', function( $classes, $item, $args, $depth ) {

			$link_type = get_field( 'link_type', $item->ID );

			if ( $link_type !== 'url' ) {

				$classes[] = 'link-type-' . $link_type;
			}

			return $classes;
		}, 11, 4 );

		add_filter( 'nav_menu_link_attributes', function( $atts, $item, $args, $depth ) {

			$link_type = get_field( 'link_type', $item->ID );

			if ( $link_type === 'email' ) {

				$email = get_lang_field( 'opt_contactdata/email', 'option' );
				$atts['href'] = 'mailto:' . antispambot( $email );
			}

			if ( $link_type === 'phone' ) {

				$phone = get_lang_field( 'opt_contactdata/fon', 'option' );
				$atts['href'] = get_tel_url( array(
					'number' => $phone,
				));
			}

			if ( $link_type === 'fax' ) {

				$fax = get_lang_field( 'opt_contactdata/fax', 'option' );
				$atts['href'] = get_tel_url( array(
					'number' => $fax,
					'prefix' => 'fax:',
				));
			}

			return $atts;
		}, 11, 4 );

		add_filter( 'nav_menu_item_title', function( $title, $item, $args, $depth ) {

			$menu_name = get_lang_field( 'menu_item/menu_name', $item->ID );
			$link_type = get_field( 'link_type', $item->ID );

			if ( $link_type === 'email' ) {

				if ( empty( $menu_name ) ) {

					$value = get_lang_field( 'opt_contactdata/email', 'option' );
					$title = antispambot( $value );
				}
			}

			if ( $link_type === 'phone' ) {

				if ( empty( $menu_name ) ) {

					$value = get_lang_field( 'opt_contactdata/fon', 'option' );
					$title = $value;
				}
			}

			if ( $link_type === 'fax' ) {

				if ( empty( $menu_name ) ) {

					$value = get_lang_field( 'opt_contactdata/fax', 'option' );
					$title = $value;
				}
			}

			return $title;
		}, 11, 4 );

	}



add_action( 'admin_head-nav-menus.php', function() {

	add_meta_box( 'nav-menu-link-types', 'Kontakt', function( $object, $args ) {

		global $nav_menu_selected_id;

		?>
		<div id="link-types-div">
			<div id="tabs-panel-link-types-all" class="tabs-panel tabs-panel-active">
			<ul id="link-types-checklist-pop" class="categorychecklist form-no-clear" >
				<li>
					<label class="menu-item-title">
						<input type="checkbox" class="menu-item-checkbox" name="menu-item[-999][menu-item-object-id]" value="1">
						E-Mail
					</label>
					<input type="hidden" class="menu-item-db-id" name="menu-item[-999][menu-item-db-id]" value="">
					<input type="hidden" class="menu-item-object" name="menu-item[-999][menu-item-object]" value="">
					<input type="hidden" class="menu-item-parent-id" name="menu-item[-999][menu-item-parent-id]" value="">
					<input type="hidden" class="menu-item-type" name="menu-item[-999][menu-item-type]" value="">
					<input type="hidden" class="menu-item-title" name="menu-item[-999][menu-item-title]" value="E-Mail">
					<input type="hidden" class="menu-item-url" name="menu-item[-999][menu-item-url]" value="https://johannheyne@web.de">
					<input type="hidden" class="menu-item-target" name="menu-item[-999][menu-item-target]" value="https://johannheyne@web.de">
					<input type="hidden" class="menu-item-attr-title" name="menu-item[-999][menu-item-attr-title]" value="https://johannheyne@web.de">
					<input type="hidden" class="menu-item-classes" name="menu-item[-999][menu-item-classes]" value="mail">
					<input type="hidden" class="menu-item-xfn" name="menu-item[-999][menu-item-xfn]" value="">
				</li>
			</ul>

			<p class="button-controls">
				<span class="add-to-menu">
					<input type="submit"<?php wp_nav_menu_disabled_check( $nav_menu_selected_id ); ?> class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e( 'Add to Menu' ); ?>" name="add-link-types-menu-item" id="submit-link-types-div" />
					<span class="spinner"></span>
				</span>
			</p>
		</div>
		<?php
	}, 'nav-menus', 'side', 'default' );
} );
