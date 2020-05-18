jQuery.noConflict();
jQuery( document ).ready( function( $ ) {

	if ( $( '.nav-menus-php' ).length > 0 ) {

		$( '.menu-item-custom' ).each( function() {

			var that = $( this ),
				value = that.val();

			if ( value !== 'url' ) {

				that.closest( '.menu-item' ).addClass( 'is_toolset_link_type' );
			}
			else {

				that.closest( '.menu-item' ).removeClass( 'is_toolset_link_type' );
			}
		});

		$( 'body' ).on( 'change', '.menu-item-custom .acf-field[data-name="link_type"] select', function() {

			var that = $( this ),
				value = that.val();

			if ( value !== 'url' ) {

				that.closest( '.menu-item' ).addClass( 'is_toolset_link_type' );
			}
			else {

				that.closest( '.menu-item' ).removeClass( 'is_toolset_link_type' );
			}
		});
	}


});
