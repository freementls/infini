<?php

include('../LOM/O.php');
//$O = new O('stattypes.xml');
$O = new O('relations.xml');
$items = new O('<items></items>');
//$stattypes = $O->_('stattype');
$rollable_stats = $O->_('.stattype_rollableon=wearable');

print('getting this to not be slow would be a good way improve performance of LOM; it shouldn\'t be slow for something simple like this. good chance the context is bloated or could be intelligently cleaned or isn\'t properly being updated');
$player_item_quality = 100;
print('$player_item_quality: ');var_dump($player_item_quality);
$item_generation_quality_center = 200;
print('$item_generation_quality_center: ');var_dump($item_generation_quality_center);
$item_points_per_stat = 50;
print('$item_points_per_stat: ');var_dump($item_points_per_stat);
$counter = 0;
while($counter < 10) {
	$rand1 = number_from_gaussian_centered_on($item_generation_quality_center);
	$rand2 = number_from_gaussian_centered_on($item_generation_quality_center);
	if($rand1 > $rand2) {
		print_item(generate_item(rand($rand2, $rand1), $item_points_per_stat, $rollable_stats, $items, $O), $player_item_quality, $O);
	} else {
		print_item(generate_item(rand($rand1, $rand2), $item_points_per_stat, $rollable_stats, $items, $O), $player_item_quality, $O);
	}
	$counter++;
}
//print('$items->_(\'item\'): ');var_dump($items->_('item'));

function generate_item($points, $item_points_per_stat, $rollable_stats, $items, $O) {
	$points_left = $points;
	$point_allocation = array();
	
	// do required stats first
	
	// some way to show preference to going hard on certain stats? another factor determining item shape; if many points come in relative to points_per_stat then items will have many stat boosts. need slot stat preferences to deal with this
	// item points per stat could also vary with other things: world area, etc.
	while($points_left > $item_points_per_stat) {
		$rand1 = number_from_gaussian_centered_on($item_points_per_stat);
		$rand2 = number_from_gaussian_centered_on($item_points_per_stat);
		if($rand1 > $rand2) {
			$points_input = rand($rand2, $rand1);
		} else {
			$points_input = rand($rand1, $rand2);
		}
		$stattype_rand = rand(0, sizeof($rollable_stats) - 1);
		if(isset($point_allocation[$stattype_rand])) {
			$point_allocation[$stattype_rand] += $points_input;
		} else {
			$point_allocation[$stattype_rand] = $points_input;
		}
		$points_left -= $points_input;
	}
	$stattype_rand = rand(0, sizeof($rollable_stats) - 1);
	if(isset($point_allocation[$stattype_rand])) {
		$point_allocation[$stattype_rand] += $points_left;
	} else {
		$point_allocation[$stattype_rand] = $points_left;
	}
	$points_left = 0;
	//print('$point_allocation: ');var_dump($point_allocation);
	// debug
	/*print('model item LOM: ');var_dump($items->string_to_LOM('<item>
<quality>' . $points . '</quality>
<stat>
<name>medium armor</name>
<pierceresistance>6</pierceresistance>
<slashresistance>7</slashresistance>
<bluntresistance>8</bluntresistance>
<maximumdurability>12</maximumdurability>
<durability>4</durability>
<weight>17</weight>
<text>6 pierceresistance, 7 slashresistance, 8 bluntresistance, 4/12 durability, 17 &lt;abbr title="Newtons"&gt;N&lt;/abbr&gt;</text>
<used resets="true">0</used>
</stat>
<stat>
<speedboost>3</speedboost>
<text>+3 speed</text>
<used resets="true">0</used>
</stat>
<stat>
<damage>5</damage>
<text>5 damage</text>
<used resets="true">0</used>
</stat>
</item>'));*/
	$item = $items->new_('<item></item>');
	//print('$items->context-1: ');$items->var_dump_full($items->context);
	$items->new_('<quality>' . $points . '</quality>', $item);
	//print('$items->context0: ');$items->var_dump_full($items->context);
	foreach($point_allocation as $index => $points_input) {
		//print('$O->_(\'cost\', $rollable_stats[$index]): ');var_dump($O->_('cost', $rollable_stats[$index]));
		eval('$stat_cost = ' . str_replace('$input', 1, $O->_('cost_value', $rollable_stats[$index])) . ';');
		$input = floor($points_input / $stat_cost); // seems wasteful. maybe do something with the remainder? put it towards item value?
		//print('$input: ');var_dump($input);
		$points_left += $points_input - ($input * $stat_cost);
		// by this method we'd have to be sure that costs don't apparoach and don't exceed $item_points_per_stat
		//$stat_string = $O->LOM_to_string($rolled_stat);
		$rolled_stat = $rollable_stats[$index];
		//$rolled_stat = $rollable_stats[11]; // debug
		//$item = $items->insert($rolled_stat, $item);
		$rolled_stat = $items->new_($rolled_stat, $item);
		//print('$rolled_stat: ');$items->var_dump_full($rolled_stat);
		//print('$items->context: ');$items->var_dump_full($items->context);
		//print('$items->_(\'item\'): ');$items->var_dump_full($items->_('item'));
		foreach($items->_('item') as $item_index => $item) {  } // work on the last item
		//print('last item: ');$items->var_dump_full($item);
		//$item = $items->new_($item, $item);
		//print('$item1.2: ');$items->var_dump_full($item);
		//print('$item2: ');var_dump($item);
		
		//$item = $items->strip_tag('value', $item);
		// eval and clean the values. no, just eval
		//$stat_subtags = $items->_('value', $items->_('stattype_*', $rolled_stat));
		
		//$stat_subtags = $items->_('stattype_*', $rolled_stat);
		//print('$stat_subtags: ');var_dump($stat_subtags);exit(0);
		
		/*$stat_subtag_values = $items->_('value', $rolled_stat);
		print('$stat_subtag_values: ');var_dump($stat_subtag_values);
		foreach($stat_subtag_values as $stat_subtag_value_index => $stat_subtag_value) {
			//$value = $O->_('value', $stat_subtag);
			//$value_index = $O->get_index('value', $stat_subtag);
			//if(!is_array($stat_subtag_value)) {
				//$evaled_value = eval($value . ';');
				//$rolled_stat = $items->delete($value, $rolled_stat);
				//$rolled_stat = $items->new_('', $rolled_stat);
				//$rolled_stat = $items->__($stat_subtag_value_index, eval($stat_subtag_value . ';'), $rolled_stat);
				eval('$evaled_value = ' . $stat_subtag_value . ';');
				//print('$stat_subtag_value_index, $input, $stat_subtag_value, $evaled_value: ');var_dump($stat_subtag_value_index, $input, $stat_subtag_value, $evaled_value);
				$item = $items->__($stat_subtag_value_index, $evaled_value, $item);
				//print('$item: ');$items->var_dump_full($item);exit(0);
			//}
		}*/
		
		$stat_evaluated_variables = array();
		$stat_subtags_with_values = $items->_('stattype_.*_value', $rolled_stat);
		//print('$stat_subtags_with_values: ');var_dump($stat_subtags_with_values);
		foreach($stat_subtags_with_values as $stat_subtag_with_values_index => $stat_subtag_with_values) {
			$stat_subtag_value = $items->_('value', $stat_subtag_with_values);
			$stat_subtag_value_index = $items->get_index('value', $stat_subtag_with_values);
			eval('$evaled_value = ' . $stat_subtag_value . ';');
			$item = $items->__($stat_subtag_value_index, $evaled_value, $item);
			//eval('$' . $items->tagname($stat_subtag_with_values) . ' = ' . $stat_subtag_value . ';');
			$stat_evaluated_variables['$' . $items->tagname($stat_subtag_with_values)] = $evaled_value;
		}
		$stat_subtag_text = $items->_('text', $rolled_stat);
		//print('$stat_subtag_text: ');var_dump($stat_subtag_text);
		$stat_subtag_text_index = $items->get_index('text', $rolled_stat);
		//eval('$evaled_value = ' . $stat_subtag_text . ';');
		$replaced_stat_subtag_text = $stat_subtag_text;
		foreach($stat_evaluated_variables as $stat_evaluated_index => $stat_evaluated_value) {
			$replaced_stat_subtag_text = str_replace($stat_evaluated_index, $stat_evaluated_value, $replaced_stat_subtag_text);
		}
		//print('$stat_subtag_text_index, $replaced_stat_subtag_text: ');var_dump($stat_subtag_text_index, $replaced_stat_subtag_text);
		$item = $items->__($stat_subtag_text_index, $replaced_stat_subtag_text, $item);
		
		/*$values = $items->_('value', $rolled_stat);
		print('$values: ');$O->var_dump_full($values);
		foreach($values as $index => $value) {
			$rolled_stat = $items->__($index, eval($value . ';'), $rolled_stat);
		}*/
		//print('$rolled_stat2: ');$O->var_dump_full($rolled_stat);
		
		//$item = $items->new_($rolled_stat, $item);
		//$item = $items->_('item');
		//print('after evaling tags: ');$items->var_dump_full($item);
	}
	if($points_left > 0) {
		$items->warning('$points_left: ' . $points_left);
	}
	
	$item = $items->change_tags_named_to($item, 'stattype', 'stat');
	//print('after stattype to stat: ');$items->var_dump_full($item);
	$item = $items->delete('cost', $item);
	//print('after removing cost: ');$items->var_dump_full($item);
	$item = $items->delete('rollableon', $item);
	//print('after removing rollableon: ');$items->var_dump_full($item);
	$item = $items->delete('requiredon', $item);
	//print('after removing requiredon: ');$items->var_dump_full($item);
	$item = $items->delete('multiplier', $item);
	//print('after removing multiplier: ');$items->var_dump_full($item);
	$item = $items->delete('relations', $item);
	//print('after removing relations: ');$O->var_dump_full($item);
	$item = $items->__('used', 0, $item);
	//print('after setting used to 0: ');$items->var_dump_full($item);
	// add the experience the the stat
	// <experience><playername>playername</playername><current>0</current><maximum>' . $items->_('cost', $rolled_stat) . '</maximum></experience> // should it be by the cost? the stat itself? a new experience maximum tag? this last sounds good; would be something to look for: a stat that takes less work to fully unlock
	$items->new_('<experience></experience>', $rolled_stat); // for now just put the experience tag in since it hasn't been worked on by any player yet
	
	//$items->new_($item);
	//$item = $items->_('item');
	//print('$item at end of generate_item: ');$items->var_dump_full($item);
	//$items->delete('item');
	return $item;
}

function print_item($item, $player_item_quality, $O) { // the item quality color coding is relative to the player viewing it
	print('<div style="float: left; border: 1px solid black;">
');
	print('item quality: <strong><span style="color: #' . black_to_red_spectrum($O->_('quality', $item), $player_item_quality * 2) . ';">' . $O->_('quality', $item) . '</span></strong><br>');
	foreach($O->_('stat_text', $item) as $text) {
		print(html_entity_decode($text) . '<br>');
	}
	print('</div>');
}

function black_to_red_spectrum($value, $maximum) {
	// would really prefer to use toroidal math instead of this empirical approach...
    if($value > $maximum) {
		$value = $maximum;
	}
	//print('$value, $maximum: ');var_dump($value, $maximum);
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
	} elseif($l >= 595.0 && $l <= 700.0) {
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

function signedpow($base, $exponent) {
	if($base < 0 && $exponent % 2 == 0) {
		return -1 * pow($base, $exponent);
	} else {
		return pow($base, $exponent);
	}
}

function number_from_gaussian_centered_on($number) {
	// what are the odds this will be coded as a real gaussian function? ;p
	$sign = rand(0, 1);
	if($sign === 0) {
		return ceil($number * (1 - (quadratic_rand() * 0.1)));
	} else {
		return ceil($number * (1 + (quadratic_rand() * 0.1)));
	}
}

function quadratic_rand($minimum = 1) {
	$counter = $minimum - 1;
	$rand = 1;
	while($rand > 0) {
		$counter++;
		$rand = rand(0, 1);
	}
	return $counter;
}

?>