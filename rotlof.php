<html>
<head>
<title>rotlof</title>
</head>
<body>
<?php

define('DS', DIRECTORY_SEPARATOR);
// where does battle occur? in space? on planets? the answer to where fighting occurs determines whether having varying attack ranges (instead of just 2) (like daiteikoku) would work. what can be owned? planets? buildings? should buildings have different sizes? for now just keep it simple. could go as far as getting more facility space using pocket dimensions familiarly
// generate world; hard-coded for now
include('..' . DS . 'LOM' . DS . 'O.php');
$O = new O('rotlof_world.xml');

// map
print('<div id="map">map</div>');

// messages
print('<div id="messages">messages</div>');

// turn status
print('<div>
<div id="your_turn" style="float: left; width: 50%;">Your turn status: (end turn button) turn not complete</div><div id="opponent_turn" style="float: left; width: 50%;">Opponent turn status: (end turn button) turn not complete</div>
</div>');

// planets
print('<div>
<div id="your_planets" style="float: left; width: 50%;">your planets (list)</div>
<div id="opponent_planets" style="float: left; width: 50%;">opponent planets (list)</div>
</div>');

// commanders
$commander_stat_types = array('attack', 'defense', 'tactics', 'strategy', 'construction', 'smart', 'supply', 'will');


function battle($group1, $group2) {
	print('$group1, $group2: ');var_dump($group1, $group2);exit(0);
	// kinetic (armor, swords or axes, can resist damage, short range)
	// psychic (cause confusion, mind blast, detect deception)
	// portal/physic (can speed travel, save themselves and allies)
	// laser (just a cooler name for beam weapons, ranged attack)
	// xenoform exotic (microscopic, non-anthropoid... somehow more volatile)
	// magic (power is proportional to their relative number?)
	// healer (can prevent a kill from unresisted damage)
	// logic (domestic affairs, negotiation)
	
	// does averaging attack and defense make sense?
	// strategy/tactics at the macro level of calculation, specialty/attack/defense at the local level of calculation? it might vary by each attack type
	// healing specialty?
	$healers_left1 = $O->_('healers', $group1);
	$healers_left2 = $O->_('healers', $group2);
	// portals attack first
	$portal_probabilities1 = array('live' => $O->_('portal', $group2) * (1 + ($O->average('commander_defense', $group2) / 100) + ($O->maximum('commander_specialty_portal', $group2) / 100)), 'kill' => $O->_('portal', $group1) * (1 + ($O->average('commander_attack', $group1) / 100) + ($O->maximum('commander_specialty_portal', $group1) / 100)));
	$portal_count1 = $O->_('portal', $group1);
	$group2_killed_by_portal = 0;
	while($portal_count1 > 0) {
		if(roll($portal_probabilities1) === 'kill') {
			if(rand(0, 1) === 0) { // unsaveable
				$group2_killed_by_portal++;
			} else { // unit is saved if an available healer successfully heals
				$unit_was_saved = false;
				while($healers_left2 > 0) {
					$healers_left2--;
					if(rand(0, 1) === 0) {
						$unit_was_saved = true;
						break;
					}
				}
				if(!$unit_was_saved) {
					$group2_killed_by_portal++;
				}
			}
		}
		$portal_count1--;
	}
	$portal_probabilities2 = array('live' => $O->_('portal', $group1) * (1 + ($O->average('commander_defense', $group1) / 100) + ($O->maximum('commander_specialty_portal', $group1) / 100)), 'kill' => $O->_('portal', $group2) * (1 + ($O->average('commander_attack', $group2) / 100) + ($O->maximum('commander_specialty_portal', $group2) / 100)));
	$portal_count2 = $O->_('portal', $group2);
	$group1_killed_by_portal = 0;
	while($portal_count2 > 0) {
		if(roll($portal_probabilities2) === 'kill') {
			if(rand(0, 1) === 0) { // unsaveable
				$group1_killed_by_portal++;
			} else { // unit is saved if an available healer successfully heals
				$unit_was_saved = false;
				while($healers_left1 > 0) {
					$healers_left1--;
					if(rand(0, 1) === 0) {
						$unit_was_saved = true;
						break;
					}
				}
				if(!$unit_was_saved) {
					$group1_killed_by_portal++;
				}
			}
		}
		$portal_count2--;
	}
	print('$group1_killed_by_portal, $group2_killed_by_portal: ');var_dump($group1_killed_by_portal, $group2_killed_by_portal);
	// no special protection for commanders? notice that overkilling is allowed according to this code; which makes sense.
	$killed_by_portal_probabilities1 = array();
	foreach($group1 as $unit_type) {
		if($O->tagname($unit_type) === 'commander') {
			$killed_by_portal_probabilities1[$O->_('name', $unit_type)] = 1;
		} else {
			$killed_by_portal_probabilities1[$O->tagname($unit_type)] = $O->tagless($unit_type);
		}
	}
	$killed_by_portal1 = array();
	while($group1_killed_by_portal > 0) {
		$roll = roll($killed_by_portal_probabilities1);
		if(isset($killed_by_portal1[$roll])) {
			$killed_by_portal1[$roll]++;
		} else {
			$killed_by_portal1[$roll] = 1;
		}
		$group1_killed_by_portal--;
	}
	$killed_by_portal_probabilities2 = array();
	foreach($group2 as $unit_type) {
		if($O->tagname($unit_type) === 'commander') {
			$killed_by_portal_probabilities2[$O->_('name', $unit_type)] = 1;
		} else {
			$killed_by_portal_probabilities2[$O->tagname($unit_type)] = $O->tagless($unit_type);
		}
	}
	$killed_by_portal2 = array();
	while($group2_killed_by_portal > 0) {
		$roll = roll($killed_by_portal_probabilities2);
		if(isset($killed_by_portal2[$roll])) {
			$killed_by_portal2[$roll]++;
		} else {
			$killed_by_portal2[$roll] = 1;
		}
		$group2_killed_by_portal--;
	}
	print('$killed_by_portal1, $killed_by_portal2: ');var_dump($killed_by_portal1, $killed_by_portal2);
	foreach($killed_by_portal1 as $killed => $number) {
		print($number . ' of player1 ' . $killed . ' killed.<br>');
		$O->subtract_zero_floor($killed, $number, $group1);
	}
	foreach($killed_by_portal2 as $killed => $number) {
		print($number . ' of player2 ' . $killed . ' killed.<br>');
		$O->subtract_zero_floor($killed, $number, $group2);
	}
	// lasers shoot second
	$laser_probabilities1 = array('live' => 1 + ($O->average('commander_defense', $group2) / 100), 'kill' => 1 + ($O->average('commander_attack', $group1) / 100) + ($O->maximum('commander_specialty_laser', $group1) / 100));
	$laser_count1 = $O->_('laser', $group1);
	$group2_killed_by_laser = 0;
	while($laser_count1 > 0) {
		if(roll($portal_probabilities1) === 'kill') {
			if(rand(0, 1) === 0) { // unsaveable
				$group2_killed_by_portal++;
			} else { // unit is saved if an available healer successfully heals
				$unit_was_saved = false;
				while($healers_left2 > 0) {
					$healers_left2--;
					if(rand(0, 1) === 0) {
						$unit_was_saved = true;
						break;
					}
				}
				if(!$unit_was_saved) {
					$group2_killed_by_portal++;
				}
			}
		}
		$laser_count1--;
	}
	$laser_probabilities2 = array('live' => 1 + ($O->average('commander_defense', $group1) / 100), 'kill' => 1 + ($O->average('commander_attack', $group2) / 100) + ($O->maximum('commander_specialty_laser', $group2) / 100));
	$laser_count2 = $O->_('laser', $group2);
	$group1_killed_by_laser = 0;
	while($laser_count2 > 0) {
		if(roll($portal_probabilities2) === 'kill') {
			if(rand(0, 1) === 0) { // unsaveable
				$group1_killed_by_portal++;
			} else { // unit is saved if an available healer successfully heals
				$unit_was_saved = false;
				while($healers_left1 > 0) {
					$healers_left1--;
					if(rand(0, 1) === 0) {
						$unit_was_saved = true;
						break;
					}
				}
				if(!$unit_was_saved) {
					$group1_killed_by_portal++;
				}
			}
		}
		$laser_count2--;
	}
	print('$group1_killed_by_laser, $group2_killed_by_laser: ');var_dump($group1_killed_by_laser, $group2_killed_by_laser);
	// no special protection for commanders? notice that overkilling is allowed according to this code; which makes sense.
	$killed_by_laser_probabilities1 = array();
	foreach($group1 as $unit_type) {
		if($O->tagname($unit_type) === 'commander') {
			$killed_by_laser_probabilities1[$O->_('name', $unit_type)] = 1;
		} else {
			$killed_by_laser_probabilities1[$O->tagname($unit_type)] = $O->tagless($unit_type);
		}
	}
	$killed_by_laser1 = array();
	while($group1_killed_by_laser > 0) {
		$roll = roll($killed_by_laser_probabilities1);
		if(isset($killed_by_laser1[$roll])) {
			$killed_by_laser1[$roll]++;
		} else {
			$killed_by_laser1[$roll] = 1;
		}
		$group1_killed_by_laser--;
	}
	$killed_by_laser_probabilities2 = array();
	foreach($group2 as $unit_type) {
		if($O->tagname($unit_type) === 'commander') {
			$killed_by_laser_probabilities2[$O->_('name', $unit_type)] = 1;
		} else {
			$killed_by_laser_probabilities2[$O->tagname($unit_type)] = $O->tagless($unit_type);
		}
	}
	$killed_by_laser2 = array();
	while($group2_killed_by_laser > 0) {
		$roll = roll($killed_by_laser_probabilities2);
		if(isset($killed_by_laser2[$roll])) {
			$killed_by_laser2[$roll]++;
		} else {
			$killed_by_laser2[$roll] = 1;
		}
		$group2_killed_by_laser--;
	}
	print('$killed_by_laser1, $killed_by_laser2: ');var_dump($killed_by_laser1, $killed_by_laser2);
	foreach($killed_by_laser1 as $killed => $number) {
		print($number . ' of player1 ' . $killed . ' killed.<br>');
		$O->subtract_zero_floor($killed, $number, $group1);
	}
	foreach($killed_by_laser2 as $killed => $number) {
		print($number . ' of player2 ' . $killed . ' killed.<br>');
		$O->subtract_zero_floor($killed, $number, $group2);
	}
	// portals play defensively third? currently portal defense is included in the portal attack calculation
	// kinetic attacks fourth
	$kinetic_probabilities1 = array('live' => 1 + ($O->average('commander_defense', $group2) / 100) + ($O->maximum('commander_specialty_kinetic', $group2) / 100), 'kill' => 4 + ($O->average('commander_attack', $group1) / 100) + ($O->maximum('commander_specialty_kinetic', $group1) / 100));
	$kinetic_count1 = $O->_('kinetic', $group1);
	$group2_killed_by_kinetic = 0;
	while($kinetic_count1 > 0) {
		if(roll($portal_probabilities1) === 'kill') {
			if(rand(0, 1) === 0) { // unsaveable
				$group2_killed_by_portal++;
			} else { // unit is saved if an available healer successfully heals
				$unit_was_saved = false;
				while($healers_left2 > 0) {
					$healers_left2--;
					if(rand(0, 1) === 0) {
						$unit_was_saved = true;
						break;
					}
				}
				if(!$unit_was_saved) {
					$group2_killed_by_portal++;
				}
			}
		}
		$kinetic_count1--;
	}
	$kinetic_probabilities2 = array('live' => 1 + ($O->average('commander_defense', $group1) / 100) + ($O->maximum('commander_specialty_kinetic', $group1) / 100), 'kill' => 4 + ($O->average('commander_attack', $group2) / 100) + ($O->maximum('commander_specialty_kinetic', $group2) / 100));
	$kinetic_count2 = $O->_('kinetic', $group2);
	$group1_killed_by_kinetic = 0;
	while($kinetic_count2 > 0) {
		if(roll($portal_probabilities2) === 'kill') {
			if(rand(0, 1) === 0) { // unsaveable
				$group1_killed_by_portal++;
			} else { // unit is saved if an available healer successfully heals
				$unit_was_saved = false;
				while($healers_left1 > 0) {
					$healers_left1--;
					if(rand(0, 1) === 0) {
						$unit_was_saved = true;
						break;
					}
				}
				if(!$unit_was_saved) {
					$group1_killed_by_portal++;
				}
			}
		}
		$kinetic_count2--;
	}
	print('$group1_killed_by_kinetic, $group2_killed_by_kinetic: ');var_dump($group1_killed_by_kinetic, $group2_killed_by_kinetic);
	// no special protection for commanders? notice that overkilling is allowed according to this code; which makes sense.
	$killed_by_kinetic_probabilities1 = array();
	foreach($group1 as $unit_type) {
		if($O->tagname($unit_type) === 'commander') {
			$killed_by_kinetic_probabilities1[$O->_('name', $unit_type)] = 1;
		} else {
			$killed_by_kinetic_probabilities1[$O->tagname($unit_type)] = $O->tagless($unit_type);
		}
	}
	$killed_by_kinetic1 = array();
	while($group1_killed_by_kinetic > 0) {
		$roll = roll($killed_by_kinetic_probabilities1);
		if($roll === 'kinetic') { // kinetic units are more likely to survive a kinetic attack
			if(rand(0, 3) === 0) {
				if(isset($killed_by_kinetic1[$roll])) {
					$killed_by_kinetic1[$roll]++;
				} else {
					$killed_by_kinetic1[$roll] = 1;
				}
			}
		} else {
			if(isset($killed_by_kinetic1[$roll])) {
				$killed_by_kinetic1[$roll]++;
			} else {
				$killed_by_kinetic1[$roll] = 1;
			}
		}
		$group1_killed_by_kinetic--;
	}
	$killed_by_kinetic_probabilities2 = array();
	foreach($group2 as $unit_type) {
		if($O->tagname($unit_type) === 'commander') {
			$killed_by_kinetic_probabilities2[$O->_('name', $unit_type)] = 1;
		} else {
			$killed_by_kinetic_probabilities2[$O->tagname($unit_type)] = $O->tagless($unit_type);
		}
	}
	$killed_by_kinetic2 = array();
	while($group2_killed_by_kinetic > 0) {
		$roll = roll($killed_by_kinetic_probabilities2);
		if($roll === 'kinetic') { // kinetic units are more likely to survive a kinetic attack
			if(rand(0, 3) === 0) {
				if(isset($killed_by_kinetic2[$roll])) {
					$killed_by_kinetic2[$roll]++;
				} else {
					$killed_by_kinetic2[$roll] = 1;
				}
			}
		} else {
			if(isset($killed_by_kinetic2[$roll])) {
				$killed_by_kinetic2[$roll]++;
			} else {
				$killed_by_kinetic2[$roll] = 1;
			}
		}
		$group2_killed_by_kinetic--;
	}
	print('$killed_by_kinetic1, $killed_by_kinetic2: ');var_dump($killed_by_kinetic1, $killed_by_kinetic2);
	foreach($killed_by_kinetic1 as $killed => $number) {
		print($number . ' of player1 ' . $killed . ' killed.<br>');
		$O->subtract_zero_floor($killed, $number, $group1);
	}
	foreach($killed_by_kinetic2 as $killed => $number) {
		print($number . ' of player2 ' . $killed . ' killed.<br>');
		$O->subtract_zero_floor($killed, $number, $group2);
	}
	return true;
}

function roll($probabilities_array) {	
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

?>
</body>
</html>