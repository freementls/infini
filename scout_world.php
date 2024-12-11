<?php

include('../LOM/O.php');
$O = new O('world.xml');
$approximations = new O('approximations.xml');

// show the result
$areas = $O->_('world_area');
//print('$areas when printing the map: ');var_dump($areas);
print('<div style="border: 1px solid red; width: ' . $O->_('world_sizex') . 'px; height: ' . $O->_('world_sizey') . ';">');
foreach($areas as $area) {
	//print('$area: ');var_dump($area);
	//print('$O->_(\'area_.name\', $area): ');var_dump($O->_('area_.name', $area));
	// notice that we are specifying the name of the area so that names of creatures are not also found
	$creatures_in_area = $O->_('creature', $area);
	print('<table border="1" cellpadding="2" cellspacing="0" style="width: 200px; position: absolute; left: ' . $O->_('x', $area) . '; top: ' . $O->_('y', $area) . ';">
<tr>
<th scope="row">' . $O->_('area_.name', $area) . '<br>plants: ' . approximate_resource_number($O->_('area_.plants', $area), $approximations) . '<br>minerals: ' . approximate_resource_number($O->_('area_.minerals', $area), $approximations) . '<br>magic: ' . approximate_resource_number($O->_('area_.magic', $area), $approximations) . '</th>
<td>');
	//print('$creatures_in_area: ');var_dump($creatures_in_area);exit(0);
	if(sizeof($creatures_in_area) > 0 && $creatures_in_area !== false) {
		$creature_types_in_area = array();
		foreach($creatures_in_area as $creature_in_area) {
			//print($O->_('name', $creature_in_area) . ' ');
			$name = $O->_('name', $creature_in_area);
			if(!isset($creature_types_in_area[$name])) {
				$creature_types_in_area[$name] = 1;
			} else {
				$creature_types_in_area[$name]++;
			}
		}
		foreach($creature_types_in_area as $creature_type => $number) {
			print($creature_type . ': ' . approximate_creature_number($number, $approximations) . '<br>');
		}
	}
	print('</td>
</tr>
</table>');
}
print('</div>');
/*print('

<form method="post" action="ecology_test.php">
<input type="submit" value="advance time" />
</form>');*/

//print('it can be seen that match indices are not always being properly filled in by printing out the full context. seems that the only time they are getting filled in is when there\'s a single text-only match, I\'d guess that get_indices or how it\'s being used by context updating needs work<br>');
//print('$O->context: ');var_dump_full($O->context);

function approximate_creature_number($number, $approximations) {
	$creatureapproximations = $approximations->_('creatureapproximations_approximation');
	$counter = 0;
	while($counter < sizeof($creatureapproximations)) {
		if($number < $approximations->_('number', $creatureapproximations[$counter])) {
			return $approximations->_('text', $creatureapproximations[$counter]);
		}
		$counter++;
	}
	return 'legion';
}

function approximate_resource_number($number, $approximations) {
	$resourceapproximations = $approximations->_('resourceapproximations_approximation');
	$counter = 0;
	while($counter < sizeof($resourceapproximations)) {
		if($number < $approximations->_('number', $resourceapproximations[$counter])) {
			return $approximations->_('text', $resourceapproximations[$counter]);
		}
		$counter++;
	}
	return 'paradise';
}

function print_event_message($message) {
	if($GLOBALS['print_event_messages']) {
		print($message);
	}
}

function var_dump_full() {
	$arguments_array = func_get_args();
	foreach($arguments_array as $index => $value) {
		$data_type = gettype($value);
		if($data_type == 'array') {
			$biggest_array_size = get_biggest_sizeof($value);
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
			ReTidy::warning('Unhandled data type in var_dump_full: ' . gettype($value));
		}
		var_dump($value);
	}
}

function get_biggest_sizeof($array, $biggest = 0) {
	if(sizeof($array) > $biggest) {
		$biggest = sizeof($array);
	}
	foreach($array as $index => $value) {
		if(is_array($value)) {
			$biggest = get_biggest_sizeof($value, $biggest);
		}
	}
	return $biggest;
}

?>