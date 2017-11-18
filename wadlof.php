<?php

// could add in the concept of equipped items to avoid the problem of figuring out which item to attack with if there are multiple enemies. for now, just have single enemies
// damage ranges? (stat ranges in general) to make gameplay more unpredictable (cheap thrills)
// multiple player characters capture enemies that become player characters... ah yeah

include('../LOM/O.php');
error_reporting(0);

//error_reporting(0);
ini_set('xdebug.var_display_max_data', '5000');
$game_name_by_request = get_by_request('game_name');
//print('$game_name_by_request: ');var_dump($game_name_by_request);
if($game_name_by_request === false) {
	print('Enter the game name to start or continue a game:<br>
');
}
print('<form method="post" action="wadlof.php">
Game: <input type="text" name="game_name" value="' . $game_name_by_request . '" /><input type="submit" value="Go!" />
</form>
');
if($game_name_by_request !== false) { // print out the game status
	if(!file_exists('wadlof_games.xml')) {
		file_put_contents('wadlof_games.xml', '<games>
</games>');
	}
	$array_item_types = array('weapon', 'armor', 'potion');
	$O = new O('wadlof_games.xml');
	$array_levels = array(0, 2, 5, 10, 20, 50, 100, 200, 500, 1000, 2000, 5000, 10000);
	//print('$O->_(\'game_name=\' . $game_name_by_request): ');var_dump($O->_('game_name=' . $O->enc($game_name_by_request)));exit(0);
	$game = $O->_('.game_name=' . $O->enc($game_name_by_request));
	//print('$game: ');var_dump($game);exit(0);
	if($game === false) { // then make a new game
		$game_string = '<game>
<name>' . $game_name_by_request . '</name>
<status>in town</status>
<idcounter>2</idcounter>
<players>
<player>
<name>' . $game_name_by_request . '</name>
<health>50</health>
<currency>20</currency>
<experience>0</experience>
<level>0</level>
<items>
<weapon>
<id>0</id>
<damage>5</damage>
<durability>24</durability>
<maximumdurability>28</maximumdurability>
<value>3</value>
</weapon>
<weapon>
<id>1</id>
<damage>22</damage>
<durability>4</durability>
<maximumdurability>12</maximumdurability>
<value>5</value>
</weapon>
</items>
</player>
</players>
<enemies>
</enemies>
</game>
';
		$game = $O->new_tag($game_string, 'games');
		//print('$game (make a new game): ');var_dump($game);exit(0);
	} else {
		//print('initial $game_name: ');var_dump($game_name);
		// messages telling the player what happened this "turn"
		$sell_id_by_request = get_by_request('sell_id');
		//print('$sell_id_by_request: ');var_dump($sell_id_by_request);
		$item_to_sell = $O->_('player/items/.*/id=' . $sell_id_by_request);
		//print('$item_to_sell: ');var_dump($item_to_sell);
		if($item_to_sell !== false) {
			$O->add($O->_('value', $item_to_sell), 'player_currency');
			print($O->get_tag_name($item_to_sell) . ' was sold for ' . $O->_('value', $item_to_sell) . '.<br>
');
			$O->delete($item_to_sell);
		}
		
		$drink_id_by_request = get_by_request('drink_id');
		$item_to_drink = $O->_('player/items/.potion/id=' . $drink_id_by_request);
		if($item_to_drink !== false) {
			$O->add($O->_('recovery', $item_to_drink), 'player_health');
			print($O->_('player_name') . ' recovered ' . $O->_('recovery', $item_to_drink) . ' health.<br>
');
			$O->delete($item_to_drink);
		}
		$repair_id_by_request = get_by_request('repair_id');
		if($repair_id_by_request !== false) {
			$item_to_repair = $O->_('player/items/.*/id=' . $repair_id_by_request);
			//$repair_cost = ceil($item_value_matches[1] / 2); // should the item value loss be proportional to the maximum durability loss?
			$repair_cost = $O->_('value', $item_to_repair); // should the item value loss be proportional to the maximum durability loss?
			if($O->_('player_currency') < $repair_cost) {
				print('Not enough currency to repair ' . $O->get_tag_name($item_to_repair) . '.<br>
');
			} else {
				$new_item_value = floor($O->_('value', $item_to_repair) / 2) + rand(0, (ceil($O->_('value', $item_to_repair) / 2) - 1));
				$new_item_maximumdurability = floor($O->_('maximumdurability', $item_to_repair) / 2) + rand(0, (ceil($O->_('maximumdurability', $item_to_repair) / 2) - 1));
				if($new_item_maximumdurability == 0) {
					print($items_matches[1][$index] . ' was destroyed while attempting repair.<br>
');
					$O->delete($item_to_repair);
				} else {
					$O->__('maximumdurability', $new_item_maximumdurability, $item_to_repair);
					$O->__('durability', $new_item_maximumdurability, $item_to_repair);
					$O->__('value', $new_item_value, $item_to_repair);
					$O->subtract('player_currency', $repair_cost);
					print($O->get_tag_name($item_to_repair) . ' was repaired for ' . $repair_cost . ' (maximum durability was reduced).<br>
');
				}
			}
		}
		$weapon_id_by_request = get_by_request('weapon_id');
		if($weapon_id_by_request !== false) {
			$items = $O->_('items', $O->_('.game_name=' . $O->enc($game_name_by_request)));
			$weapon = $O->_('.weapon/id=' . $weapon_id_by_request, $items);
			$weapon_damage = $O->_('damage', $weapon); // can further shorthand be developed?
			$enemy = $O->_('enemy', $O->_('.game_name=' . $O->enc($game_name_by_request)));
			$enemy_armor = $O->_('armor', $enemy);
			//print('$weapon_id_by_request, $items, $weapon, $weapon_damage, $enemy_armor: ');var_dump($weapon_id_by_request, $items, $weapon, $weapon_damage, $enemy_armor);
			$damage_done = $weapon_damage - $enemy_armor;
			if($damage_done < 0) {
				$damage_done = 0;
			}
			print($damage_done . ' damage was done to enemy.<br>
');
			$weapon_durability = $O->_('durability', $weapon);
			$new_weapon_durability = $weapon_durability - 1;
			$O->__('durability', $new_weapon_durability, $weapon);
			$enemy_health = $O->_('health', $enemy);
			if($damage_done >= $enemy_health) {
				// gain experience and potentially level up
				$new_player_experience = $O->_('player_experience') + $O->_('enemy_experience');
				$O->__('player_experience', $new_player_experience);
				if($O->_('enemy_experience') > 0) {
					print($O->_('enemy_experience') . ' experience gained.<br>
');
				}
				while($new_player_experience >= $array_levels[$O->_('player_level') + 1] && $O->_('player_level') < sizeof($array_levels)) {
					$O->increment('player_level');
					print($O->_('player_name') . ' is now level ' . $O->_('player_level') . '.<br>
');
					$health_increase = $O->_('player_level') * 5;
					$O->add($health_increase, 'player_health');
					print($O->_('player_name') . ' health increased by ' . $health_increase . '.<br>
');
				}
				$O->delete('enemy');
				print('Enemy was killed.<br>
');
				// now for delicious loot
				generate_loot($O->_('player_name'), $O->_('id', $enemy), $array_item_types, $O);
			} else {
				$O->__('enemy_health', $O->_('enemy_health') - $damage_done);
				// enemy attacks back
				$enemy_damage_done = $O->_('enemy_damage');
				$total_reduction = 0;
				$armors = $O->_('items/armor', $O->_('.game_name=' . $O->enc($game_name_by_request)));
				if(is_array($armors) && sizeof($armors) > 0) {
					foreach($armors as $armor) {
						if($O->_('durability', $armor) > 0) {
							$total_reduction += $O->_('reduction', $armor);
						}
					}
				}
				$enemy_damage_done -= $total_reduction;
				$O->decrement_zero_floor('items/armor/durability', $items);
				if($enemy_damage_done < 0) {
					$enemy_damage_done = 0;
				}
				$O->__('player_health', $O->_('player_health') - $enemy_damage_done);
				print('Enemy attacks ' . $O->_('player_name') . ' for ' . $enemy_damage_done . '.<br>
');
				if($O->_('player_health') <= 0) {
					$O->__('game_status', 'dead');
					print($O->_('player_name')  . ' has died!<br>
');
				}
			}
		}
		$status_change = get_by_request('status_change');
		//print('$status_change: ');var_dump($status_change);
		if($status_change !== false) {
			$O->__('game_status', $status_change);
			if($status_change === 'in dungeon') { // spawn a monster
				generate_enemy($O->_('idcounter'), $O);
				$O->increment('idcounter');
			} elseif($status_change === 'in town') {
				if($O->_('enemy') === false) {
					
				} else {
				//	$O->__('enemy', '');
					$O->delete('enemy');
				}
			}
		}
	}
	
	// print the player
	print('<hr>
');
	print('Health: ' . $O->_('player_health') . '<br>
');
	print('Currency: ' . $O->_('player_currency') . '<br>
');
	print('Experience: ' . $O->_('player_experience') . '<br>
');
	print('Level: ' . $O->_('player_level') . '<br>
');
	print('Status: ' . $O->_('game_status') . '<br>
');
	$weapons = $O->_('items/weapon', $O->_('.game_name=' . $O->enc($game_name_by_request)));
	//print('$weapons: ');var_dump($weapons);
	print('<table cellspacing="0" border="1">
<caption>Weapons</caption>
<tr>
');
	if(is_array($weapons) && sizeof($weapons) > 0) {
		foreach($weapons as $weapon) {
			//print('$weapon, $O->_(\'damage\', $weapon): ');var_dump($weapon, $O->_('damage', $weapon));
			//print('$O->_(\'enemy\', $O->_(\'.game_name=\' . $game_name_by_request)): ');var_dump($O->_('enemy', $O->_('.game_name=' . $O->enc($game_name_by_request))));
			//print('$O->node_string($O->_(\'enemy\', $O->_(\'.game_name=\' . $game_name_by_request))): ');var_dump($O->node_string($O->_('enemy', $O->_('.game_name=' . $O->enc($game_name_by_request)))));exit(0);
			print('<td>Damage: ' . $O->_('damage', $weapon) . '<br>Durability: ' . $O->_('durability', $weapon) . '/' . $O->_('maximumdurability', $weapon) . '<br>Value: ' . $O->_('value', $weapon));
			if($O->_('game_status') === 'in town') {
				print('<form method="post" action="wadlof.php">
<input type="hidden" name="game_name" value="' . $O->_('game_name') . '" />
<input type="hidden" name="sell_id" value="' . $O->_('id', $weapon) . '" />
<input type="submit" value="Sell" />
</form>
');
				print('<form method="post" action="wadlof.php">
<input type="hidden" name="game_name" value="' . $O->_('game_name') . '" />
<input type="hidden" name="repair_id" value="' . $O->_('id', $weapon) . '" />
<input type="submit" value="Repair" />
</form>
');
			} elseif($O->_('game_status') === 'in dungeon' && strlen($O->node_string($O->_('enemy', $O->_('.game_name=' . $O->enc($game_name_by_request))))) > 0) {
				if($O->_('durability', $weapon) > 0) {
					print('<form method="post" action="wadlof.php">
<input type="hidden" name="game_name" value="' . $O->_('game_name') . '" />
<input type="hidden" name="weapon_id" value="' . $O->_('id', $weapon) . '" />
<input type="submit" value="Attack" />
</form>
');
				}
			}
			print('</td>
');
		}
	} else {
		print('<td>none</td>
');
	}
	print('</tr>
</table>
');
	$armors = $O->_('items/armor', $O->_('.game_name=' . $O->enc($game_name_by_request)));
	//print('$armors: ');var_dump($armors);exit(0);
	print('<table cellspacing="0" border="1">
<caption>Armor</caption>
<tr>
');
	//if(sizeof($armors) > 0) {
	if(is_array($armors) && sizeof($armors) > 0) {
		foreach($armors as $armor) {
			print('<td>Reduction: ' . $O->_('reduction', $armor) . '<br>Durability: ' . $O->_('durability', $armor) . '/' . $O->_('maximumdurability', $armor) . '<br>Value: ' . $O->_('value', $armor));
			if($O->_('game_status') === 'in town') {
				print('<form method="post" action="wadlof.php">
<input type="hidden" name="game_name" value="' . $O->_('game_name') . '" />
<input type="hidden" name="sell_id" value="' . $O->_('id', $armor) . '" />
<input type="submit" value="Sell" />
</form>
');
				print('<form method="post" action="wadlof.php">
<input type="hidden" name="game_name" value="' . $O->_('game_name') . '" />
<input type="hidden" name="repair_id" value="' . $O->_('id', $armor) . '" />
<input type="submit" value="Repair" />
</form>
');
			}
			print('</td>
');
		}
	} else {
		print('<td>none</td>
');
	}
	print('</tr>
</table>
');
	//print('$items_string before potion: ');var_dump($items_string);
	$potions = $O->_('items/potion', $O->_('.game_name=' . $O->enc($game_name_by_request)));
	print('<table cellspacing="0" border="1">
<caption>Potions</caption>
<tr>
');
	//if(sizeof($potions) > 0) {
	if(is_array($potions) && sizeof($potions) > 0) {
		foreach($potions as $potion) {
			print('<td>Recovery: ' . $O->_('recovery', $potion) . '<br>Value: ' . $O->_('value', $potion));
			if($O->_('game_status') === 'in town') {
				print('<form method="post" action="wadlof.php">
<input type="hidden" name="game_name" value="' . $O->_('game_name') . '" />
<input type="hidden" name="sell_id" value="' . $O->_('id', $potion) . '" />
<input type="submit" value="Sell" />
</form>
');
			}
			if($O->_('game_status') !== 'dead') {
				print('<form method="post" action="wadlof.php">
<input type="hidden" name="game_name" value="' . $O->_('game_name') . '" />
<input type="hidden" name="drink_id" value="' . $O->_('id', $potion) . '" />
<input type="submit" value="Drink" />
</form>
');
			}
			print('</td>
');
		}
	} else {
		print('<td>none</td>
');
	}
	print('</tr>
</table>
');
	$game_enemy = $O->_('enemy', $O->_('.game_name=' . $O->enc($game_name_by_request)));
	if($O->_('game_status') === 'in town') {
		//print('$O->_(\'game_name\'): ');var_dump($O->_('game_name'));
		print('<form method="post" action="wadlof.php">
<input type="hidden" name="game_name" value="' . $O->_('game_name') . '" />
<input type="hidden" name="status_change" value="in dungeon" />
<input type="submit" value="Enter Dungeon" />
</form>
');
	} elseif($O->_('game_status') === 'in dungeon') {
		if($game_enemy === false) {
			print('<form method="post" action="wadlof.php">
<input type="hidden" name="game_name" value="' . $O->_('game_name') . '" />
<input type="hidden" name="status_change" value="in dungeon" />
<input type="submit" value="Proceed in Dungeon" />
</form>
');
		}
	}
	
	// print the enemy
	//print('$game_name014: ');var_dump($game_name);
	//print('$O->_(\'enemy\', $game): ');var_dump($O->_('enemy', $game));
	if($game_enemy) {
		print('<hr>
');
		print('Enemy<br>
Damage: ' . $O->_('enemy_damage') . '<br>
');
		print('Armor: ' . $O->_('enemy_armor') . '<br>
');
		print('Health: ' . $O->_('enemy_health') . '<br>
');
		print('Experience: ' . $O->_('enemy_experience') . '<br>
');
	}
	if($O->_('game_status') === 'in dungeon') {
		print('<form method="post" action="wadlof.php">
<input type="hidden" name="game_name" value="' . $O->_('game_name') . '" />
<input type="hidden" name="status_change" value="in town" />
<input type="submit" value="Back to Town" />
</form>
');
	}
	// very important to save the game after all these contextual changes ;p
	$O->save_LOM_to_file('wadlof_games.xml');
}

function generate_enemy($enemy_id, $O) {
	//print('$enemy_id in generate_enemy, $O->LOM: ');var_dump($enemy_id, $O->LOM);
	// implying that there are 4 evenly relevant stats; damage, health, armor, experience
	$generation_points = ceil(pow($enemy_id, 1.1) * 4); // tuned 10, 5, 3, 7, | 1.1, 1.07, 1.05, 1.03
	$generation_points_left = $generation_points;
	$damage = ceil(pow(number_from_gaussian_centered_on($generation_points_left / 4), 1.20)); // tuned 1.5*
	$generation_points_left -= $damage;
	$health = 2 * (number_from_gaussian_centered_on($generation_points_left / 3)); // tuned 2*
	if($health < 0) {
		$health *= -1;
	}
	$generation_points_left -= $health;
	//$armor = ceil(number_from_gaussian_centered_on($generation_points_left / 2) / 1.5); // tuned /2
	$armor = ceil(number_from_gaussian_centered_on(pow(0.5 * $enemy_id, 1.20)));
	//$armor = ceil(number_from_gaussian_centered_on(0.7 * $enemy_id));
	//$generation_points_left -= $armor;
	$experience = ceil($generation_points_left);
	if($experience < 0) {
		$experience = 0;
	}
	$O->new_tag('<enemy>
<id>' . $enemy_id . '</id>
<damage>' . $damage . '</damage>
<health>' . $health . '</health>
<armor>' . $armor . '</armor>
<experience>' . $experience . '</experience>
</enemy>
', 'enemies');
	print('Encountered enemy.<br>
');
}

function generate_loot($game_name, $enemy_id, $array_item_types, $O) {
	$generation_points = $enemy_id * 10;
	$number_of_items_to_generate = ceil(quadratic_rand() / 1.8); // tuned /2, /1.7, /2
	while($number_of_items_to_generate > 0 && $generation_points > 0) {
		$item_type = $array_item_types[rand(0, sizeof($array_item_types) - 1)];
		$generation_points_to_use = number_from_gaussian_centered_on($generation_points / $number_of_items_to_generate);
		if($generation_points_to_use > $generation_points) {
			$generation_points_to_use = $generation_points;
		}
		$generation_points_left = $generation_points_to_use;
		if($item_type === 'weapon') {
			// implying that there are 3 evenly relevant stats; damage, maximumdurability, value
			$damage = number_from_gaussian_centered_on(pow($generation_points_left / 3, 1.2)); // tuned 1.05, 1.1, 1.2
			$generation_points_left -= $damage;
			$maximumdurability = number_from_gaussian_centered_on(4);
			if($maximumdurability < 1) {
				$maximumdurability = 1;
			}
			$durability = rand(0, $maximumdurability);
			$generation_points_left -= $generation_points_left / 2;
			$value = ceil($generation_points_left);
			$O->new_tag('<weapon>
<id>' . $O->_('game_idcounter') . '</id>
<damage>' . $damage . '</damage>
<durability>' . $durability . '</durability>
<maximumdurability>' . $maximumdurability . '</maximumdurability>
<value>' . $value . '</value>
</weapon>
', 'items');
			print('Weapon found.<br>
');
		} elseif($item_type === 'armor') {
			// implying that there are 3 evenly relevant stats; reduction, maximumdurability, value
			$reduction = ceil(number_from_gaussian_centered_on($generation_points_left / 3) / 3.2); // tuned to /2, /3, /4
			$generation_points_left -= $reduction;
			$maximumdurability = number_from_gaussian_centered_on(12);
			if($maximumdurability < 1) {
				$maximumdurability = 1;
			}
			$durability = rand(0, $maximumdurability);
			$generation_points_left -= $generation_points_left / 2;
			$value = ceil($generation_points_left);
			$O->new_tag('<armor>
<id>' . $O->_('game_idcounter') . '</id>
<reduction>' . $reduction . '</reduction>
<durability>' . $durability . '</durability>
<maximumdurability>' . $maximumdurability . '</maximumdurability>
<value>' . $value . '</value>
</armor>
', 'items');
			print('Armor found.<br>
');
		} elseif($item_type === 'potion') {
			// implying that there are 2 evenly relevant stats; recovery, value
			$recovery = number_from_gaussian_centered_on($generation_points_left / 2);
			$generation_points_left -= $recovery;
			$recovery *= 2;
			$value = ceil($generation_points_left);
			$O->new_tag('<potion>
<id>' . $O->_('game_idcounter') . '</id>
<recovery>' . $recovery . '</recovery>
<value>' . $value . '</value>
</potion>
', 'items');
			print('Potion found.<br>
');
		} else {
			print('Unhandled item type: ');var_dump($item_type);exit(0);
		}
		$generation_points -= $generation_points_to_use;
		$O->increment('idcounter');
		$number_of_items_to_generate--;
	}
	$O->add($generation_points, 'player_currency');
	if($generation_points > 0) {
		print($generation_points . ' currency earned.<br>
');
	}
	//exit(0);
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