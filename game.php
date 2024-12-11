<?php

include('../LOM/O.php');

// maybe the game (with its required components) is selected here
$game = new game();

class game {

function __construct() {
	include('tiles.php');
	$tiles_object = new tiles();
	$this->tiles = $tiles_object->tile();
	//exit(0);
	
	include('units.php');
	$units_object = new units($tiles_object->get_tile_sides(), $tiles_object->get_tiling_scheme(), $tiles_object->get_tiling_schemes(), $this->tiles);
	$units_object->new_unit('red_sniper', 'images/units/red-sniper/red-archer.png', 23, 6, 1);
	$units_object->new_unit('captain', 'images/units/captain.png', 0, 2, 1);
	//$units_object->new_unit($this->tiles, 'captain', 'images/units/captain.png', 2, 2);
	$units_object->new_unit('city', 'images/city_blank.png', 10, 10, 1);
	/*$units_object->move_one_tile('captain', 's');
	$units_object->move_one_tile('captain', 's');
	$units_object->move_one_tile('captain', 'se');
	$units_object->move_one_tile('captain', 'se');
	$units_object->move_one_tile('captain', 'ne');
	$units_object->move_one_tile('captain', 'ne');
	$units_object->move_one_tile('captain', 'n');
	$units_object->move_one_tile('captain', 'n');
	$units_object->move_one_tile('captain', 'nw');
	$units_object->move_one_tile('captain', 'nw');
	$units_object->move_one_tile('captain', 'sw');
	$units_object->move_one_tile('captain', 'sw');
	$units_object->move_one_tile('captain', 'n');
	$units_object->move_one_tile('captain', 'n');*/
	//$units_object->move_on_path('captain', 's,s,se,se,ne,ne,n,n,nw,nw,sw,sw,n,n');
	//$units_object->move_on_path('red_sniper', 'se,ne,se,ne,s,s,se,s,nw,n,n');
	//$units_object->move_one_tile('red_sniper', 'se');
	//$units_object->move_one_tile('red_sniper', 'se');
	//$units_object->move_one_tile('red_sniper', 'se');
	$units_object->move_to('red_sniper', 1, 1);
	//$units_object->move_one_tile('captain', 's');
	// cool that they actually move at the same time!
	//$units_object->idle_animation('red_sniper', 'images/units/red-sniper/red-archer-idle-[1~6,3~6,3~6,2,1].png:100'); // probably the short-hand is not handled
	$units_object->randomly_create_new_units('city', 'images/city_48_blank.png', 'random', $this->tiles->get_playing_field_width() * $this->tiles->get_playing_field_height() * 0.10, array('no_contiguous' => true)); // fill 10% of the tiles
	$this->tiles = $units_object->get_tiles();
	$this->units = $units_object->get_units();
	exit(0);
	
	include('game_interface.php'); // menus, minimap, mouse hover

}

function save_game_state() {
	$this->tiles->save_LOM_to_file('infini_tiles.xml');
	$this->units->save_LOM_to_file('infini_units.xml');
	$this->game_interface->save_LOM_to_file('infini_interface.xml');
}

function load_game_state() {
	$game_state = unserialize(file_get_contents('game_state'));
	// different games will have to write to different files, but for now everything writes to the same files
	$this->tiles = new O('infini_tiles.xml');
	$this->units = new O('infini_units.xml');
	$this->game_interface = new O('infini_interface.xml');
}

function fatal_error($message) {
	print('<span style="color: red;">' . $message . '</span>');exit(0);
}

function warning($message) { 
	print('<span style="color: orange;">' . $message . '</span><br>');
}

function fatal_error_once($string) {
	if(!isset($this->printed_strings[$string])) {
		print('<span style="color: red;">' . $string . '</span>');exit(0);
		$this->printed_strings[$string] = true;
	}
	return true;
}

function warning_if($string, $count) {
	if($count > 1) {
		fs::warning($string);
	}
}

function warning_once($string) {
	if(!isset($this->printed_strings[$string])) {
		print('<span style="color: orange;">' . $string . '</span><br>');
		$this->printed_strings[$string] = true;
	}
	return true;
}

}

?>