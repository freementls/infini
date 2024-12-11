<?php

class i { // it might be useful to think of units as game pieces

function __construct($tile_sides = false, $tiling_scheme = false, $tiling_schemes = false, $tiles = false) {
	//$this->units = array();
//	$this->units = new O('infini_units.xml');
	$this->tile_sides = $tile_sides;
	$this->tiling_scheme = $tiling_scheme;
	$this->tiling_schemes = $tiling_schemes;
	$this->tiles = $tiles;
	$this->animation_speed = 'slow';
	$this->team_sides = array(
	'images/flag_48_red.png',
	'images/flag_48_green.png',
	'images/flag_48_blue.png',
	'images/flag_48_orange.png',
	'images/flag_48_purple.png',
	'images/flag_48_teal.png',
	'images/flag_48_yellow.png',
	'images/flag_48_black.png',
	);
	// animation speed: (https://www.w3schools.com/jquery/eff_animate.asp) Optional. Specifies the speed of the animation. Default value is 400 milliseconds. Possible values: milliseconds (like 100, 1000, 5000, etc) "slow" "fast"
//function __construct() {
	//print('$game, $tiles, $this->tiles, $game->tiles, parent, get_parent_class(), parent::tiles, parent::tiles::tile_sides, parent::tiles, parent::tiles->tile_sides: ');var_dump($game, $tiles, $game->tiles, parent, get_parent_class(), parent::tiles, parent::tiles::tile_sides, parent::tiles, parent::tiles->tile_sides);exit(0);
	//print('$game, $tiles, $this->tiles, $game->tiles: ');var_dump($game, $tiles, $this->tiles, $game->tiles);exit(0);
}
	
function move_one_tile($unit_id, $direction, $print_script_tags = true) {
	// directions are defined in order: up is 1 then incrementing directions are clockwise
	// how to handle diagonals (for square specifically, although conceivably for more exotic tiling patterns)
	// can't assume regular polygons so can't assume the angles determining the x and y components
	$image_width = tiles::get_image_width($this->tiling_schemes[$this->tiling_scheme][1][0]);
	$image_height = tiles::get_image_height($this->tiling_schemes[$this->tiling_scheme][1][0]);
	$hypoteneuse = sqrt(pow($image_width, 2) + pow($image_height, 2));

	if($print_script_tags) {
		print('<script>
');
	}
	if($this->tile_sides === 3) {
		game::fatal_error('triangle moves probably don\'t work');
		// triangle is less perfect than square since 3 different symmetry slides allowed
		// the direction ordering is thus questionable
		if(is_string($direction)) { // have to determine which direction number is being referred to
			if($direction === 'n') {
				$direction = 1;
			} elseif($direction === 'ne') {
				$direction = 2;
			} elseif($direction === 'se') {
				$direction = 3;
			} elseif($direction === 's') {
				$direction = 4;
			} elseif($direction === 'sw') {
				$direction = 5;
			} elseif($direction === 'nw') {
				$direction = 6;
			} else {
				game::fatal_error('unhandled direction string (' . $direction . ') in move_one_tile');
			}
		}
		// have to account for the triangle being flipped somewhere for all these non-obvious directions
		if($direction === 1) {
			$x_component = 0;
			$y_component = -1;
			units::update_state($unit_id, 0, -1);
		} elseif($direction === 2) {
			//$x_component = cos(deg2rad(30));
			//$y_component = sin(deg2rad(30));
			$x_component = 0.75 * ($image_width / $image_height);
			$y_component = -0.5 * ($image_height / $image_width);
			if($this->units->_('x', '.unit_id=' . $unit_id) % 2 === 0) {
				units::update_state($unit_id, 1, 0);
			} else {
				units::update_state($unit_id, 1, -1);
			}
		} elseif($direction === 3) {
			//$x_component = cos(deg2rad(330));
			//$y_component = sin(deg2rad(330));
			$x_component = 0.75 * ($image_width / $image_height);
			$y_component = 0.5 * ($image_height / $image_width);
			if($this->units->_('x', '.unit_id=' . $unit_id) % 2 === 0) {
				units::update_state($unit_id, 1, 1);
			} else {
				units::update_state($unit_id, 1, 0);
			}
		} elseif($direction === 4) {
			$x_component = 0;
			$y_component = 1;
			units::update_state($unit_id, 0, 1);
		} elseif($direction === 5) {
			//$x_component = cos(deg2rad(210));
			//$y_component = sin(deg2rad(210));
			$x_component = -0.75 * ($image_width / $image_height);
			$y_component = 0.5 * ($image_height / $image_width);
			if($this->units->_('x', '.unit_id=' . $unit_id) % 2 === 0) {
				units::update_state($unit_id, -1, 1);
			} else {
				units::update_state($unit_id, -1, 0);
			}
		} elseif($direction === 6) {
			//$x_component = cos(deg2rad(150));
			//$y_component = sin(deg2rad(150));
			$x_component = -0.75 * ($image_width / $image_height);
			$y_component = -0.5 * ($image_height / $image_width);
			if($this->units->_('x', '.unit_id=' . $unit_id) % 2 === 0) {
				units::update_state($unit_id, -1, 0);
			} else {
				units::update_state($unit_id, -1, -1);
			}
		} else {
			game::fatal_error('unhandled direction (' . $direction . ') in move_one_tile');
		}
	} elseif($this->tile_sides === 4) {
		// square is less perfect than hexagon if diagonals are allowed (2 different symmetry slides allowed)
		if(is_string($direction)) { // have to determine which direction number is being referred to
			if($direction === 'n') {
				$direction = 1;
			} elseif($direction === 'e') {
				$direction = 2;
			} elseif($direction === 's') {
				$direction = 3;
			} elseif($direction === 'w') {
				$direction = 4;
			} elseif($direction === 'ne') { // diagonal
				$direction = 5;
			} elseif($direction === 'se') { // diagonal
				$direction = 6;
			} elseif($direction === 'sw') { // diagonal
				$direction = 7;
			} elseif($direction === 'nw') { // diagonal
				$direction = 8;
			} else {
				game::fatal_error('unhandled direction string (' . $direction . ') in move_one_tile');
			}
		}
		if($direction === 1) {
			$x_component = 0;
			$y_component = 1;
			units::update_state($unit_id, 0, 1);
		} elseif($direction === 2) {
			$x_component = 1;
			$y_component = 0;
			units::update_state($unit_id, 1, 0);
		} elseif($direction === 3) {
			$x_component = 0;
			$y_component = -1;
			units::update_state($unit_id, 0, -1);
		} elseif($direction === 4) {
			$x_component = -1;
			$y_component = 0;
			units::update_state($unit_id, -1, 0);
		} elseif($direction === 5) {
			//$x_component = cos(deg2rad(45));
			//$y_component = sin(deg2rad(45));
			$x_component = $image_width / $hypoteneuse;
			$y_component = $image_height / $hypoteneuse;
			units::update_state($unit_id, 1, -1);
		} elseif($direction === 6) {
			$x_component = $image_width / $hypoteneuse;
			$y_component = -1 * $image_height / $hypoteneuse;
			units::update_state($unit_id, 1, 1);
		} elseif($direction === 7) {
			$x_component = -1 * $image_width / $hypoteneuse;
			$y_component = -1 * $image_height / $hypoteneuse;
			units::update_state($unit_id, -1, 1);
		} elseif($direction === 8) {
			$x_component = -1 * $image_width / $hypoteneuse;
			$y_component = $image_height / $hypoteneuse;
			units::update_state($unit_id, -1, -1);
		} else {
			game::fatal_error('unhandled direction (' . $direction . ') in move_one_tile');
		}
	} elseif($this->tile_sides === 6) {
		// 1 symmetry slide allowed
		if(is_string($direction)) { // have to determine which direction number is being referred to
			if($direction === 'n') {
				$direction = 1;
			} elseif($direction === 'ne') {
				$direction = 2;
			} elseif($direction === 'se') {
				$direction = 3;
			} elseif($direction === 's') {
				$direction = 4;
			} elseif($direction === 'sw') {
				$direction = 5;
			} elseif($direction === 'nw') {
				$direction = 6;
			} else {
				game::fatal_error('unhandled direction string (' . $direction . ') in move_one_tile');
			}
		}
		if($direction === 1) {
			$x_component = 0;
			$y_component = -1;
			units::update_state($unit_id, 0, -1);
		} elseif($direction === 2) {
			//$x_component = cos(deg2rad(30));
			//$y_component = sin(deg2rad(30));
			$x_component = 0.75 * ($image_width / $image_height);
			$y_component = -0.5 * ($image_height / $image_width);
			if($this->units->_('x', '.unit_id=' . $unit_id) % 2 === 0) {
				units::update_state($unit_id, 1, 0);
			} else {
				units::update_state($unit_id, 1, -1);
			}
		} elseif($direction === 3) {
			//$x_component = cos(deg2rad(330));
			//$y_component = sin(deg2rad(330));
			$x_component = 0.75 * ($image_width / $image_height);
			$y_component = 0.5 * ($image_height / $image_width);
			if($this->units->_('x', '.unit_id=' . $unit_id) % 2 === 0) {
				units::update_state($unit_id, 1, 1);
			} else {
				units::update_state($unit_id, 1, 0);
			}
		} elseif($direction === 4) {
			$x_component = 0;
			$y_component = 1;
			units::update_state($unit_id, 0, 1);
		} elseif($direction === 5) {
			//$x_component = cos(deg2rad(210));
			//$y_component = sin(deg2rad(210));
			$x_component = -0.75 * ($image_width / $image_height);
			$y_component = 0.5 * ($image_height / $image_width);
			if($this->units->_('x', '.unit_id=' . $unit_id) % 2 === 0) {
				units::update_state($unit_id, -1, 1);
			} else {
				units::update_state($unit_id, -1, 0);
			}
		} elseif($direction === 6) {
			//$x_component = cos(deg2rad(150));
			//$y_component = sin(deg2rad(150));
			$x_component = -0.75 * ($image_width / $image_height);
			$y_component = -0.5 * ($image_height / $image_width);
			if($this->units->_('x', '.unit_id=' . $unit_id) % 2 === 0) {
				units::update_state($unit_id, -1, 0);
			} else {
				units::update_state($unit_id, -1, -1);
			}
		} else {
			game::fatal_error('unhandled direction (' . $direction . ') in move_one_tile');
		}
	} else {
		game::fatal_error('unhandled number of tile_sides (' . $this->tile_sides . ') in move_one_tile');
	}
	//$("#captain").animate({left: '+=54px',top: '+=36px'}, "slow");
	$left_pixels = $image_width * $x_component;
	
	$inner_angle = (180 * ($this->tile_sides - 2)) / $this->tile_sides;
	$width_step = (1 - (abs(cos(deg2rad($inner_angle))) / ($this->tiling_schemes[$this->tiling_scheme][0] / 3))) * $image_width;
	//print('cos(deg2rad($this->tiling_schemes[$this->tiling_scheme][1])), $this->tiling_schemes[$this->tiling_scheme][1], $this->tiling_schemes[$this->tiling_scheme][0], $image_width: ');var_dump(cos(deg2rad($this->tiling_schemes[$this->tiling_scheme][1])), $this->tiling_schemes[$this->tiling_scheme][1], $this->tiling_schemes[$this->tiling_scheme][0], $image_width);
	//print('$width_step: ');var_dump($width_step);exit(0);
	$width += $width_step;
	
	if($left_pixels < 0) {
		$left_pixels_string = '-=' . abs($left_pixels) . 'px';
	} else {
		$left_pixels_string = '+=' . $left_pixels . 'px';
	}
	$top_pixels = $image_height * $y_component;
	if($top_pixels < 0) {
		//$top_pixels_string = '+=' . abs($top_pixels) . 'px';
		$top_pixels_string = '-=' . abs($top_pixels) . 'px';
	} else {
		//$top_pixels_string = '-=' . $top_pixels . 'px';
		$top_pixels_string = '+=' . $top_pixels . 'px';
	}
	//print('$left_pixels, $top_pixels: ');var_dump($left_pixels, $top_pixels);
	print('$("#' . $unit_id . '").animate({left: \'' . $left_pixels_string . '\',top: \'' . $top_pixels_string . '\'}, "' . $this->animation_speed . '");
');
	if($print_script_tags) {
		print('</script>
');
	}
}

function move_on_path($unit_id, $path, $print_script_tags = true) {
	if($print_script_tags) {
		print('<script>
');
	}
	$path_points = explode(',', $path);
	foreach($path_points as $path_point) {
		units::move_one_tile($unit_id, $path_point, false);
	}
	if($print_script_tags) {
		print('</script>
');
	}
}

function move_to($unit_id, $destination_x, $destination_y, $print_script_tags = true) {
	// here the path has to be generated instead of supplied
	// this becomes more complicated when varying symmetry slides are allowed or tiles have different weighting associated with moving to them
	if($print_script_tags) {
		print('<script>
');
	}
	// need to take into account irregular polygons
	// also what about allowing diagonals and not allowing diagonals for quadrangle
	$image_height = tiles::get_image_height($this->tiling_schemes[$this->tiling_scheme][1][0]);
	$image_width = tiles::get_image_width($this->tiling_schemes[$this->tiling_scheme][1][0]);
	if($image_height !== $image_width) {
		game::fatal_error('irregular polygons not yet coded for in move_to');
	}
	// extremely dumb
	//$debug_counter = 0;
	$x_difference = $destination_x - $this->units->_('x', '.unit_id=' . $unit_id);
	if($x_difference % 2 === 0) {
		$y_difference = $destination_y - $this->units->_('y', '.unit_id=' . $unit_id);
	} else {
		if($this->units->_('x', '.unit_id=' . $unit_id) % 2 === 0) {
			$y_difference = $destination_y - $this->units->_('y', '.unit_id=' . $unit_id) - 0.5;
		} else {
			$y_difference = $destination_y - $this->units->_('y', '.unit_id=' . $unit_id) + 0.5;
		}
	}
	$hypoteneuse = sqrt(pow($x_difference, 2) + pow($y_difference, 2));
	while($x_difference !== 0 || $y_difference !== 0) {
		$x_component = $x_difference / $hypoteneuse;
		if($this->tiling_schemes[$this->tiling_scheme][2] === true) {
			$y_component = $y_difference / $hypoteneuse / 2;
		} else {
			$y_component = $y_difference / $hypoteneuse;
		}
		$arccos = acos($x_component);
		//print('/*$hypoteneuse: ' . $hypoteneuse . ', $x_component: ' . $x_component . ', $y_component: ' . $y_component . ', $arccos: ' . $arccos . '.<br>*/');
		if($this->tile_sides === 3) {
			game::fatal_error('triangle moves probably don\'t work in move_to');
			if($y_component >= 0) {
				if($arccos >= 0 && $arccos < pi() / 3) {
					$path_point = 'se';
				} elseif($arccos >= pi() / 3 && $arccos < 2 * pi() / 3) {
					$path_point = 's';
				} else {
					$path_point = 'sw';
				}
			} else {
				if($arccos >= 0 && $arccos < pi() / 3) {
					$path_point = 'ne';
				} elseif($arccos >= pi() / 3 && $arccos < 2 * pi() / 3) {
					$path_point = 'n';
				} else {
					$path_point = 'nw';
				}
			}
		} elseif($this->tile_sides === 4) {
			if($y_component >= 0) {
				if($arccos >= 0 && $arccos < pi() / 8) {
					$path_point = 'e';
				} elseif($arccos >= pi() / 8 && $arccos < 3 * pi() / 8) {
					$path_point = 'ne';
				} elseif($arccos >= 3 * pi() / 8 && $arccos < 5 * pi() / 8) {
					$path_point = 'n';
				} else {
					$path_point = 'nw';
				}
			} else {
				if($arccos >= 0 && $arccos < pi() / 8) {
					$path_point = 'e';
				} elseif($arccos >= pi() / 8 && $arccos < 3 * pi() / 8) {
					$path_point = 'se';
				} elseif($arccos >= 3 * pi() / 8 && $arccos < 5 * pi() / 8) {
					$path_point = 's';
				} else {
					$path_point = 'sw';
				}
			}
		} elseif($this->tile_sides === 6) {
			if($y_component >= 0) {
				if($arccos >= 0 && $arccos < pi() / 3) {
					$path_point = 'se';
				} elseif($arccos >= pi() / 3 && $arccos < 2 * pi() / 3) {
					$path_point = 's';
				} else {
					$path_point = 'sw';
				}
			} else {
				if($arccos >= 0 && $arccos < pi() / 3) {
					$path_point = 'ne';
				} elseif($arccos >= pi() / 3 && $arccos < 2 * pi() / 3) {
					$path_point = 'n';
				} else {
					$path_point = 'nw';
				}
			}
		} else {
			game::fatal_error('unhandled number of $this->tile_sides (' . $this->tile_sides . ') in move_to');
		}
		//print('/*moving $unit_id: ' . $unit_id . ' on $path_point: ' . $path_point . '.<br>*/');
		//print('/*$current_y: ' . $current_y . ' before moving.<br>*/');
		units::move_one_tile($unit_id, $path_point, false);
		//print('/*$current_y: ' . $current_y . ' after moving.<br>*/');
		$x_difference = $destination_x - $this->units->_('x', '.unit_id=' . $unit_id);
		if($x_difference % 2 === 0) {
			$y_difference = $destination_y - $this->units->_('y', '.unit_id=' . $unit_id);
		} else {
			if($this->units->_('x', '.unit_id=' . $unit_id) % 2 === 0) {
				$y_difference = $destination_y - $this->units->_('y', '.unit_id=' . $unit_id) - 0.5;
			} else {
				$y_difference = $destination_y - $this->units->_('y', '.unit_id=' . $unit_id) + 0.5;
			}
		}
		$hypoteneuse = sqrt(pow($x_difference, 2) + pow($y_difference, 2));
		//$debug_counter++;
		//if($debug_counter > 2) {
		//	break;
		//}
	}
	if($print_script_tags) {
		print('</script>
');
	}
}

function update_state($unit_id, $x_change, $y_change) {
	units::remove_unit($unit_id, $this->units->_('x', $this->units->_('.unit_id=' . $unit_id)), $this->units->_('y', $this->units->_('.unit_id=' . $unit_id)));
	$this->units->add($x_change, 'x', $this->units->_('.unit_id=' . $unit_id));
	$this->units->add($y_change, 'y', $this->units->_('.unit_id=' . $unit_id));
	units::place_unit($unit_id, $this->units->_('x', $this->units->_('.unit_id=' . $unit_id)), $this->units->_('y', $this->units->_('.unit_id=' . $unit_id)));
}

function remove_unit($unit_id, $x, $y) {
	$this->tiles->delete('unitid=' . $unit_id, $this->tiles->_('.tile_x=' . $x . '&y=' . $y));
}

function place_unit($unit_id, $x, $y) {
	$this->tiles->new_('<unitid>' . $unit_id . '</unitid>', $this->tiles->_('.tile_x=' . $x . '&y=' . $y));
}

function new_unit($unit_id, $image_path, $x, $y, $side) {
	// other stats: name, hp, attack, etc. would be better to pass all the stats as an array
	$top_pixels = $this->tiles->_('toppixels', $this->tiles->_('.tile_x=' . $x . '&y=' . $y));
	$left_pixels = $this->tiles->_('leftpixels', $this->tiles->_('.tile_x=' . $x . '&y=' . $y));
	$top_pixels = $this->tiles->add(tiles::offset_image_height_to_center($this->tiling_schemes[$this->tiling_scheme][1][0], $this->team_sides[$side]), $top_pixels);
	$left_pixels = $this->tiles->add(tiles::offset_image_width_to_center($this->tiling_schemes[$this->tiling_scheme][1][0], $this->team_sides[$side]), $left_pixels);
	$top_pixels_string = $top_pixels . 'px';
	$left_pixels_string = $left_pixels . 'px';
	$flag_top_pixels = $this->tiles->add(tiles::offset_image_height_to_center($this->tiling_schemes[$this->tiling_scheme][1][0], $this->team_sides[$side]), 'toppixels', $this->tiles->_('.tile_x=' . $x . '&y=' . $y));
	$flag_left_pixels = $this->tiles->add(tiles::offset_image_width_to_center($this->tiling_schemes[$this->tiling_scheme][1][0], $this->team_sides[$side]), 'leftpixels', $this->tiles->_('.tile_x=' . $x . '&y=' . $y));
	$flag_top_pixels_string = $top_pixels . 'px';
	$flag_left_pixels_string = $left_pixels . 'px';
	$unit_array = array('id' => $unit_id, 'image_path' => $image_path, 'x' => $x, 'y' => $y, 'side' => $side);
	if($this->units->_('.unit_id=' . $unit_id)) {
		game::fatal_error('trying to make a new unit but a unit with the provided id (' . $unit_id . ') already exists.');
	} else {
		$this->units->new_('<unit>
<id>' . $unit_id . '</id>
<imagepath>' . $image_path . '</imagepath>
<x>' . $x . '</x>
<y>' . $y . '</y>
<side>' . $side . '</side>
</unit>');
	}
	//if(isset($this->tiles[$x][$y][2]['units'])) {
		$this->tiles->new_('<unitid>' . $unit_id . '</unitid>', $this->tiles->_('.tile_x=' . $x . '&y=' . $y));
	//} else {
	//	$this->tiles[$x][$y][2]['units'] = $unit_array;
	//}
	print('<img id="' . $unit_id . '" src="' . $image_path . '" style="position: absolute; top: ' . $top_pixels_string . '; left: ' . $left_pixels_string . '; z-index: 1;" />
');
	if(strpos($unit_id, 'city') !== false) { // hack
		print('<img id="' . $unit_id . '_side_' . $side . '" src="' . $this->team_sides[$side] . '" style="position: absolute; top: ' . $flag_top_pixels_string . '; left: ' . $flag_left_pixels_string . '; z-index: 2;" />
');
	}
	//return $tiles;
}

function randomly_create_new_units($unit_id_base, $image_path, $side, $units_to_create, $parameters) {
	$units_created = 0;
	while($units_created < $units_to_create) {
		$x = rand(0, $this->tiles->get_playing_field_width() - 1);
		$y = rand(0, $this->tiles->get_playing_field_height() - 1);
		// check that the parameters are satisfied
		foreach($parameters as $parameter => $parameter_value) {
			if($parameter = 'no_contiguous' && $parameter_value === true) {
				// simple check that may not be good enough down the line...
				if($this->tile_sides === 3) {
					if($x % 2 === 0) {
						$array_contiguous_tiles = array(
						array($x - 1, $y),
						array($x - 1, $y + 1),
						array($x, $y - 1),
						array($x, $y),
						array($x, $y + 1),
						array($x + 1, $y),
						array($x + 1, $y + 1),
						);
					} else {
						$array_contiguous_tiles = array(
						array($x - 1, $y - 1),
						array($x - 1, $y),
						array($x, $y - 1),
						array($x, $y),
						array($x, $y + 1),
						array($x + 1, $y - 1),
						array($x + 1, $y),
						);
					}
				} elseif($this->tile_sides === 4) {
					$array_contiguous_tiles = array(
					array($x - 1, $y - 1),
					array($x - 1, $y),
					array($x - 1, $y + 1),
					array($x, $y - 1),
					array($x, $y),
					array($x, $y + 1),
					array($x + 1, $y - 1),
					array($x + 1, $y),
					array($x + 1, $y + 1),
					);
				} elseif($this->tile_sides === 6) {
					if($x % 2 === 0) {
						$array_contiguous_tiles = array(
						array($x - 1, $y),
						array($x - 1, $y + 1),
						array($x, $y - 1),
						array($x, $y),
						array($x, $y + 1),
						array($x + 1, $y),
						array($x + 1, $y + 1),
						);
					} else {
						$array_contiguous_tiles = array(
						array($x - 1, $y - 1),
						array($x - 1, $y),
						array($x, $y - 1),
						array($x, $y),
						array($x, $y + 1),
						array($x + 1, $y - 1),
						array($x + 1, $y),
						);
					}
				} else {
					game::fatal_error('$this->tile_sides (' . $this->tile_sides . ') not handled in randomly_create_new_units');
				}
				foreach($array_contiguous_tiles as $contiguous_tile) {
					$contiguous_x = $contiguous_tile[0];
					$contiguous_y = $contiguous_tile[1];
					if($this->tiles->_('unitid', $this->tiles->_('.tile_x=' . $contiguous_x . '&y=' . $contiguous_y))) {
						continue 3;
					}
				}
			}
		}
		if($side === 'random') {
			$team_side = rand(0, sizeof($this->team_sides) - 1);
		} else {
			$team_side = $side;
		}
		units::new_unit($unit_id_base . $this->units->_('idcounter'), $image_path, $x, $y, $team_side);
		$this->units->increment('idcounter');
		$units_created++;
	}
}

function idle_animation($unit_id, $image_string, $print_script_tags = true) {
	//image="units/red-sniper/red-archer-idle-[1~6,3~6,3~6,2,1].png:100"
	if($print_script_tags) {
		print('<script>
');
	}
	//preg_match_all('/[="\s,;]([^\s"=\/;\(\),]+?\/[^\s"=;\(\)]+?.png)/is', $image_string, $image_matches);
	preg_match_all('/[="\s,;]([^\s"=\/;\(\),]+?\/[^\s"=;\(\)]+?.png)(:[0-9]+){0,1}/is', $image_string, $image_matches);
	//print('debug0107');var_dump($Entry3, $image_matches);exit(0);
	$expanded_image_references = array();
	foreach($image_matches[0] as $index => $value) {
		$image_match = $image_matches[1][$index];
		$strpos_open_square = strpos($image_match, '[');
		if($strpos_open_square !== false) {
			//print('probably have to expand this short-hand for multiple images<br>');
			$squared_expression = substr($image_match, $strpos_open_square + 1, strpos($image_match, ']') - $strpos_open_square - 1);
			$squared_components = explode(',', $squared_expression);
			foreach($squared_components as $squared_component) {
				$asterisk_position = strpos($squared_component, '*');
				if($asterisk_position !== false) {
					$squared_component = substr($squared_component, 0, $asterisk_position);
				}
				$start_number = false;
				$end_number = false;
				$tilde_position = strpos($squared_component, '~');
				if($tilde_position !== false) {
					$start_number = substr($squared_component, 0, $tilde_position);
					$end_number = substr($squared_component, $tilde_position + 1);
				} else {
					$start_number = $end_number = $squared_component;
				}
				while($start_number <= $end_number) {
					$replaced_image_match = preg_replace('/\[[^\[\]]+\]/is', $start_number, $image_match);
					$expanded_image_references[$replaced_image_match] = true;
					$start_number++;
				}
			}
			//print('debug0108');var_dump($Entry3, $image_matches, $expanded_image_references);exit(0);
		} else {
			$expanded_image_references[$image_match] = true;
		}
		//if(strpos($image_match, 'attacks') === false && strpos($image_match, 'icons') === false && strpos($image_match, 'halo') === false && strpos($image_match, 'misc') === false && strpos($image_match, 'portrait') === false && strpos($image_match, '[') === false && strpos($image_match, ']') === false && strpos($image_match, '{') === false && strpos($image_match, '}') === false && !file_exists('C:\Games\Battle for Wesnoth 1.12.0 dev\userdata\data\add-ons\Shards_Era\images' . DIRECTORY_SEPARATOR . $image_match)) {
	}
	print('debug0109; $Entry3, $image_matches, $expanded_image_references: ');var_dump($Entry3, $image_matches, $expanded_image_references);exit(0);
	print('$("#' . $unit_id . '")
');
	foreach($expanded_image_references as $expanded_image_reference => $expanded_image_reference_true) {
		$images_referenced[$expanded_image_reference] = true;
		if(strpos($expanded_image_reference, '{') === false && strpos($expanded_image_reference, '}') === false) {
			if(!file_exists('C:\Games\Battle for Wesnoth 1.12.0 dev\userdata\data\add-ons\Shards_Era\images' . DIRECTORY_SEPARATOR . $expanded_image_reference) && !file_exists('C:\Games\Battle for Wesnoth 1.12.0 dev\userdata\data\add-ons\Shards_Era_Resources\images' . DIRECTORY_SEPARATOR . $expanded_image_reference) && !file_exists('C:\Games\Battle for Wesnoth 1.12.0 dev\data\core\images' . DIRECTORY_SEPARATOR . $expanded_image_reference)) {
				print('<span style="color: red;">image ' . $expanded_image_reference . ' not found.</span>');var_dump_full($entry3, $contents3, 'C:\Games\Battle for Wesnoth 1.12.0 dev\userdata\data\add-ons\Shards_Era\images' . DIRECTORY_SEPARATOR . $expanded_image_reference, 'C:\Games\Battle for Wesnoth 1.12.0 dev\data\core\images' . DIRECTORY_SEPARATOR . $expanded_image_reference);exit(0);
				// fix it
			}
			if(file_exists('C:\Games\Battle for Wesnoth 1.12.0 dev\userdata\data\add-ons\Shards_Era\images\\' . $expanded_image_reference) && file_exists('C:\Games\Battle for Wesnoth 1.12.0 dev\data\core\images' . DIRECTORY_SEPARATOR . $expanded_image_reference)) {
				// redundant to the core image
				$expanded_image_reference = str_replace('/', '\\', $expanded_image_reference);
				$new_expanded_image_reference = str_replace_last('\\', '\other\\', 'C:\Games\Battle for Wesnoth 1.12.0 dev\userdata\data\add-ons\Shards_Era\images\\' . $expanded_image_reference);
				rename('C:\Games\Battle for Wesnoth 1.12.0 dev\userdata\data\add-ons\Shards_Era\images\\' . $expanded_image_reference, $new_expanded_image_reference);
				print('<span style="color: green;">' . 'C:\Games\Battle for Wesnoth 1.12.0 dev\userdata\data\add-ons\Shards_Era\images\\' . $expanded_image_reference . ' redundantly existed in the addon; it has been moved to ' . $new_expanded_image_reference . '</span><br>');
				//print('extraneous image');var_dump($expanded_image_reference);exit(0);
			}
		}
		
		print('.delay(' . $delay_in_milliseconds . ')
');
		print('.queue(function() {
$(this).attr(\'src\', \'' . $expanded_image_reference . '\').dequeue();
');
		print('})
');
	}
	print('.delay(' . $delay_in_milliseconds . ')
');
	if($print_script_tags) {
		print('</script>
');
	}
}

function get_units() {
	//print('$this->units: ');var_dump($this->units);
	return $this->units;
}

function get_tiles() {
	//print('$this->tiles: ');var_dump($this->tiles);
	return $this->tiles;
}

static function generate_full_name($parameters) {
	//print('$parameters in generate_full_name(): ');var_dump($parameters);
	if($parameters['race'] === 'any') {
		$possible_races = array('english', 'japanese', 'korean', 'dwarf', 'elf', 'orc'); // human excluded
		//$possible_races = array('korean'); // debug
		$parameters['race'] = $possible_races[rand(0, sizeof($possible_races) - 1)];
	}
	if($parameters['race'] === 'korean' || $parameters['race'] === 'dwarf' || $parameters['race'] === 'elf' || $parameters['race'] === 'orc') { // only generate a single first name
		$full_name = i::generate_name($parameters);
	} else { // first name and last name
		$full_name = i::generate_name($parameters) . ' ' . i::generate_name($parameters);
	}
	return $full_name;
}

static function generate_name($parameters) {
	//print('$parameters in generate_name(): ');var_dump($parameters);
	foreach($parameters as $parameter_index => $parameter) {
		$parameters[$parameter_index] = strtolower($parameter);
	}
	$name = '';
	//print('$parameters: ');var_dump($parameters);
	if($parameters['object'] === 'person' || !isset($parameters['object'])) {
		// races
		if($parameters['race'] === 'any') {
			$possible_races = array('english', 'arabic', 'chinese', 'japanese', 'korean', 'dwarf', 'elf', 'orc'); // human excluded
			//$possible_races = array('korean'); // debug
			$parameters['race'] = $possible_races[rand(0, sizeof($possible_races) - 1)];
		}
		if($parameters['race'] === 'english') {
			// http://web.archive.org/web/20160805180548/http://semarch.linguistics.fas.nyu.edu/barker/Syllables/index.txt
			$vowels = array('aa', 'ae', 'ah', 'ao', 'aw', 'ax', 'ay', 'ea', 'eh', 'er', 'ey', 'ia', 'ih', 'iy', 'oh', 'ow', 'oy', 'ua', 'uh', 'uw');
			$consonants = array('p', 'b', 't', 'd', 'f', 'v', 'th', 'dh', 's', 'z', 'sh', 'zh', 'ch', 'jh', 'k', 'ng', 'g', 'm', 'n', 'l', 'r', 'w', 'y', 'hh');
			// for simplicity, create syllables only as a consonant followed by a vowel, ignoring the possibility of more consonants after the vowel.
			$syllables = array();
			foreach($vowels as $vowel) {
				foreach($consonants as $consonant) {
					$syllables[] = $consonant . $vowel;
				}
			}
			//$number_of_syllables = rand(2, 4);
			$number_of_syllables = rand(2, 3);
		} elseif($parameters['race'] === 'arabic') {
			// https://www.loc.gov/catdir/cpso/romanization/arabic.pdf
			$vowels = array('ī', 'aw', 'ay',);
			$consonants = array(	'b', 't', 'th', 'j', 'ḥ', 'kh', 'd', 'dh', 'r', 'z', 's', 'sh', 'ṣ', 'ḍ', 'ṭ', 'ẓ', '‘', 'gh', 'f', 'q', 'k', 'l', 'm', 'n', 'h', 'w', 'y',);
			// for simplicity, create syllables only as a consonant followed by a vowel, ignoring the possibility of more consonants after the vowel.
			$syllables = array();
			foreach($vowels as $vowel) {
				foreach($consonants as $consonant) {
					$syllables[] = $consonant . $vowel;
				}
			}
			$number_of_syllables = 3;
		} elseif($parameters['race'] === 'chinese') {
			// https://en.wikipedia.org/wiki/Bopomofo
			$syllables = array('a',	'o',	'ê',	'e',	'ai',	'ei',	'ao',	'ou',	'an',	'en',	'ang',	'eng',	'ong',	'er',
				'eh',	'ên',	'êng',	'ung',	'êrh',
				'yi',	'ye',	'you',	'yan',	'yin',	'ying',	'yong',	'wu',	'wo',	'wei',	'wen',	'weng',	'yu',	'yue',	'yuan',	'yun',
				'wun',	'wong',
				'i',	'yeh',	'yen',	'yung',	'wên',	'wêng',	'yü',	'yüeh',	'yüan',	'yün',
				'b',	'p',	'm',	'feng',	'diu',	'dui',	'dun',	'te',	'nü',	'lü',	'ge',	'ke',	'he',
				'fong',	'diou',	'duei',	'nyu',	'lyu',
				'pʻ',	'fêng',	'tiu',	'tui',	'tun',	'tʻê',	'ko',	'kʻo',	'ho',
				'jian',	'jiong',	'qin',	'xuan',	'zhe',	'zhi',	'che',	'chi',	'she',	'shi',	're',	'ri',	'ze',	'zuo',	'zi',	'ce',	'ci',	'se',	'si',
				'jyong',	'cin',	'syuan',	'jhe',	'jhih',	'chih',	'shih',	'rih',	'zih',	'cih',	'sih',
				'chien',	'chiung',	'chʻin',	'hsüan',	'chê',	'chʻê',	'chʻih',	'shê',	'jê',	'jih',	'tsê',	'tso',	'tzŭ',	'tsʻê',	'tzʻŭ',	'sê',	'ssŭ',
				'mā',	'má',	'mǎ',	'mà',	'ma',
				'må',
				);
			$number_of_syllables = rand(1, 2);
		} elseif($parameters['race'] === 'japanese') {
			//$syllables = array('shin', 'ji', 'ko', 'no', 'yu', 'ki', 'mu', 'sa', 'shi', 'ya', 'ma', 'ta', 'na', 'ka', 'hi', 'ro', 'han', 'zou', 'chi', 'do', 'ri', 'la');
			// http://www.linguanaut.com/japanese_alphabet.htm
			$syllables = array(
			'a', 'ka', 'sa', 'ta', 'na',
			'i', 'ki', 'shi', 'chi', 'ni',
			'u', 'ku', 'su', 'tsu', 'nu',
			'e', 'ke', 'se', 'te', 'ne',
			'o', 'ko', 'so', 'to', 'no',
			'ha', 'ma', 'ya', 'ra', 'wa',
			'hi', 'mi', 'ri', 'wi',
			'fu', 'mu', 'yu', 'ru', 'n',
			'he', 'me', 're',
			'ho', 'mo', 'yo', 'ro', 'wo',
			'ga', 'za', 'da', 'ba', 'pa',
			'gi', 'ji',       'bi', 'pi',
			'gu', 'zu',       'bu', 'pu',
			'ge', 'ze', 'de', 'be', 'pe',
			'go', 'zo', 'do', 'bo', 'po',
			'kya', 'sha', 'cha', 'hya', 'pya',
			'kyu', 'shu', 'chu', 'hyu', 'pyu',
			'kyo', 'sho', 'cho', 'hyo', 'pyo',
			'gya', 'ja', 'nya', 'bya', 'mya',
			       'ju', 'nyu', 'byu', 'my',
			'gyo', 'jo', 'nyo', 'byo', 'myo',
			'rya', 'ryu',
			'ye', 'va', 'she',
			      'vi', 'je',
			'we', 'vu', 've', 'che',

			'vo', 'vya',
			'ti', 'tsa', 'fa',
			'tu', 'tsi', 'fi',
			'tyu', 'tse', 'fe',
			'di', 'tso', 'fo',
			'du', 'fyu',
			'dyu',
			);
			$number_of_syllables = rand(2, 4);
		} elseif($parameters['race'] === 'korean') {
			//$syllables = array('jang ', 'hyun ', 'woo ', 'kim ', 'yoo ', 'jin ', 'cho ', 'sung ', 'ho ', 'han ', 'lee ', 'seok ', 'jae ', 'sun ', 'doh ', 'wook ', 'jung ', 'ji ', 'hoon ', 'choo ', 'hyuk ', 'dong ', 'nyoung ', 'byung ', 'ryul ', 'kyung ', 'baek ', 'jun ', 'hyun ', 'il ', 'ki ', 'shin ', 'hwang ', 'guk ', 'hyeon ', 'han ', 'ji ', 'won ', 'park ', 'chang ', 'gon ', 'kang ', 'eo ', 'su ');
			// http://www.linguanaut.com/korean_alphabet.htm
			$vowels = array('a', 'augh', 'ee', 'o', 'oo', 'u', 'ay', 'wa', 'wo', 'we', 'way', 'uee', 'ya', 'yu', 'yo', 'you', 'yea', 'ye');
			$consonants = array('g', 'n', 't', 'r', 'm', 'b', 's', 'shi', 'ng', 'j', 'ch', 'k', 'p', 'h', 'kk', 'tt', 'pp', 'ss', 'tch');
			// for simplicity, create syllables only as a consonant followed by a vowel, ignoring the possibility of more consonants after the vowel.
			$syllables = array();
			foreach($vowels as $vowel) {
				foreach($consonants as $consonant) {
					$syllables[] = $consonant . $vowel;
				}
			}
			$number_of_syllables = 3;
		} elseif($parameters['race'] === 'dwarf') {
			$syllables = array('gim', 'li', 'gam', 'glo', 'in', 'tho', 'rin', 'ger', 'min', 'gul', 'garn', 'gal', 'dram', 'gran', 'rak', 'grom', 'ber', 'nyl', 'thel', 'gril', 'em', 'hur', 'thal', 'ban', 'dal', 'thy', 'mond', 'um', 'rim', 'bel', 'rig', 'mor', 'daer');
			$number_of_syllables = 2;
		} elseif($parameters['race'] === 'elf') {
			$syllables = array('el', 'o', 'im', 'di', 'ril', 'sal', 'ka', 'vil', 'ya', 'alu', 'glyn', 'dae', 'lim', 'maer', 'syl', 'fa', 'ren', 'rin', 'min', 'per', 'vir', 'sar', 'ran', 'nel', 'lu', 'ner', 'bi', 'fae', 'sha', 'cai', 'yn', 'da', 'u', 'na');
			$number_of_syllables = rand(3, 5);
			if($parameters['sex'] === 'male') {
				$sex_suffix = 'o';
			} else {
				if(rand(1, 2) === 1) {
					$sex_suffix = 'a';
				} else {
					$sex_suffix = 'e';
				}
			}
		} elseif($parameters['race'] === 'orc') {
			$syllables = array('drokk', 'jakk', 'brakk', 'mog', 'gro', 'dukk', 'dhak', 'zal', 'vug', 'drar', 'la', 'ruk', 'gul', 'zakk', 'dogg', 'donn', 'drun', 'drod', 'nug', 'zod', 'zok', 'zun', 'zab', 'zag', 'eye', 'rhul', 'grod', 'gor', 'trub', 'jod', 'ar', 'zogg', 'gram', 'mu', 'rud');
			if($parameters['sex'] === 'male') {
				$sex_suffix = 'a';
			} else {
				$sex_suffix = 'o';
			}
			$number_of_syllables = rand(1, 3);
		} elseif($parameters['race'] === 'human') {
			i::fatal_error('syllables not set for human');
			if($parameters['sex'] === 'male') {
				$sex_suffix = 'o';
			} else {
				$sex_suffix = 'a';
			}
			$number_of_syllables = rand(1, 5);
		} else {
			i::fatal_error('unknown race: ' . $parameters['race']);
		}
		// debug
		if(sizeof($syllables) !== sizeof(array_unique($syllables))) {
			i::warning('duplicates detected in $syllables: ');i::var_dump_full($syllables, array_unique($syllables));
		}
		$syllables_counter = 0;
		while($syllables_counter < $number_of_syllables) {
			$name .= $syllables[rand(0, sizeof($syllables) - 1)];
			$syllables_counter++;
		}
		//print('$name: ');var_dump($name);
		// sex
		if(strlen($sex_suffix) > 0) {
			$last_letter = $name[strlen($name) - 1];
			if($last_letter === 'a' || $last_letter === 'e' || $last_letter === 'i' || $last_letter === 'o' || $last_letter === 'u') {
				$name[strlen($name) - 1] = $sex_suffix;
			} else {
				$name .= $sex_suffix;
			}
		}
	} elseif($parameters['object'] === 'city' || $parameters['object'] === 'town' || $parameters['object'] === 'village' || $parameters['object'] === 'region' || $parameters['object'] === 'area') {
		$prefixes = array('north', 'east', 'south', 'west', 'upper', 'lower', 'little', 'big', 'clear', 'high', 'low', 'long', 'king\'s', 'queen\'s', 'new', 'old', 'santa', 'saint', 'calm', 'green');
		$city_name = i::generate_name(array('object' => 'person', 'race' => 'human')); // a little recursion
		$suffixes = array('ville', 'town', 'ton', 'shore', 'brook', 'river', 'beach', 'burg', 'mont', 'hill', 'rise', 'dale', 'lee', 'bridge', 'glen', 'mont', 'vale', 'port');
		if(rand(1, 2) === 1) {
			$name = $prefixes[rand(0, sizeof($prefixes) - 1)] . ' ';
		}
		$name .= $city_name;
		if(rand(1, 2) === 1) {
			$name .= $suffixes[rand(0, sizeof($suffixes) - 1)];
		}
	}
	// capitalize first letter; how to capitalize names like korean with three pieces?
	$name[0] = strtoupper($name[0]);
	$name = trim($name);
	return $name;
}

static function roll($probabilities_array) {	
	$probabilities_sum = 0;
	foreach($probabilities_array as $rolled_item => $probability) {
		$probabilities_sum += $probability;
	}
	$higher_order_of_magnitude = 1;
	while($probabilities_sum > $higher_order_of_magnitude / 100) {
		$higher_order_of_magnitude *= 10;
	}
	$rand = rand(0, $higher_order_of_magnitude);
	$sum = 0;
	foreach($probabilities_array as $rolled_item => $probability) {
		if(($rand / $higher_order_of_magnitude) <= ($sum + $probability) / $probabilities_sum) {
			break;
		}
		$sum += $probability;
	}
	return $rolled_item;
}

function infinidate($total_seconds) {
	$date_string = '';
	/* $double_digit_minutes = false;
	$double_digit_seconds = false;
	if($seconds >= 3600) {
		$hours = $seconds % 3600;
		$date_string .= $hours;
		$seconds -= $hours * 3600;
		$double_digit_minutes = true;
		$double_digit_seconds = true;
	}
	if($seconds >= 60) {
		$minutes = $seconds % 60;
		print('$minutes in infinidate: ');var_dump($minutes);
		if($double_digit_minutes && strlen($minutes) === 1) {
			$minutes = '0' . $minutes;
		}
		$date_string .= $minutes;
		$seconds -= $minutes * 60;
		$double_digit_seconds = true;
	}
	if($double_digit_seconds && strlen($seconds) === 1) {
		$seconds = '0' . $seconds;
	}
	$date_string .= $seconds; */
	$total_seconds = (int)$total_seconds;
	$seconds = $total_seconds % 60;
	$total_seconds -= $seconds;
	if(strlen($seconds) < 2) {
		$seconds = '0' . $seconds;
	}
	$minutes = ($total_seconds % 3600) / 60;
	$total_seconds -= $minutes * 60;
	$hours = $total_seconds / 3600;
	if($hours > 0) {
		if(strlen($minutes) < 2) {
			$minutes = '0' . $minutes;
		}
		$date_string = $hours . ':' . $minutes . ':' . $seconds;
	} else {
		$date_string = $minutes . ':' . $seconds;
	}
	$total_seconds -= $hours * 3600;
	if($total_seconds > 0) {
		print('$total_seconds, $date_string: ');var_dump($total_seconds, $date_string);
		i::fatal_error('error in infinidate');
	}
	return $date_string;
}

function infiniclock($total_seconds) {
	//print('$total_seconds at start of infiniclock: ');var_dump($total_seconds);
	if(is_array($total_seconds)) {
		print('$total_seconds: ');var_dump($total_seconds);
		i::fatal_error('is_array($total_seconds) in infiniclock');
	}
	$clock_string = '';
	if($total_seconds > 10800) {
		$meridiem_string = ' pm';
		//$total_seconds -= 41220;
		//$total_seconds -= 32000;
		//$total_seconds -= 52400;
		//$total_seconds -= 32400;
		//$total_seconds -= 52400;
		//$total_seconds -= 43200;
		//$total_seconds -= 10800;
		$total_seconds -= 43200; // 10800 + 32400
	} else {
		$meridiem_string = ' am';
	}
	//$total_seconds += 43200;
	$total_seconds += 32400; // assumes a start at 9:00 am for the game
	$total_seconds = (int)$total_seconds;
	$seconds = $total_seconds % 60;
	$total_seconds -= $seconds;
	if(strlen($seconds) < 2) {
		$seconds = '0' . $seconds;
	}
	$minutes = ($total_seconds % 3600) / 60;
	$total_seconds -= $minutes * 60;
	$hours = $total_seconds / 3600;
	if(strlen($minutes) < 2) {
		$minutes = '0' . $minutes;
	}
	if($hours > 0) {
		$clock_string = $hours . ':' . $minutes . ':' . $seconds;
	} else {
		$clock_string = '12:' . $minutes . ':' . $seconds; // 12 is like a 0 in pm
	}
	$total_seconds -= $hours * 3600;
	if($total_seconds > 0) {
		print('$total_seconds, $clock_string: ');var_dump($total_seconds, $clock_string);
		i::fatal_error('error in infiniclock');
	}
	$clock_string .= $meridiem_string;
	return $clock_string;
}

function get_fame_range($fame_amount) {
	if($fame_amount >= 500) {
		return '500+ International';
	} elseif($fame_amount >= 300 && $fame_amount < 500) {
		return '300 - 500 National';
	} elseif($fame_amount >= 150 && $fame_amount < 300) {
		return '150 - 300 Regional';
	} elseif($fame_amount >= 50 && $fame_amount < 150) {
		return '50 - 150 Local';
	} elseif($fame_amount >= 0 && $fame_amount < 50) {
		return '0 - 50 Obscure';
	} else {
		print('$fame_amount: ');var_dump($fame_amount);
		i::fatal_error('unhandled $fame_amount in get_fame_range()');
	}
}
function get_infamy_range($infamy_amount) {
	if($infamy_amount >= 500) {
		return '500+ Darkest Evil';
	} elseif($infamy_amount >= 300 && $infamy_amount < 500) {
		return '300 - 500 Diabolical';
	} elseif($infamy_amount >= 150 && $infamy_amount < 300) {
		return '150 - 300 Sociopathic';
	} elseif($infamy_amount >= 50 && $infamy_amount < 150) {
		return '50 - 150 Inhumane';
	} elseif($infamy_amount >= 0 && $infamy_amount < 50) {
		return '0 - 50 Sketchy';
	} else {
		print('$infamy_amount: ');var_dump($infamy_amount);
		i::fatal_error('unhandled $infamy_amount in get_infamy_range()');
	}
}

function replace_generics($string, $games, $game_with_id, $action_id) {
//function replace_generics($string, $action_with_id) {
	//print('$string, $games, $game_with_id, $action_id in replace_generics: ');var_dump($string, $games, $game_with_id, $action_id);
	//print('$string, $action_id in replace_generics: ');var_dump($string, $action_id);
	//$games->reset_context(); // questionable
	$action_with_id = $games->_('.action_id=' . $games->enc($action_id), $game_with_id);
	//print('$action_with_id in replace_generics: ');var_dump($action_with_id);
	//print('$string, $action_with_id in replace_generics: ');var_dump($string, $action_with_id);
	if(is_array($action_with_id) && sizeof($action_with_id) === 0) {
		print('$action_id, $action_with_id, $game_with_id, $games: ');var_dump($action_id, $action_with_id, $game_with_id, $games);
		i::fatal_error('is_array($action_with_id) && sizeof($action_with_id) === 0 in replace_generics.');
	}
	//// UMMMMMMMMMM!!!!!!!
	$person_name_counter = 1;
	//print('$person_name_counter, $action_with_id, $games->ge(\'person\' . $person_name_counter . \'_name\', $action_with_id) in replace_generics: ');var_dump($person_name_counter, $action_with_id, $games->ge('person' . $person_name_counter . '_name', $action_with_id));
	while(is_string($games->ge('person' . $person_name_counter . '_name', $action_with_id))) {
		//$person_names[$person_name_counter] = $games->_($games->enc('person' . $person_name_counter . '_name'), $todays_action);
		//$string = str_replace('$P' . $person_name_counter . '$', $games->ge('person' . $person_name_counter . '_name', $action_with_id), $string);
		//print('$string, $person_name_counter before replace in replace_generics: ');var_dump($string, $person_name_counter);
		preg_match('/\$P' . $person_name_counter . '\$/is', $string, $matches);
		//print('$matches: ');var_dump($matches);
		$string = preg_replace('/\$P' . $person_name_counter . '(|_[^\$]+)\$/is', $games->ge('person' . $person_name_counter . '_name', $action_with_id), $string);
		//print('$string after replace in replace_generics: ');var_dump($string);//exit(0);
		$person_name_counter++;
	}
	return $string;
}

function infinispectrum($value, $maximum) { // alias
	return i::infini_spectrum($value, $maximum); 
}

function infini_spectrum($value, $maximum) {
	return i::black_to_red_spectrum(log($value) + 0.5, log($maximum)); // + 0.5 to only use the red part of the spectrum and not the black?
}

function black_to_red_spectrum($value, $maximum) {
	// would really prefer to use toroidal math instead of this empirical approach...
	if($maximum === 0) { // maximum cannot be 0 or else we get a divide by zero error
		$maximum = 0.00000000000001;
	}
	$l = (300 * $value / $maximum) + 400;
	//print('$l: ');var_dump($l);
	$red_component = 0.0;
	$green_component = 0.0;
	$blue_component = 0.0;
	if($l >= 400.0 && $l < 410.0) {
		$t = ($l - 400.0) / (410.0 - 400.0);
		$red_component = (0.33 * $t) - (0.20 * $t * $t);
	} elseif($l >= 410.0 && $l < 475.0) {
		$t = ($l - 410.0) / (475.0 - 410.0);
		$red_component = 0.14 - (0.13 * $t * $t);
	} elseif($l >= 545.0 && $l < 595.0) {
		$t = ($l - 545.0) / (595.0 - 545.0);
		$red_component = (1.98 * $t) - ($t * $t);
	} elseif($l >= 595.0 && $l < 700.0) {
		$t = ($l - 595.0) / (700.0 - 595.0);
		$red_component = 0.98 + (0.06 * $t) - (0.40 * $t * $t);
	}/* elseif($l >= 650.0 && $l < 700.0) {
		$t = ($l - 650.0) / (700.0 - 650.0);
		$red_component = 0.65 - (0.84 * $t) + (0.20 * $t * $t);
	}*/
	if($l >= 415.0 && $l < 475.0) {
		$t = ($l - 415.0) / (475.0 - 415.0);
		$green_component = (0.80 * $t * $t);
	} elseif($l >= 475.0 && $l < 590.0) {
		$t = ($l - 475.0) / (590.0 - 475.0);
		$green_component = 0.8 + (0.76 * $t) - (0.80 * $t * $t);
	} elseif($l >= 585.0 && $l < 639.0) {
		$t = ($l - 585.0) / (639.0 - 585.0);
		$green_component = 0.84 - (0.84 * $t);
	}
	if($l >= 400.0 && $l < 475.0) {
		$t = ($l - 400.0) / (475.0 - 400.0);
		$blue_component = (2.20 * $t) - (1.50 * $t * $t);
	} elseif($l >= 475.0 && $l < 560.0) {
		$t = ($l - 475.0) / (560.0 - 475.0);
		$blue_component = 0.7 - ($t) + (0.30 * $t * $t);
	}
	//print('$red_component, $green_component, $blue_component mid-function: ');var_dump($red_component, $green_component, $blue_component);
	$red_component *= 255;
	$green_component *= 255;
	$blue_component *= 255;
	$red_component = dechex(round($red_component));
	if(strlen($red_component) < 2) {
		$red_component = '0' . $red_component;
	}
	$green_component = dechex(round($green_component));
	if(strlen($green_component) < 2) {
		$green_component = '0' . $green_component;
	}
	$blue_component = dechex(round($blue_component));
	if(strlen($blue_component) < 2) {
		$blue_component = '0' . $blue_component;
	}
	return $red_component . $green_component . $blue_component;
}

function green_to_red_spectrum($value, $maximum) {
	// would really prefer to use toroidal math instead of this empirical approach...
    if($maximum === 0) { // maximum cannot be 0 or else we get a divide by zero error
		$maximum = 0.00000000000001;
	}
	$l = (180 * $value / $maximum) + 520;
	//print('$l: ');var_dump($l);
	$red_component = 0.0;
	$green_component = 0.0;
	$blue_component = 0.0;
	if($l >= 400.0 && $l < 410.0) {
		$t = ($l - 400.0) / (410.0 - 400.0);
		$red_component = (0.33 * $t) - (0.20 * $t * $t);
	} elseif($l >= 410.0 && $l < 475.0) {
		$t = ($l - 410.0) / (475.0 - 410.0);
		$red_component = 0.14 - (0.13 * $t * $t);
	} elseif($l >= 545.0 && $l < 595.0) {
		$t = ($l - 545.0) / (595.0 - 545.0);
		$red_component = (1.98 * $t) - ($t * $t);
	} elseif($l >= 595.0 && $l < 700.0) {
		$t = ($l - 595.0) / (700.0 - 595.0);
		$red_component = 0.98 + (0.06 * $t) - (0.40 * $t * $t);
	}/* elseif($l >= 650.0 && $l < 700.0) {
		$t = ($l - 650.0) / (700.0 - 650.0);
		$red_component = 0.65 - (0.84 * $t) + (0.20 * $t * $t);
	}*/
	if($l >= 415.0 && $l < 475.0) {
		$t = ($l - 415.0) / (475.0 - 415.0);
		$green_component = (0.80 * $t * $t);
	} elseif($l >= 475.0 && $l < 590.0) {
		$t = ($l - 475.0) / (590.0 - 475.0);
		$green_component = 0.8 + (0.76 * $t) - (0.80 * $t * $t);
	} elseif($l >= 585.0 && $l < 639.0) {
		$t = ($l - 585.0) / (639.0 - 585.0);
		$green_component = 0.84 - (0.84 * $t);
	}
	if($l >= 400.0 && $l < 475.0) {
		$t = ($l - 400.0) / (475.0 - 400.0);
		$blue_component = (2.20 * $t) - (1.50 * $t * $t);
	} elseif($l >= 475.0 && $l < 560.0) {
		$t = ($l - 475.0) / (560.0 - 475.0);
		$blue_component = 0.7 - ($t) + (0.30 * $t * $t);
	}
	//print('$red_component, $green_component, $blue_component mid-function: ');var_dump($red_component, $green_component, $blue_component);
	$red_component *= 255;
	$green_component *= 255;
	$blue_component *= 255;
	$red_component = dechex(round($red_component));
	if(strlen($red_component) < 2) {
		$red_component = '0' . $red_component;
	}
	$green_component = dechex(round($green_component));
	if(strlen($green_component) < 2) {
		$green_component = '0' . $green_component;
	}
	$blue_component = dechex(round($blue_component));
	if(strlen($blue_component) < 2) {
		$blue_component = '0' . $blue_component;
	}
	return $red_component . $green_component . $blue_component;
}

function red_to_green_spectrum($value, $maximum) {
	// would really prefer to use toroidal math instead of this empirical approach...
    if($maximum === 0) { // maximum cannot be 0 or else we get a divide by zero error
		$maximum = 0.00000000000001;
	}
	$l = (180 * $value / $maximum) + 401;
	//$l = (-1 * (700 - $l)) + 400;
	$l = 400 + (700 - $l);
	//print('$l: ');var_dump($l);
	$red_component = 0.0;
	$green_component = 0.0;
	$blue_component = 0.0;
	if($l >= 400.0 && $l < 410.0) {
		$t = ($l - 400.0) / (410.0 - 400.0);
		$red_component = (0.33 * $t) - (0.20 * $t * $t);
	} elseif($l >= 410.0 && $l < 475.0) {
		$t = ($l - 410.0) / (475.0 - 410.0);
		$red_component = 0.14 - (0.13 * $t * $t);
	} elseif($l >= 545.0 && $l < 595.0) {
		$t = ($l - 545.0) / (595.0 - 545.0);
		$red_component = (1.98 * $t) - ($t * $t);
	} elseif($l >= 595.0 && $l < 700.0) {
		$t = ($l - 595.0) / (700.0 - 595.0);
		$red_component = 0.98 + (0.06 * $t) - (0.40 * $t * $t);
	}/* elseif($l >= 650.0 && $l < 700.0) {
		$t = ($l - 650.0) / (700.0 - 650.0);
		$red_component = 0.65 - (0.84 * $t) + (0.20 * $t * $t);
	}*/
	if($l >= 415.0 && $l < 475.0) {
		$t = ($l - 415.0) / (475.0 - 415.0);
		$green_component = (0.80 * $t * $t);
	} elseif($l >= 475.0 && $l < 590.0) {
		$t = ($l - 475.0) / (590.0 - 475.0);
		$green_component = 0.8 + (0.76 * $t) - (0.80 * $t * $t);
	} elseif($l >= 585.0 && $l < 639.0) {
		$t = ($l - 585.0) / (639.0 - 585.0);
		$green_component = 0.84 - (0.84 * $t);
	}
	if($l >= 400.0 && $l < 475.0) {
		$t = ($l - 400.0) / (475.0 - 400.0);
		$blue_component = (2.20 * $t) - (1.50 * $t * $t);
	} elseif($l >= 475.0 && $l < 560.0) {
		$t = ($l - 475.0) / (560.0 - 475.0);
		$blue_component = 0.7 - ($t) + (0.30 * $t * $t);
	}
	//print('$red_component, $green_component, $blue_component mid-function: ');var_dump($red_component, $green_component, $blue_component);
	$red_component *= 255;
	$green_component *= 255;
	$blue_component *= 255;
	$red_component = dechex(round($red_component));
	if(strlen($red_component) < 2) {
		$red_component = '0' . $red_component;
	}
	$green_component = dechex(round($green_component));
	if(strlen($green_component) < 2) {
		$green_component = '0' . $green_component;
	}
	$blue_component = dechex(round($blue_component));
	if(strlen($blue_component) < 2) {
		$blue_component = '0' . $blue_component;
	}
	return $red_component . $green_component . $blue_component;
}

function is_more_recent($realdate1, $realdate2) {
	preg_match_all('/[0-9]+/is', $realdate1, $realdate1_number_matches);
	preg_match_all('/[0-9]+/is', $realdate2, $realdate2_number_matches);
	foreach($realdate1_number_matches as $index => $value) {
		if($value < $realdate2_number_matches[$index]) {
			return false;
		}
	}
	return true;
}

static function fatal_error($message) {
	print('<span style="color: red;">' . $message . '</span>');exit(0);
}

static function warning($message) { 
	print('<span style="color: orange;">' . $message . '</span><br>');
}

static function good_news($message) { 
	print('<span style="color: green;">' . $message . '</span><br>');
}

static function fatal_error_once($string) {
	if(!isset($this->printed_strings[$string])) {
		print('<span style="color: red;">' . $string . '</span>');exit(0);
		$this->printed_strings[$string] = true;
	}
	return true;
}

static function warning_once($string) {
	if(!isset($this->printed_strings[$string])) {
		print('<span style="color: orange;">' . $string . '</span><br>');
		$this->printed_strings[$string] = true;
	}
	return true;
}

static function good_news_once($string) {
	if(!isset($this->printed_strings[$string])) {
		print('<span style="color: green;">' . $string . '</span><br>');
		$this->printed_strings[$string] = true;
	}
	return true;
}

static function var_dump_full() {
	$arguments_array = func_get_args();
	foreach($arguments_array as $index => $value) {
		$data_type = gettype($value);
		if($data_type == 'array') {
			$biggest_array_size = i::get_biggest_sizeof($value);
			if($biggest_array_size > 2000) {
				ini_set('xdebug.var_display_max_children', '2000');
			} elseif($biggest_array_size > ini_get('xdebug.var_display_max_children')) {
				ini_set('xdebug.var_display_max_children', $biggest_array_size);
			}
		} elseif($data_type == 'string') {
			$biggest_string_size = strlen($value);
			if($biggest_string_size > 10000) {
				ini_set('xdebug.var_display_max_data', '10000');
			} elseif($biggest_string_size > ini_get('xdebug.var_display_max_data')) {
				ini_set('xdebug.var_display_max_data', $biggest_string_size);
			}
		} elseif($data_type == 'integer' || $data_type == 'float' || $data_type == 'chr' || $data_type == 'boolean' || $data_type == 'NULL') {
			// these are already compact enough
		} else {
			warning('Unhandled data type in var_dump_full: ' . gettype($value));
		}
		var_dump($value);
	}
}

static function get_biggest_sizeof($array, $biggest = 0) {
	if(sizeof($array) > $biggest) {
		$biggest = sizeof($array);
	}
	foreach($array as $index => $value) {
		if(is_array($value)) {
			$biggest = i::get_biggest_sizeof($value, $biggest);
		}
	}
	return $biggest;
}

}

?>
