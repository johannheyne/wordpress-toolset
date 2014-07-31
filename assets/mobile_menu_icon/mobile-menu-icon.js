jQuery.noConflict();
jQuery(document).ready(function ($) {

	/* MOBILE MENU ICON { */
	
		(function(){

			var obj_nav_main = jQuery('.nav-main')
					.before('<div class="mobile-menu-icon">'),
				obj_mobile_menu_icon = jQuery('.mobile-menu-icon');
			
			obj_mobile_menu_icon
				.on( 'click', function (e) {
					e.stopPropagation();
					if ( obj_mobile_menu_icon.hasClass('open') ) {
						obj_mobile_menu_icon.removeClass('open');
						obj_nav_main.hide();
					}
					else {
						obj_mobile_menu_icon.addClass('open');
						obj_nav_main.show();
					}
				});

			syze.callback(function(currentSize) {

				if ( currentSize < 530 ) {
					obj_mobile_menu_icon.show();
					obj_nav_main.hide();
				}
				else {
					obj_mobile_menu_icon.hide();
					obj_nav_main.show();
				}
			});

		}());
		
	/* MOBILE MENU ICON } */

});