<?php

	// Version: 1.0

	/* CHANGELOG

		v1.0
		- initial

	*/

	// TRANSLATIONS {

		add_action( 'init', function(  ) {

			$GLOBALS['toolset']['classes']['ToolsetTranslation']->add_text( array(
				'text' => 'Maximal allowed filesize: {value}', // use it like _x( 'my_text', 'my_context', 'tool_translate' ),
				'context' => 'Formular', // optional
				'domain' => 'tool_translate', // optional, default: 'tool_translate'
				'param' => array(
					'text_default' => 'Maximal allowed filesize: {value}', // The default text if there is no translation
					'type' => 'text', // editing field type: 'text', 'textarea'
					'description' => 'Maximal filesize hint in the file field description.',
					'default_transl' => array(
						'de' => 'Maximal erlaubte Dateigröße: {value}',
						'en' => 'Maximal possible filesize: {value}',
					),
					'js' => false, // false, 'all' (all languages), 'current' (current language) // translation accessable with App.ln.get( string, context, domain );
				),
			));

			$GLOBALS['toolset']['classes']['ToolsetTranslation']->add_text( array(
				'text' => 'Allowed file types: {value}', // use it like _x( 'my_text', 'my_context', 'tool_translate' ),
				'context' => 'Formular', // optional
				'domain' => 'tool_translate', // optional, default: 'tool_translate'
				'param' => array(
					'text_default' => 'Allowed file types: {value}', // The default text if there is no translation
					'type' => 'text', // editing field type: 'text', 'textarea'
					'description' => 'Allowed file types hint in the file field description.',
					'default_transl' => array(
						'de' => 'Erlaubte Dateiformate: {value}',
						'en' => 'Allowed file types: {value}',
					),
					'js' => false, // false, 'all' (all languages), 'current' (current language) // translation accessable with App.ln.get( string, context, domain );
				),
			));

		}, 10, 2 );

	// }
