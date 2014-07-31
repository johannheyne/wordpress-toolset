<script src="<?php echo $path; ?>../dependencies/modernizr.js" type="text/javascript"></script>
<script src="<?php echo $path; ?>../dependencies/jquery.js"></script>

<?php
	
	if ( stristr( $_SERVER['SERVER_ADDR'], '127.0.0.1' ) ) {
	
		$yersliderfolderabsolutepath = '/';
	}
	elseif ( stristr( $_SERVER['SERVER_NAME'], 'johannheyne' ) ) {
	
		$yersliderfolderabsolutepath = '/yerslider/';
	}
	
?>
<script>

	var YerSliderGlobals = {
		param: {
			yersliderfolderabsolutepath: '<?php echo $yersliderfolderabsolutepath; ?>',
		}
	};
	
</script>
<script src="<?php echo $path; ?>../core/yerslider.js" type="text/javascript"></script>
<script src="<?php echo $path; ?>../themes/default/yerslider.js" type="text/javascript"></script>


