// MAGNIFIC-POPUP {

	// Source: https://github.com/dimsemenov/Magnific-Popup/
	// Documentation: http://dimsemenov.com/plugins/magnific-popup/documentation.html#animation
	// 	!!! Use link "build tool" in the header of the Documentation
	// 		to generate optimized version of main JS file.

	$.extend( true, $.magnificPopup.defaults, {
		tClose: 'Schließen (Esc)', // Alt text on close button
		tLoading: 'Lade...', // Text that is displayed during loading. Can contain %curr% and %total% keys
		gallery: {
			tPrev: 'Zurück (Pfeiltaste links)', // Alt text on left arrow
			tNext: 'Weiter (Pfeiltaste rechts)', // Alt text on right arrow
			tCounter: '%curr% von %total%' // Markup for "1 of 7" counter
		},
		image: {
			tError: '<a href="%url%">Das Bild</a> konnte nicht geladen werden.' // Error message when image could not be loaded
		},
		ajax: {
			tError: '<a href="%url%">Der Inhalt</a>  konnte nicht geladen werden.' // Error message when ajax request failed
		}
	});

	jQuery( 'a[href*=".jpg"], a[href*=".jpeg"], a[href*=".png"]' ).magnificPopup({
		type:'image',
		closeMarkup: '',
		closeOnContentClick: true,
		closeOnBgClick: true,
		image: {
			titleSrc: function( item ) {
				return item.el.parent().find('figcaption').text();
			}
		}
	});

// }