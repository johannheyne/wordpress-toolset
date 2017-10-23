<?php

	// ADD CAPABILITIES TO ROLE ( Version 2 ) {

		function tool_add_capabilities_to_role( $p = array() ) {

			/* EXAMPLES

				tool_add_capabilities_to_role( array(
					'role' => 'administrator',
					'capability_name' => 'produkte'
					'capabilities' => array(
						'read' => true,
						'publish' => true,
						'edit' => true,
						'delete' => true
					)
				) );

			*/

			/* SOURCES

				http://codex.wordpress.org/Roles_and_Capabilities

					ROLES
						administrator
						editor
						author
						contributor
						subscriber
			*/

			$p += array(
				'role' => false,
				'capability_name' => false,
				'capabilities' => array(

					/* read_{} */
					'read' => false,
					'read_private' => false,

					/* publish_{} */
					'publish' => false,

					/* edit_{} */
					'edit' => false,
					'edit_published' => false,
					'edit_others' => false,
					'edit_private' => false,

					/* delete_{} */
					'delete' => false,
					'delete_published' => false,
					'delete_others' => false,
					'delete_private' => false,

					/* sites */
					'manage_network' => false,
					'manage_sites' => false,
					'manage_network_users' => false,
					'manage_network_themes' => false,
					'manage_network_options' => false,
					'create_product' => false,

					/* sanitising */
					'unfiltered_html' => false,

					/* plugins */
					'install_plugins' => false,
					'activate_plugins' => false,
					'delete_plugins' => false,
					'edit_plugins' => false,
					'update_plugins' => false,

					/* themes */
					'install_themes' => false,
					'edit_themes' => false,
					'edit_theme_options' => false,
					'switch_themes' => false,
					'delete_themes' => false,
					'update_themes' => false,
					'edit_files' => false,

					/* users */
					'list_users' => false,
					'create_users' => false,
					'edit_users' => false,
					'delete_users' => false,
					'promote_users' => false,
					'remove_users' => false,

					/* tools */
					'export' => false,
					'import' => false,
					'manage_options' => false,
					'update_core' => false,
					'edit_dashboard' => false,

					/* comments */
					'moderate_comments' => false,

					/* categories */
					'manage_categories' => false,

					/* terms */
					'assign_terms' => false,
					'manage_terms' => false,
					'edit_terms' => false,
					'delete_terms' => false,

					/* links */
					'manage_links' => false,

					/* upload */
					'upload_files' => false
				)
			);

			if ( $p['role'] && $p['capability_name'] ) {

				$role_object = get_role( $p['role'] );

				foreach ( $p['capabilities'] as $key => $value ) {

					if ( $p['capabilities'][$key] ) {
						$role_object->add_cap( $key . '_' .  $p['capability_name'] );
					}
				}
			}
		}

	// }
