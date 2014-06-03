<?php

	// ADD CLASS TO HTML-STRING ( Version 1 ) {

		function tool_add_class( $p = array() ) {

			 $p += array(
				'class' => false,
				'html' => false
			 );

			if ( $p['html'] && $p['class'] ) {

				$p['html'] = preg_replace('/^(.*)class="(.*)"(.*)/is', '$1class="$2 ' . $p['class'] . '"$3', $p['html'] );
			}

			return $p['html'];
		}

	// }

?>