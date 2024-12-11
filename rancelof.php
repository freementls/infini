<style type="text/css">
form { margin-bottom: 0; }
</style>

<?php

// for now there are only attack skills

include('../LOM/O.php');

//error_reporting(0);
ini_set('xdebug.var_display_max_data', '5000');
$game_name_by_request = get_by_request('game_name');
//print('$game_name_by_request: ');var_dump($game_name_by_request);
if($game_name_by_request === false) {
	print('Enter the game name to start or continue a game:<br>
');
}
print('<form method="post" action="rancelof.php">
Game: <input type="text" name="game_name" value="' . $game_name_by_request . '" /><input type="submit" value="Go!" />
</form>
');
if($game_name_by_request !== false) { // print out the game status
	if(!file_exists('rancelof_games.xml')) {
		file_put_contents('rancelof_games.xml', '<games>
</games>');
	}
	//$array_item_types = array('weapon', 'armor', 'potion');
	$O = new O('rancelof_games.xml');
	$stats = new O('rancelof_stats.xml');
	$skills = new O('rancelof_skills.xml');
	$array_levels = array(0, 2, 5, 10, 20, 50, 100, 200, 500, 1000, 2000, 5000, 10000);
	//print('$O->_(\'game_name=\' . $game_name_by_request): ');var_dump($O->_('game_name=' . $O->enc($game_name_by_request)));exit(0);
	$game = $O->_('.game_gamename=' . $O->enc($game_name_by_request));
	//print('$game: ');var_dump($game);exit(0);
	if($game === false) { // then make a new game
		$game_string = '<game>
<name>' . $game_name_by_request . '</name>
<idcounter>2</idcounter>
<currency>20</currency>
<inventory>
</inventory>
<players>
<player>
<id>0</id>
<name>' . $game_name_by_request . '</name>
<x>1</x>
<y>1</y>
<health>50</health>
<maximumhealth>50</maximumhealth>
<attack>3</attack>
<defense>3</defense>
<speed>3</speed>
<cooldown>0</cooldown>
<experience>0</experience>
<level>0</level>
<itemslot></itemslot>
<skill>
<id>1</id>
<name>sword attack</name>
<multiplier>1</multiplier>
<cooldown>0.5</cooldown>
<range>1</range>
</skill>
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
		// advancement is different from moving
		$advance = get_by_request('advance');
		if($advance == true) {
			$O->decrement('enemy_x');
			$backline_y = 0;
			while($backline_y < 2) {
				$rand = rand(1, 3);
				if($rand === 1) {
					//print('her3759<br>');
					$O = generate_enemy(5, $backline_y, $O, $stats, $skills);
					//print('generated enemy at 5,' . $backline_y . '<br>');
				}
				$backline_y++;
			}
		}
		$player_id = get_by_request('player_id');
		//print('$player_id: ');var_dump($player_id);
		$player = $O->_('.player_id=' . $player_id);
		//print('$player: ');var_dump($player);
		$rename = get_by_request('rename');
		if($rename == true) {
			$new_name = get_by_request('new_name');
			$O->__('name', $new_name, $player);
		}
		$move_left = get_by_request('move_left');
		if($move_left == true) {
			$O->decrement('x', $player);
		}
		$move_right = get_by_request('move_right');
		if($move_right == true) {
			$O->increment('x', $player);
		}
		$move_up = get_by_request('move_up');
		if($move_up == true) {
			$O->decrement('y', $player);
		}
		$move_down = get_by_request('move_down');
		if($move_down == true) {
			$O->increment('y', $player);
		}
		/*$select_id_by_request = get_by_request('select_id');
		//print('$select_id_by_request: ');var_dump($select_id_by_request);
		$item_to_select = $O->_('inventory_.*_id=' . $select_id_by_request);
		//print('$item_to_select: ');var_dump($item_to_select);
		if($item_to_select !== false) {
			$O->fatal_error('item selection not coded yet');
			$O->add($O->_('value', $item_to_select), 'player_currency');
			print($O->_('name', $item_to_select) . ' was sold for ' . $O->_('value', $item_to_select) . '.<br>
');
			$O->__($item_to_select, '');
		}
		$sell_id_by_request = get_by_request('sell_id');
		//print('$sell_id_by_request: ');var_dump($sell_id_by_request);
		$item_to_sell = $O->_('inventory_.*_id=' . $sell_id_by_request);
		//print('$item_to_sell: ');var_dump($item_to_sell);
		if($item_to_sell !== false) {
			$O->add($O->_('value', $item_to_sell), 'player_currency');
			print($O->_('name', $item_to_sell) . ' was sold for ' . $O->_('value', $item_to_sell) . '.<br>
');
			$O->__($item_to_sell, '');
		}
		$drink_id_by_request = get_by_request('drink_id');
		$item_to_drink = $O->_('inventory/.potion/id=' . $drink_id_by_request);
		if($item_to_drink !== false) {
			$O->add($O->_('recovery', $item_to_drink), 'player_health');
			print($O->_('player_name') . ' recovered ' . $O->_('recovery', $item_to_drink) . ' health.<br>
');
			$O->delete($item_to_drink);
		}
		$repair_id_by_request = get_by_request('repair_id');
		if($repair_id_by_request !== false) {
			$item_to_repair = $O->_('inventory_.*_id=' . $repair_id_by_request);
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
					$O->__($item_to_repair, '');
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
				// instead of generating it, drop what the enemy had; maybe without perfect conservation
				//generate_item($O->_('player_name'), $O->_('id', $enemy), $array_item_types, $O);
			} else {
				$O->__('enemy_health', $O->_('enemy_health') - $damage_done);
				// enemy attacks back
				$enemy_damage_done = $O->_('enemy_damage');
				$total_reduction = 0;
				$armors = $O->_('items/armor', $O->_('.game_name=' . $O->enc($game_name_by_request)));
				foreach($armors as $armor) {
					if($O->_('durability', $armor) > 0) {
						$total_reduction += $O->_('reduction', $armor);
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
		}*/
	}
	/*
	// inventory
	$items = $O->_('inventory/item', $O->_('.game_name=' . $O->enc($game_name_by_request)));
	print('<table cellspacing="0" border="1">
<caption>inventory</caption>
<tr>
');
	//if(sizeof($items) > 0) {
	if($items !== false) {
		foreach($items as $item) {
			print('<td>');
			$item_stats = $O->_('*', $item);
			$item_stat_indices = $O->get_indices('*', $item);
			foreach($item_stat_indices as $item_stat_index) {				
				print($O->get_tag_name($item_stat_index) . ': ' . $item_stats[$item_stat_index] . '<br>');
			}
			print('<form method="post" action="rancelof.php">
<input type="hidden" name="game_name" value="' . $O->_('game_name') . '" />
<input type="hidden" name="select_id" value="' . $O->_('id', $item) . '" />
<input type="submit" value="select" />
</form>
');
			print('<form method="post" action="rancelof.php">
<input type="hidden" name="game_name" value="' . $O->_('game_name') . '" />
<input type="hidden" name="sell_id" value="' . $O->_('id', $item) . '" />
<input type="submit" value="sell" />
</form>
');
			print('</td>
');
		}
	} else {
		print('<td>none</td>
');
	}
	print('</tr>
</table>
');*/
	
	//print('$O->LOM, $O->context: ');$O->var_dump_full($O->LOM, $O->context);exit(0);
	// print the field
	print('<hr>
');
	print('<table border="1" cellspacing="0" cellpadding="4">
');
	$height = 0;
	//$height = 1;
	while($height < 3) {
		print('<tr>
');
		$units_in_height = $O->_('.*_y=' . $height);
		//print('$height, $units_in_height: ');var_dump($height, $units_in_height);
		$width = 0;
		//$width = 1;
		while($width < 6) {
			print('<td style="height: 250px; width: 250px;">
');
			if(is_array($units_in_height)) {
				$units_in_tile = $O->_('.*_x=' . $width, $units_in_height);
				//print('$width, $units_in_tile: ');var_dump($width, $units_in_tile);
				if(is_array($units_in_tile)) {
					//print('$units_in_tile: ');var_dump($units_in_tile);
					foreach($units_in_tile as $unit_in_tile) {
						$attack_boost = 0;
						$defense_boost = 0;
						$speed_boost = 0;
						$damage_boost = 0;
						$armor_boost = 0;
						$unit_cooldown = $O->_('cooldown', $unit_in_tile);
						$items_string = '';
						$unit_items = $O->_('itemslot', $unit_in_tile);
						if(is_array($unit_items)) {
							foreach($unit_items as $unit_item) {
								if($O->_('id', $unit_item) === false) {
									$items_string .= '<div style="width: 50px; height: 50px; border: 1px solid black; margin-right: 2px; margin-bottom: 2px; float: left; text-align: center; font-size: 75%; color: grey;">empty<br>item<br>slot</div>';
								} else {
									$item_string = '<div style="width: 50px; height: 50px; border: 1px solid black; margin-right: 2px; margin-bottom: 2px; float: left; text-align: left; font-size: 75%; line-height: 10px;">';
									$item_stats = $O->_('stat', $unit_item);
									if($item_stats) {
										foreach($item_stats as $item_stat) {
											// print the text and apply the mod
											$stat_text = $O->_('text', $item_stat);
											if(is_array($stat_text)) {
												$stat_text = $O->string_from_LOM($stat_text);
												$stat_text = str_replace('<text>', '', $stat_text);
												$stat_text = str_replace('</text>', '', $stat_text);
											}
											$item_string .= $stat_text . '<br>';
											if($O->_('attackboost', $item_stat) !== false) {
												$attack_boost += $O->_('attackboost', $item_stat);
											}
											if($O->_('defenseboost', $item_stat) !== false) {
												$defense_boost += $O->_('defenseboost', $item_stat);
											}
											if($O->_('speedboost', $item_stat) !== false) {
												$speed_boost += $O->_('speedboost', $item_stat);
											}
											if($O->_('damageboost', $item_stat) !== false) {
												$damage_boost += $O->_('damageboost', $item_stat);
											}
											if($O->_('armorboost', $item_stat) !== false) {
												$armor_boost += $O->_('armorboost', $item_stat);
											}
										}
									}
									$item_string .= 'value: ' . $O->_('value', $unit_item) . '<br>';
									$item_string .= '</div>';
									$items_string .= $item_string;
								}
							}
						}
						$actions_string = '';
						$enemy_actions_string = '';
						$unit_skills = $O->_('skill', $unit_in_tile);
						if(is_array($unit_skills)) {
							foreach($unit_skills as $unit_skill) {
								$actions_string .= '<form action="rancelof.php" method="post" style="border: 1px solid black; margin-top: 2px;">
<input type="submit" value="' . $O->_('skillname', $unit_skill) . '" />
<input type="hidden" name="game_name" value="' . $game_name_by_request . '" />
<input type="hidden" name="player_id" value="' . $O->_('id', $unit_in_tile) . '" />
<input type="hidden" name="skill_id" value="' . $O->_('id', $unit_skill) . '" />
<input type="hidden" name="skill_multiplier" value="' . $O->_('skillmultiplier', $unit_skill) . '" />magnitude: ' . 100 * $O->_('skillmultiplier', $unit_skill) . '%
<input type="hidden" name="skill_cooldown" value="' . $O->_('skillcooldown', $unit_skill) . '" />cooldown: ' . $O->_('skillcooldown', $unit_skill) . ' seconds 
<input type="hidden" name="skill_range" value="' . $O->_('skillrange', $unit_skill) . '" />range: ' . $O->_('skillrange', $unit_skill) . ' 
</form>';
								$enemy_actions_string .= '<div style="border: 1px solid black; margin-top: 2px; padding: 1px;">
<span style="border: 1px solid grey; background-color: lightgrey;">' . $O->_('skillname', $unit_skill) . '</span>
<span>magnitude: ' . 100 * $O->_('skillmultiplier', $unit_skill) . '%</span>
<span>cooldown: ' . $O->_('skillcooldown', $unit_skill) . ' seconds</span>
<span>range: ' . $O->_('skillrange', $unit_skill) . '</span>
</div>';
							}
						}
						if($O->get_tag_name($unit_in_tile) === 'player') {
							print('<div style="float: right;"><form action="rancelof.php" method="post">
<input type="submit" value="rename" />
<input type="hidden" name="game_name" value="' . $game_name_by_request . '" />
<input type="hidden" name="rename" value="true" />
<input type="hidden" name="player_id" value="' . $O->_('id', $unit_in_tile) . '" />
<input type="text" name="new_name" value="" placeholder="new name" size="8" />
</form></div>');
						}
						//print('$O->_(\'name\', $unit_in_tile): ');var_dump($O->_('name', $unit_in_tile));
						print('<div style="float: left;"><strong>' . $O->_('name', $unit_in_tile) . '</strong></div>
<div style="clear: both;">health: ' . $O->_('health', $unit_in_tile) . '/' . $O->_('maximumhealth', $unit_in_tile) . ' level: ' . $O->_('level', $unit_in_tile) . ' experience: ' . $O->_('experience', $unit_in_tile) . '</div>
');
						print('<abbr title="' . $O->_('attack', $unit_in_tile) . ' ');
						if($attack_boost !== 0) {
							if($attack_boost < 0) {
								print('- ' . substr($attack_boost, 1) . ' ');
							} else {
								print('+ ' . $attack_boost . ' ');
							}
						}
						print('attack" style="color: red;">');
						if($attack_boost !== 0) {
							print('(');
						}
						print('<strong>' . ($O->_('attack', $unit_in_tile) + $attack_boost) . '</strong>');
						if($attack_boost !== 0) {
							print(')');
						}
						print('</abbr> ');
						
						print('<abbr title="' . $O->_('defense', $unit_in_tile) . ' ');
						if($defense_boost !== 0) {
							if($defense_boost < 0) {
								print('- ' . substr($defense_boost, 1) . ' ');
							} else {
								print('+ ' . $defense_boost . ' ');
							}
						}
						print('defense" style="color: blue;">');
						if($defense_boost !== 0) {
							print('(');
						}
						print('<strong>' . ($O->_('defense', $unit_in_tile) + $defense_boost) . '</strong>');
						if($defense_boost !== 0) {
							print(')');
						}
						print('</abbr> ');
						
						print('<abbr title="' . $O->_('speed', $unit_in_tile) . ' ');
						if($speed_boost !== 0) {
							if($speed_boost < 0) {
								print('- ' . substr($speed_boost, 1) . ' ');
							} else {
								print('+ ' . $speed_boost . ' ');
							}
						}
						print('speed" style="color: green;">');
						if($speed_boost !== 0) {
							print('(');
						}
						print('<strong>' . ($O->_('speed', $unit_in_tile) + $speed_boost) . '</strong>');
						if($speed_boost !== 0) {
							print(')');
						}
						print('</abbr> ');

						print('cooldown: ' . $unit_cooldown . '<br>
' . $items_string);
						print('<div style="clear: both;">');
						if($unit_cooldown <= 0 && $O->get_tag_name($unit_in_tile) === 'player') {
							//$unit_in_tile_x = $O->_('x', $unit_in_tile);
							//$unit_in_tile_y = $O->_('y', $unit_in_tile);
							$unit_id = $O->_('player_id', $unit_in_tile);
							//print('$unit_id: ');var_dump($unit_id);
							//if($unit_in_tile_x == 1) {
							//	print('$unit_in_tile_x, $unit_in_tile_y: ');var_dump($unit_in_tile_x, $unit_in_tile_y);
							//	print('$O->_(\'.*_x=\' . $unit_in_tile_x . \'&y=\' . ($unit_in_tile_y - 1)): ');var_dump($O->_('.*_x=' . $unit_in_tile_x . '&y=' . ($unit_in_tile_y - 1)));
							//	exit(0);
							//}
							print('<table border="0" cellspacing="0" cellpadding="0"><tr><td></td><td>');
							if($height > 0 && $O->_('.*_x=' . $width . '&y=' . ($height - 1)) === false) {
								print('<form action="rancelof.php" method="post">
<input type="submit" value="&uarr;" />
<input type="hidden" name="game_name" value="' . $game_name_by_request . '" />
<input type="hidden" name="player_id" value="' . $unit_id . '" />
<input type="hidden" name="move_up" value="true" />
</form>');
							}
							print('</td><td></td><td>&nbsp;</td></tr><tr><td>');
							if($width > 0 && $O->_('.*_x=' . ($width - 1) . '&y=' . $height) === false) {
								print('<form action="rancelof.php" method="post">
<input type="submit" value="&larr;" />
<input type="hidden" name="game_name" value="' . $game_name_by_request . '" />
<input type="hidden" name="player_id" value="' . $unit_id . '" />
<input type="hidden" name="move_left" value="true" />
</form>');
							}
							print('</td><td>&nbsp;</td><td>');
							if($width < 2 && $O->_('.*_x=' . ($width + 1) . '&y=' . $height) === false) {
								print('<form action="rancelof.php" method="post">
<input type="submit" value="&rarr;" />
<input type="hidden" name="game_name" value="' . $game_name_by_request . '" />
<input type="hidden" name="player_id" value="' . $unit_id . '" />
<input type="hidden" name="move_right" value="true" />
</form>');
							}
							print('</td><td>');
							// all units take time to advance
							$enemy_xs = $O->_('enemy_x');
							$there_is_an_enemy_in_their_front_line = false;
							if(is_array($enemy_xs)) {
								foreach($enemy_xs as $enemy_x) {
									if($enemy_x == 4) {
										$there_is_an_enemy_in_their_front_line = true;
									}
								}
							}
							if(!$there_is_an_enemy_in_their_front_line) {
								print('<form action="rancelof.php" method="post">
<input type="submit" value="advance" />
<input type="hidden" name="game_name" value="' . $game_name_by_request . '" />
<input type="hidden" name="advance" value="true" />
</form>');
							}
							print('</td></tr><tr><td></td><td>');
							if($height < 2 && $O->_('.*_x=' . $width . '&y=' . ($height + 1)) === false) {
								print('<form action="rancelof.php" method="post">
<input type="submit" value="&darr;" />
<input type="hidden" name="game_name" value="' . $game_name_by_request . '" />
<input type="hidden" name="player_id" value="' . $unit_id . '" />
<input type="hidden" name="move_down" value="true" />
</form>');
							}
							print('</td><td></td><td>&nbsp;</td></tr></table>');
							print($actions_string);
						} elseif($O->get_tag_name($unit_in_tile) === 'enemy') {
							print($enemy_actions_string);
						}
						print('</div>');
					}
				}
			}
			print('</td>
');
			$width++;
			//exit(0);
		}
		//exit(0);
		print('</tr>
');
		$height++;
	}
	print('</table>
');
	

	// very important to save the game after all these contextual changes ;p
	$O->save_LOM_to_file('rancelof_games.xml');
}

function generate_enemy($x, $y, $O, $stats, $skills) {
	//print('$enemy_id in generate_enemy, $O->LOM: ');var_dump($enemy_id, $O->LOM);
	// implying that there are 4 evenly relevant stats; damage, health, armor, experience
	//$generation_points = ceil(pow($enemy_id, 1.1) * 4); // tuned 10, 5, 3, 7, | 1.1, 1.07, 1.05, 1.03
	$generation_points = 100 + (10 * $O->_('idcounter'));
	$generation_points_left = $generation_points;
	// must have at least one skill
	// skill rarity?
	$all_skills = $skills->_('skill');
	$random_skill = $all_skills[rand(0, sizeof($all_skills) - 1)];
	//print('$random_skill: ');var_dump($random_skill);
	$generation_points_left -= $skills->_('cost', $random_skill);
	$attack = 3;
	$defense = 3;
	$speed = 3;
	$rollable_stats = $stats->_('.stat_rollableon=enemy');
	$quadratic_rand = quadratic_rand();
	while($quadratic_rand > 0) {
		$roll = rand(0, sizeof($rollable_stats) - 1);
		foreach($rollable_stats as $rollable_stat_index => $rollable_stat) {
			if($roll === 0) {
				$rolled_stat = $rollable_stat;
				break;
			}
			$roll--;
		}
		$stat_input = quadratic_rand() - quadratic_rand();
		//print('$rollable_stat: ');var_dump($rollable_stat);
		if($stats->_('attackboost', $rollable_stat)) {
			$attack += $stats->_('attackboost', $rollable_stat);
		} elseif($stats->_('defenseboost', $rollable_stat)) {
			$defense += $stats->_('defenseboost', $rollable_stat);
		} elseif($stats->_('speedboost', $rollable_stat)) {
			$speed += $stats->_('speedboost', $rollable_stat);
		}
		$stat_cost = exec(str_replace('x', $stat_input, $stats->_('cost', $rollable_stat)));
		if($stat_cost <= $generation_points_left) {
			$generation_points_left -= $stat_cost;
		}
		$quadratic_rand--;
	}
	// maybe not always have maximum health?
	$health = 25 + $generation_points_left;
	//if($health < 0) {
	//	$health *= -1;
	//}
	$experience = ceil($generation_points / 5);
	$currency = ceil($generation_points / 5);
	//if($experience < 0) {
	//	$experience = 0;
	//}
	$generated_name = 'Enemy ' . $O->_('idcounter');
	//print('her3760<br>');
	$items_string = '';
	$items_quadratic_rand = quadratic_rand();
	while($items_quadratic_rand > -1) {
		$items_string .= '<itemslot>
<id>' . $O->_('idcounter') . '</id>
' . generate_item($O, $stats) . '
</itemslot>
';
		//print('her3760.5<br>');
		$O->increment('idcounter');
		$items_quadratic_rand--;
		//print('her3761<br>');
	}
	//print('her3762<br>');
	$O->new_tag('<enemy>
<id>' . $O->_('idcounter') . '</id>
<name>' . $generated_name . '</name>
<x>' . $x . '</x>
<y>' . $y . '</y>
<health>' . $health . '</health>
<maximumhealth>' . $health . '</maximumhealth>
<attack>' . $attack . '</attack>
<defense>' . $defense . '</defense>
<speed>' . $speed . '</speed>
<cooldown>0</cooldown>
<experience>' . $experience . '</experience>
<currency>' . $currency . '</currency>
<level>0</level>
' . $items_string . $O->string_from_LOM($random_skill) . '
</enemy>
', 'enemies');
	//print('her3763<br>');
	$O->increment('idcounter');
	return $O;
}

function generate_item($O, $stats) {
	$generation_points = $O->_('idcounter') * 10;
	$generation_points_left = $generation_points;
	$item_string = '';
	$rollable_stats = $stats->_('.stat_rollableon=item');
	// must have durability
	/*<stat>
<text>{durability}/{maximumdurability} durability</text>
<durability>{x}</durability>
<maximumdurability>{x}</maximumdurability>
<cost>{x}</cost>
<rollableon>item</rollableon>
</stat>*/
	$stat_input = -1;
	while($stat_input <= 0) {
		$stat_input = number_from_gaussian_centered_on($generation_points_left / 4);
	}
	if($stat_input <= $generation_points_left) {
		$item_string .= '<stat>
<text>' . $stat_input . '/' . $stat_input . ' durability</text>
<durability>' . $stat_input . '</durability>
<maximumdurability>' . $stat_input . '</maximumdurability>
</stat>';
		$generation_points_left -= $stat_input;
	}
	$quadratic_rand = quadratic_rand();
	while($quadratic_rand > 0) {
		$roll = rand(0, sizeof($rollable_stats) - 1);
		foreach($rollable_stats as $rollable_stat_index => $rollable_stat) {
			if($roll === 0) {
				$rolled_stat = $rollable_stat;
				break;
			}
			$roll--;
		}
		$stat_input = quadratic_rand() - quadratic_rand();
		if($stat_input != 0) {
			eval('$stat_cost = ' . str_replace('{x}', $stat_input, $stats->_('cost', $rollable_stat)) . ';');
			if($stat_cost <= $generation_points_left) {
				$stat_string = $O->LOM_to_string($rolled_stat);
				$stat_string = preg_replace('/<cost>.*?<\/cost>/is', '', $stat_string);
				$stat_string = preg_replace('/<rollableon>.*?<\/rollableon>/is', '', $stat_string);
				$stat_string = str_replace('
', '', $stat_string);
				//print('$stat_string1: ');var_dump($stat_string);
				$stat_LOM = $O->string_to_LOM($stat_string);
				$stat_string = activate_string($stat_LOM, $stat_input, $O);
				//print('$stat_string2: ');var_dump($stat_string);
				$item_string .= $stat_string;
				$generation_points_left -= $stat_cost;
			}
		}
		$quadratic_rand--;
	}
	if($generation_points_left > 0) {
		$item_string .= '<value>' . $generation_points_left . '</value>';
	}
	//print('$item_string: ');var_dump($item_string);exit(0);
	return $item_string;
}

function activate_string($stat_LOM, $stat_input, $O) {
	// exec the stuff with x
	foreach($stat_LOM as $index => $value) {
		if($value[0] === 0) { // text
			//$O->warning_once('need a better way to check whether a string should be executed versus when it\'s just text');
			if(strpos($stat_LOM[$index][1], '{x}') !== false) { // pretty sloppy condition...
				$stat_LOM[$index][1] = str_replace('{x}', $stat_input, $stat_LOM[$index][1]);
				//print('$stat_LOM[$index][1]: ');var_dump($stat_LOM[$index][1]);
				eval('$stat_LOM[$index][1] = ' . $stat_LOM[$index][1] . ';');
				//print('$stat_LOM[$index][1]: ');var_dump($stat_LOM[$index][1]);
			}
		}
	}
	foreach($stat_LOM as $index => $value) {
		if($value[0] === 0) { // text
			preg_match_all('/\{([^\{\}]+)\}/is', $stat_LOM[$index][1], $curly_bracket_replaces);
			//print('$curly_bracket_replaces: ');var_dump($curly_bracket_replaces);
			foreach($curly_bracket_replaces[1] as $tagname) {
				$stat_LOM[$index][1] = str_replace('{' . $tagname . '}', $O->_($tagname, $stat_LOM, false), $stat_LOM[$index][1]);
			}
			$stat_LOM[$index][1] = str_replace('+-', '-', $stat_LOM[$index][1]);
		}
	}
	//print('$stat_LOM: ');var_dump($stat_LOM);
	return $O->LOM_to_string($stat_LOM);
}

function sign($n) {
    return ($n > 0) - ($n < 0);
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