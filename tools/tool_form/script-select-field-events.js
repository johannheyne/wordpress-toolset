(function(){

	jQuery.noConflict();
	jQuery( document ).ready( function( $ ) {

		function SelectFieldEvents() {

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

		App.SelectFieldEvents = new SelectFieldEvents();
		App.Modules.add( 'SelectFieldEvents' ); // or simply App.SelectFieldEvents.init();

	});

}());
