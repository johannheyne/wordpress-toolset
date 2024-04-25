(function(){

	jQuery.noConflict();
	jQuery( document ).ready( function( $ ) {

		function ToolFormSelectFieldEvents() {

			var t = this;

			t.ids = {
				change_location: 'js-select-field-event-change-location',
				submit_form: 'js-select-field-event-submit-form',
			};

			t.sel = {
				change_location: '.' + t.ids.change_location,
				submit_form: '.' + t.ids.submit_form,
			};

			t.init =  function() {

			};

			t.run = function() {

				t.adds_select_field_change_location_event();
				t.adds_select_field_submit_form_event();
			};

			t.adds_select_field_change_location_event = function() {

				App.obj.body.on( 'change', t.sel.change_location, t.change_location );
			};

			t.change_location = function() {

				var select_field = $( this );

				document.location.href = select_field.val();
			};

			t.adds_select_field_submit_form_event = function() {

				App.obj.body.on( 'change', t.sel.submit_form, t.submit_form );
			};

			t.submit_form = function() {

				var select_field = $( this ),
					form = select_field.closest( 'form' );

				form.submit();
			};

		};

		App.ToolFormSelectFieldEvents = new ToolFormSelectFieldEvents();
		App.Modules.add( 'ToolFormSelectFieldEvents' ); // or simply App.SelectFieldEvents.init();


		function ToolFormFileField() {

			var t = this;

			t.ids = {
				label: 'form-field-file-input',
				input: 'input[type="file"]',
			};

			t.sel = {
				label: '.' + t.ids.label,
				input: t.ids.input,
			};

			t.obj = {
				input: $( t.sel.input ),
			};

			t.param = {
				//name: false,
			};

			t.priv = {
				//name: false,
			};

			t.vars = {
				//name: false,
			};

			t.init =  function() {

				// INITS {

					t = $.extend( true, new App.ext.Json(), t );

				// }

				// ADDS ACTIONS {



				// }

				// ADDS FILTERS {



				// }

				// ADDS EVENTS {



				// }

			};

			t.run = function() {

				t.add_event_input_change();
			};

			t.format_file_size = function( size ) {

				var arr_units = [
					'<acronym lang="en" xml:lang="en" title="Byte">B</acronym>',
					'<acronym lang="en" xml:lang="en" title="Kilobyte">KB</acronym>',
					'<acronym lang="en" xml:lang="en" title="Megabyte">MB</acronym>',
					'<acronym lang="en" xml:lang="en" title="Gigabyte">GB</acronym>',
					'<acronym lang="en" xml:lang="en" title="Terabyte">TB</acronym>'
				];

				// GETS SIZE AND UNIT INDEX {

					for ( var i = 0; size >= 1000; i++ ) {

						size = size / 1000;
					}

				// }

				// DECIMALS {

					var decimals = 2;

					if ( i < 2 ) {

						decimals =  0;
					}

				// }

				// ROUNDS SIZE UP ACCORDING TO DECIMALS {

					var multiplier = 1;

					for ( var l = 0; l < decimals; l++ ) {

						if ( multiplier == 1 ) {

							multiplier = 10;
						}
						else {

							multiplier = multiplier * 10;
						}
					}

					size = Math.ceil( size * multiplier ) / multiplier;

				// }

				// LOCALIZE SIZE{

					var locale = App.obj.html.attr( 'lang' );

					size.toLocaleString( locale, {
						maximumFractionDigits: decimals,
						minimumFractionDigits: decimals
					});

				// }

				return size + ' ' + arr_units[ i ];
			};

			t.add_event_input_change = function() {

				App.obj.body.on( 'change', t.sel.input, function() {

					t.update_file_info( this );
				});
			};

			t.update_file_info = function( that ) {

				var input = $( that ),
					input_name = input.attr( 'name' ),
					label = input.siblings( t.sel.label ),
					filename = false,
					files = label.next( '._files' );
					//data = label.siblings( '[type="hidden"]' );

				files.remove();

				// GETS FILE LIST {

					if ( input[0].files.length > 0 ) {

						var list = [];

						for ( var i in input[0].files ) {

							if (
								typeof input[0].files[ i ].name === 'string' &&
								! isNaN( i ) // is number ?
							) {

								list.push( input[0].files[ i ].name + ' <span>(' + t.format_file_size( input[0].files[ i ].size ) + ')</span>' );
							}
						}
					}

				// }

				if ( input[0].files.length > 1 ) {

					filename = input[0].files.length;

					var text = App.ln.get( '{value} files selected', 'Formular', 'tool_translate' );
					text = text.replace( '{value}', filename );
					label.text( text );

					label.after( '<ul class="_files"><li>' + list.join( '</li><li>' ) + '</li></ul>' );
				}
				else if ( input[0].files.length === 1 ) {

					var filesize = t.format_file_size( input[0].files[0].size );
					filesize = filesize.replace(/(<([^>]+)>)/gi, ""); // strip tags

					filename = input[0].files[0].name + ' (' + filesize + ')';

					label.text( filename  );
				}
				else {

					label.text( label.data( 'label-text' ) );
				}

			};
		};

		App.ToolFormFileField = new ToolFormFileField();
		App.Modules.add( 'ToolFormFileField' ); // or simply App.Kickstart.init();


		function ToolFormSend() {

			var t = this;

			t.ids = {
				form: '[data-form-id][method="post"]',
			};

			t.sel = {
				form: t.ids.form,
			};

			t.obj = {
				//form: $( t.sel.form ),
			};

			t.param = {
				//name: false,
			};

			t.priv = {
				//name: false,
			};

			t.vars = {
				//name: false,
			};

			t.init =  function() {

				// INITS {

					t = $.extend( true, new App.ext.Json(), t );

				// }

				// ADDS ACTIONS {



				// }

				// ADDS FILTERS {



				// }

				// ADDS EVENTS {



				// }

			};

			t.run = function() {

				t.adds_submit_event();
			};


			t.adds_submit_event = function() {

				App.obj.body.on( 'submit', t.sel.form, function( event ) {

					event.preventDefault();

					var $that = $( this ),
						form_unique_id = $that.data( 'form-unique-id' ),
						form_id = $that.data( 'form-id' ),
						form_post_id = $that.data( 'form-post-id' );

					var form_data = new FormData( this );

					form_data.append( 'form_id', form_id );
					form_data.append( 'form_post_id', form_post_id );
					form_data.append( 'action', 'tool_form' );
					form_data.append( 'nonce', WPData.nonce );
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

							let selector = '[data-form-post-id="' + form_post_id + '"]',
								$form = $( selector ),
								form_offset = $form.offset();

							selector = App.Filters.do( 'ToolForm', 'success_selector', selector, {
								form_unique_id: form_unique_id,
								form_id: form_id,
								form_post_id: form_post_id,
							} );

							$( selector )
								.empty()
								.append( data );

							App.Actions.do( 'ToolForm', 'sent', {
								form_unique_id: form_unique_id,
								form_id: form_id,
								form_post_id: form_post_id,
								form_offset: form_offset,
							} );
						},
						error: function( errorThrown ) {

							//console.log( 'Sorry, but could not add the Like!' );
						}
					});
				});

			};

		};

		App.ToolFormSend = new ToolFormSend();
		App.Modules.add( 'ToolFormSend' ); // or simply App.Kickstart.init();

	});

}());
