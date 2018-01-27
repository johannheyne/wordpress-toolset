jQuery.noConflict();
jQuery(document).ready(function(){

	/* Source: https://core.trac.wordpress.org/ticket/34823
		https://core.trac.wordpress.org/browser/tags/4.6/src/wp-includes/js/tinymce/plugins/wpeditimage/plugin.js#L356
	*/

	if ( typeof wp.media === 'function' ) {

		wp.media.events.on( 'editor:image-update', function( data ) {

			var src = data.metadata.url,
				size = data.metadata.size;

			// remove last image size slug {

				var arr = src.split( '-' );
				var	arr2 = arr[ arr.length - 1 ].split( '.' );
				arr.splice(-1,1);
				var new_src = arr.join( '-' ) + '.' + arr2[1];

			// }

			if ( src.indexOf( '?size=' + size ) == -1 ) {

				// UPDATE VISUELL, YOU CANT CHECK THIS CODE BACK {

					data.editor.$( data.image ).attr( 'src', new_src + '?size=' + size );

				// }

				// UPDATE SOURCE TEXT {

					// does not work with same source images in one editor

					var html = tinyMCE.activeEditor.getContent();

					//src = html.replace( '?size=' + size, '' );

					html = html.replace( src, new_src + '?size=' + size );

					tinyMCE.activeEditor.setContent( html );

				// }

			}

		} );
	}

});
