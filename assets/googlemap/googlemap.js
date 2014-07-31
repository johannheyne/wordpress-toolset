// Google Map {

	(function(){

		jQuery('.googlemap').each( function () {

			  var id = jQuery(this).attr('id');

			  var geocoder;
			  var map;

			  /* initialize */
			  geocoder = new google.maps.Geocoder();
			  var latlng = new google.maps.LatLng( 0, 0 );
			  var mapOptions = {
					zoom: 17,
					center: latlng,
					mapTypeId: google.maps.MapTypeId.HYBRID,
					disableDefaultUI: true,
					panControl: true,
					zoomControl: true,
					streetViewControl: true,
			  }
			  map = new google.maps.Map(document.getElementById( id ), mapOptions);

			  /* geocode adress, set marker */
			  var address = jQuery( '#' + id ).data('adresse');
			  geocoder.geocode( { 'address': address}, function(results, status) {
				  if (status == google.maps.GeocoderStatus.OK) {
						var home = results[0].geometry.location;

						var center = home;
						center.Xa = home.Xa;
						center.Ya = home.Ya;
						map.setCenter( home );

						var pin = home;
						pin.Xa = home.Xa;
						pin.Ya = home.Ya;
						var marker = new google.maps.Marker({
							map: map,
							position: home,
							icon: jQuery( '#' + id ).data('marker')
						});
				  } else {
					  //alert("Geocode was not successful for the following reason: " + status);
				  }
			  });

		});

	}());

// }
