<?php

	// COMMENT MODERATION RECIPIENTS ( Version 2 ) {

		add_filter( 'comment_moderation_recipients', function( $emails, $comment_id ) {

			$emails = array_flip( $emails );

			// REMOVE EMAILS
			if (
				isset( $GLOBALS['toolset']['inits']['tool_wp_comment_moderation_recipients']['emails_remove'] )
				AND count( $GLOBALS['toolset']['inits']['tool_wp_comment_moderation_recipients']['emails_remove'] ) > 0
			) {

				foreach ( $GLOBALS['toolset']['inits']['tool_wp_comment_moderation_recipients']['emails_remove'] as $item ) {

					if ( $item === 'admin_email' ) {

						$item = get_option( 'admin_email' );
					}

					unset( $emails[ $item ] );
				}
			}

			// ADD EMAILS
			if (
				isset( $GLOBALS['toolset']['inits']['tool_wp_comment_moderation_recipients']['emails_add'] )
				AND count( $GLOBALS['toolset']['inits']['tool_wp_comment_moderation_recipients']['emails_add'] ) > 0
			) {

				foreach ( $GLOBALS['toolset']['inits']['tool_wp_comment_moderation_recipients']['emails_add'] as $item ) {

					$emails[ $item ];
				}
			}

			$emails = array_flip( $emails );

			return $emails;

		}, 10, 2 );

	// }
