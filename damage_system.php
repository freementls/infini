<?php

// whereas some variables will be hard-coded here, in the context of a 3d game engine they would be supplied
// consider wheels of counters in JRPGs of attack types (spear, horseman, archer, etc.). we are complicating such a system to be more interesting and realistic
// it's worth writing that a shield would increase the surface of a hand substantially so the question of how to properly calculate for this natural kind of gameplay becomes important
// also how to factor in natural tendency of a body to protect its weak areas? strikes to the head or nexk can be very damaging if unopposed but a body will usually put a hand or arm in the way but this is not accounted for

include('../LOM/O.php');
$O = new O('wearableslots.xml');

print('(physical) damage system<br><br>');
$wearableslots = $O->_('wearableslots_*');
//print('$wearableslots: ');$O->var_dump_full($wearableslots);
//$volumes_wearable_slots = $O->_('volume', $wearableslots);
//print('volumes of $wearableslots: ');$O->var_dump_full($volumes_wearable_slots);
//$volume_sum = $O->sum('volume', $wearableslots);
//print('$volume_sum: ');var_dump($volume_sum);exit(0);
foreach($wearableslots as $wearableslot) {
	//print('$wearableslot: ');var_dump($wearableslot);
	print($O->tagname($wearableslot) . ' impacteffectiveness: ' . $O->_('impacteffectiveness', $wearableslot) . '<br>');
}
exit(0);

// pierce is the 0-dimensional physical attack type
// slash is the 1-dimensional physical attack type
// impact is the 2-dimensional physical attack type

// hard-code incoming volumes for the different attack types (although they should be calculated from the attack being used and the weapon and the skill of the attacker)
$array_attack_types = array('impact' => 0.33, 'slash' => 0.33, 'pierce' => 0.33);
$array_attack_volumes = array('impact' => 0.5, 'slash' => 0.2, 'pierce' => 0.05);

// 100 random hits
//$surface_sum = $O->sum('surface');
//$array_zero_index_transformations = array('none' => 5, 'repeat_block' => 0, 'initial_pair' => 4, 'mirror' => 4, 'reverse' => 1, 'add_modified' => 0, 'modify' => 6, 'transform_segment' => 6);
//$transformation = music::roll_item($array_zero_index_transformations);
$array_surface_associations = array();
$slots_with_surface = $O->_('.*_surface');
foreach($slots_with_surface as $slot_with_surface) {
	$array_surface_associations[$O->tagname($slot_with_surface)] = $O->_('surface', $slot_with_surface);
}
print('$array_surface_associations: ');$O->var_dump_full($array_surface_associations);
$slot_hits_record = array();
$counter = 0;
while($counter < 100) {
	$hit_slot = roll_item($array_surface_associations);
	if(isset($slot_hits_record[$hit_slot])) {
		$slot_hits_record[$hit_slot]++;
	} else {
		$slot_hits_record[$hit_slot] = 1;
	}
	//$magnitude_rand = rand(1, 20);
	$strength_rand = rand(1, 20);
	//$mass_rand = rand(1, 5);
	// crude approximations of how the different dimensionalities of the attacks will affect the resulting damage that should be calculated from weapon stats and not hard-coded
	// hard-coded resistances whereas these should come from item stats
	$pierce_resistance = 5;
	$slash_resistance = 5;
	$impact_resistance = 5;
	$attack_type = roll_item($array_attack_types);
	if($attack_type === 'impact') {
		$attack_surface = 100;
		$mass_rand = rand(1, 50);
		$attack_resistance = $impact_resistance;
	} elseif($attack_type === 'slash') {
		$attack_surface = 10;
		$mass_rand = rand(1, 10);
		$attack_resistance = $slash_resistance;
	} elseif($attack_type === 'pierce') {
		$attack_surface = 1;
		$mass_rand = rand(1, 10) / 20;
		$attack_resistance = $pierce_resistance;
	} else {
		print('unknown $attack_type: ' . $attack_type);exit(0);
	}
	// crudely equate this with acceleration for the purposes of determining force
	$force = $mass_rand * $strength_rand;
	// should the attack resistance be applied to the force or the volume damaged?
	$volume_damaged = $force - $attack_resistance;
	if($volume_damaged < 0) {
		$volume_damaged = 0;
	}
	// coming to realize armor volume remaining is more relevant than a flat durability stat, which makes sense when considering how repairing would affect the item: there is less material to work with so stats (durability, others) are reduced
	$armor_volume_remaining = 5;
	//$armor_volume_damaged = $force * $attack_surface * $O->_($attack_type . 'effectiveness', $hit_slot) / 100;
	//$armor_volume_damaged = 0;
	//if($volume_damaged > $attack_surface) {
		$armor_volume_damaged = $volume_damaged * $attack_surface / 100; // crudely assuming armor thickness of 1
		if($armor_volume_damaged > $armor_volume_remaining) {
			$armor_volume_remaining = 0;
			$armor_volume_damaged = $armor_volume_remaining;
		} else {
			$armor_volume_remaining -= $armor_volume_damaged;
		}
	//}
	$volume_damaged -= $armor_volume_damaged;
	$volume_damaged *= $O->_($attack_type . 'effectiveness', $hit_slot) / 100;
	//$volume_damaged = $magnitude_rand * $array_attack_volumes[$attack_type] * $O->_($attack_type . 'effectiveness', $hit_slot) / 100;
	$destruction_string = '';
	$killed_string = '';
	if($volume_damaged >= $O->_($hit_slot . '_volume', $hit_slot)) {
		$destruction_string = ' (' . $O->_($attack_type . 'destruction', $hit_slot) . ')';
		if($O->_('destructionisfatal', $hit_slot) === 'true') {
			$killed_string = ' (killed)';
		}
	}
	//print($hit_slot . ' was ' . $attack_type . 'ed with magnitude ' . $magnitude_rand . ' damaging a volume of ' . $volume_damaged . ' (' . $destruction_string . ').<br>');
	print($hit_slot . ' was ' . $attack_type . 'ed with force ' . $force . ' (strength: ' . $strength_rand . ' x mass: ' . $mass_rand . ') for armor volume remaining of ' . $armor_volume_remaining . ' and damaging a volume of ' . $volume_damaged . $destruction_string . $killed_string . '.<br>');
	$counter++;
}
ksort($slot_hits_record);
print('$slot_hits_record: ');$O->var_dump_full($slot_hits_record);

// 3 arrows to the knee

// slash across the chest

// limb rending attack

// head impact

// lose a finger

// stomach impact

// leg slash

// arm pierce

// neck pierce

function roll_item($probabilities_array) {	
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