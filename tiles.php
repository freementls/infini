<?php

class tiles {

function __construct() {

	// it's worth asking if things like quarks with 1/3 or -2/3 charge really exist or whether these arise from geometrical considerations such that, for example, 3d space may not be cartesian with perpendicularly arranged
	// dimensions whereby fractional charges are due to different shapes of space or geometries of orbits (think of star of david when not flattened to 2d) and correspondingly the math for tiling schemes may be overcomplicated
	// by the assumption of cartesian dimensions, but for now shrug

	$this->tiling_schemes = array(
	// name => sides, tiles, needs_stagger
	// tile images are assumed (though not checked) to have the same image dimensions
	// these shapes are assumed to have a flat edge on the bottom
	'wesnoth' => array(6, array('images/terrain/grass/green.png'), true), // irregular hexagons
	'triangle' => array(3, array('images/green_triangle.png'), true), // the tile has partially transparent edges
	'quadrangle' => array(4, array('images/square_grass_outlined.png'), false),
	'staggered quadrangle' => array(4, array('images/square_grass_outlined.png'), true),
	'pentagon' => array(5, array('images/pentagon_grass_outlined.png'), true), // impossible in 2d
	'hexagon' => array(6, array('images/hexagon_grass_outlined.png'), true), // width?
	'heptagon' => array(7, array('images/heptagon_grass.png'), true), // impossible in 2d
	'octagon' => array(8, array('images/octagon_grass.png'), true), // impossible in 2d
	'nonagon' => array(9, array('images/nonagon_grass.png'), true), // impossible in 2d
	'decagon' => array(10, array('images/decagon_grass.png'), true), // impossible in 2d
	'dodecagon' => array(12, array('images/dodecagon_grass.png'), true), // impossible in 2d
	/*'heptagon' => array(54, 72, true, array('images/terrain/grass/green.png')),
	'octagon' => array(54, 72, true, array('images/terrain/grass/green.png')),
	'octagon-square 1' => array(54, 72, true, array('images/terrain/grass/green.png')),
	'octagon-square 2' => array(54, 72, true, array('images/terrain/grass/green.png')),
	'nonagon' => array(54, 72, true, array('images/terrain/grass/green.png')),
	'decagon' => array(54, 72, true, array('images/terrain/grass/green.png')),
	'dodecagon' => array(54, 72, true, array('images/terrain/grass/green.png')),*/
	// grid-friendly and grid-unfriendly tilings
	// buckey ball hexagon and pentagon is this only possible in 3d?
	// 
	);

	$this->tiling_scheme = 'wesnoth';
	$this->tile_sides = $this->tiling_schemes[$this->tiling_scheme][0];
	$this->image_dimensions = array();

}

function get_tile_sides() {
	return $this->tile_sides;
}

function get_tiling_scheme() {
	return $this->tiling_scheme;
}

function get_tiling_schemes() {
	return $this->tiling_schemes;
}

function tile() {
	// what about non-grid maps? possible but would require extra handling and it's unclear whether there's any advantage over using tiles not considered part of the game but nevertheless in the tiles grid
	/*print('<!DOCTYPE html>
<html>
<head>
    <title>jQuery Minimap demo</title>
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script type="text/javascript" src="jquery minimap/src/jquery.minimap.js"></script>
	<style type="text/css">

		@import url(http://fonts.googleapis.com/css?family=Alegreya|Diplomata);

		html {
		  height: 100%;
		  min-height: 100%;
		}

		body {
		  margin: 0;
		  padding: 0;
		  font-family: "Alegreya", serif;
		  height: 100%;
		}

		.utility {
			font-family: Helvetica, "Liberation Sans", Arial, sans-serif;
			position: fixed;
			top: .5em;
			left: .5em;
		}

		h1 {
		  font-family: "Diplomata", cursive;
		  color: #999;
		}

		h2, h3, h4 {
		  font-style: italic;
		  color: #444;
		}

		.body_column {
		  overflow: hidden;
		  width: 80%;
		  max-width: 40em;
		  margin: 0 auto;
		}

		img {
		  max-width: 100%;
		  height: auto;
		}

		figcaption {
		  font-size: .9em;
		  font-style: italic;
		}

	</style>
	<link rel="stylesheet" href="jquery minimap/src/jquery.minimap.css" />
    <script type="text/javascript">
        $(document).ready( function() {
            $(\'.body_column\').minimap();
        });
    </script>
</head>
<body>
');*/
print('<html>
<head>
<style type="text/css">
div:hover { /*position: relative; top: 0; left: 0; height: 72px; width: 72px; image: url(\'images/misc/hover-hex.png\'); border: 5px solid red;*/ }
.hover_image { z-index: 100 !important; /* we\'ll have to implement layers instead of hard-coding z-indices */ }
#red_sniper, #captain { z-index: 1 !important; }
/* ugh haven\'t figured out how to overlay the hover-hex over unit images */
</style>
<script src="jquery.min.js"></script> <!-- jquery 3.2.0 -->
<script>
$(document).ready(function(){
    $("div").hover(function(){
        var txt1 = \'<img src="images/misc/hover-hex.png" class="hover_image" style="position: relative; z-index: 100 !important;" />\';
		$(this).append(txt1);
    },
    function(){
		$("img").remove(".hover_image");
    });
});
</script>
</head>
<body>
');
	/*print('<div class="utility">
	<p><a href="javascript:$.minimap.toggle();">Toggle the minimap</a></p>
	<p><a href="https://github.com/goldenapples/jquery.minimap/">View/Fork this on Github</a></p>
</div>
');*/
	print('<div class="body_column">
');
	$width = 0;
	//if($this->tiling_schemes[$this->tiling_scheme][2]) {
		$height = tiles::get_image_height($this->tiling_schemes[$this->tiling_scheme][1][0]) / 2;
	//} else {
	//	$height = 0;
	//}
	$flipper = 0;
	//$this->tiles = array();
	$this->tiles = new O('infini_tiles.xml');
	$x = 0;
	while($width < 2000) {
		$y = 0;
		while($height < 2000) {
			$rand = rand(0, sizeof($this->tiling_schemes[$this->tiling_scheme][1]) - 1);
			$image_height = tiles::get_image_height($this->tiling_schemes[$this->tiling_scheme][1][$rand]);
			//print('$image_height: ');var_dump($image_height);exit(0);
			$image_width = tiles::get_image_width($this->tiling_schemes[$this->tiling_scheme][1][$rand]);
			if($flipper === 1 && $this->tiling_schemes[$this->tiling_scheme][0] % 2 !== 0) {
				$image_url = tiles::get_rotated_image_url($this->tiling_schemes[$this->tiling_scheme][1][$rand], 180);
				//print('rotated $image_url: ');var_dump($image_url);exit(0);
			//	$flipper = 0;
			} else {
				$image_url = $this->tiling_schemes[$this->tiling_scheme][1][$rand];
			//	$flipper = 1;
			}
			print('<div style="position: absolute; top: ' . $height . '; left: ' . $width . '; height: ' . $image_height . 'px; width: ' . $image_width . 'px; background-image: url(\'' . $image_url . '\');"></div>
');
			$this->tiles->new_('<tile>
<x>' . $x . '</x>
<y>' . $y . '</y>
<toppixels>' . $height . '</toppixels>
<leftpixels>' . $width . '</leftpixels>
<tileimage>' . $image_url . '</tileimage>
</tile>');
			$height += $image_height;
		}
		//$width += $this->tiling_schemes[$this->tiling_scheme][0];
		//$inner_angle = $this->tiling_schemes[$this->tiling_scheme][1];
		$inner_angle = (180 * ($this->tile_sides - 2)) / $this->tile_sides;
		$width_step = (1 - (abs(cos(deg2rad($inner_angle))) / ($this->tiling_schemes[$this->tiling_scheme][0] / 3))) * $image_width;
		//print('cos(deg2rad($this->tiling_schemes[$this->tiling_scheme][1])), $this->tiling_schemes[$this->tiling_scheme][1], $this->tiling_schemes[$this->tiling_scheme][0], $image_width: ');var_dump(cos(deg2rad($this->tiling_schemes[$this->tiling_scheme][1])), $this->tiling_schemes[$this->tiling_scheme][1], $this->tiling_schemes[$this->tiling_scheme][0], $image_width);
		//print('$width_step: ');var_dump($width_step);exit(0);
		$width += $width_step;
		// flipping and staggering
		if(isset($this->tiling_schemes[$this->tiling_scheme][2])) {
			$needs_flipper = $this->tiling_schemes[$this->tiling_scheme][2];
		} else {
			$needs_flipper = true;
		}
		if($needs_flipper && $flipper === 0) {
			//if($this->tiling_schemes[$this->tiling_scheme][3]) {
				$height = (-1 * (1 - (1 / ($this->tile_sides / 3))) * $image_height) + tiles::get_image_height($this->tiling_schemes[$this->tiling_scheme][1][0]) / 2; // ??
			//}
			$flipper = 1;
		} else {
			//if($this->tiling_schemes[$this->tiling_scheme][2]) {
				$height = tiles::get_image_height($this->tiling_schemes[$this->tiling_scheme][1][0]) / 2;
			//} else {
			//	$height = 0;
			//}
			$flipper = 0;
			$y++;
		}
		// first index is 0
		//$this->tiles[] = $tiles_column;
		$x++;
	}
	print('</div>
');
	print('</body>
</html>
');
	return $this->tiles;
}

function get_image_width($image_filename) {
	if(isset($this->image_dimensions[$image_filename]['width'])) {
		return $this->image_dimensions[$image_filename]['width'];
	}
	$data = getimagesize($image_filename);
	$image_width = $data[0];
	$this->image_dimensions[$image_filename]['width'] = $image_width;
	return $image_width;
}

function get_image_height($image_filename) {
	if(isset($this->image_dimensions[$image_filename]['height'])) {
		return $this->image_dimensions[$image_filename]['height'];
	}
	$data = getimagesize($image_filename);
	$image_height = $data[1];
	$this->image_dimensions[$image_filename]['height'] = $image_height;
	return $image_height;
}

function offset_image_width_to_center($tile_image, $unit_image) {
	$tile_data = getimagesize($tile_image);
	$tile_image_width = $tile_data[0];
	$unit_data = getimagesize($unit_image);
	$unit_image_width = $unit_data[0];
	return ($tile_image_width - $unit_image_width) / 2;
}

function offset_image_height_to_center($tile_image, $unit_image) {
	$tile_data = getimagesize($tile_image);
	$tile_image_height = $tile_data[1];
	$unit_data = getimagesize($unit_image);
	$unit_image_height = $unit_data[1];
	return ($tile_image_height - $unit_image_height) / 2;
}

function get_rotated_image_url($filename, $angle = 180) {
	//if(file_exists(str_replace('.png', '_rotated.png', $filename))) {
	//	
	//} else {
	//	tiles::rotate_image($filename, $angle);
	//}
	return str_replace('.png', '_rotated.png', $filename);
}

function rotate_image($filename, $angle = 180) {
    $source = imagecreatefrompng($filename) or die('Error opening file ' . $filename);
    imagealphablending($source, false);
    imagesavealpha($source, true);
    $rotation = imagerotate($source, $angle, imageColorAllocateAlpha($source, 0, 0, 0, 127));
    imagealphablending($rotation, false);
    imagesavealpha($rotation, true);

    header('Content-type: image/png');
    //imagepng($rotation);
	file_put_contents(str_replace('.png', '_rotated.png', $filename), $rotation);
    imagedestroy($source);
    imagedestroy($rotation);
}

}

?>