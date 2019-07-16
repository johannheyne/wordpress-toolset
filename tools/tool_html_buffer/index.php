<?php

	class ToolsetHtmlBuffer {

		private $replace = array(
			'®' => '<span class="char-cr">®</span>'
		);

		function __construct() {

			add_action( 'wp_loaded', array( $this, 'ob_start' ) ); // after_setup_theme
			add_action( 'wp_print_footer_scripts', array( $this, 'ob_end_flush' ) ); // shutdown
		}

		function ob_start() {

			ob_start( array( $this, 'buffer' ) );
		}

		function ob_end_flush() {

			ob_end_flush();
		}

		function buffer( $buffer ) {

			$buffer = apply_filters( 'toolset/tool_html_buffer/buffer', $buffer );

			return $buffer;
		}

	}

	new ToolsetHtmlBuffer();
