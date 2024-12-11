<html>
<head>
<meta charset="utf-8">
<title>bbalof</title>
<link rel="stylesheet" href="jquery-ui/jquery-ui.css">
<style>
td[id], .enemy_background td, .ally_background td { min-width: 100px; height: 50px; vertical-align: top; }
.enemy_background { background-color: #FEE; }
.ally_background { background-color: #EEF; }
table { margin-top: 10px; }
th form, td form { display: inline; }
th, td { line-height: 16px; }
</style>
<script src="jquery.min.js"></script>
<script src="jquery-ui/jquery-ui.js"></script>
</head>
<body>

<?php

//print('all_debug001<br />' . PHP_EOL);
define('DS', DIRECTORY_SEPARATOR);
$GLOBALS['swap_counter'] = 0;
include('i.php');
//$i = new i();
//print('$i: ');var_dump($i);exit(0);

//print('all_debug002<br />' . PHP_EOL);
function print_swap_box($contents = false) {
	//print('all_debug003<br />' . PHP_EOL);
	print("<script>
//$(document).ready(function() {
$(function() {
	$('#swap" . $GLOBALS['swap_counter'] . "').draggable({
		cursor: 'move',
		revert: true,
		drag: function(event, ui) {
			draged_id = $(this).attr('id');
		}
	});
	$('#swap" . $GLOBALS['swap_counter'] . "').droppable({
		accept: 'td',
		classes: {'ui-droppable-active': 'ui-state-active', 'ui-droppable-hover': 'ui-state-hover'},
		drop: function(event, ui) {
			saved_droped_contents = document.getElementById($(this).attr('id')).innerHTML;
			saved_draged_contents = document.getElementById(draged_id).innerHTML;
			draged_unit_id = String(saved_draged_contents.match(/<!--(.+)-->/g));
			//draged_unit_id = draged_unit_id[1];
			draged_unit_id = draged_unit_id.substr(4, draged_unit_id.length - 4);
			draged_unit_id = draged_unit_id.split('').reverse().join('');
			draged_unit_id = draged_unit_id.substr(3, draged_unit_id.length - 3);
			draged_unit_id = draged_unit_id.split('').reverse().join('');
			draged_unit_location = $('#' + draged_id).parent().parent().parent().attr('location');
			droped_unit_id = String(saved_droped_contents.match(/<!--(.+)-->/g));
			//droped_unit_id = droped_unit_id[1];
			droped_unit_id = droped_unit_id.substr(4, droped_unit_id.length - 4);
			droped_unit_id = droped_unit_id.split('').reverse().join('');
			droped_unit_id = droped_unit_id.substr(3, droped_unit_id.length - 3);
			droped_unit_id = droped_unit_id.split('').reverse().join('');
			droped_unit_location = $(this).parent().parent().parent().attr('location');
			//alert('unit with id: ' + draged_unit_id + ' from location: ' + draged_unit_location + ' was draged onto unit with id: ' + droped_unit_id + ' at location: ' + droped_unit_location);
			if(saved_draged_contents.length > 0) {
				//$('#moves').attr('value', $('#moves').attr('value') + draged_unit_id + 'to' + droped_unit_location + ',');
				$.post('move_unit_forjs.php', { 'unit_id': draged_unit_id, 'new_unit_location': droped_unit_location}, function(result){
					//alert(result);
				});
			}
			if(saved_droped_contents.length > 0) {
				//$('#moves').attr('value', $('#moves').attr('value') + droped_unit_id + 'to' + draged_unit_location + ',');
				$.post('move_unit_forjs.php', { 'unit_id': droped_unit_id, 'new_unit_location': draged_unit_location}, function(result){
					//alert(result);
				});
			}
			document.getElementById($(this).attr('id')).innerHTML = saved_draged_contents;
			document.getElementById(draged_id).innerHTML = saved_droped_contents;
			//$(this).attr('id', $(this).attr('id').replace('droppable', 'draggable'));
			//$('#' + draged_id).attr('id', $('#' + draged_id).attr('id').replace('draggable', 'droppable'));
		}
	});
});
//});
</script>
<td id=\"swap" . $GLOBALS['swap_counter'] . "\">" . $contents . "</td>
");
	$GLOBALS['swap_counter']++;
}

function unit_card($unit, $units) {
	//print('all_debug004<br />' . PHP_EOL);
	//print('$unit: ');var_dump($unit);
	$unit_string = '<!--' . $units->_('id', $unit) . '-->' . $units->_('unit_name', $unit) . ' [' . $units->_('type', $unit) . ']<br>
health: ' . $units->_('health_current', $unit) . '/' . $units->_('health_maximum', $unit) . '<br>
attack: ' . $units->_('attack', $unit) . '<br>
range: ' . $units->_('range', $unit) . '<br>
precision: ' . $units->_('precision', $unit) . '<br>
evasion: ' . $units->_('evasion', $unit) . '<br>
counter: ' . $units->_('counter', $unit) . '<br>
stamina: ' . $units->_('stamina_current', $unit) . '/' . $units->_('stamina_maximum', $unit) . '<br>
cost: ' . $units->_('cost', $unit) . '<br>
income: ' . $units->_('income', $unit);
	$unit_image = $units->_('image', $unit);
	if(is_array($unit_image)) { // presumably empty result

	} elseif(strlen($unit_image) > 0) {
		$unit_string .= '<br><img src="bba/images/' . $units->_('image', $unit) . '" />';
	}
	return $unit_string;
}

function generate_unit($archetype = false, $multiplier = false) {
	//print('all_debug005<br />' . PHP_EOL);
	if($multiplier == false) {
		$multiplier = 1;
	}
	//print('$archetype, $multiplier in generate_unit: ');var_dump($archetype, $multiplier);
	$unit_archetypes = new O('bba' . DS . 'unit_archetypes.xml');
	if($archetype === false) {
		$all_unit_archetypes = $unit_archetypes->_('unit_type');
		//print('$all_unit_archetypes: ');var_dump($all_unit_archetypes);exit(0);
		$archetype = $all_unit_archetypes[rand(0, sizeof($all_unit_archetypes) - 1)];
	}
	$unit = $unit_archetypes->_('.unit_type=' . $archetype);
	//print('$unit in generate_unit: ');var_dump($unit);exit(0);
	$unit_string = $unit[0][0];
	preg_match_all('/<([^<>]+)>([0-9]+)<\/\1>/is', $unit_string, $matches);
	//print('$matches in generate_unit: ');var_dump($matches);
	foreach($matches[0] as $index => $value) {
		//print('$index, $value: ');var_dump($index, $value);
		if($matches[1][$index] === 'id') {
			$unit_string = str_replace($value, '<id>' . $GLOBALS['idcounter'] . '</id>', $unit_string);
			$GLOBALS['idcounter']++;
		} elseif($matches[1][$index] === 'range') { // don't mess with the range
			
		} else {
			$number = $matches[2][$index] * $multiplier;
			$new_number = round(number_from_gaussian_centered_on($number));
			//print('$value, $matches[2][$index], $multiplier, $number, $new_number in generate_unit: ');var_dump($value, $matches[2][$index], $multiplier, $number, $new_number);
			$unit_string = str_replace($value, '<' . $matches[1][$index] . '>' . $new_number . '</' . $matches[1][$index] . '>', $unit_string);
		}
	}
	$unit_string = preg_replace('/<name>[^<>]+<\/name>/is', '<name>' . i::generate_name(array('race' => 'japanese')) . '</name>', $unit_string);
	//print('$unit_string at the end of generate_unit: ');var_dump($unit_string);exit(0);
	return $unit_string;
}

function combat_locations_string($units, $locations) {
	//print('all_debug006<br />' . PHP_EOL);
	$string = '';
	foreach($locations->_('location') as $location) {
		if(sizeof($units->_('.unit_location=' . $locations->_('id', $location) . '&team=0')) > 0 && sizeof($units->_('.unit_location=' . $locations->_('id', $location) . '&team=1')) > 0) {
			$string .= $locations->_('id', $location) . ',';
		}
	}
	return substr($string, 0, strlen($string) - 1);
}

function other_combat_locations_string($location_id, $combat_locations) {
	//print('all_debug007<br />' . PHP_EOL);
	$string = '';
	foreach($combat_locations as $index => $value) {
		if($index === 'pen' || $index === $location_id) {
			
		} elseif($value === true) {
			$string .= $index . ',';
		}
	}
	return substr($string, 0, strlen($string) - 1);
}

function number_from_gaussian_centered_on($number) {
	//print('all_debug008<br />' . PHP_EOL);
	// what are the odds this will be coded as a real gaussian function? ;p
	$sign = rand(0, 1);
	if($sign === 0) {
		return ceil($number * (1 - (quadratic_rand() * 0.1)));
	} else {
		return ceil($number * (1 + (quadratic_rand() * 0.1)));
	}
}

function quadratic_rand($minimum = 1) {
	//print('all_debug009<br />' . PHP_EOL);
	$counter = $minimum - 1;
	$rand = 1;
	while($rand > 0) {
		$counter++;
		$rand = rand(0, 1);
	}
	return $counter;
}

function get_by_request($variable) {
	//print('$variable in get_by_request: ');var_dump($variable);
	//print('all_debug010<br />' . PHP_EOL);
	if($_REQUEST[$variable] == '') {
		//print('all_debug010.1<br />' . PHP_EOL);
		//warning($variable . ' not properly specified.<br>');
		return false;
	} else {
		//print('all_debug010.2<br />' . PHP_EOL);
		$variable = query_decode($_REQUEST[$variable]);
		//print('all_debug010.3<br />' . PHP_EOL);
	}
	return $variable;
}

function query_encode($string) {
	//print('all_debug011<br />' . PHP_EOL);
	$string = str_replace('&', '%26', $string);
	return $string;
}

function query_decode($string) {
	//print('all_debug012<br />' . PHP_EOL);
	$string = str_replace('%26', '&', $string);
	//print('all_debug012.1<br />' . PHP_EOL);
	return $string;
}

//print('all_debug013<br />' . PHP_EOL);
if(!include('..' . DS . 'LOM' . DS . 'O.php')) {
	print('<a href="https://www.phpclasses.org/package/12467-PHP-Query-XML-documents-to-extract-content-by-name.html">LOM</a> is required');exit(0);
}
//include('i.php');
//print('all_debug014<br />' . PHP_EOL);

$scenario = get_by_request('scenario');
//print('$scenario: ');var_dump($scenario);
if($scenario !== false && $scenario !== NULL) { // then load the files to set up the scenario
	//print('sce27640<br>');
	$scenario_dir = 'bba' . DS . 'scenario' . $scenario;
	$scenario_handle = opendir($scenario_dir);
	while(($entry = readdir($scenario_handle)) !== false) {
		//print('sce27641<br>');
		if($entry === '.' || $entry === '..') {
			//print('sce27642<br>');
		} else {
			//print('sce27643<br>');
			copy($scenario_dir . DS . $entry, 'bba' . DS . $entry);
		}
	}
	closedir($scenario_handle);
}
//print('all_debug015<br />' . PHP_EOL);

$units = new O('bba' . DS . 'units.xml');
//$all_units = $units->_('unit'); // set the context, which should persist throughout this code
$teams = new O('bba' . DS . 'teams.xml');
//$all_teams = $teams->_('team'); // set the context, which should persist throughout this code
$locations = new O('bba' . DS . 'locations.xml');
//$all_locations = $locations->_('location'); // set the context, which should persist throughout this code
$GLOBALS['idcounter'] = file_get_contents('bba' . DS . 'idcounter.txt');
//print('all_debug016<br />' . PHP_EOL);

// apply location actions
//$searchpossibility_by_request = get_by_request('searchpossibility');
$searchlocation_by_request = get_by_request('searchlocation');
if($searchlocation_by_request != false) {
	//print('all_debug017<br />' . PHP_EOL);
	//if(strpos($searchpossibility_by_request, ',') !== false) {
	//	$searchpossibilities = explode(',', $searchpossibility_by_request);
	//} else {
	//	$searchpossibilities = array($searchpossibility_by_request);
	//}
	//$search_result = $searchpossibilities[rand(0, sizeof($searchpossibilities) - 1)];
	$search_probabilities = array();
	foreach($locations->_('searchpossibility', '.location_id=' . $searchlocation_by_request) as $index => $searchpossibility) {
		$search_probabilities[$locations->_('name', $searchpossibility)] = $locations->_('probability', $searchpossibility);
	}
	$keep_rolling = true;
	//print('all_debug018<br />' . PHP_EOL);
	while($keep_rolling) {
		//print('all_debug019<br />' . PHP_EOL);
		$search_result = i::roll($search_probabilities);
		if(strpos($search_result, 'unique unit') !== false) {
			$unique_units = new O('bba' . DS . str_replace(' ', '_', $search_result) . 's.xml');
			if(sizeof($unique_units->_('unit')) === 0) {
				continue;
			}
			$unique_unit = $unique_units->random('unit');
			$unique_unit_name = $unique_units->_('unit_name', $unique_unit);
			$unique_unit[0] = str_replace('<id>0</id>', '<id>' . $GLOBALS['idcounter'] . '</id>', $unique_unit[0]);
			$GLOBALS['idcounter']++;
			//print('$unique_unit, $unique_unit_name: ');var_dump($unique_unit, $unique_unit_name);
			$units->new_($unique_unit);
			$unique_units->delete($unique_unit);
			$unique_units->save();
			print($unique_unit_name . ' was recruited!<br>');
			$keep_rolling = false;
		} elseif($search_result === 'unit') {
			$new_unit_string = generate_unit();
			$new_unit_string = preg_replace('/<team>[0-9]<\/team>/is', '<team>1</team>', $new_unit_string);
			$new_unit_string = preg_replace('/<location>[0-9]<\/location>/is', '<location>pen</location>', $new_unit_string);
			//print('$new_unit_string in generating unit as result of search: ');var_dump($new_unit_string);exit(0);
			$units->new_($new_unit_string);
			print('A new unit was recruited.<br>');
			$keep_rolling = false;
		} elseif($search_result === 'gain currency') {
			//$rand = rand(1, 10);
			$rand = 5 * quadratic_rand() - rand(0, 4);
			$teams->add($rand, 'currency', '.team_id=1');
			print($rand . ' currency was gained.<br>');
			$keep_rolling = false;
		} elseif($search_result === 'lose currency') {
			//$rand = rand(1, 10);
			$rand = 5 * quadratic_rand() - rand(0, 4);
			$teams->subtract($rand, 'currency', '.team_id=1');
			print($rand . ' currency was lost.<br>');
			$keep_rolling = false;
		} else {
			fatal_error('unknown search result: ' . $search_result);
		}
	}
}
//print('all_debug020<br />' . PHP_EOL);
$recruitarchetype_by_request = get_by_request('recruitarchetype');
if($recruitarchetype_by_request != false) {
	$new_unit_string = generate_unit($recruitarchetype_by_request, $i);
	$new_unit_string = preg_replace('/<team>[0-9]<\/team>/is', '<team>1</team>', $new_unit_string);
	$new_unit_string = preg_replace('/<location>[0-9]<\/location>/is', '<location>pen</location>', $new_unit_string);
	$units->new_($new_unit_string);
	print('A new ' . $recruitarchetype_by_request . ' was recruited.<br>');
}
$cost_by_request = get_by_request('cost');
if($cost_by_request != false) {
	$teams->subtract($cost_by_request, 'currency', '.team_id=1');
}
//print('all_debug021<br />' . PHP_EOL);

//$units->set_variable('all_units', $units->_('unit'));

$units->reset_context();
$unit_groups = array();
//$unit_groups = array('pen' => array());
foreach($locations->_('location') as $location) {
	$unit_groups[$locations->_('id', $location)] = array(array(), array());
}
$allies_counter = 0;
//print('$units->_(\'unit\'): ');$units->var_dump_full($units->_('unit'));exit(0);
//print('$units, $unit_groups: ');$units->var_dump_full($units, $unit_groups);
//foreach($units->v('all_units') as $unit) {
//foreach($all_units as $unit) {
foreach($units->_('unit') as $unit) {
	//print('$unit, $units->_(\'location\', $unit), $units->_(\'team\', $unit): ');$units->var_dump_full($unit, $units->_('location', $unit), $units->_('team', $unit));
	$unit_groups[$units->_('location', $unit)][$units->_('team', $unit)][] = $unit;
	if($units->_('team', $unit) === '1') {
		$allies_counter++;
	}
}
//print('all_debug022<br />' . PHP_EOL);
//print('first $allies_counter: ');var_dump($allies_counter);exit(0);
//print('first $unit_groups: ');var_dump($unit_groups);

$calculate_income = get_by_request('calculate_income');
//print('$calculate_income: ');var_dump($calculate_income);
if($calculate_income === 'true') {
	// income and costs
	//print('all_debug022.2<br />' . PHP_EOL);
	$locations->reset_context();
	$team0_income = 0;
	$team1_income = 0;
	foreach($locations->_('location') as $location_index => $location) {
		//print('$location in calculate_income: ');var_dump($location);
		// earn income up to the maximum the location allows within the fractional control of the location
		$team0_income_at_location = 0;
		$team1_income_at_location = 0;
		$team0_maximum_income_at_location = floor($locations->_('income', $location) / $locations->_('control_maximum', $location) * $locations->_('control_team@id=0', $location));
		$team1_maximum_income_at_location = floor($locations->_('income', $location) / $locations->_('control_maximum', $location) * $locations->_('control_team@id=1', $location));
		//$team0_units_at_location = $units->_('.unit_location=' . $locations->_('id', $location), '.unit_team=0');
		//$team1_units_at_location = $units->_('.unit_location=' . $locations->_('id', $location), '.unit_team=1');
		foreach($unit_groups[$locations->_('id', $location)][0] as $index0 => $team0_unit) {
			$team0_income_at_location += $units->_('income', $team0_unit);
		}
		foreach($unit_groups[$locations->_('id', $location)][1] as $index1 => $team1_unit) {
			$team1_income_at_location += $units->_('income', $team1_unit);
		}
		if($team0_income_at_location > $team0_maximum_income_at_location) {
			$team0_income_at_location = $team0_maximum_income_at_location;
		}
		if($team1_income_at_location > $team1_maximum_income_at_location) {
			$team1_income_at_location = $team1_maximum_income_at_location;
		}
		$team0_income += $team0_income_at_location;
		$team1_income += $team1_income_at_location;
	}
	//print('all_debug022.3<br />' . PHP_EOL);
	$units->reset_context();
	$team0_costs = $units->sum('cost', '.unit_team=0'); // maybe optimizable
	$team1_costs = $units->sum('cost', '.unit_team=1'); // maybe optimizable
	//print('$units->_(\'cost\', \'.unit_team=0\'), $units->_(\'cost\', \'.unit_team=1\'), $team0_income, $team1_income, $team0_costs, $team1_costs: ');var_dump($units->_('cost', '.unit_team=0'), $units->_('cost', '.unit_team=1'), $team0_income, $team1_income, $team0_costs, $team1_costs);exit(0);
	$teams->add($team0_income, 'currency', '.team_id=0');
	$teams->add($team1_income, 'currency', '.team_id=1');
	$teams->subtract($team0_costs, 'currency', '.team_id=0');
	$teams->subtract($team1_costs, 'currency', '.team_id=1');
	print($teams->_('name', '.team_id=0') . ' had costs of ' . $team0_costs . ' and income of ' . $team0_income . '.<br>');
	print($teams->_('name', '.team_id=1') . ' had costs of ' . $team1_costs . ' and income of ' . $team1_income . '.<br>');
	//print('all_debug022.4<br />' . PHP_EOL);
	// enemy actions
	include('bba' . DS . 'AI_placement.php');
	//print('all_debug022.5<br />' . PHP_EOL);
	// units in the pen recover (want to recover only once per unit placement phase) (also want the recovery to show; might have to use live variables)
	//$all_units = $units->_('unit');
	//print('$units->v(\'all_units\'): ');$units->var_dump_full($units->v('all_units'));
	// unlikely to have 2-digit stamina, but whatever
	//print('$set_variable_result, $units->v(\'all_units\'): ');var_dump($set_variable_result, $units->v('all_units'));
	$units->reset_context();
	//print('all_debug022.6<br />' . PHP_EOL);
	$all_units = $units->_('unit');
	//print('all_debug022.7<br />' . PHP_EOL);
	//print('$units->_(\'unit\'): ');$units->var_dump_full($units->_('unit'));
	//foreach($units->v('all_units') as $unit_index => $unit) {
	$counter = sizeof($all_units) - 1;
	//print('all_debug023<br />' . PHP_EOL);
	while($counter > -1) {
		//print('all_debug024<br />' . PHP_EOL);
		//print('$unit, $units->_(\'stamina_maximum\', $units->v(\'all_units\')[$unit_index]), $units->_(\'stamina_current\', $units->v(\'all_units\')[$unit_index]), $units->context: ');var_dump($unit, $units->_('stamina_maximum', $units->v('all_units')[$unit_index]), $units->_('stamina_current', $units->v('all_units')[$unit_index]), $units->context);
		//print('$all_units[$counter], $units->_(\'stamina_maximum\', $all_units[$counter]), $units->_(\'stamina_current\', $all_units[$counter]), $units->context: ');var_dump($all_units[$counter], $units->_('stamina_maximum', $all_units[$counter]), $units->_('stamina_current', $all_units[$counter]), $units->context);
		//$possible_stamina_recovery = $units->_('stamina_maximum', $units->v('all_units')[$unit_index]) - $units->_('stamina_current', $units->v('all_units')[$unit_index]);
		$possible_stamina_recovery = $units->_('stamina_maximum', $all_units[$counter]) - $units->_('stamina_current', $all_units[$counter]);
		if($possible_stamina_recovery > 2) {
			$stamina_recovery = 2;
		} else {
			$stamina_recovery = $possible_stamina_recovery;
		}
		//print('$unit, $possible_stamina_recovery, $stamina_recovery: ');var_dump($unit, $possible_stamina_recovery, $stamina_recovery);
		//$units->add($stamina_recovery, 'stamina_current', $units->v('all_units')[$unit_index]);
		$units->add($stamina_recovery, 'stamina_current', $all_units[$counter]);
		//print('$units->v(\'all_units\'): ');$units->var_dump_full($units->v('all_units'));
		$counter--;
	}
	//$units->clear_variable('all_units');
	print('all units have gained 2 stamina<br>');
	$units->reset_context();
	$units_in_pen = $units->_('.unit_location=pen&team=1');
	//$units->set_variable('units_in_pen', $units->_('.unit_location=pen&team=1'));
	//print('$units_in_pen: ');var_dump($units_in_pen);
	//foreach($units->v('units_in_pen') as $unit_in_pen_index => $unit_in_pen) {
	$counter = sizeof($units_in_pen) - 1;
	//print('all_debug024.1<br />' . PHP_EOL);
	while($counter > -1) {
		//print('all_debug024.2<br />' . PHP_EOL);
		$possible_health_recovery = $units->_('health_maximum', $units_in_pen[$counter]) - $units->_('health_current', $units_in_pen[$counter]);
		if($possible_health_recovery > 10) {
			$health_recovery = 10;
		} else {
			$health_recovery = $possible_health_recovery;
		}
		$units->add($health_recovery, 'health_current', $units_in_pen[$counter]);
		$counter--;
	}
	//$units->clear_variable('units_in_pen');
	print('units in the pen have gained 10 health<br>');
	// it would probably be good to do something like heal 5 health for every point of stamina that is overhealed
}

//print('all_debug025<br />' . PHP_EOL);
// control change
// will become tricky with multi-team battles
$control_change = get_by_request('control_change');
$combat_location = get_by_request('combat_location');
//print('$control_change, $combat_location: ');var_dump($control_change, $combat_location);
if($control_change != false) {
	preg_match('/(.+)to(.+)/is', $control_change, $matches);
	$team = $matches[2];
	if($team === '0') {
		$other_team = '1';
	} else {
		$other_team = '0';
	}
	$to_add = $matches[1];
	//print('all_debug026<br />' . PHP_EOL);
	while($to_add > 0) {
		//print('all_debug027<br />' . PHP_EOL);
		if($locations->_('location_control_team@id=0', '.location_id=' . $combat_location) + $locations->_('location_control_team@id=1', '.location_id=' . $combat_location) < $locations->_('location_control_maximum', '.location_id=' . $combat_location)) {
			$locations->increment('location_control_team@id=' . $team, '.location_id=' . $combat_location);
			print($teams->_('name', '.team_id=' . $team) . ' gained 1 point of control in ' . $locations->_('location_name', '.location_id=' . $combat_location) . '.<br>');
		} elseif($locations->_('location_control_team@id=' . $other_team, '.location_id=' . $combat_location) > 0) {
			$locations->decrement_zero_floor('location_control_team@id=' . $other_team, '.location_id=' . $combat_location);
			print($teams->_('name', '.team_id=' . $other_team) . ' lost 1 point of control in ' . $locations->_('location_name', '.location_id=' . $combat_location) . '.<br>');
		}
		$to_add--;
	}
}

//if($control_change != false) {
//	print();
//	$combat_locations_string = get_by_request('designated_combat_locations');
//	$combat_locations = explode(',', $combat_locations_string);
//} else {
	//print('all_debug028<br />' . PHP_EOL);
	$designated_combat_locations = array();
	$other_combat_locations_by_request = get_by_request('other_combat_locations');
	if($other_combat_locations_by_request !== false && $other_combat_locations_by_request !== NULL) {
		if(strpos($other_combat_locations_by_request, ',') !== false) {
			foreach(explode(',', $other_combat_locations_by_request) as $index => $value) {
				$designated_combat_locations[$value] = true;
			}
		} else {
			$designated_combat_locations[$other_combat_locations_by_request] = true; // this maybe catching zero length string, which for now seems to be a harmless bug
		}
	} elseif(get_by_request('phase') === 'combat') { // designate all locations as combat locations to start {
		$locations->reset_context();
		foreach($locations->_('location') as $location) {
			$designated_combat_locations[$locations->_('id', $location)] = true;
			// uncontested control changes
			$uncontested_control_change_here = false;
			$combat_location = $locations->_('id', $location);
			//if(sizeof($units->_('.unit_location=' . $combat_location . '&team=0')) === 0 && sizeof($units->_('.unit_location=' . $combat_location . '&team=1')) > 0) { // no enemies and some allies
			if(sizeof($unit_groups[$combat_location][0]) === 0 && sizeof($unit_groups[$combat_location][1]) > 0) { // no enemies and some allies
				$control_change = '2to1';
				$uncontested_control_change_here = true;
			//} elseif(sizeof($units->_('.unit_location=' . $combat_location . '&team=1')) === 0 && sizeof($units->_('.unit_location=' . $combat_location . '&team=0')) > 0) { // no allies and some enemies
			} elseif(sizeof($unit_groups[$combat_location][0]) === 0 && sizeof($unit_groups[$combat_location][1]) > 0) { // no allies and some enemies
				$control_change = '2to0';
				$uncontested_control_change_here = true;
			}
			//print('$control_change, $combat_location: ');var_dump($control_change, $combat_location);
			if($uncontested_control_change_here) {
				preg_match('/(.+)to(.+)/is', $control_change, $matches);
				$team = $matches[2];
				if($team === '0') {
					$other_team = '1';
				} else {
					$other_team = '0';
				}
				$to_add = $matches[1];
				//print('all_debug028.1<br />' . PHP_EOL);
				while($to_add > 0) {
					//print('all_debug028.2<br />' . PHP_EOL);
					if($locations->_('location_control_team@id=0', '.location_id=' . $combat_location) + $locations->_('location_control_team@id=1', '.location_id=' . $combat_location) < $locations->_('location_control_maximum', '.location_id=' . $combat_location)) {
						$locations->increment('location_control_team@id=' . $team, '.location_id=' . $combat_location);
						print($teams->_('name', '.team_id=' . $team) . ' gained 1 point of control in ' . $locations->_('location_name', '.location_id=' . $combat_location) . '.<br>');
					} elseif($locations->_('location_control_team@id=' . $other_team, '.location_id=' . $combat_location) > 0) {
						$locations->decrement_zero_floor('location_control_team@id=' . $other_team, '.location_id=' . $combat_location);
						print($teams->_('name', '.team_id=' . $other_team) . ' lost 1 point of control in ' . $locations->_('location_name', '.location_id=' . $combat_location) . '.<br>');
					}
					$to_add--;
				}
			}
		}
	}
	//print('all_debug029<br />' . PHP_EOL);
	//$combat_locations_by_request = get_by_request('combat_locations');
	//print('$unit_groups: ');var_dump($unit_groups);
	$combat_locations_string = '';
	$there_is_at_least_one_combat_location = false;
	$there_is_at_least_one_combat_location_left = false;
	$locations->reset_context();
	foreach($locations->_('location') as $location) {
		//if(sizeof($unit_groups[$locations->_('id', $location)][0]) > 0 && sizeof($unit_groups[$locations->_('id', $location)][1]) > 0 && $designated_combat_locations[$locations->_('id', $location)]) {
		//if(sizeof($units->_('.unit_location=' . $locations->_('id', $location) . '&team=0')) > 0 && sizeof($units->_('.unit_location=' . $locations->_('id', $location) . '&team=1')) > 0 && $designated_combat_locations[$locations->_('id', $location)] === true) {
		//print('$location, $locations->_(\'id\', $location), $unit_groups[$locations->_(\'id\', $location)][0], $unit_groups[$locations->_(\'id\', $location)][1]: ');var_dump($location, $locations->_('id', $location), $unit_groups[$locations->_('id', $location)][0], $unit_groups[$locations->_('id', $location)][1]);
		//if(sizeof($unit_groups[$locations->_('id', $location)][0]) > 0 && sizeof($unit_groups[$locations->_('id', $location)][1]) > 0 && $designated_combat_locations[$locations->_('id', $location)] === true) {
		//if(sizeof($unit_groups[$locations->_('id', $location)][0]) > 0 && $designated_combat_locations[$locations->_('id', $location)] === true) {
		if((!isset($unit_groups[$locations->_('id', $location)][0]) || sizeof($unit_groups[$locations->_('id', $location)][0]) > 0) && (!isset($unit_groups[$locations->_('id', $location)][1]) || sizeof($unit_groups[$locations->_('id', $location)][1]) > 0) && $designated_combat_locations[$locations->_('id', $location)] === true) { // guess
			$combat_locations[$locations->_('id', $location)] = true;
			$combat_locations_string .= $locations->_('id', $location) . ',';
			$there_is_at_least_one_combat_location = true;
			$there_is_at_least_one_combat_location_left = true;
		} else {
			$combat_locations[$locations->_('id', $location)] = false;
		}
	}
	if($there_is_at_least_one_combat_location) {
		$combat_locations_string = substr($combat_locations_string, 0, strlen($combat_locations_string) - 1);
	}
//}
//$phase = get_by_request('phase');
//print('all_debug030<br />' . PHP_EOL);
if($there_is_at_least_one_combat_location_left) {
	$phase = 'combat';
} else {
	$phase = 'placement';
}

//print('all_debug031<br />' . PHP_EOL);
//print('$phase, $other_combat_locations_by_request, $designated_combat_locations, $combat_locations, $combat_locations_string: ');$units->var_dump_full($phase, $other_combat_locations_by_request, $designated_combat_locations, $combat_locations, $combat_locations_string);
if($phase === 'combat') {
	print('<h2>combat phase</h2>');
} else {
	print('<h2 style="float: left;">unit placement phase</h2><p style="float: left; padding-bottom: 10px; margin-left: 10px;">(drag allies to place them)</p>');
}
//print('$there_is_at_least_one_combat_location_left, $phase: ');var_dump($there_is_at_least_one_combat_location_left, $phase);

// organize the units
// what about enemy pen?
//$unit_teams = array('pen' => array());
//foreach($teams->_('team') as $team) {
//	$unit_teams[$teams->_('id', $team)] = array();
//}

//print('$allies_counter: ');var_dump($allies_counter);
//print('$unit_groups: ');var_dump($unit_groups);

//if($phase === 'combat' && $combat_locations_string !== false && $combat_locations_string !== NULL) { // calculate the combat locations
//	foreach($locations->_('location') as $location) {
//		if(sizeof($unit_groups[$locations->_('id', $location)][0]) > 0 && sizeof($unit_groups[$locations->_('id', $location)][1]) > 0) {
//			$combat_locations[$locations->_('id', $location)] = true;
//		} else {
//			$combat_locations[$locations->_('id', $location)] = false;
//		}
//	}
//} else {
//	$combat_locations_array = explode(',', substr($combat_locations_string, 0, strlen($combat_locations_string) - 1));
//	foreach($locations->_('location') as $location) {
//		foreach($combat_locations_array as $index => $value) {
//			if($value === $locations->_('id', $location)) {
//				$combat_locations[$locations->_('id', $location)] = true;
//				continue 2;
//			}
//		}
//		$combat_locations[$locations->_('id', $location)] = false;
//	}
//}

// second time this is being calculated since stamina and health were updated
//if($calculate_income != false) {
	//$unit_groups = array();
	$unit_groups = array('pen' => array(array(), array()));
	//print('1.5 $unit_groups, $locations->_(\'location\'): ');$units->var_dump_full($unit_groups, $locations->_('location'));
	$locations->reset_context();
	foreach($locations->_('location') as $location) {
		$unit_groups[$locations->_('id', $location)] = array(array(), array());
	}
	$units->reset_context();
	foreach($units->_('unit') as $unit) {
		$unit_groups[$units->_('location', $unit)][$units->_('team', $unit)][] = $unit;
	}
//}

//print('all_debug032<br />' . PHP_EOL);
//print('second $unit_groups: ');$units->var_dump_full($unit_groups);
foreach($unit_groups as $location_id => $units_in_group) {
	if($location_id === 'pen') { // print the pen last
		continue;
	}
	print('<table border="1" cellspacing="0" cellpadding="4" location="' . $location_id . '" style="clear: both;">
<caption><strong>' . $locations->_('location_name', $locations->_('.location_id=' . $location_id)) . ' (income: ' . $locations->_('location_income', $locations->_('.location_id=' . $location_id)) . ')</strong></caption>
<thead>
<tr>
<th scope="col">control (maximum: ' . $locations->_('control_maximum', $locations->_('.location_id=' . $location_id)) . ')</th>
<th colspan="6" scope="colgroup">units');
	if($phase === 'combat' && $combat_locations[$location_id]) {
		print('<form action="bbabat.php" method="post">
<input type="hidden" name="combat_location" value="' . $location_id . '" />
<input type="hidden" name="other_combat_locations" value="' . other_combat_locations_string($location_id, $combat_locations) . '" />
<input type="submit" value="resolve combat" />
</form>');
	}
	$control = $locations->_('control_team@id=0', $locations->_('.location_id=' . $location_id));
	print('</th>
<th scope="col">actions</th>
</tr>
</thead>
<tbody>
<tr class="enemy_background">
<th>' . $teams->_('name', $teams->_('.team_id=0')) . ' (control: ' . $control . ')</th>');
	// could have a loop if there were more than 2 teams
	$box_counter = 0;
	//print('all_debug032.1<br />' . PHP_EOL);
	while($box_counter < 6) {
		//print('all_debug032.2<br />' . PHP_EOL);
		// if there's a unit here then print it, otherwise print an empty box
		if(isset($units_in_group[0][$box_counter])) {
			print('<td>' . unit_card($units_in_group[0][$box_counter], $units) . '</td>
');
		} else {
			print('<td></td>
');
		}
		$box_counter++;
	}
	$control = $locations->_('control_team@id=1', $locations->_('.location_id=' . $location_id));
	print('<td></td>
</tr>
<tr class="ally_background">
<th>' . $teams->_('name', $teams->_('.team_id=1')) . ' (control: ' . $control . ')</th>');
	$box_counter = 0;
	//print('all_debug032.3<br />' . PHP_EOL);
	while($box_counter < 6) {
		//print('all_debug032.4<br />' . PHP_EOL);
		// if there's a unit here then print it, otherwise print an empty box
		if(isset($units_in_group[1][$box_counter])) {
			//if($phase === 'combat' && $combat_locations_by_request !== false && $combat_locations_by_request !== NULL) {
			if($phase === 'combat') {
				print('<td>' . unit_card($units_in_group[1][$box_counter], $units) . '</td>');
			} else {
				print_swap_box(unit_card($units_in_group[1][$box_counter], $units));
			}
		} else {
			//if($phase === 'combat' && $combat_locations_by_request !== false && $combat_locations_by_request !== NULL) {
			if($phase === 'combat') {
				print('<td></td>');
			} else {
				print_swap_box();
			}
		}
		$box_counter++;
	}
	print('<td>
');
	//print('all_debug033<br />' . PHP_EOL);
	$locations->reset_context();
	foreach($locations->_('action', '.location_id=' . $location_id) as $action_index => $action) {
		//print('$location_id, $action: ');var_dump($location_id, $action);
		//print('$action, $locations->_(\'action_name\', $action): ');var_dump($action, $locations->_('action_name', $action));
		$action_name = $locations->_('action_name', $action); // hack for unknown reason
		//$action_name = $locations->_('name', $action);
		//print('$action_name: ');var_dump($action_name);
		if($phase !== 'combat' && $control >= $locations->_('controlrequired', $action) && $teams->_('currency', '.team_id=1') >= $locations->_('cost', $action)) {
			if(is_string($locations->_('recruitarchetype', $action))) {
				$action_input = '<input type="hidden" name="recruitarchetype" value="' . $locations->_('recruitarchetype', $action) . '" />
<input type="hidden" name="cost" value="' . $locations->_('cost', $action) . '" />';
//			} elseif(sizeof($locations->_('searchpossibility', $action)) > 1) {
//				$action_input = '<input type="hidden" name="searchpossibility" value="' . implode(',', $locations->_('searchpossibility', $action)) . '" />
//<input type="hidden" name="cost" value="' . $locations->_('cost', $action) . '" />';
			} else {
				$action_input = '<input type="hidden" name="searchlocation" value="' . $location_id . '" />
<input type="hidden" name="cost" value="' . $locations->_('cost', $action) . '" />';
			}
			print('<form action="bbalof.php" method="post">
<input type="hidden" name="phase" value="placement" />
' . $action_input . '
<input type="submit" value="' . $action_name . '">
</form>');
		} else {
			print($action_name);
		}
		//print('$action, $locations->_(\'action_name\', $action)2: ');var_dump($action, $locations->_('action_name', $action));
		if($control >= $locations->_('controlrequired', $action)) {
			print(' <span style="color: green;">(requires ' . $locations->_('controlrequired', $action) . ' control)</span>');
		} else {
			print(' <span style="color: red;">(requires ' . $locations->_('controlrequired', $action) . ' control)</span>');
		}
		if($teams->_('currency', '.team_id=1') >= $locations->_('cost', $action)) {
			print(' <span style="color: green;">(cost: ' . $locations->_('cost', $action) . ')</span><br>');
		} else {
			print(' <span style="color: red;">(cost: ' . $locations->_('cost', $action) . ')</span><br>');
		}
	}
	print('</td>
</tr>
</tbody>
</table>');
}

//print('all_debug034<br />' . PHP_EOL);
$pen_boxes = $allies_counter;
if($pen_boxes > 12) {
	$pen_boxes = 12;
}
// print the pen
print('<div style="padding-bottom: 330px;"></div>
<div style="position: fixed; bottom: 0px; background-color: #FFF;">
<table border="1" cellspacing="0" cellpadding="4" location="pen">
<caption><strong>pen</strong></caption>
<thead>
<tr>
<td></td>
<th colspan="' . $pen_boxes . '" scope="colgroup">units</th>
</tr>
</thead>
<tbody>
<tr class="ally_background" style="height: 160px;">
<th>' . $teams->_('name', $teams->_('.team_id=1')) . '</th>');
	//$units_in_group = $unit_groups['pen'];
	//$units_in_pen = $units->_('.unit_location=pen');
	$box_counter = 0;
	//print('all_debug035<br />' . PHP_EOL);
	while($box_counter < $pen_boxes) {
		//print('all_debug036<br />' . PHP_EOL);
		// if there's a unit here then print it, otherwise print an empty box
		if(isset($unit_groups['pen'][1][$box_counter])) {
			//if($phase === 'combat' && $combat_locations_by_request !== false && $combat_locations_by_request !== NULL) {
			if($phase === 'combat') {
				print('<td>' . unit_card($unit_groups['pen'][1][$box_counter], $units) . '</td>');
			} else {
				print_swap_box(unit_card($unit_groups['pen'][1][$box_counter], $units));
			}
		} else {
			//if($phase === 'combat' && $combat_locations_by_request !== false && $combat_locations_by_request !== NULL) {
			if($phase === 'combat') {
				print('<td></td>');
			} else {
				print_swap_box();
			}
		}
		$box_counter++;
	}
	print('</tr>
</tbody>
</table>
<div>');

//print('all_debug037<br />' . PHP_EOL);
$units->reset_context();
//print('$teams->_(\'.team_id=1\'), $teams->_(\'currency\', \'.team_id=1\'): ');var_dump($teams->_('.team_id=1'), $teams->_('currency', '.team_id=1'));exit(0);
print('currency: ' . $teams->_('currency', '.team_id=1') . '<br>');
if($allies_counter === 0) { // what about ability to recruit?
	print('You lose.<br>
go to <a href="bbalof.php?scenario=0">scenario 0</a><br>
go to <a href="bbalof.php?scenario=1">scenario 1</a><br>
go to <a href="bbalof.php?scenario=2">scenario 2</a>');
} elseif(sizeof($units->_('.unit_team=0')) === 0) {
	print('You win!<br>
go to <a href="bbalof.php?scenario=0">scenario 0</a><br>
go to <a href="bbalof.php?scenario=1">scenario 1</a><br>
go to <a href="bbalof.php?scenario=2">scenario 2</a>');
} elseif($phase === 'combat' && strlen($combat_locations_string) > 0) {
	//print('$combat_locations: ');var_dump($combat_locations);
} else {
	// kind of ugly to calculate income before combat but so far this seems to be the only deterministic change in phase
	print('<form action="bbalof.php" method="post">
<input type="hidden" name="calculate_income" value="true" />
<input type="hidden" name="phase" value="combat" />
<input type="submit" value="proceed to combat phase">
</form>');
}

$units->save_LOM_to_file();
//if($scenario !== false && $scenario !== NULL) {
	$teams->save_LOM_to_file();
	$locations->save_LOM_to_file();
//}
file_put_contents('bba' . DS . 'idcounter.txt', $GLOBALS['idcounter']);
//print('all_debug038<br />' . PHP_EOL);
?>
 
</body>
</html>
