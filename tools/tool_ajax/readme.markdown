[back to overview](../../README.markdown#initial-functionality)

Tool tool_ajax
===============================

If you want to make ajax calls in the WordPress enviroment, you should use the build in functionality of WordPress.

All ajax calls should made by calling the admin-ajax.php file in WordPress. This file executes a defined function that may returns a result to your ajax call. Also you should use the WordPress nonce for security reason.

First you have to register a variable with some informations for making ajax calls:

````php
add_action( 'wp_enqueue_scripts', 'myajax_script' );
function my_script() {

	wp_register_script( 'my_script', get_stylesheet_directory_uri() . '/js/my_script.js', '1.0.0', true );
	wp_localize_script( 'my_script', 'wpAjax', array( 
		'ajaxurl' => admin_url( 'admin-ajax.php' ) 
		'ajax_nonce' => wp_create_nonce( 'my-unique-nonce-name' ),
	) );
	wp_enqueue_script( 'my_script' );
}
````

Then you need a PHP function to handle the ajax request and returning data:

````php
add_action( 'wp_ajax_myajaxfunction', 'myajaxfunction' );
add_action( 'wp_ajax_nopriv_myajaxfunction', 'myajaxfunction' );

function myajaxfunction() {

	// there is a hidden input field with a global nonce
	if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'my-unique-nonce-name') ) {

		exit ( 'No naughty business please' );
	}   

	$return = 'Hello World!';
	
	/* README
		You also can return an array that will be send as JSON.
		New lines in the $return (\n) must be replaced by tool_ajax_return( $return, 'newline replacecement' ).
		But using ACF WYSIWYG-Field, the (\n) should replaced by ''.
	*/
	
	// The tool_ajax_return() handles the formating of the $return.
	tool_ajax_return( $return, '' );
}
````

Now you can define a Ajax call in JavaScript:

````javascript
$.ajax({
	url: wpAjax.ajaxurl,
	data: {
		nonce: wpAjax.ajax_nonce,
		action: 'myajaxfunction',
	},
	success:function( data ) {

		// if result is json
		// data = $.parseJSON( data );

		console.log( data );
	},
	error: function( errorThrown ) {

	}
});
````

[back to overview](../../README.markdown#initial-functionality)
