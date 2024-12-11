<html>
<head>
<meta charset="utf-8">
<style type="text/css">
form { margin: 0; }
td, th { vertical-align: top; }
.enemy_background { background-color: #FEE; }
.ally_background { background-color: #EEF; }
table { margin-top: 10px; }
</style>

<?php
//print('bbabat000<br />' . PHP_EOL);exit(0);
define('DS', DIRECTORY_SEPARATOR);
if(!include('..' . DS . 'LOM' . DS . 'O.php')) {
	print('<a href="https://www.phpclasses.org/package/12467-PHP-Query-XML-documents-to-extract-content-by-name.html">LOM</a> is required');exit(0);
}
//$initial_time = O::getmicrotime();
//include('units.php');
include('i.php');
//$team1 = new O('bba' . DS . 'battleteamplayer.xml');
//$team0 = new O('bba' . DS . 'battleteamAI.xml');
$units = new O('bba' . DS . 'units.xml');
$teams = new O('bba' . DS . 'teams.xml');
$unit_type_interactions = new O('bba' . DS . 'unit_type_interactions.xml');

$total_turns = get_by_request('total_turns');
$turn_number = get_by_request('turn_number');
$combat_location = get_by_request('combat_location');
$other_combat_locations = get_by_request('other_combat_locations');
$unit1_id_by_request = get_by_request('unit1_id');
$unit0_id_by_request = get_by_request('unit0_id');
//print('$total_turns, $turn_number, $combat_location, $other_combat_locations: ');var_dump($total_turns, $turn_number, $combat_location, $other_combat_locations);
$units->_('.unit_location=' . $combat_location); // set the context, which should persist throughout this code
$player_units = $units->_('.unit_team=1&location=' . $combat_location);
$AI_units = $units->_('.unit_team=0&location=' . $combat_location);
$team1_capture_tries = 1;
$team1_save_tries = 1;
$team0_capture_tries = 1;
$team0_save_tries = 1;
//print('$player_units, $AI_units: ');var_dump($player_units, $AI_units);
//print('here0001<br>');

print('<table border="1" cellspacing="0" cellpadding="4">
<caption><strong>previous turn details</strong></caption>
<tr>');
$number_of_team1_units_with_health = 0;
foreach($units->_('.unit_team=1&location=' . $combat_location) as $team1_unit) {
	if($units->_('health_current', $team1_unit) > 0) {
		$number_of_team1_units_with_health++;
	}
}
$number_of_team0_units_with_health = 0;
foreach($units->_('.unit_team=0&location=' . $combat_location) as $team0_unit) {
	if($units->_('health_current', $team0_unit) > 0) {
		$number_of_team0_units_with_health++;
	}
}
if($total_turns == false) {
	//$total_turns = max(sizeof($units->_('.unit_team=1&location=' . $combat_location)), sizeof($units->_('.unit_team=0&location=' . $combat_location)));
	$total_turns = max($number_of_team1_units_with_health, $number_of_team0_units_with_health); // more precise
	$turn_number = 1;
} else {
	// process previous turn's fight
	//$units->_('.unit_id=' . $unit1_id_by_request) = $units->_('.unit_id=' . $units->_('.unit_id=' . $unit1_id_by_request)_id_by_request);
	//print('$units->_('.unit_id=' . $unit1_id_by_request)_id_by_request, initial $units->_('.unit_id=' . $unit1_id_by_request): ');var_dump($units->_('.unit_id=' . $unit1_id_by_request)_id_by_request, $units->_('.unit_id=' . $unit1_id_by_request));
	//$units->_('.unit_id=' . $unit0_id_by_request) = $units->_('.unit_id=' . $units->_('.unit_id=' . $unit0_id_by_request)_id_by_request);
	//print('$units->_('.unit_id=' . $unit0_id_by_request)_id_by_request, initial $units->_('.unit_id=' . $unit0_id_by_request): ');var_dump($units->_('.unit_id=' . $unit0_id_by_request)_id_by_request, $units->_('.unit_id=' . $unit0_id_by_request));
	if($units->_('range', $units->_('.unit_id=' . $unit1_id_by_request)) > $units->_('range', $units->_('.unit_id=' . $unit0_id_by_request))) { // unit1 attacks first
		if($units->_('stamina_current', $units->_('.unit_id=' . $unit1_id_by_request)) === '0') {
			print($units->_('unit_name', $units->_('.unit_id=' . $unit1_id_by_request)) . ' is exhausted.<br>');
		} elseif($units->_('stamina_current', $units->_('.unit_id=' . $unit0_id_by_request)) > 0 && rand(0, 100) <= (100 + $units->_('evasion', $units->_('.unit_id=' . $unit0_id_by_request)) - $units->_('precision', $units->_('.unit_id=' . $unit1_id_by_request)))) { // unit0 evades
			print($units->_('unit_name', $units->_('.unit_id=' . $unit0_id_by_request)) . ' evaded.<br>');
		} else {
			$unit0_damage_taken = $units->_('attack', $units->_('.unit_id=' . $unit1_id_by_request)) + $unit_type_interactions->_('defender@name=' . $units->_('type', $units->_('.unit_id=' . $unit0_id_by_request)), 'attacker@name=' . $units->_('type', $units->_('.unit_id=' . $unit1_id_by_request)));
			$units->subtract_zero_floor('health_current', $unit0_damage_taken, $units->_('.unit_id=' . $unit0_id_by_request));
			//$units->_('.unit_id=' . $unit1_id_by_request) = $units->_('.unit_id=' . $units->_('.unit_id=' . $unit1_id_by_request)_id_by_request); // recalculate it
		}
		if($units->_('stamina_current', $units->_('.unit_id=' . $unit0_id_by_request)) > 0 && rand(0, 100) <= $units->_('counter', $units->_('.unit_id=' . $unit0_id_by_request))) { // unit0 counters
			print($units->_('unit_name', $units->_('.unit_id=' . $unit0_id_by_request)) . ' counters.<br>');
			if($units->_('stamina_current', $units->_('.unit_id=' . $unit1_id_by_request)) > 0 && rand(0, 100) <= (100 + $units->_('evasion', $units->_('.unit_id=' . $unit1_id_by_request)) - $units->_('precision', $units->_('.unit_id=' . $unit0_id_by_request)))) { // unit1 evades
				print($units->_('unit_name', $units->_('.unit_id=' . $unit1_id_by_request)) . ' evaded.<br>');
			} else {
				$unit1_damage_taken = $units->_('attack', $units->_('.unit_id=' . $unit0_id_by_request)) + $unit_type_interactions->_('defender@name=' . $units->_('type', $units->_('.unit_id=' . $unit1_id_by_request)), 'attacker@name=' . $units->_('type', $units->_('.unit_id=' . $unit0_id_by_request)));
				$units->subtract_zero_floor('health_current', $unit1_damage_taken, $units->_('.unit_id=' . $unit1_id_by_request));
				//$units->_('.unit_id=' . $unit0_id_by_request) = $units->_('.unit_id=' . $units->_('.unit_id=' . $unit0_id_by_request)_id_by_request); // recalculate it
			}
		}
	} elseif($units->_('range', $units->_('.unit_id=' . $unit0_id_by_request)) > $units->_('range', $units->_('.unit_id=' . $unit1_id_by_request))) { // unit0 attacks first
		//print('$units->_('.unit_id=' . $unit1_id_by_request), $units->_(\'evasion\', $units->_('.unit_id=' . $unit1_id_by_request)), $units->_('.unit_id=' . $unit0_id_by_request), $units->_(\'precision\', $units->_('.unit_id=' . $unit0_id_by_request)) when unit0 attacks first: ');var_dump($units->_('.unit_id=' . $unit1_id_by_request), $units->_('evasion', $units->_('.unit_id=' . $unit1_id_by_request)), $units->_('.unit_id=' . $unit0_id_by_request), $units->_('precision', $units->_('.unit_id=' . $unit0_id_by_request)));
		if($units->_('stamina_current', $units->_('.unit_id=' . $unit0_id_by_request)) === '0') {
			print($units->_('unit_name', $units->_('.unit_id=' . $unit0_id_by_request)) . ' is exhausted.<br>');
		} elseif($units->_('stamina_current', $units->_('.unit_id=' . $unit1_id_by_request)) > 0 && rand(0, 100) <= (100 + $units->_('evasion', $units->_('.unit_id=' . $unit1_id_by_request)) - $units->_('precision', $units->_('.unit_id=' . $unit0_id_by_request)))) { // unit1 evades
			print($units->_('unit_name', $units->_('.unit_id=' . $unit1_id_by_request)) . ' evaded.<br>');
		} else {
			$unit1_damage_taken = $units->_('attack', $units->_('.unit_id=' . $unit0_id_by_request)) + $unit_type_interactions->_('defender@name=' . $units->_('type', $units->_('.unit_id=' . $unit1_id_by_request)), 'attacker@name=' . $units->_('type', $units->_('.unit_id=' . $unit0_id_by_request)));
			$units->subtract_zero_floor('health_current', $unit1_damage_taken, $units->_('.unit_id=' . $unit1_id_by_request));
			//$units->_('.unit_id=' . $unit0_id_by_request) = $units->_('.unit_id=' . $units->_('.unit_id=' . $unit0_id_by_request)_id_by_request); // recalculate it
		}
		if($units->_('stamina_current', $units->_('.unit_id=' . $unit1_id_by_request)) > 0 && rand(0, 100) <= $units->_('counter', $units->_('.unit_id=' . $unit1_id_by_request))) { // unit1 counters
			print($units->_('unit_name', $units->_('.unit_id=' . $unit1_id_by_request)) . ' counters.<br>');
			if($units->_('stamina_current', $units->_('.unit_id=' . $unit0_id_by_request)) > 0 && rand(0, 100) <= (100 + $units->_('evasion', $units->_('.unit_id=' . $unit0_id_by_request)) - $units->_('precision', $units->_('.unit_id=' . $unit1_id_by_request)))) { // unit0 evades
				print($units->_('unit_name', $units->_('.unit_id=' . $unit0_id_by_request)) . ' evaded.<br>');
			} else {
				$unit0_damage_taken = $units->_('attack', $units->_('.unit_id=' . $unit1_id_by_request)) + $unit_type_interactions->_('defender@name=' . $units->_('type', $units->_('.unit_id=' . $unit0_id_by_request)), 'attacker@name=' . $units->_('type', $units->_('.unit_id=' . $unit1_id_by_request)));
				$units->subtract_zero_floor('health_current', $unit0_damage_taken, $units->_('.unit_id=' . $unit0_id_by_request));
				//$units->_('.unit_id=' . $unit1_id_by_request) = $units->_('.unit_id=' . $units->_('.unit_id=' . $unit1_id_by_request)_id_by_request); // recalculate it
			}
		}
	} else { // both attack simultaneously
		//print('here0001.1<br>');
		//print('$units->_(\'evasion\', $units->_('.unit_id=' . $unit1_id_by_request)), $units->_(\'precision\', $units->_('.unit_id=' . $unit0_id_by_request)): ');var_dump($units->_('evasion', $units->_('.unit_id=' . $unit1_id_by_request)), $units->_('precision', $units->_('.unit_id=' . $unit0_id_by_request)));
		if($units->_('stamina_current', $units->_('.unit_id=' . $unit0_id_by_request)) === '0') {
			print($units->_('unit_name', $units->_('.unit_id=' . $unit0_id_by_request)) . ' is exhausted.<br>');
		} elseif($units->_('stamina_current', $units->_('.unit_id=' . $unit1_id_by_request)) > 0 && rand(0, 100) <= (100 + $units->_('evasion', $units->_('.unit_id=' . $unit1_id_by_request)) - $units->_('precision', $units->_('.unit_id=' . $unit0_id_by_request)))) { // unit1 evades
			print($units->_('unit_name', $units->_('.unit_id=' . $unit1_id_by_request)) . ' evaded.<br>');
		} else {
			$unit1_damage_taken = $units->_('attack', $units->_('.unit_id=' . $unit0_id_by_request)) + $unit_type_interactions->_('defender@name=' . $units->_('type', $units->_('.unit_id=' . $unit1_id_by_request)), 'attacker@name=' . $units->_('type', $units->_('.unit_id=' . $unit0_id_by_request)));
			$units->subtract_zero_floor('health_current', $unit1_damage_taken, $units->_('.unit_id=' . $unit1_id_by_request));
			//$units->_('.unit_id=' . $unit0_id_by_request) = $units->_('.unit_id=' . $units->_('.unit_id=' . $unit0_id_by_request)_id_by_request); // recalculate it
		}
		//print('here0001.2<br>');
		if($units->_('stamina_current', $units->_('.unit_id=' . $unit1_id_by_request)) === '0') {
			print($units->_('unit_name', $units->_('.unit_id=' . $unit1_id_by_request)) . ' is exhausted.<br>');
		} elseif($units->_('stamina_current', $units->_('.unit_id=' . $unit0_id_by_request)) > 0 && rand(0, 100) <= (100 + $units->_('evasion', $units->_('.unit_id=' . $unit0_id_by_request)) - $units->_('precision', $units->_('.unit_id=' . $unit1_id_by_request)))) { // unit0 evades
			//print('here0001.21<br>');
			print($units->_('unit_name', $units->_('.unit_id=' . $unit0_id_by_request)) . ' evaded.<br>');
		} else {
			//print('here0001.22<br>');
			$unit0_damage_taken = $units->_('attack', $units->_('.unit_id=' . $unit1_id_by_request)) + $unit_type_interactions->_('defender@name=' . $units->_('type', $units->_('.unit_id=' . $unit0_id_by_request)), 'attacker@name=' . $units->_('type', $units->_('.unit_id=' . $unit1_id_by_request)));
			//print('$units->_('.unit_id=' . $unit0_id_by_request) before subtract_zero_floor: ');var_dump($units->_('.unit_id=' . $unit0_id_by_request));
			$units->subtract_zero_floor('health_current', $unit0_damage_taken, $units->_('.unit_id=' . $unit0_id_by_request));
			//print('$units->_('.unit_id=' . $unit0_id_by_request) after subtract_zero_floor: ');var_dump($units->_('.unit_id=' . $unit0_id_by_request));
			//$units->_('.unit_id=' . $unit1_id_by_request) = $units->_('.unit_id=' . $units->_('.unit_id=' . $unit1_id_by_request)_id_by_request); // recalculate it
		}
	}
	if($units->_('health_current', $units->_('.unit_id=' . $unit1_id_by_request)) === '0') {
		print($units->_('unit_name', $units->_('.unit_id=' . $unit1_id_by_request)) . ' cannot fight.<br>');
	}
	if($units->_('health_current', $units->_('.unit_id=' . $unit0_id_by_request)) === '0') {
		print($units->_('unit_name', $units->_('.unit_id=' . $unit0_id_by_request)) . ' cannot fight.<br>');
	}
	//print('here0001.3<br>');
	$unit1_before_stamina_decrease = $units->_('.unit_id=' . $unit1_id_by_request);
	$units->decrement_zero_floor('stamina_current', $units->_('.unit_id=' . $unit1_id_by_request));
	//$units->_('.unit_id=' . $unit0_id_by_request) = $units->_('.unit_id=' . $units->_('.unit_id=' . $unit0_id_by_request)_id_by_request); // recalculate it
	//print('here0001.4<br>');
	print('<td class="ally_background">' . $units->_('unit_name', $units->_('.unit_id=' . $unit1_id_by_request)) . ' [' . $units->_('type', $units->_('.unit_id=' . $unit1_id_by_request)) . ']<br>
health: ' . $units->_('health_current', $units->_('.unit_id=' . $unit1_id_by_request)) . '/' . $units->_('health_maximum', $units->_('.unit_id=' . $unit1_id_by_request)));
	if($unit1_damage_taken > 0) {
		print(' <span style="color: red;">-' . $unit1_damage_taken . '</span>');
	}
	//print('$units->_(\'.unit_id=\' . $unit1_id_by_request), $units->_(\'stamina_current\', $units->_(\'.unit_id=\' . $unit1_id_by_request)), $units->_(\'stamina_maximum\', $units->_(\'.unit_id=\' . $unit1_id_by_request))1: ');var_dump($units->_('.unit_id=' . $unit1_id_by_request), $units->_('stamina_current', $units->_('.unit_id=' . $unit1_id_by_request)), $units->_('stamina_maximum', $units->_('.unit_id=' . $unit1_id_by_request)));
	print('<br>
attack: ' . $units->_('attack', $units->_('.unit_id=' . $unit1_id_by_request)) . '<br>
range: ' . $units->_('range', $units->_('.unit_id=' . $unit1_id_by_request)) . '<br>
precision: ' . $units->_('precision', $units->_('.unit_id=' . $unit1_id_by_request)) . '<br>
evasion: ' . $units->_('evasion', $units->_('.unit_id=' . $unit1_id_by_request)) . '<br>
counter: ' . $units->_('counter', $units->_('.unit_id=' . $unit1_id_by_request)) . '<br>
stamina: ' . $units->_('stamina_current', $units->_('.unit_id=' . $unit1_id_by_request)) . '/' . $units->_('stamina_maximum', $units->_('.unit_id=' . $unit1_id_by_request)));
	if($unit1_before_stamina_decrease !== $units->_('.unit_id=' . $unit1_id_by_request)) {
		print(' <span style="color: red;">-1</span>');
	}
	$unit_image = $units->_('image', $units->_('.unit_id=' . $unit1_id_by_request));
	if(is_array($unit_image)) { // presumably empty result

	} elseif(strlen($unit_image) > 0) {
		print('<br><img src="bba/images/' . $unit_image . '" /><br>');
	}
	//print('$units->_(\'.unit_id=\' . $unit1_id_by_request), $units->_(\'stamina_current\', $units->_(\'.unit_id=\' . $unit1_id_by_request)), $units->_(\'stamina_maximum\', $units->_(\'.unit_id=\' . $unit1_id_by_request))2: ');var_dump($units->_('.unit_id=' . $unit1_id_by_request), $units->_('stamina_current', $units->_('.unit_id=' . $unit1_id_by_request)), $units->_('stamina_maximum', $units->_('.unit_id=' . $unit1_id_by_request)));
	//print('here0001.5<br>');
	print('</td>
<td class="enemy_background">' . $units->_('unit_name', $units->_('.unit_id=' . $unit0_id_by_request)) . ' [' . $units->_('type', $units->_('.unit_id=' . $unit0_id_by_request)) . ']<br>
health: ' . $units->_('health_current', $units->_('.unit_id=' . $unit0_id_by_request)) . '/' . $units->_('health_maximum', $units->_('.unit_id=' . $unit0_id_by_request)));
	if($unit0_damage_taken > 0) {
		print(' <span style="color: red;">-' . $unit0_damage_taken . '</span>');
	}
	$unit0_before_stamina_decrease = $units->_('.unit_id=' . $unit0_id_by_request);
	$units->decrement_zero_floor('stamina_current', $units->_('.unit_id=' . $unit0_id_by_request));
	//$units->_('.unit_id=' . $unit1_id_by_request) = $units->_('.unit_id=' . $units->_('.unit_id=' . $unit1_id_by_request)_id_by_request); // recalculate it
	//print('$units->_(\'.unit_id=\' . $unit0_id_by_request), $units->_(\'stamina_current\', $units->_(\'.unit_id=\' . $unit0_id_by_request)), $units->_(\'stamina_maximum\', $units->_(\'.unit_id=\' . $unit0_id_by_request))1: ');var_dump($units->_('.unit_id=' . $unit0_id_by_request), $units->_('stamina_current', $units->_('.unit_id=' . $unit0_id_by_request)), $units->_('stamina_maximum', $units->_('.unit_id=' . $unit0_id_by_request)));
	print('<br>
attack: ' . $units->_('attack', $units->_('.unit_id=' . $unit0_id_by_request)) . '<br>
range: ' . $units->_('range', $units->_('.unit_id=' . $unit0_id_by_request)) . '<br>
precision: ' . $units->_('precision', $units->_('.unit_id=' . $unit0_id_by_request)) . '<br>
evasion: ' . $units->_('evasion', $units->_('.unit_id=' . $unit0_id_by_request)) . '<br>
counter: ' . $units->_('counter', $units->_('.unit_id=' . $unit0_id_by_request)) . '<br>
stamina: ' . $units->_('stamina_current', $units->_('.unit_id=' . $unit0_id_by_request)) . '/' . $units->_('stamina_maximum', $units->_('.unit_id=' . $unit0_id_by_request)));
	if($unit0_before_stamina_decrease !== $units->_('.unit_id=' . $unit0_id_by_request)) {
		print(' <span style="color: red;">-1</span>');
	}
	$unit_image = $units->_('image', $units->_('.unit_id=' . $unit0_id_by_request));
	if(is_array($unit_image)) { // presumably empty result

	} elseif(strlen($unit_image) > 0) {
		print('<br><img src="bba/images/' . $unit_image . '" /><br>');
	}
	//print('$units->_(\'.unit_id=\' . $unit0_id_by_request), $units->_(\'stamina_current\', $units->_(\'.unit_id=\' . $unit0_id_by_request)), $units->_(\'stamina_maximum\', $units->_(\'.unit_id=\' . $unit0_id_by_request))2: ');var_dump($units->_('.unit_id=' . $unit0_id_by_request), $units->_('stamina_current', $units->_('.unit_id=' . $unit0_id_by_request)), $units->_('stamina_maximum', $units->_('.unit_id=' . $unit0_id_by_request)));
	print('</td>');
}
print('</tr>
</table>
');
//print('here0002<br>');
//print('$turn_number, $total_turns: ');var_dump($turn_number, $total_turns);
$units->reset_context();
//print('$units->context before AI: ');var_dump($units->context);
$units->_('.unit_location=' . $combat_location); // setting the context to only units at this location
//print('$units->_(\'.unit_team=0&location=\' . $combat_location), $units->sum(\'health_current\', \'.unit_team=0&location=\' . $combat_location), $units->sum(\'health_current\', \'.unit_team=1&location=\' . $combat_location): ');var_dump($units->_('.unit_team=0&location=' . $combat_location), $units->sum('health_current', '.unit_team=0&location=' . $combat_location), $units->sum('health_current', '.unit_team=1&location=' . $combat_location));
print('<table border="1" cellspacing="0" cellpadding="4">
');
$fight_completed = false;
if($turn_number > $total_turns || $units->sum('health_current', '.unit_team=0&location=' . $combat_location) == 0 || $units->sum('health_current', '.unit_team=1&location=' . $combat_location) == 0) {
	$fight_completed = true;
}
if($fight_completed) {
	print('<caption><strong>no more turns</strong></caption>
<tr>');
} else {
	// for now, AI always shows their unit first
	// artificial intelligence choosing which unit to send out
	$unit_effectiveness_scores = array();
	/*if($units->sum('stamina_current', '.unit_team=0&location=' . $combat_location) * $units->sum('health_current', '.unit_team=0&location=' . $combat_location) == 0) {
		foreach($units->_('.unit_team=0&location=' . $combat_location) as $unit) {
			$unit_effectiveness_scores[$units->_('id', $unit)] = $units->_('health_current', $unit);
		}
	} else {*/
		foreach($units->_('.unit_team=0&location=' . $combat_location) as $unit) {
			//print('$unit in AI loop: ');var_dump($unit);
			if($units->_('health_current', $unit) === '0') { // don't consider it
				
			} else {
				$unit_effectiveness_scores[$units->_('id', $unit)] = $units->_('stamina_current', $unit) * $units->_('health_current', $unit);
			}
		}
	//}
	asort($unit_effectiveness_scores);
	$unit_effectiveness_scores = array_reverse($unit_effectiveness_scores, true);
	//print('$unit_effectiveness_scores: ');var_dump($unit_effectiveness_scores);
	foreach($unit_effectiveness_scores as $AI_unit_id => $score) { break; }
	print('<caption><strong>this turn (' . $turn_number . '/' . $total_turns . ')</strong></caption>
<tr>');
	$unit = $units->_('.unit_id=' . $AI_unit_id);
	//print('$unit: ');var_dump($unit);
	print('<td class="ally_background" style="min-width: 100px; height: 50px; vertical-align: middle;">[choose your unit]</td>
<td class="enemy_background">' . $units->_('unit_name', $unit) . ' [' . $units->_('type', $unit) . ']<br>
health: ' . $units->_('health_current', $unit) . '/' . $units->_('health_maximum', $unit) . '<br>
attack: ' . $units->_('attack', $unit) . '<br>
range: ' . $units->_('range', $unit) . '<br>
precision: ' . $units->_('precision', $unit) . '<br>
evasion: ' . $units->_('evasion', $unit) . '<br>
counter: ' . $units->_('counter', $unit) . '<br>
stamina: ' . $units->_('stamina_current', $unit) . '/' . $units->_('stamina_maximum', $unit) . '
');
	$unit_image = $units->_('image', $unit);
	if(is_array($unit_image)) { // presumably empty result

	} elseif(strlen($unit_image) > 0) {
		print('<br><img src="bba/images/' . $unit_image . '" /><br>');
	}
}
print('</td>
</tr>
</table>
');
//print('here0003<br>');
//$units->reset_context();
$units->_('.unit_location=' . $combat_location); // setting the context to only units at this location
// not sure about this bug?
//print('$units->_(\'.unit_team=1&location=\' . $combat_location), $units->_(\'.unit_team=0&location=\' . $combat_location): ');var_dump($units->_('.unit_team=1&location=' . $combat_location), $units->_('.unit_team=0&location=' . $combat_location));
print('<table border="1" cellspacing="0" cellpadding="4">
<caption><strong>teams information</strong></caption>
<thead>
<tr>
<th colspan="' . sizeof($units->_('.unit_team=1&location=' . $combat_location, false, false, false)) . '" scope="colgroup">' . $teams->_('name', $teams->_('.team_id=1')) . '</th>
<th colspan="' . sizeof($units->_('.unit_team=0&location=' . $combat_location, false, false, false)) . '" scope="colgroup">' . $teams->_('name', $teams->_('.team_id=0')) . '</th>
</tr>
</thead>
<tbody>
<tr>
');
//print('here0004<br>');
$team1_experience_earned = $units->sum('experience_maximum', '.unit_team=0&location=' . $combat_location);
$team0_experience_earned = $units->sum('experience_maximum', '.unit_team=1&location=' . $combat_location);
$stat_gain_probabilities = array('health' => 5, 'attack' => 5, 'precision' => 5, 'evasion' => 5, 'counter' => 5, 'stamina' => 1); // could vary by archetype
$ids_of_units_to_capture = array();
$ids_of_units_to_delete = array();
//$units->set_variable('ally_units', $units->_('.unit_team=1&location=' . $combat_location));
//$units->set_variable('enemy_units', $units->_('.unit_team=0&location=' . $combat_location));
$enemy_units = $units->_('.unit_team=0&location=' . $combat_location, false, false);
//$ally_units = $units->_('.unit_team=1&location=' . $combat_location);
$ally_unit_ids = $units->_('id', '.unit_team=1&location=' . $combat_location);
if(is_string($ally_unit_ids)) {
	$ally_unit_ids = array($ally_unit_ids);
}
//print('$ally_unit_ids: ');var_dump($ally_unit_ids);
$number_of_ally_units = sizeof($ally_unit_ids);
$number_of_enemy_units = sizeof($enemy_units);
//print('here0004.5<br>');
if(sizeof($ally_unit_ids) > 0) {
	//print('here8969945<br>');
	//$units->set_variable('ally_units', $units->_('.unit_team=1&location=' . $combat_location));
	//$unit_index = sizeof($ally_units) - 1;
	$id_index = sizeof($ally_unit_ids) - 1;
	//print('bbabat001<br />' . PHP_EOL);exit(0);
	while($id_index > -1) {
	//foreach($ally_units as $unit_index => $unit) {
		//print('bbabat002<br />' . PHP_EOL);
		//print('$units->v(\'units\'), $units->v(\'units\')[$unit_index], $units->_(\'name\', $units->v(\'units\')[$unit_index]): ');var_dump($ally_units, $units->_('.unit_id=' . $ally_unit_ids[$id_index]), $units->_('name', $units->_('.unit_id=' . $ally_unit_ids[$id_index])));exit(0);
		//$unit = $units->_('.unit_team=1&location=' . $combat_location)[$unit_index]; // ugly hack for the meantime where we don't have living variables
		//print('here8969946<br>');
		//print('$units->_(\'.unit_id=\' . $ally_unit_ids[$id_index]): ');var_dump($units->_('.unit_id=' . $ally_unit_ids[$id_index]));
		print('<td class="ally_background">' . $units->_('unit_name', $units->_('.unit_id=' . $ally_unit_ids[$id_index])) . ' [' . $units->_('type', $units->_('.unit_id=' . $ally_unit_ids[$id_index])) . ']<br>
health: ' . $units->_('health_current', $units->_('.unit_id=' . $ally_unit_ids[$id_index])) . '/' . $units->_('health_maximum', $units->_('.unit_id=' . $ally_unit_ids[$id_index])) . '<br>
attack: ' . $units->_('attack', $units->_('.unit_id=' . $ally_unit_ids[$id_index])) . '<br>
range: ' . $units->_('range', $units->_('.unit_id=' . $ally_unit_ids[$id_index])) . '<br>
precision: ' . $units->_('precision', $units->_('.unit_id=' . $ally_unit_ids[$id_index])) . '<br>
evasion: ' . $units->_('evasion', $units->_('.unit_id=' . $ally_unit_ids[$id_index])) . '<br>
counter: ' . $units->_('counter', $units->_('.unit_id=' . $ally_unit_ids[$id_index])) . '<br>
stamina: ' . $units->_('stamina_current', $units->_('.unit_id=' . $ally_unit_ids[$id_index])) . '/' . $units->_('stamina_maximum', $units->_('.unit_id=' . $ally_unit_ids[$id_index])) . '<br>');
		$unit_image = $units->_('image', $units->_('.unit_id=' . $ally_unit_ids[$id_index]));
		if(is_array($unit_image)) { // presumably empty result

		} elseif(strlen($unit_image) > 0) {
			print('<img src="bba/images/' . $unit_image . '" /><br>');
		}
		//print('here8969947<br>');
		if($fight_completed) {
			//print('here8969948<br>');
			// team 1 is player (team id 1), team 0 is AI (team id 0)
			if($units->_('health_current', $units->_('.unit_id=' . $ally_unit_ids[$id_index])) === '0') {
				//print('here8969948.5<br>');
				$result = false;
				//print('bbabat003<br />' . PHP_EOL);
				while($team0_capture_tries > 0 && $units->sum('health_current', $units->_('.unit_team=0', false, false)) > 0) { // avoid totally killed teams capturing their opponents
					//print('bbabat004<br />' . PHP_EOL);
					if(rand(0, 100) < 25) {
					//if(rand(0, 100) > 2000) { // debug
						//$units->__('health_current', '1', $units->_('.unit_id=' . $ally_unit_ids[$id_index]));
						//$units->__('team', '0', $units->_('.unit_id=' . $ally_unit_ids[$id_index]));
						$ids_of_units_to_capture[] = $ally_unit_ids[$id_index];
						$number_of_ally_units--;
						$number_of_enemy_units++;
						print('captured!');
						$result = 'captured';
						break;
					}
					$team0_capture_tries--;
				}
				if($result === false) {
					//print('bbabat005<br />' . PHP_EOL);
					while($team1_save_tries > 0 && $units->sum('health_current', $units->_('.unit_team=1')) > 0) {
						//print('bbabat006<br />' . PHP_EOL);
						if(rand(0, 100) < 50) {
							$units->__('health_current', '1', $units->_('.unit_id=' . $ally_unit_ids[$id_index]));
							print('saved!');
							$result = 'saved';
							break;
						}
						$team1_save_tries--;
					}
				}
				if($result === false) {
				//	if(rand(0, 100) < 50) {
					//if(rand(0, 100) < 2000) { // debug
						//$units->delete($units->_('.unit_id=' . $ally_unit_ids[$id_index]));
						$ids_of_units_to_delete[] = $ally_unit_ids[$id_index];
						$number_of_ally_units--;
						print('killed!');
						$result = 'killed';
				//	}
				}
				if($result === false) {
					$units->__('health_current', '1', $units->_('.unit_id=' . $ally_unit_ids[$id_index]));
				}
			} else {
				// experience gain
				//$units->add(ceil($team1_experience_earned / sizeof($units->_('.unit_team=1&location=' . $combat_location))), 'experience_current', $units->_('.unit_id=' . $ally_unit_ids[$id_index])); // nasty bug: units gaining experience can change the offsets in the unit variable. need living variables
				// since experience currenct could  become 2 digits or more long and throw off the offsets
				$units->add(ceil($team1_experience_earned / sizeof($ally_unit_ids)), 'experience_current', $units->_('.unit_id=' . $ally_unit_ids[$id_index])); // doesn't really make sense that captured units give their experience to the remaining units on that team
				//print('here8969949<br>');
				//print('bbabat007<br />' . PHP_EOL);
				while($units->_('experience_current', $units->_('.unit_id=' . $ally_unit_ids[$id_index])) >= $units->_('experience_maximum', $units->_('.unit_id=' . $ally_unit_ids[$id_index]))) { // level up
				//print('bbabat008<br />' . PHP_EOL);
					//print('here8969950<br>');
					$stats_to_boost = rand(1, 5);
					//print('bbabat009<br />' . PHP_EOL);
					while($stats_to_boost > 0) {
						//print('bbabat010<br />' . PHP_EOL);
						//print('here8969951<br>');
						$stat_to_boost = i::roll($stat_gain_probabilities);
						$boost = rand(1, 4);
						if($stat_to_boost === 'health') {
							//print('here8969952<br>');
							$units->add($boost, 'health_current', $units->_('.unit_id=' . $ally_unit_ids[$id_index]));
							$units->add($boost, 'health_maximum', $units->_('.unit_id=' . $ally_unit_ids[$id_index]));
							print($units->_('unit_name', $units->_('.unit_id=' . $ally_unit_ids[$id_index])) . ' gained ' . $boost . ' health.<br>');
						} elseif($stat_to_boost === 'attack') {
							//print('here8969953<br>');
							$units->add($boost, 'attack', $units->_('.unit_id=' . $ally_unit_ids[$id_index]));
							print($units->_('unit_name', $units->_('.unit_id=' . $ally_unit_ids[$id_index])) . ' gained ' . $boost . ' attack.<br>');
						} elseif($stat_to_boost === 'stamina') {
							//print('here8969954<br>');
							$units->add(1, 'stamina_current', $units->_('.unit_id=' . $ally_unit_ids[$id_index]));
							$units->add(1, 'stamina_maximum', $units->_('.unit_id=' . $ally_unit_ids[$id_index]));
							print($units->_('unit_name', $units->_('.unit_id=' . $ally_unit_ids[$id_index])) . ' gained 1 stamina.<br>');
						} else { // precision, evasion, counter
							//print('here8969955<br>');
							$boost *= 2;
							$units->add($boost, $stat_to_boost, $units->_('.unit_id=' . $ally_unit_ids[$id_index]));
							print($units->_('unit_name', $units->_('.unit_id=' . $ally_unit_ids[$id_index])) . ' gained ' . $boost . ' ' . $stat_to_boost . '.<br>');
						}
						$stats_to_boost--;
					}
					//print('here8969956<br>');
					//print('$units->_(\'.unit_id=\' . $ally_unit_ids[$id_index]), $units->_(\'experience_maximum\', $units->_(\'.unit_id=\' . $ally_unit_ids[$id_index])), $units->context: ');$units->var_dump_full($units->_('.unit_id=' . $ally_unit_ids[$id_index]), $units->_('experience_maximum', $units->_('.unit_id=' . $ally_unit_ids[$id_index])), $units->context);
					$units->subtract('experience_maximum', 'experience_current', $units->_('.unit_id=' . $ally_unit_ids[$id_index]));
					$units->__('experience_maximum', ceil($units->_('experience_maximum', $units->_('.unit_id=' . $ally_unit_ids[$id_index])) * 1.5), $units->_('.unit_id=' . $ally_unit_ids[$id_index]));
				}
			}
		} elseif($turn_number <= $total_turns) {
			if($units->_('stamina_current', $units->_('.unit_id=' . $ally_unit_ids[$id_index])) > 0 && $units->_('health_current', $units->_('.unit_id=' . $ally_unit_ids[$id_index])) > 0) {
				print('<form action="bbabat.php" method="post">
<input type="hidden" name="total_turns" value="' . $total_turns . '" />
<input type="hidden" name="turn_number" value="' . ($turn_number + 1) . '" />
<input type="hidden" name="combat_location" value="' . $combat_location . '" />
<input type="hidden" name="other_combat_locations" value="' . $other_combat_locations . '" />
<input type="hidden" name="unit1_id" value="' . $ally_unit_ids[$id_index] . '" />
<input type="hidden" name="unit0_id" value="' . $AI_unit_id . '" />
<input type="submit" value="Fight!">
</form>');
			} elseif($units->_('health_current', $units->_('.unit_id=' . $ally_unit_ids[$id_index])) > 0) {
				print('<form action="bbabat.php" method="post">
<input type="hidden" name="total_turns" value="' . $total_turns . '" />
<input type="hidden" name="turn_number" value="' . ($turn_number + 1) . '" />
<input type="hidden" name="combat_location" value="' . $combat_location . '" />
<input type="hidden" name="other_combat_locations" value="' . $other_combat_locations . '" />
<input type="hidden" name="unit1_id" value="' . $ally_unit_ids[$id_index] . '" />
<input type="hidden" name="unit0_id" value="' . $AI_unit_id . '" />
<input type="submit" value="get hit">
</form>');
			}
		}
		print('</td>');
		$id_index--;
	}
	//$units->clear_variable('ally_units');
}
//print('here0005<br>');
$units->reset_context();
$units->_('.unit_location=' . $combat_location); // setting the context to only units at this location
//$ally_units = $units->_('.unit_team=1', false, false);
//$enemy_units = $units->_('.unit_team=0');
$enemy_unit_ids = $units->_('id', '.unit_team=0&location=' . $combat_location);
if(is_string($enemy_unit_ids)) {
	$enemy_unit_ids = array($enemy_unit_ids);
}
if(sizeof($enemy_unit_ids) > 0) {
	//$units->set_variable('enemy_units', $units->_('.unit_team=0&location=' . $combat_location));
	//foreach($enemy_units as $unit_index => $unit) {
	$id_index = sizeof($enemy_unit_ids) - 1;
	//print('bbabat011<br />' . PHP_EOL);
	while($id_index > -1) {
		//print('bbabat012<br />' . PHP_EOL);
		//$units->set_variable('unit', $unit);
		print('<td class="enemy_background">' . $units->_('unit_name', $units->_('.unit_id=' . $enemy_unit_ids[$id_index])) . ' [' . $units->_('type', $units->_('.unit_id=' . $enemy_unit_ids[$id_index])) . ']<br>
health: ' . $units->_('health_current', $units->_('.unit_id=' . $enemy_unit_ids[$id_index])) . '/' . $units->_('health_maximum', $units->_('.unit_id=' . $enemy_unit_ids[$id_index])) . '<br>
attack: ' . $units->_('attack', $units->_('.unit_id=' . $enemy_unit_ids[$id_index])) . '<br>
range: ' . $units->_('range', $units->_('.unit_id=' . $enemy_unit_ids[$id_index])) . '<br>
precision: ' . $units->_('precision', $units->_('.unit_id=' . $enemy_unit_ids[$id_index])) . '<br>
evasion: ' . $units->_('evasion', $units->_('.unit_id=' . $enemy_unit_ids[$id_index])) . '<br>
counter: ' . $units->_('counter', $units->_('.unit_id=' . $enemy_unit_ids[$id_index])) . '<br>
stamina: ' . $units->_('stamina_current', $units->_('.unit_id=' . $enemy_unit_ids[$id_index])) . '/' . $units->_('stamina_maximum', $units->_('.unit_id=' . $enemy_unit_ids[$id_index])) . '<br>');
		$unit_image = $units->_('image', $units->_('.unit_id=' . $enemy_unit_ids[$id_index]));
		if(is_array($unit_image)) { // presumably empty result

		} elseif(strlen($unit_image) > 0) {
			print('<img src="bba/images/' . $unit_image . '" /><br>');
		}
		if($fight_completed) {
			// team 1 is player (team id 1), team 0 is AI (team id 0)
			if($units->_('health_current', $units->_('.unit_id=' . $enemy_unit_ids[$id_index])) === '0') {
				$result = false;
				//print('bbabat013<br />' . PHP_EOL);
				while($team1_capture_tries > 0 && $units->sum('health_current', $units->_('.unit_team=1', false, false)) > 0) { // avoid totally killed teams capturing their opponents
					//print('bbabat014<br />' . PHP_EOL);
					if(rand(0, 100) < 25) {
					//if(rand(0, 100) > 2000) { // debug
						//$units->__('health_current', '1', $units->_('.unit_id=' . $enemy_unit_ids[$id_index]));
						//$units->__('team', '1', $units->_('.unit_id=' . $enemy_unit_ids[$id_index]));
						//$units->__('location', 'pen', $units->_('.unit_id=' . $enemy_unit_ids[$id_index]));
						$ids_of_units_to_capture[] = $enemy_unit_ids[$id_index];
						$number_of_ally_units++;
						$number_of_enemy_units--;
						print('captured!');
						$result = 'captured';
						break;
					}
					$team1_capture_tries--;
				}
				if($result === false) {
					//print('bbabat015<br />' . PHP_EOL);
					while($team0_save_tries > 0 && $units->sum('health_current', $units->_('.unit_team=0')) > 0) {
						//print('bbabat016<br />' . PHP_EOL);
						if(rand(0, 100) < 50) {
							$units->__('health_current', '1', $units->_('.unit_id=' . $enemy_unit_ids[$id_index]));
							print('saved!');
							$result = 'saved';
							break;
						}
						$team0_save_tries--;
					}
				}
				if($result === false) {
				//	if(rand(0, 100) < 50) {
					//if(rand(0, 100) < 2000) { // debug
						//$units->delete($units->_('.unit_id=' . $enemy_unit_ids[$id_index]));
						$ids_of_units_to_delete[] = $enemy_unit_ids[$id_index];
						$number_of_enemy_units--;
						print('killed!');
						$result = 'killed';
				//	}
				}
				if($result === false) {
					$units->__('health_current', '1', $units->_('.unit_id=' . $enemy_unit_ids[$id_index]));
				}
			} else {
				// experience gain
				//$units->add($team0_experience_earned, 'experience_current', $units->_('.unit_id=' . $enemy_unit_ids[$id_index]));
				//$units->add(ceil($team0_experience_earned / sizeof($units->_('.unit_team=0&location=' . $combat_location))), 'experience_current', $units->_('.unit_id=' . $enemy_unit_ids[$id_index]));
				$units->add(ceil($team0_experience_earned / sizeof($enemy_unit_ids)), 'experience_current', $units->_('.unit_id=' . $enemy_unit_ids[$id_index]));
				//print('here340<br>');
				//print('bbabat017<br />' . PHP_EOL);
				while($units->_('experience_current', $units->_('.unit_id=' . $enemy_unit_ids[$id_index])) >= $units->_('experience_maximum', $units->_('.unit_id=' . $enemy_unit_ids[$id_index]))) { // level up
					//print('bbabat018<br />' . PHP_EOL);
					//print('here341<br>');
					$stats_to_boost = rand(1, 5);
					//print('bbabat019<br />' . PHP_EOL);
					while($stats_to_boost > 0) {
						//print('bbabat020<br />' . PHP_EOL);
						//print('here342<br>');
						$stat_to_boost = i::roll($stat_gain_probabilities);
						$boost = rand(1, 4);
						if($stat_to_boost === 'health') {
							$units->add($boost, 'health_current', $units->_('.unit_id=' . $enemy_unit_ids[$id_index]));
							$units->add($boost, 'health_maximum', $units->_('.unit_id=' . $enemy_unit_ids[$id_index]));
							print($units->_('unit_name', $units->_('.unit_id=' . $enemy_unit_ids[$id_index])) . ' gained ' . $boost . ' health.<br>');
						} elseif($stat_to_boost === 'attack') {
							$units->add($boost, 'attack', $units->_('.unit_id=' . $enemy_unit_ids[$id_index]));
							print($units->_('unit_name', $units->_('.unit_id=' . $enemy_unit_ids[$id_index])) . ' gained ' . $boost . ' attack.<br>');
						} elseif($stat_to_boost === 'stamina') {
							$units->add(1, 'stamina_current', $units->_('.unit_id=' . $enemy_unit_ids[$id_index]));
							$units->add(1, 'stamina_maximum', $units->_('.unit_id=' . $enemy_unit_ids[$id_index]));
							print($units->_('unit_name', $units->_('.unit_id=' . $enemy_unit_ids[$id_index])) . ' gained 1 stamina.<br>');
						} else { // precision, evasion, counter
							$boost *= 2;
							$units->add($boost, $stat_to_boost, $units->_('.unit_id=' . $enemy_unit_ids[$id_index]));
							print($units->_('unit_name', $units->_('.unit_id=' . $enemy_unit_ids[$id_index])) . ' gained ' . $boost . ' ' . $stat_to_boost . '.<br>');
						}
						$stats_to_boost--;
					}
					//print('here343<br>');
					//print('$enemy_unit_ids[$id_index], $units->_(\'.unit_id=\' . $enemy_unit_ids[$id_index]), $units->_(\'experience_maximum\', $units->_(\'.unit_id=\' . $enemy_unit_ids[$id_index])), $units->context: ');$units->var_dump_full($enemy_unit_ids[$id_index], $units->_('.unit_id=' . $enemy_unit_ids[$id_index]), $units->_('experience_maximum', $units->_('.unit_id=' . $enemy_unit_ids[$id_index])), $units->context);
					$units->subtract('experience_maximum', 'experience_current', $units->_('.unit_id=' . $enemy_unit_ids[$id_index]));
					$units->__('experience_maximum', ceil($units->_('experience_maximum', $units->_('.unit_id=' . $enemy_unit_ids[$id_index])) * 1.5), $units->_('.unit_id=' . $enemy_unit_ids[$id_index]));
					//print('here344<br>');
				}
			}
		}
		print('</td>');
		$id_index--;
	}
	//$units->clear_variable('enemy_units');
}
$units->reset_context();
//print('$ids_of_units_to_delete, $ids_of_units_to_capture: ');var_dump($ids_of_units_to_delete, $ids_of_units_to_capture);
// the reason captured units were being sent to the pen was that there could be 6 ally units at the capture location, in which case the captured unit would not show
// how to properly adress this and enemy not having a pen?
foreach($ids_of_units_to_capture as $id) {
	$units->__('health_current', '1', '.unit_id=' . $id);
	if($units->_('team', '.unit_id=' . $id) === '1') {
		$units->__('team', '0', '.unit_id=' . $id);
	} else {
		$units->__('team', '1', '.unit_id=' . $id);
	}
}
foreach($ids_of_units_to_delete as $id) {
	//print('$id of unit to delete: ');var_dump($id);
	//print('$units->_(\'.unit_id=\' . $id): ');var_dump($units->_('.unit_id=' . $id));
	$units->delete('.unit_id=' . $id);
}
//$units->delete('.unit_id=4');exit(0); // debug

print('
</tr>
</tbody>
</table>
');
//print('here0006<br>');
//var_dump($units->_('.unit_team=0&location=' . $combat_location), $units->sum('health_current', '.unit_team=0&location=' . $combat_location));

// second time these are being calculated
/*$units->context_reset();
$units->_('.unit_location=' . $combat_location);
$number_of_team1_units_with_health = 0;
foreach($units->_('.unit_team=1&location=' . $combat_location) as $team1_unit) {
	if($units->_('health_current', $team1_unit) > 0) {
		$number_of_team1_units_with_health++;
	}
}
$number_of_team0_units_with_health = 0;
foreach($units->_('.unit_team=0&location=' . $combat_location) as $team0_unit) {
	if($units->_('health_current', $team0_unit) > 0) {
		$number_of_team0_units_with_health++;
	}
}*/
$number_of_team1_units_with_health = $number_of_ally_units;
$number_of_team0_units_with_health = $number_of_enemy_units;
if($fight_completed) {
	$number_of_original_team1_units = sizeof($player_units);
	$number_of_original_team0_units = sizeof($AI_units);
	//print('$number_of_original_team1_units, $number_of_team1_units_with_health, $number_of_original_team0_units, $number_of_team0_units_with_health: ');var_dump($number_of_original_team1_units, $number_of_team1_units_with_health, $number_of_original_team0_units, $number_of_team0_units_with_health);
	if($number_of_team1_units_with_health == 0 && $number_of_team0_units_with_health == 0) { // no change
		$control_change_string = '0to0';
	} elseif($number_of_team1_units_with_health == 0 && $number_of_team0_units_with_health != 0) { // +2 to team 0
		$control_change_string = '2to0';
	} elseif($number_of_team1_units_with_health != 0 && $number_of_team0_units_with_health == 0) { // +2 to team 1
		$control_change_string = '2to1';
	} elseif($number_of_team1_units_with_health !== 0 && $number_of_team0_units_with_health !== 0) {
		$number_of_team1_units_lost = $number_of_original_team1_units - $number_of_team1_units_with_health;
		$number_of_team0_units_lost = $number_of_original_team0_units - $number_of_team0_units_with_health;
		if($number_of_team1_units_lost === $number_of_team0_units_lost) { // no change
			$control_change_string = '0to0';
		} elseif($number_of_team1_units_lost > $number_of_team0_units_lost) { // +1 to team 0
			$control_change_string = '1to0';
		} elseif($number_of_team1_units_lost < $number_of_team0_units_lost) { // +1 to team 1
			$control_change_string = '1to1';
		}
	}
	print('fight completed.<br>');
	//print('$control_change_string: ');var_dump($control_change_string);
	print('<form action="bbalof.php" method="post">
<input type="hidden" name="combat_location" value="' . $combat_location . '" />
<input type="hidden" name="other_combat_locations" value="' . $other_combat_locations . '" />
<input type="hidden" name="control_change" value="' . $control_change_string . '" />
<input type="submit" value="back to units screen" />
</form>');
} else {
	// if you retreat before the end of the fight then capturing, saving and killing doesn't occur; is this desired?
	// for example: units with 0 health stay at 0 health which is wonky
	//if($units->sum('stamina_current', $units->_('.unit_team=1&location=' . $combat_location)) > 0 && $units->sum('health_current', $units->_('.unit_team=1&location=' . $combat_location)) > 0) {
		print('<form action="bbalof.php" method="post">
<input type="hidden" name="combat_location" value="' . $combat_location . '" />
<input type="hidden" name="other_combat_locations" value="' . $other_combat_locations . '" />
<input type="hidden" name="control_change" value="2to0" />
<input type="submit" value="retreat" />
</form>');
	//}
}

$units->save_LOM_to_file();
//$units->dump_total_time_taken();

function get_by_request($variable) {
	if($_REQUEST[$variable] == '') {
		//warning($variable . ' not properly specified.<br>');
		return false;
	} else {
		$variable = query_decode($_REQUEST[$variable]);
	}
	return $variable;
}

function query_encode($string) {
	$string = str_replace('&', '%26', $string);
	return $string;
}

function query_decode($string) {
	$string = str_replace('%26', '&', $string);
	return $string;
}

?>
