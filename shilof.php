<?php

include('../LOM/O.php');

//error_reporting(0);
ini_set('xdebug.var_display_max_data', '5000');
$encounter_rate = 0.5;
$game_name_by_request = get_by_request('game_name');
//print('$game_name_by_request: ');var_dump($game_name_by_request);
if($game_name_by_request === false) {
	print('Enter the game name to start or continue a game:<br>
');
}
print('<form method="post" action="shilof.php">
game: <input type="text" name="game_name" value="' . $game_name_by_request . '" /><input type="submit" value="Go!" />
</form>
');
if($game_name_by_request !== false) { // print out the game status
	if(!file_exists('shilof_games.xml')) {
		file_put_contents('shilof_games.xml', '<games>
</games>');
	}
	$O = new O('shilof_games.xml');
	$game = $O->_('.game_name=' . $O->enc($game_name_by_request));
	//print('$game: ');var_dump($game);
	if(sizeof($game) === 0) { // then make a new game
		$game_string = '<game>
<name>' . $game_name_by_request . '</name>
<idcounter>2</idcounter>
<character>
<name>' . $game_name_by_request . '</name>
<location>0</location>
<experience>0</experience>
<level>0</level>
<health>20</health>
<maximumhealth>20</maximumhealth>
<attack>5</attack>
<defense>5</defense>
<speed>5</speed>
<skill>5</skill>
<perception>5</perception>
<attackgrowth>1</attackgrowth>
<defensegrowth>1</defensegrowth>
<speedgrowth>1</speedgrowth>
<skillgrowth>1</skillgrowth>
<perceptiongrowth>1</perceptiongrowth>
<age>0</age>
</character>
<opponent>
<name>dummy</name>
<location>0</location>
<experience>0</experience>
<level>1</level>
<health>10</health>
<maximumhealth>10</maximumhealth>
<attack>2</attack>
<defense>2</defense>
<speed>2</speed>
<skill>2</skill>
<perception>2</perception>
<attackgrowth>1</attackgrowth>
<defensegrowth>1</defensegrowth>
<speedgrowth>1</speedgrowth>
<skillgrowth>1</skillgrowth>
<perceptiongrowth>1</perceptiongrowth>
<age>0</age>
</opponent>
</game>
';
		$game = $O->new_tag($game_string, 'games');
	} else {
		// messages telling the character what happened this "turn". not needed if the page refresh is fact enough to see the differences. but it won't be with many characters
		/*$swap_id1_by_request = get_by_request('swap_id1');
		$swap_id2_by_request = get_by_request('swap_id2');
		if($swap_id1_by_request !== false) {*/
		
		$messages = array();
		foreach($O->_('character') as $character) {
			$location = $O->_('location', $character);
			$opponent = $O->_('.opponent_location=' . $location);
			if(sizeof($opponent) > 0) {
				// simultaneous attacks
				// not accounting for skill or speed yet
				$character_damage_done = $O->_('attack', $character) - $O->_('defense', $opponent);
				if($character_damage_done < 0) {
					$character_damage_done = 0;
				}
				$messages[$location] .= $character_damage_done . ' damage was done to ' . $O->_('name', $opponent) . '.<br>
';
				$opponent_damage_done = $O->_('attack', $opponent) - $O->_('defense', $character);
				if($opponent_damage_done < 0) {
					$opponent_damage_done = 0;
				}
				$messages[$location] .= $opponent_damage_done . ' damage was done to ' . $O->_('name', $character) . '.<br>
';
				$character_experience_gained = $O->_('level', $opponent) + $O->_('skill', $opponent) + $character_damage_done; // good formula?
				$messages[$location] .= $O->_('name', $character) . ' gained ' . $character_experience_gained . ' experience.<br>
';
				$O->add($character_experience_gained, 'experience', $character);
				if($O->_('experience', $character) >= 100) { // level up!
					$O->subtract(100, $O->_('experience', $character));
					$O->increment('level', $character);
					$messages[$location] .= $O->_('name', $character) . ' is now level ' . $O->_('level', $character) . '.<br>
';
					// stat gains (do it dumb for now)
					$O->add('attackgrowth', 'attack', $character);
					$O->add('defensegrowth', 'defense', $character);
					$O->add('speedgrowth', 'speed', $character);
					$O->add('skillgrowth', 'skill', $character);
					$O->add('perceptiongrowth', 'perception', $character);
				}
				$opponent_experience_gained = $O->_('level', $character) + $O->_('skill', $character) + $opponent_damage_done; // good formula?
				$O->add($opponent_experience_gained, 'experience', $opponent);
				if($O->_('experience', $opponent) >= 100) { // level up!
					$O->subtract(100, $O->_('experience', $opponent));
					$O->increment('level', $opponent);
					// stat gains (do it dumb for now)
					$O->add('attackgrowth', 'attack', $opponent);
					$O->add('defensegrowth', 'defense', $opponent);
					$O->add('speedgrowth', 'speed', $opponent);
					$O->add('skillgrowth', 'skill', $opponent);
					$O->add('perceptiongrowth', 'perception', $opponent);
				}
				$opponent_health = $O->_('health', $opponent);
				if($character_damage_done >= $opponent_health) {
					$messages[$location] .= $O->_('name', $opponent) . ' was killed.<br>
';
					$O->delete($opponent);
					// automatic healing. hmm healing if there is no enemy would be better. brings up the question of leeching, regen
				}
			} else { // chance to generate an opponent
				// dumb for now
				if((rand(0, 1000) / 1000) < $encounter_rate) {
					$O->new_('<opponent>
<name>dummy</name>
<location>0</location>
<experience>0</experience>
<level>1</level>
<health>10</health>
<maximumhealth>10</maximumhealth>
<attack>2</attack>
<defense>2</defense>
<speed>2</speed>
<skill>2</skill>
<perception>2</perception>
<attackgrowth>1</attackgrowth>
<defensegrowth>1</defensegrowth>
<speedgrowth>1</speedgrowth>
<skillgrowth>1</skillgrowth>
<perceptiongrowth>1</perceptiongrowth>
<age>0</age>
</opponent>', $game);
				}
			}
		}
		// could add multiplayer code to this and it might be quite fun!
	}
	
	// print the characters
	print('<hr>
<table border="1" cellspacing="0" cellpadding="4">
<tr>');
	foreach($O->_('character') as $character) {
		print('<td>');
		// character's opponent
		$opponent = $O->_('.opponent_location=' . $O->_('location', $character));
		if($O->_('perception', $character) > (5 * $O->_('skill', $opponent))) {
			print($O->_('name', $opponent) . '<br>
');
		} else {
			print('??<br>
');
		}
		if($O->_('level', $character) > $O->_('level', $opponent) + 2) {
			print('level: ' . $O->_('level', $opponent) . '<br>
');
		} else {
			print('level: ??<br>
');
		}
		//print('$O->_(\'perception\', $character): ');var_dump($O->_('perception', $character));
		if((5 * $O->_('perception', $character)) > $O->_('skill', $opponent)) {
			print('health: ' . $O->_('health', $opponent) . '/' . $O->_('maximumhealth', $opponent) . '<br>
');
		} else {
			print('health: ??<br>
');
		}
		if($O->_('perception', $character) + $O->_('attack', $character) > $O->_('attack', $opponent)) {
			print('attack: ' . $O->_('attack', $opponent) . '<br>
');
		} else {
			print('attack: ??<br>
');
		}
		if($O->_('perception', $character) + $O->_('defense', $character) > $O->_('defense', $opponent)) {
			print('defense: ' . $O->_('defense', $opponent) . '<br>
');
		} else {
			print('defense: ??<br>
');
		}
		if($O->_('perception', $character) + $O->_('speed', $character) > $O->_('speed', $opponent)) {
			print('speed: ' . $O->_('speed', $opponent) . '<br>
');
		} else {
			print('speed: ??<br>
');
		}
		if($O->_('perception', $character) + $O->_('skill', $character) > $O->_('skill', $opponent)) {
			print('skill: ' . $O->_('skill', $opponent) . '<br>
');
		} else {
			print('skill: ??<br>
');
		}
		if($O->_('perception', $character) > (5 * $O->_('skill', $opponent))) {
			print('perception: ' . $O->_('perception', $opponent) . '<br>
');
		} else {
			print('perception: ??<br>
');
		}
		print('<hr>
');
		// character
		print($O->_('name', $character) . '<br>
');
		print('level: ' . $O->_('level', $character) . '<br>
');
		print('health: ' . $O->_('health', $character) . '/' . $O->_('maximumhealth', $character) . '<br>
');
		print('attack: ' . $O->_('attack', $character) . '<br>
');
		print('defense: ' . $O->_('defense', $character) . '<br>
');
		print('speed: ' . $O->_('speed', $character) . '<br>
');
		print('skill: ' . $O->_('skill', $character) . '<br>
');
		print('perception: ' . $O->_('perception', $character) . '<br>
');
		print('age: ' . $O->_('age', $character) . '<br>
');
		// attack button, others? capture, swap
		print('<hr>
');
		print($messages[$O->_('location', $character)]);
		print('</td>');
	}
	print('</tr>
</table>');
	print('<form action="shilof.php" method="post">
<input type="hidden" name="game_name" value="' . $O->_('game_name') . '" />
<input type="submit" value="process turn" />
</form>');
	// very important to save the game after all these contextual changes ;p
	$O->save_LOM_to_file('shilof_games.xml');
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