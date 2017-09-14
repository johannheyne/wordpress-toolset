<?php

	class ToolsetFieldsValue {

		function __construct() {

			// this filter is called by get_field()
			add_filter( 'acf/format_value', array( $this, 'filter' ) );
		}

		function filter( $value ) {

			// replaces in a string
			if ( ! is_array( $value ) ) {

				$value = apply_filters( 'toolset/tool_fields_value/value', $value );
			}

			// replaces values of an array (repeater field etc.)
			else {

				array_walk_recursive( $value, function( &$item, $key ) {

					$item = apply_filters( 'toolset/tool_fields_value/value', $item );

				} );
			}

			return $value;
		}

	}

	new ToolsetFieldsValue();
