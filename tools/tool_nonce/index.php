<?php

	// NONCE ( Version 1 ) {

		function tool_wp_nonce() {

			echo '<input class="nonce" type="hidden" value="' . wp_create_nonce('nonce') . '"/>';
			echo '<input class="post_id" type="hidden" value="' . @get_the_ID() . '"/>';
		}

	// }
