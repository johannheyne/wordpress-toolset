<?php
	
	$sat = 70;
	$lum = 80;
	$w = 1000;
	$h = 700;
	
	generate_rainbow_images( array(
		'color_step' => 20,
		'color_hsl' => array(
			'sat' => $sat,
			'lum' => $lum,
		),
		'img_w' => $w,
		'img_h' => $h,
		'path' => '../landscape/',
		'text' => 'Large',
		'font_size' => 70,
		'filename_sufix' => '-large',
	) );

	generate_rainbow_images( array(
		'color_step' => 20,
		'color_hsl' => array(
			'sat' => $sat,
			'lum' => $lum,
		),
		'img_w' => $w / 10,
		'img_h' => $h / 10,
		'path' => '../landscape/',
		'text' => 'Thumb',
		'font_size' => 14,
		'filename_sufix' => '-thumb',
	) );

	generate_rainbow_images( array(
		'color_step' => 20,
		'color_hsl' => array(
			'sat' => $sat,
			'lum' => $lum,
		),
		'img_w' => $h,
		'img_h' => $w,
		'path' => '../portrait/',
		'text' => 'Large',
		'font_size' => 70,
		'filename_sufix' => '-large',
	) );

	generate_rainbow_images( array(
		'color_step' => 20,
		'color_hsl' => array(
			'sat' => $sat,
			'lum' => $lum,
		),
		'img_w' => $h / 10,
		'img_h' => $w / 10,
		'path' => '../portrait/',
		'text' => 'Thumb',
		'font_size' => 14,
		'filename_sufix' => '-thumb',
	) );

	function generate_rainbow_images( $p = array() ) {

	    // DEFAULTS {

	        $defaults = array(
	            'color_step' => 20, // < 360
				'color_hsl' => array(
					'sat' => 100,
					'lum' => 75,
				),
				'img_w' => 1000,
				'img_h' => 700,
				'path' => '',
				'text' => false,
				'font_size' => false,
				'font_file' => 'arial.ttf',
	        	'filename_sufix' => '',
			);

	        $p = array_replace_recursive( $defaults, $p );

	    // }

		$color_step_current = 0;
		$i = 0;

		while ( $color_step_current < 359 ) {

			// JOB {

				$i = $i + 1;

				$hue = $color_step_current;
				$sat = $p['color_hsl']['sat'];
				$lum = $p['color_hsl']['lum'];

				$color_bg = ColorHSLToRGB( $hue, $sat, $lum );
				$color_text = ColorHSLToRGB( $hue, $sat, 60 );

				$img = imagecreate( $p['img_w'], $p['img_h'] );

				$img_color_bg = imagecolorallocate( $img, $color_bg['r'], $color_bg['g'], $color_bg['b'] );
				$img_color_text = imagecolorallocate( $img, $color_text['r'], $color_text['g'], $color_text['b'] );

				imagefill( $img, 0, 0, $img_color_bg );

				if ( $p['text'] ) {

					$textinfo = imagettfbbox ( $p['font_size'] , 0 , $p['font_file'], $p['text'] );

					$text_lenght = $textinfo[2] - $textinfo[0];
					$text_height = $textinfo[1] - $textinfo[7];

					$text_x = ( $p['img_w'] - $text_lenght ) / 2;
					$text_y = ( $p['img_h'] / 2 ) + ( $text_height / 2 ) - $p['img_h'] / 50;

					imagettftext( $img , $p['font_size'] , 0, $text_x , $text_y , $img_color_text, $p['font_file'], $p['text'] );
				}

				imagejpeg( $img, $p['path'] . str_pad( $i, 2, '0', STR_PAD_LEFT ) . $p['filename_sufix'] . '.jpg', 100 );

				//echo '<div style="width: 20px;height: 20px; background: rgb(' . $color_bg['r'] . ',' . $color_bg['g'] . ',' . $color_bg['b'] . ');"></div>';

			// }

			$color_step_current = $color_step_current + $p['color_step'];
		}
	}

	function ColorHSLToRGB($h, $s, $l) {

			$h /= 360;
			$s /= 100;
			$l /= 100;

			$r = $l;
			$g = $l;
			$b = $l;
			$v = ($l <= 0.5) ? ($l * (1.0 + $s)) : ($l + $s - $l * $s);
			if ($v > 0){
				$m;
				$sv;
				$sextant;
				$fract;
				$vsf;
				$mid1;
				$mid2;

				$m = $l + $l - $v;
				$sv = ($v - $m ) / $v;
				$h *= 6.0;
				$sextant = floor($h);
				$fract = $h - $sextant;
				$vsf = $v * $sv * $fract;
				$mid1 = $m + $vsf;
				$mid2 = $v - $vsf;

				switch ($sextant)
				{
						case 0:
							$r = $v;
							$g = $mid1;
							$b = $m;
							break;
						case 1:
							$r = $mid2;
							$g = $v;
							$b = $m;
							break;
						case 2:
							$r = $m;
							$g = $v;
							$b = $mid1;
							break;
						case 3:
							$r = $m;
							$g = $mid2;
							$b = $v;
							break;
						case 4:
							$r = $mid1;
							$g = $m;
							$b = $v;
							break;
						case 5:
							$r = $v;
							$g = $m;
							$b = $mid2;
							break;
				}
			}

			return array('r' => floor($r * 255.0), 'g' => floor($g * 255.0), 'b' => floor($b * 255.0));
	}

?>