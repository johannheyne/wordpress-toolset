<?php

	// WP RELATIVE URLS ( Version 1 ) {

		// http://www.deluxeblogtips.com/2012/06/relative-urls.html

		function rw_remove_root( $url ) {
			$url = str_replace( home_url(), '', $url );
			return '/' . ltrim( $url, '/' );
		}

		function wp_make_link_relative( $link ) {
			return preg_replace( '|https?://[^/]+(/.*)|i', '$1', $link );
		}

		$filters = array(
			'post_link',	   // Normal post link
			'post_type_link',  // Custom post type link
			'page_link',	   // Page link
			'attachment_link', // Attachment link
			'get_shortlink',   // Shortlink
			'post_type_archive_link',	// Post type archive link
			'get_pagenum_link',		  // Paginated link
			'get_comments_pagenum_link', // Paginated comment link
			'term_link',   // Term link, including category, tag
			'search_link', // Search link
			'day_link',   // Date archive link
			'month_link',
			'year_link',
		);

		foreach ( $filters as $filter ) {

			add_filter( $filter, 'wp_make_link_relative' );
		}

	// }

?>