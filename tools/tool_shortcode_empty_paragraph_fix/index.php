<?php

	// SHORTCODE EMPTY PARAGRAPH FIX {

		/*
		Plugin Name: Shortcode empty Paragraph fix
		Plugin URI: http://www.johannheyne.de/wordpress/shortcode-empty-paragraph-fix/
		Description: This plugin fixes the empty paragraphs using shortcodes
		Author URI: http://www.johannheyne.de
		Version: 0.1
		Put this in /wp-content/plugins/ of your Wordpress installation
		*/

		add_filter('the_content', 'shortcode_empty_paragraph_fix');
		function shortcode_empty_paragraph_fix($content)
		{
			$array = array (
				'<p>[' => '[',
				']</p>' => ']',
				']<br />' => ']'
			);

			$content = strtr($content, $array);

			return $content;
		}

	// }
