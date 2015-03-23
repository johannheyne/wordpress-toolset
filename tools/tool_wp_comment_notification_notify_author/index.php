<?php

	// TOOL COMMENT NOTIFICATION NOTIFY AUTHOR ( Version 1 ) {

		add_filter( 'comment_notification_notify_author', function( $bol ) {

			$return = false;

			if ( $GLOBALS['toolset']['inits']['tool_wp_comment_notification_notify_author'] ) {

				$return = true;
			}

			return $return;

		}, 10, 2 );

	// }

?>