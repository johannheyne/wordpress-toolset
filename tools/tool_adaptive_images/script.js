jQuery.noConflict();
jQuery(document).ready(function(){

	/* Source: https://core.trac.wordpress.org/ticket/34823
		https://core.trac.wordpress.org/browser/tags/4.6/src/wp-includes/js/tinymce/plugins/wpeditimage/plugin.js#L356
	*/

	if ( typeof wp.media === 'function' ) {

		wp.media.events.on( 'editor:image-update', function( data ) {

			var $img = data.editor.$( data.image ),
				src = $img.attr( 'src' ),
				classes = $img.attr( 'class' ),
				size = classes.split('size-')[1];


			if ( src.indexOf( '?size=' + size ) == -1 ) {

				// UPDATE VISUELL {

					data.editor.$( data.image ).attr( {
						'src':  src + '?size=' + size,
					} );

				// }

				// UPDATE TEXT {

					// does not work with same source images in one editor

					var html = tinyMCE.activeEditor.getContent();

					//src = html.replace( '?size=' + size, '' );

					html = html.replace( src, src + '?size=' + size );

					tinyMCE.activeEditor.setContent( html );

				// }

			}

		} );
	}

});
