[back to overview](../../README.markdown#initial-functionality)

Tool tool_ajax
===============================

- [Tool Functionalities](#tool-functionalities)
- [General Ajax Usage Informations](#general-ajax-usage-informations)
	- [Register WordPress Ajax File Path and Nonce](#register-wordpress-ajax-file-path-and-nonce)
	- [Ajax PHP Action](#ajax-php-action)
	- [Submit a Form by jQuery](#submit-a-form-by-jquery)

## Tool Functionalities

Provides a helper function to return data from an WordPress Ajax action.

````php
tool_ajax_return( content [string/array], linebreak [string] );
````

## General Ajax Usage Informations

If you want to make ajax calls in the WordPress enviroment, you should use the build in functionality of WordPress.

All ajax calls should made by calling the admin-ajax.php file in WordPress. This file executes a defined function that may returns a result to your ajax call. Also you should use the WordPress nonce for security reason.


### Register WordPress Ajax File Path and Nonce

First you have to register a variable in your script with some informations needed for making ajax calls:

````php
// header.php

add_action( 'wp_enqueue_scripts', 'register_my_script' );
function register_my_script() {

	wp_register_script( 'my_script', get_stylesheet_directory_uri() . '/js/my_script.js', '1.0.0', true );

	// this registers the object wpAjax in my_script.js
	wp_localize_script( 'my_script', 'wpAjax', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' )
		'ajax_nonce' => wp_create_nonce( 'my-unique-nonce-name' ),
	) );

	wp_enqueue_script( 'my_script' );
}
````


### Ajax PHP Action

Then you need a PHP function to handle the ajax request and returning data:

````php
// functions.php

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

### Basic Ajax Call in jQuery

````javascript
jQuery.noConflict();
jQuery(document).ready( function( $ ) {

	$.ajax({
		url: wpAjax.ajaxurl,
		data: {
			nonce: wpAjax.ajax_nonce,
			action: 'myajaxfunction',
			locale: App.obj.html.attr( 'lang' ),
		},
		success:function( data ) {

			// if result is json
			// data = $.parseJSON( data );

			console.log( data );
		},
		error: function( error ) {

		}
	});

});
````

### Submit a Form by jQuery

````javascript
jQuery.noConflict();
jQuery(document).ready( function( $ ) {

	App.obj.body.on( 'submit', 'form_selector', function( event ) {

		event.preventDefault();

		let $that = $( this ),
			form_data = new FormData( this );

		form_data.append( 'action', 'myajaxfunction' );
		form_data.append( 'nonce', wpAjax.ajax_nonce );
		//form_data.append( 'nonce', WPData.nonce ); // When kickstart theme used
		form_data.append( 'locale', App.obj.html.attr( 'lang' ) );

		$.ajax({
			type: 'post',
			url: WPData.ajaxurl,
			cache: false,
			contentType: false,
			processData: false,
			data: form_data,
			success:function( data ) {

				//data = $.parseJSON( data );


			},
			error: function( errorThrown ) {

				//console.log( 'Sorry, but could not add the Like!' );
			}
		});

	});
});
````

[back to overview](../../README.markdown#initial-functionality)
