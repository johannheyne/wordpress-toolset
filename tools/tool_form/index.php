<?php

	// Version: 1.0

	/* CHANGELOG

		v1.0
		- initial

	*/

	// SCRIPT {

		add_action( 'wp_enqueue_scripts', function() {

			wp_enqueue_script( 'slug', $GLOBALS['toolset']['plugins_url'] . '/wordpress-toolset/tools/tool_form/script.js', array( 'jquery' ), config_get_theme_version(), false );
		} );

	// }

	// TRANSLATIONS {

		add_action( 'init', function(  ) {

			$GLOBALS['toolset']['classes']['ToolsetTranslation']->add_text( array(
				'text' => '{value} files selected', // use it like _x( 'my_text', 'my_context', 'tool_translate' ),
				'context' => 'Formular', // optional
				'domain' => 'tool_translate', // optional, default: 'tool_translate'
				'param' => array(
					'text_default' => '{value} files selected', // The default text if there is no translation
					'type' => 'text', // editing field type: 'text', 'textarea'
					'description' => 'File upload field label text if more than 1 file was selectet.',
					'default_transl' => array(
						'de' => '{value} ausgewählte Dateien',
						'en' => '{value} files selected',
					),
					'js' => 'all', // false, 'all' (all languages), 'current' (current language) // translation accessable with App.ln.get( string, context, domain );
				),
			));

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

			$GLOBALS['toolset']['classes']['ToolsetTranslation']->add_text( array(
				'text' => 'This field is required.', // use it like _x( 'my_text', 'my_context', 'tool_translate' ),
				'context' => 'Formular', // optional
				'domain' => 'tool_translate', // optional, default: 'tool_translate'
				'param' => array(
					'text_default' => 'This field is required.', // The default text if there is no translation
					'type' => 'text', // editing field type: 'text', 'textarea'
					'description' => 'Field validation message.',
					'default_transl' => array(
						'de' => 'Dieses Feld ist erforderlich.',
						'en' => 'This field is required.',
					),
					'js' => false, // false, 'all' (all languages), 'current' (current language) // translation accessable with App.ln.get( string, context, domain );
				),
			));

			$GLOBALS['toolset']['classes']['ToolsetTranslation']->add_text( array(
				'text' => 'At least one field has an validation error.', // use it like _x( 'my_text', 'my_context', 'tool_translate' ),
				'context' => 'Formular', // optional
				'domain' => 'tool_translate', // optional, default: 'tool_translate'
				'param' => array(
					'text_default' => 'At least one field has an validation error.', // The default text if there is no translation
					'type' => 'text', // editing field type: 'text', 'textarea'
					'description' => 'Field validation message.',
					'default_transl' => array(
						'de' => 'Mindestens ein Feld muss geprüft werden.',
						'en' => 'At least one field has an validation error.',
					),
					'js' => false, // false, 'all' (all languages), 'current' (current language) // translation accessable with App.ln.get( string, context, domain );
				),
			));

			$GLOBALS['toolset']['classes']['ToolsetTranslation']->add_text( array(
				'text' => 'The file size was to large.', // use it like _x( 'my_text', 'my_context', 'tool_translate' ),
				'context' => 'Formular', // optional
				'domain' => 'tool_translate', // optional, default: 'tool_translate'
				'param' => array(
					'text_default' => 'The file size was to large.', // The default text if there is no translation
					'type' => 'text', // editing field type: 'text', 'textarea'
					'description' => 'Field validation message.',
					'default_transl' => array(
						'de' => 'Die maximale Dateigröße ist überschritten.',
						'en' => 'The file size was to large.',
					),
					'js' => false, // false, 'all' (all languages), 'current' (current language) // translation accessable with App.ln.get( string, context, domain );
				),
			));

			$GLOBALS['toolset']['classes']['ToolsetTranslation']->add_text( array(
				'text' => 'The email is not valid.', // use it like _x( 'my_text', 'my_context', 'tool_translate' ),
				'context' => 'Formular', // optional
				'domain' => 'tool_translate', // optional, default: 'tool_translate'
				'param' => array(
					'text_default' => 'The email is not valid.', // The default text if there is no translation
					'type' => 'text', // editing field type: 'text', 'textarea'
					'description' => 'Field validation message.',
					'default_transl' => array(
						'de' => 'Die E-Mail scheint nicht korrekt.',
						'en' => 'The email is not valid.',
					),
					'js' => false, // false, 'all' (all languages), 'current' (current language) // translation accessable with App.ln.get( string, context, domain );
				),
			));

			$GLOBALS['toolset']['classes']['ToolsetTranslation']->add_text( array(
				'text' => 'The file format is not allowed.', // use it like _x( 'my_text', 'my_context', 'tool_translate' ),
				'context' => 'Formular', // optional
				'domain' => 'tool_translate', // optional, default: 'tool_translate'
				'param' => array(
					'text_default' => 'The file format is not allowed.', // The default text if there is no translation
					'type' => 'text', // editing field type: 'text', 'textarea'
					'description' => 'Field validation message.',
					'default_transl' => array(
						'de' => 'Das Datei Format ist nicht erlaubt.',
						'en' => 'The file format is not allowed.',
					),
					'js' => false, // false, 'all' (all languages), 'current' (current language) // translation accessable with App.ln.get( string, context, domain );
				),
			));

			$GLOBALS['toolset']['classes']['ToolsetTranslation']->add_text( array(
				'text' => 'The email was sent.', // use it like _x( 'my_text', 'my_context', 'tool_translate' ),
				'context' => 'Formular', // optional
				'domain' => 'tool_translate', // optional, default: 'tool_translate'
				'param' => array(
					'text_default' => 'The email was sent.', // The default text if there is no translation
					'type' => 'text', // editing field type: 'text', 'textarea'
					'description' => 'Field validation message.',
					'default_transl' => array(
						'de' => 'Die E-Mail wurde versendet.',
						'en' => 'The email was sent.',
					),
					'js' => false, // false, 'all' (all languages), 'current' (current language) // translation accessable with App.ln.get( string, context, domain );
				),
			));

			$GLOBALS['toolset']['classes']['ToolsetTranslation']->add_text( array(
				'text' => 'Choose a file', // use it like _x( 'my_text', 'my_context', 'tool_translate' ),
				'context' => 'Formular', // optional
				'domain' => 'tool_translate', // optional, default: 'tool_translate'
				'param' => array(
					'text_default' => 'Choose a file', // The default text if there is no translation
					'type' => 'text', // editing field type: 'text', 'textarea'
					'description' => 'File field text',
					'default_transl' => array(
						'de' => 'Dateien auswählen',
						'en' => 'Choose a file',
					),
					'js' => false, // false, 'all' (all languages), 'current' (current language) // translation accessable with App.ln.get( string, context, domain );
				),
			));

		}, 10, 2 );

	// }
