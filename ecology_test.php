<?php

include('../LOM/O.php');
//$O = new O('ecology_test.xml', false);
$O = new O('ecology_test.xml');
$world = $O->_('world');
print('(((must verify that delete works before publishing LOM ... delayed delete function?)))<br>
no check for minimum 2 animals: growth is very simplistic<br>
takes a loooong time for something apparently simple... why? context? helps some.<br>');
$GLOBALS['print_event_messages'] = true;

// advance time and process the resulting changes (and report the changes for debugging)
// should growth or death come first? wouldn't matter with sufficiently large populations in terms of genocide but would certainly affect populations

// growth
$areas = $O->_('area');
foreach($areas as $area) {
	//print('$area: ');var_dump($area);
	$creatures_in_area = $O->_('creature', $area);
	if(sizeof($creatures_in_area) > 0 && $creatures_in_area !== false) {
		foreach($creatures_in_area as $creature_in_area) {
			$birthrate = $O->_('birthrate', $O->_('.creaturetype_name=' . $O->_('name', $creature_in_area)));
			$rand = rand(0, 99);
			if($rand < $birthrate * 100) {
				//$O->new_('<creature><name>' . $O->_('name', $creature_in_area) . '</name></creature>', $area);
				$O->delayed_new('<creature><name>' . $O->_('name', $creature_in_area) . '</name></creature>', $area);
				print_event_message('a ' . $O->_('name', $creature_in_area) . ' was born.<br>');
			}
		}
	}
}
//print('before delayed_actions after growth<br>');
$O->delayed_actions();
//print('after delayed actions<br>');
//print('$O->LOM after growth: ');var_dump_full($O->tagstring($O->LOM));
//$O->validate();

// death
// go in reverse order since deletion affects the indices
$areas = $O->_('area');
$area_counter = sizeof($areas) - 1;
while($area_counter > -1) {
//foreach($areas as $area) {
	//$area = $areas[$area_counter];
	//print('$area: ');var_dump($area);
	$creatures_in_area = $O->_('creature', $O->_('area')[$area_counter]);
	if($creatures_in_area !== false) {
		//print('$creatures_in_area: ');var_dump($creatures_in_area);
		$counter = sizeof($creatures_in_area) - 1;
		while($counter > -1) {
		//foreach($creatures_in_area as $creature_in_area) {
			$creature_in_area = $creatures_in_area[$counter];
			//print('$creature_in_area, $O->_(\'creature_.name\', $creature_in_area): ');var_dump($creature_in_area, $O->_('creature_.name', $creature_in_area));
			//if($O->_('area_.name', $area) === 'area 2') { 
			//	$O->delete($creature_in_area); break 2; 
			//}
			$deathrate = $O->_('deathrate', $O->_('.creaturetype_name=' . $O->_('name', $creature_in_area)));
			$rand = rand(0, 99);
			//print('$deathrate, $rand: ');var_dump($deathrate, $rand);
			if($rand < $deathrate * 100) {
				//$areas = $O->delete($creature_in_area, $areas); // not sure if feeding the array in as the selector works...
				//$O->__($creature_in_area, '');
				//foreach($creature_in_area as $index => $value) {  }
				//$area = $O->delete($index); // not sure if feeding the array in as the selector works...
				print_event_message('a ' . $O->_('name', $creature_in_area) . ' died.<br>');
				//print('$creature_in_area, $O->_(\'area\')[$area_counter] before delete1: ');var_dump($creature_in_area, $O->_('area')[$area_counter]);
				$O->delayed_delete($creature_in_area);
			}
			$counter--;
		}
	}
	$area_counter--;
}
//print('before delayed_actions after death<br>');
$O->delayed_actions();
//print('$O->LOM after death: ');var_dump_full($O->tagstring($O->LOM));
//$O->validate();

// emigration
//$to_new_array = array();
$areas = $O->_('area');
$area_counter = sizeof($areas) - 1;
while($area_counter > -1) {
	//$area = $areas[$area_counter];
	$creatures_in_area = $O->_('creature', $O->_('area')[$area_counter]);
	if($creatures_in_area !== false) {
		$counter = sizeof($creatures_in_area) - 1;
		while($counter > -1) {
			$creature_in_area = $creatures_in_area[$counter];
			$emigrationrate = $O->_('emigrationrate', $O->_('.creaturetype_name=' . $O->_('name', $creature_in_area)));
			$rand = rand(0, 99);
			//print('$creature_in_area, $O->_(\'.creaturetype_name=\' . $O->_(\'name\', $creature_in_area)), $emigration, $rand: ');var_dump($creature_in_area, $O->_('.creaturetype_name=' . $O->_('name', $creature_in_area)), $emigration, $rand);
			if($rand < $emigrationrate * 100) {
				// adjacent?
				$random_area = rand(0, sizeof($areas) - 1);
				while($random_area === $area_counter) {
					$random_area = rand(0, sizeof($areas) - 1);
				}
				//print('$random_area: ');var_dump($random_area);exit(0);
				//$O->new_($O->tagstring($creature_in_area), $areas[$random_area]);
				//$to_new_array[] = array($O->tagstring($creature_in_area), $random_area);
				$O->delayed_new($O->tagstring($creature_in_area), $areas[$random_area]);
				print_event_message('a ' . $O->_('name', $creature_in_area) . ' emigrated from ' . $O->_('area_.name', $O->_('area')[$area_counter]) . ' to ' . $O->_('area_.name', $areas[$random_area]) . '.<br>');
				//print('$creature_in_area, $O->_(\'area\')[$area_counter] before delete2: ');var_dump($creature_in_area, $O->_('area')[$area_counter]);
				$O->delayed_delete($creature_in_area);
			}
			$counter--;
		}
	}
	$area_counter--;
}
//print('before delayed_actions after emigration<br>');
$O->delayed_actions();
//print('$O->LOM after emigration: ');var_dump_full($O->tagstring($O->LOM));
//$O->validate();
//$areas = $O->_('area');
//foreach($to_new_array as $index => $to_new) {
//	$O->new_($to_new[0], $O->_('area')[$to_new[1]]);
//}

// eating (and starvation)
// harshly say that if a creature doesn't get its full requirement of food that it dies
$areas = $O->_('area');
//print('$areas before eating: ');var_dump($areas);
$area_counter = sizeof($areas) - 1;
while($area_counter > -1) {
	$area = $areas[$area_counter];
	//print('$area_counter, $area: ');var_dump_full($area_counter, $area);
	$area_plants = $O->_('plants', $area);
	$area_killed_prey = array();
	$creatures_in_area = $O->_('creature', $area);
	if($creatures_in_area !== false) {
		$counter = sizeof($creatures_in_area) - 1;
		while($counter > -1) {
			$creature_in_area = $creatures_in_area[$counter];
			$eats = $O->_('eats', $O->_('.creaturetype_name=' . $O->_('name', $creature_in_area)));
			$food = $O->_('name', $eats);
			//print('$food, $eats, $area_plants: ');var_dump($food, $eats, $area_plants);
			if($food === 'plants') {
				//print('$O->_(\'number\', $eats): ');var_dump($O->_('number', $eats));
				if($area_plants >= $O->_('number', $eats)) {
					// it lives
					$area_plants -= $O->_('number', $eats);
					print_event_message('a ' . $O->_('name', $creature_in_area) . ' ate ' . $O->_('number', $eats) . ' plants.<br>');
				} else {
					// it dies
					print_event_message('a ' . $O->_('name', $creature_in_area) . ' starved.<br>');
					//print('$creature_in_area, $O->_(\'area\')[$area_counter] before delete2.5: ');var_dump($creature_in_area, $O->_('area')[$area_counter]);
					$O->delayed_delete($creature_in_area);
				}
			} elseif(sizeof($O->_('.creature_name=' . $food, $area)) >= $O->_('number', $eats)) {
				// no consideration given to which creatures are eaten... not problematic when they are undifferentiated but would be if they had different properties, as they would in an interesting game
				// its prey dies and it lives
				$prey_creatures = $O->_('.creature_name=' . $food, $area);
				//print('$prey_creatures: ');var_dump($prey_creatures);
				if(sizeof($prey_creatures) - $O->_('number', $eats) >= $area_killed_prey[$food]) {
					$prey_counter = sizeof($prey_creatures) - 1;
					//print('$O->_(\'number\', $eats), $food, $prey_creatures, $prey_counter: ');var_dump($O->_('number', $eats), $food, $prey_creatures, $prey_counter);
					$killed_prey = 0;
					while($prey_counter > -1) {
						//print('$food, $prey_counter: ');var_dump($food, $prey_counter);
						//print('$prey_creatures[$prey_counter] before delete3: ');var_dump($prey_creatures[$prey_counter]);
						//$O->delayed_delete($prey_creatures[$prey_counter]);
						$O->delayed_delete($prey_creatures[$prey_counter]); // cannot be delayed so that it cannot be eaten more than once?
						$killed_prey++;
						$area_killed_prey[$food]++;
						if($killed_prey == $O->_('number', $eats)) {
							break;
						}
						$prey_counter--;
					}
					print_event_message('a ' . $O->_('name', $creature_in_area) . ' ate ' . $killed_prey . ' ' . $food . '.<br>');
				} else {
					print_event_message('a ' . $O->_('name', $creature_in_area) . ' starved.<br>');
					//print('$creature_in_area, $O->_(\'area\')[$area_counter] before delete4: ');var_dump($creature_in_area, $O->_('area')[$area_counter]);
					$O->delayed_delete($creature_in_area);
				}
			} else {
				// it dies
				print_event_message('a ' . $O->_('name', $creature_in_area) . ' starved.<br>');
				//print('$creature_in_area, $O->_(\'area\')[$area_counter] before delete4: ');var_dump($creature_in_area, $O->_('area')[$area_counter]);
				$O->delayed_delete($creature_in_area);
			}
			$counter--;
		}
	}
	$area_counter--;
}
//print('before delayed_actions after eating<br>');
$O->delayed_actions();
//print('$O->LOM after eating: ');var_dump_full($O->tagstring($O->LOM));
$O->validate();
//$O->save_LOM_to_file('ecology_test.xml');
//print('$O->LOM string: ');var_dump_full($O->tagstring($O->LOM));

// show the result
$areas = $O->_('world_area');
//print('$areas when printing the map: ');var_dump($areas);
print('<table border="1">
');
foreach($areas as $area) {
	//print('$area: ');var_dump($area);
	//print('$O->_(\'area_.name\', $area): ');var_dump($O->_('area_.name', $area));
	// notice that we are specifying the name of the area so that names of creatures are not also found
	$creatures_in_area = $O->_('creature', $area);
	print('<tr>
<th scope="row">' . $O->_('area_.name', $area) . '<br>plants: ' . $O->_('area_.plants', $area) . '<br>number of creatures: ' . sizeof($creatures_in_area) . '</th>
<td>');
	//print('$creatures_in_area: ');var_dump($creatures_in_area);exit(0);
	if(sizeof($creatures_in_area) > 0 && $creatures_in_area !== false) {
		foreach($creatures_in_area as $creature_in_area) {
			print($O->_('name', $creature_in_area) . ' ');
		}
	}
	print('</td>
</tr>');
}
print('
</table>
<form method="post" action="ecology_test.php">
<input type="submit" value="advance time" />
</form>');

print('it can be seen that match indices are not always being properly filled in by printing out the full context. seems that the only time they are getting filled in is when there\'s a single text-only match, I\'d guess that get_indices or how it\'s being used by context updating needs work<br>');
//print('$O->context: ');var_dump_full($O->context);

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