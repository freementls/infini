<?php

// could add in the concept of equipped items to avoid the problem of figuring out which item to attack with if there are multiple enemies. for now, just have single enemies
// damage ranges? (stat ranges in general) to make gameplay more unpredictable (cheap thrills)
// multiple player characters capture enemies that become player characters... ah yeah

include('../LOM/O.php');

//error_reporting(0);
ini_set('xdebug.var_display_max_data', '5000');
$item_destruction_threshhold = 0.05; // hard-coded

$game_name_by_request = get_by_request('game_name');
//print('$game_name_by_request: ');var_dump($game_name_by_request);
if($game_name_by_request === false) {
	print('Enter the game name to start or continue a game:<br>
');
}
print('<form method="post" action="balancelof.php">
Game: <input type="text" name="game_name" value="' . $game_name_by_request . '" /><input type="submit" value="Go!" />
</form>
');
// should a game world be generated for each game? making a single game world accessible by all players makes it more interesting (more replayable, or specifically, continually playable)
warning_once('need to check if a game world exists and generate one otherwise');
warning_once('there should be no static values anywhere. but we\'d like to get the game into a working state before making it more dynamic');
if($game_name_by_request !== false) { // print out the game status
	if(!file_exists('balancelof_games.xml')) {
		file_put_contents('balancelof_games.xml', '<games>
</games>');
	}
	$array_item_types = array('wearable', 'socketable', 'consumable', 'container');
	$O = new O('balancelof_games.xml');
	//$array_levels = array(0, 2, 5, 10, 20, 50, 100, 200, 500, 1000, 2000, 5000, 10000); // crude overarching level systems are not used
	//print('$O->_(\'game_name=\' . $game_name_by_request): ');var_dump($O->_('game_name=' . $O->enc($game_name_by_request)));exit(0);
	$game = $O->_('.game_name=' . $O->enc($game_name_by_request));
	//print('$game: ');var_dump($game);exit(0);
	$wearableslots_object = new O('wearableslots.xml');
	$array_surface_associations = array();
	$slots_with_surface = $wearableslots_object->_('.*_surface');
	foreach($slots_with_surface as $slot_with_surface) {
		$array_surface_associations[$wearableslots_object->tagname($slot_with_surface)] = $wearableslots_object->_('surface', $slot_with_surface);
	}
	print('$array_surface_associations: ');$wearableslots_object->var_dump_full($array_surface_associations);exit(0);	
	if($game === false) { // then make a new game
		$game_string = '<game>
<name>' . $game_name_by_request . '</name>
<idcounter>6</idcounter>
<day>1</day>
<hour>12</hour>
<players>
<player>
<status>alive</status>
<location>town</location>
<name>' . $game_name_by_request . '</name>
<currency>20</currency>
');
		// there is room here for things like different races having different body part properties (specified as wearable slots) but for now just straight copy it
		//$bodyparts = $wearableslots_object->change_tag_names_from_to($wearableslots_object->change_tag_names_from_to($wearableslots_object->LOM, 'wearableslots', 'bodyparts'), 'volume', 'volumeremaining');
		$wearableslots_object->change_tag_names_from_to($wearableslots_object->LOM, 'wearableslots', 'bodyparts');
		$bodyparts = $wearableslots_object->_('bodyparts_*');
		warning_once('need to figure out what will affect the player\'s bodypart volumes; maybe increased strength increases them');
		$bodyparts_volumes = $wearableslots_object->_('volume', $bodyparts);
		$wearableslots_object->delayed_delete($bodyparts_volumes);
		//$counter = sizeof($bodyparts) - 1;
		while(sizeof($bodyparts) > 0) {
			foreach($bodyparts as $last_index => $last_value) {  }
			$wearableslots_object->new_('<volume><current>' . $wearableslots_object->_('volume', $last_value) . '</current><maximum>' . $wearableslots_object->_('volume', $last_value) . '</maximum><experience>0</experience></volume>', $last_value);
			unset($bodyparts[$last_index]);
		}
		$wearableslots_object->delayed_actions();
		// replace static volume tags with current-maximum-experience
		$body_parts_string = $wearableslots_object->tostring($wearableslots_object->LOM);
		print('$body_parts_string: ');var_dump($body_parts_string);exit(0);
		$game_string .= $body_parts_string;
		// should there be a check of the attributes against all the stat types? wouldn't be much fun to have a stat that boosts an attribute the player doesn't have
		$game_string .= '<attribute>
<name>strength</name>
<useableat>0</useableat>
<presenseat>1</presenseat>
<knownat>2</knownat>
<trainableat>4</trainableat>
<used>0</used>
<current>10</current>
<maximum>15</maximum>
<experience>0</experience>
<replenishment><experiencecost>1</experiencecost></replenishment>
<capacity><experiencecost>10</experiencecost><scalepower>0.3</scalepower></capacity>
</attribute>
<attribute>
<name>quickness</name>
<useableat>0</useableat>
<presenseat>1</presenseat>
<knownat>2</knownat>
<trainableat>4</trainableat>
<used>0</used>
<current>10</current>
<maximum>15</maximum>
<experience>0</experience>
<replenishment><experiencecost>1</experiencecost></replenishment>
<capacity><experiencecost>10</experiencecost><scalepower>0.3</scalepower></capacity>
</attribute>
<attribute>
<name>perception</name>
<useableat>0</useableat>
<presenseat>1</presenseat>
<knownat>2</knownat>
<trainableat>4</trainableat>
<used>0</used>
<current>10</current>
<maximum>15</maximum>
<experience>0</experience>
<replenishment><experiencecost>1</experiencecost></replenishment>
<capacity><experiencecost>10</experiencecost><scalepower>0.3</scalepower></capacity>
</attribute>
<attribute>
<name>pierce attack ability</name>
<useableat>0</useableat>
<presenseat>1</presenseat>
<knownat>2</knownat>
<trainableat>4</trainableat>
<used>0</used>
<current>0</current>
<maximum>5</maximum>
<experience>0</experience>
<replenishment><experiencecost>1</experiencecost></replenishment>
<capacity><experiencecost>10</experiencecost><scalepower>0.3</scalepower></capacity>
</attribute>
<items>
<wearable>
<id>0</id>
<name>wooden club</name>
<stat>
<face>true</face>
<facesurface><value>30</value><multiplier>1</multiplier></facesurface>
<canimpact>true</canimpact>
<text>faced</text>
</stat>
<stat>
<surface><value>40</value><multiplier>1</multiplier></surface>
<text>$surface &lt;abbr title="centimeters squared"&gt;cm&lt;sup&gt;2&lt;/sup&gt;&lt;/abbr&gt;</text>
</stat>
<stat>
<volume><value>3</value><multiplier>1</multiplier></volume>
<volumeremaining><value>2.8</value><multiplier>1</multiplier></volumeremaining>
<text>$volumeremaining/$volume durability</text>
</stat>
<stat>
<mass><value>1.2</value><multiplier>1</multiplier></mass>
<text>$mass &lt;abbr title="kilograms"&gt;kg&lt;/abbr&gt;</text>
</stat>
<stat>
<currencyvalue><value>12</value><multiplier>1</multiplier></currencyvalue>
<text>$currencyvalue currency value</text>
</stat>
<slot>rightcarried</slot>
</wearable>
<wearable>
<id>1</id>
<name>father\'s spear</name>
<stat>
<name>piercing attack experience</name>
<useableat>0</useableat>
<presenseat>4</presenseat>
<knownat>5</knownat>
<developableat>0</developableat>
<current><value>3</value><multiplier>1</multiplier><scalepower>0.6</scalepower></current>
<text>$current piercing attack experience</text>
</stat>
<stat>
<point>true</point>
<pointlength><value>10</value><multiplier>1</multiplier></pointlength>
<pointbasesurface><value>6</value><multiplier>1</multiplier></pointbasesurface>
<canpierce>true</canpierce>
<text>pointed</text>
</stat>
<stat>
<surface><value>80</value><multiplier>1</multiplier></surface>
<text>$surface &lt;abbr title="centimeters squared"&gt;cm&lt;sup&gt;2&lt;/sup&gt;&lt;/abbr&gt;</text>
</stat>
<stat>
<volume><value>1.5</value><multiplier>1</multiplier></volume>
<volumeremaining><value>1.3</value><multiplier>1</multiplier></volumeremaining>
<text>$volumeremaining/$volume durability</text>
</stat>
<stat>
<mass><value>6.5</value><multiplier>1</multiplier></mass>
<text>$mass &lt;abbr title="kilograms"&gt;kg&lt;/abbr&gt;</text>
</stat>
<stat>
<weight><value>12</value><multiplier>1</multiplier></weight>
<text>$weight &lt;abbr title="Newtons"&gt;N&lt;/abbr&gt;</text>
</stat>
<stat>
<currencyvalue><value>5</value><multiplier>1</multiplier></currencyvalue>
<text>$currencyvalue currency value</text>
</stat>
<slot>none</slot>
</wearable>
<wearable>
<id>2</id>
<name>leather garb</name>
<stat>
<pierceresistance><value>2</value><multiplier>1</multiplier></pierceresistance>
<text>$pierceresistance pierce resistance</text>
</stat>
<stat>
<slashresistance><value>7</value><multiplier>1</multiplier></slashresistance>
<text>$slashresistance slash resistance</text>
</stat>
<stat>
<impactresistance><value>3</value><multiplier>1</multiplier></impactresistance>
<text>$impactresistance impact resistance</text>
</stat>
<stat>
<surface><value>600</value><multiplier>1</multiplier></surface>
<text>$surface &lt;abbr title="centimeters squared"&gt;cm&lt;sup&gt;2&lt;/sup&gt;&lt;/abbr&gt;</text>
</stat>
<stat>
<volume><value>0.7</value><multiplier>1</multiplier></volume>
<volumeremaining><value>0.6</value><multiplier>1</multiplier></volumeremaining>
<text>$volumeremaining/$volume durability</text>
</stat>
<stat>
<mass><value>2.5</value><multiplier>1</multiplier></mass>
<text>$mass &lt;abbr title="kilograms"&gt;kg&lt;/abbr&gt;</text>
</stat>
<stat>
<weight><value>6</value><multiplier>1</multiplier></weight>
<text>$weight &lt;abbr title="Newtons"&gt;N&lt;/abbr&gt;</text>
</stat>
<stat>
<currencyvalue><value>8</value><multiplier>1</multiplier></currencyvalue>
<text>$currencyvalue currency value</text>
</stat>
<slot>breast</slot>
</wearable>
<socketable>

</socketable>
<consumable>
<id>4</id>
<name>healing potion</name>
<stat>
<volumerestored><value>2</value><multiplier>1</multiplier></volumerestored>
<text>$volumerestored volume restored</text>
</stat>
<stat>
<currencyvalue><value>10</value><multiplier>1</multiplier></currencyvalue>
<text>$currencyvalue currency value</text>
</stat>
</consumable>
<container>

</container>
</items>
</player>
</players>
<enemies>
</enemies>
</game>
';
		// variable character attributes when character is created (would like more randomization once game at least is working off the values?)
		
		$game = $O->new_tag($game_string, 'games');
		//print('$game (make a new game): ');var_dump($game);exit(0);
	} else {
		//print('initial $game_name: ');var_dump($game_name);
		// messages telling the player what happened this "turn"
		// increase time and do anything that needs to be done based on this
		$O->increment('hour');
		if($O->_('hour') == 24) {
			$O->increment('day');
			$O->__('hour', 0);
		}
		if($O->_('hour') == 6) {
			// balance things
			// should stats (attributes) balance based on whether equipped? kept? increased?
			// player that wins (something) gets to design a unique item, of which only 1 can drop on the server at a time
			// update data
			//print('$_REQUEST: ');var_dump($_REQUEST);
			// experience requirements for current and maximum could also scale, but for now they are linear
			//$attribute_properties = array('current', 'maximum', 'experience'); // hard-coded for now
			// maybe current durability should also have a cost instead of only maximumdurability
			$balanceables = array('stattypes', 'creaturetypes');
			foreach($balanceables as $balanceable) {
				$balanceable_object = new O($balanceable . '.xml');
				$multiplier_changes = array();
				$balancers = array_unique($balanceable_object->_('with', $balanceable_object->_($balanceable)));
				//print('$balanceable, $balancers: ');var_dump($balanceable, $balancers);		
				foreach($balancers as $balancer) {
					$balancings_with_balancer = $balanceable_object->_('.' . $balanceable . '_' . $balancer);
					$balancer_sum = $balanceable_object->sum($balanceable_object->_($balanceable . '_' . $balancer));
					//print('$balancer, $balancings_with_balancer, $balancer_sum: ');var_dump($balancer, $balancings_with_balancer, $balancer_sum);
					if($balancer_sum > 0) {
						foreach($balancings_with_balancer as $index => $value) {
							//print('$value: ');var_dump($value);
							$balancer_of_value = $balanceable_object->_($balancer, $value);
							if($balancer_of_value == 0) {
								$balancer_of_value = 1;
							}
							//print('$balancer_of_value: ');var_dump($balancer_of_value);
							$balance_factor = $balancer_of_value / ($balancer_sum / sizeof($balancings_with_balancer));
							print('$balance_factor: ');var_dump($balance_factor);
							// would like monte carlo approach when there is more than one multiplier related to a given thing; how to code this?
							// especially have to notice that we are over-balancing when there is more than one thing to balance. have to build a balance distribution from balance points is a way similar to building an item from item points
							//if($balanceable_object->_('name', $value) !== false) { // giving 'name' a priviledged status in that it cannot be used in offspring
							//if(sizeof($balanceable_object->_('name', $value)) > 0) { // giving 'name' a priviledged status in that it cannot be used in offspring
							$elements_to_balance = $balanceable_object->_('.*_*_*_with=' . $balancer, $value);
							//print('$value, $balancer, $balance_factor, $elements_to_balance, $balanceable_object->_(\'multiplier\', $elements_to_balance): ');var_dump($value, $balancer, $balance_factor, $elements_to_balance, $balanceable_object->_('multiplier', $elements_to_balance));
							foreach($elements_to_balance as $element_index => $element_to_balance) {
								//print('getrandmax(): ');var_dump(getrandmax());exit(0);
								if($element_index === sizeof($elements_to_balance) - 1) {
									$balance_factor_portion = $balance_factor;
								} else {
									if($balance_factor > 1) {
										//$balance_factor_portion = $balance_factor + 1;
										//while($balance_factor_portion >= $balance_factor) {
											//$balance_factor_portion = round(rand(0, $balance_factor * 1000)) / 1000;
											$balance_factor_portion = round(rand(1000, $balance_factor * 1000)) / 1000;
										//}
									} else {
										//$balance_factor_portion = 0;
										//while($balance_factor_portion <= $balance_factor) {
											//$balance_factor_portion = round(rand(0, $balance_factor * 1000)) / 1000;
											$balance_factor_portion = round(rand($balance_factor * 1000, 1000)) / 1000;
										//}
									}
									$balance_factor /= $balance_factor_portion;
								}
								print('$balance_factor, $balance_factor_portion: ');var_dump($balance_factor, $balance_factor_portion);
								$relation_types = $balanceable_object->_('relations_relation_type', $element_to_balance);
								//print('$relation_types: ');var_dump($relation_types);
								if(is_string($relation_types)) {
									if($relation_types === 'direct') {
										//$balanceable_object->multiply('multiplier', $balance_factor, $element_to_balance); // would like a threshhold instead of balancing every value?
										//print($balanceable_object->_('name', $value) . ' ' . $balanceable_object->tagname($element_to_balance) . ' multiplier was multiplied by ' . $balance_factor . '<br>');
										$multiplier_changes[] = array($balance_factor_portion, $element_to_balance, $value);
									} else {
										//$balanceable_object->divide('multiplier', $balance_factor, $element_to_balance); // would like a threshhold instead of balancing every value?
										//print($balanceable_object->_('name', $value) . ' ' . $balanceable_object->tagname($element_to_balance) . ' multiplier was divided by ' . $balance_factor . '<br>');
										$multiplier_changes[] = array(1 / $balance_factor_portion, $element_to_balance, $value);
									}
								} else {
									foreach($relation_types as $relation_type) {
										//$balanceable_object->_('multiplier', $element_to_balance); // for magic? puts it into the context, I guess. just indicates that there is a problem with get_indices
										if($relation_type === 'direct') {
											//$balanceable_object->multiply('multiplier', $balance_factor, $element_to_balance); // would like a threshhold instead of balancing every value?
											//print($balanceable_object->_('name', $value) . ' ' . $balanceable_object->tagname($element_to_balance) . ' multiplier was multiplied by ' . $balance_factor . '<br>');
											$multiplier_changes[] = array($balance_factor_portion, $element_to_balance, $value);
										} else {
											//$balanceable_object->divide('multiplier', $balance_factor, $element_to_balance); // would like a threshhold instead of balancing every value?
											//print($balanceable_object->_('name', $value) . ' ' . $balanceable_object->tagname($element_to_balance) . ' multiplier was divided by ' . $balance_factor . '<br>');
											$multiplier_changes[] = array(1 / $balance_factor_portion, $element_to_balance, $value);
										}
									}
								}
							}
							//print('unipeg attack multiplier: ');var_dump($balanceable_object->_('attack_multiplier', $balanceable_object->_('.creaturetype_name=' . $balanceable_object->enc('unipeg rider')))); // debug
						}
					}
					//print('unipeg attack multiplier: ');var_dump($balanceable_object->_('attack_multiplier', $balanceable_object->_('.creaturetype_name=' . $balanceable_object->enc('unipeg rider')))); // debug
				}
				foreach($multiplier_changes as $index => $value) {
					$balance_factor = $value[0];
					$element_to_balance = $value[1];
					$value = $value[2];
					$balanceable_object->multiply('multiplier', $balance_factor, $element_to_balance); // would like a threshhold instead of balancing every value?
					if(sizeof($balanceable_object->_('name', $value)) > 0) {
						print($balanceable_object->_('name', $value) . ' ' . $balanceable_object->tagname($element_to_balance) . ' multiplier was multiplied by ' . $balance_factor . '<br>');
					} else {
						print($balanceable_object->tagname($value) . ' ' . $balanceable_object->tagname($element_to_balance) . ' multiplier was multiplied by ' . $balance_factor . '<br>');
					}
				}
				// reset values that are being balanced from
				warning_once('need to write the code that changes values when multipliers are too great, say, factor of 10. as well as actually changing values instead of just saying so');
				$balanceable_object->__('*@resets=true', 0);
				$balanceable_object->save_LOM_to_file($balanceable . '.xml');
			}

			// progress the game world
			$world_object = new O('world.xml');
			$world = $world_object->_('world');
			$GLOBALS['print_event_messages'] = true;

			// advance time and process the resulting changes (and report the changes for debugging)
			// should growth or death come first? wouldn't matter with sufficiently large populations in terms of genocide but would certainly affect populations

			// growth
			$areas = $world_object->_('area');
			foreach($areas as $area) {
				//print('$area: ');var_dump($area);
				$creatures_in_area = $world_object->_('creature', $area);
				if(sizeof($creatures_in_area) > 0 && $creatures_in_area !== false) {
					foreach($creatures_in_area as $creature_in_area) {
						$birthrate = $world_object->_('birthrate', $world_object->_('.creaturetype_name=' . $world_object->_('name', $creature_in_area)));
						$rand = rand(0, 99);
						if($rand < $birthrate * 100) {
							//$world_object->new_('<creature><name>' . $world_object->_('name', $creature_in_area) . '</name></creature>', $area);
							$world_object->delayed_new('<creature><name>' . $world_object->_('name', $creature_in_area) . '</name></creature>', $area);
							print_event_message('a ' . $world_object->_('name', $creature_in_area) . ' was born.<br>');
						}
					}
				}
			}
			//print('before delayed_actions after growth<br>');
			$world_object->delayed_actions();
			//print('after delayed actions<br>');
			//print('$world_object->LOM after growth: ');var_dump_full($world_object->tagstring($world_object->LOM));
			//$world_object->validate();

			// death
			// go in reverse order since deletion affects the indices
			$areas = $world_object->_('area');
			$area_counter = sizeof($areas) - 1;
			while($area_counter > -1) {
			//foreach($areas as $area) {
				//$area = $areas[$area_counter];
				//print('$area: ');var_dump($area);
				$creatures_in_area = $world_object->_('creature', $world_object->_('area')[$area_counter]);
				if($creatures_in_area !== false) {
					//print('$creatures_in_area: ');var_dump($creatures_in_area);
					$counter = sizeof($creatures_in_area) - 1;
					while($counter > -1) {
					//foreach($creatures_in_area as $creature_in_area) {
						$creature_in_area = $creatures_in_area[$counter];
						//print('$creature_in_area, $world_object->_(\'creature_.name\', $creature_in_area): ');var_dump($creature_in_area, $world_object->_('creature_.name', $creature_in_area));
						//if($world_object->_('area_.name', $area) === 'area 2') { 
						//	$world_object->delete($creature_in_area); break 2; 
						//}
						$deathrate = $world_object->_('deathrate', $world_object->_('.creaturetype_name=' . $world_object->_('name', $creature_in_area)));
						$rand = rand(0, 99);
						//print('$deathrate, $rand: ');var_dump($deathrate, $rand);
						if($rand < $deathrate * 100) {
							//$areas = $world_object->delete($creature_in_area, $areas); // not sure if feeding the array in as the selector works...
							//$world_object->__($creature_in_area, '');
							//foreach($creature_in_area as $index => $value) {  }
							//$area = $world_object->delete($index); // not sure if feeding the array in as the selector works...
							print_event_message('a ' . $world_object->_('name', $creature_in_area) . ' died.<br>');
							//print('$creature_in_area, $world_object->_(\'area\')[$area_counter] before delete1: ');var_dump($creature_in_area, $world_object->_('area')[$area_counter]);
							$world_object->delayed_delete($creature_in_area);
						}
						$counter--;
					}
				}
				$area_counter--;
			}
			//print('before delayed_actions after death<br>');
			$world_object->delayed_actions();
			//print('$world_object->LOM after death: ');var_dump_full($world_object->tagstring($world_object->LOM));
			//$world_object->validate();

			// emigration
			//$to_new_array = array();
			$areas = $world_object->_('area');
			$area_counter = sizeof($areas) - 1;
			while($area_counter > -1) {
				//$area = $areas[$area_counter];
				$creatures_in_area = $world_object->_('creature', $world_object->_('area')[$area_counter]);
				if($creatures_in_area !== false) {
					$counter = sizeof($creatures_in_area) - 1;
					while($counter > -1) {
						$creature_in_area = $creatures_in_area[$counter];
						$emigrationrate = $world_object->_('emigrationrate', $world_object->_('.creaturetype_name=' . $world_object->_('name', $creature_in_area)));
						$rand = rand(0, 99);
						//print('$creature_in_area, $world_object->_(\'.creaturetype_name=\' . $world_object->_(\'name\', $creature_in_area)), $emigration, $rand: ');var_dump($creature_in_area, $world_object->_('.creaturetype_name=' . $world_object->_('name', $creature_in_area)), $emigration, $rand);
						if($rand < $emigrationrate * 100) {
							// adjacent?
							$random_area = rand(0, sizeof($areas) - 1);
							while($random_area === $area_counter) {
								$random_area = rand(0, sizeof($areas) - 1);
							}
							//print('$random_area: ');var_dump($random_area);exit(0);
							//$world_object->new_($world_object->tagstring($creature_in_area), $areas[$random_area]);
							//$to_new_array[] = array($world_object->tagstring($creature_in_area), $random_area);
							$world_object->delayed_new($world_object->tagstring($creature_in_area), $areas[$random_area]);
							print_event_message('a ' . $world_object->_('name', $creature_in_area) . ' emigrated from ' . $world_object->_('area_.name', $world_object->_('area')[$area_counter]) . ' to ' . $world_object->_('area_.name', $areas[$random_area]) . '.<br>');
							//print('$creature_in_area, $world_object->_(\'area\')[$area_counter] before delete2: ');var_dump($creature_in_area, $world_object->_('area')[$area_counter]);
							$world_object->delayed_delete($creature_in_area);
						}
						$counter--;
					}
				}
				$area_counter--;
			}
			//print('before delayed_actions after emigration<br>');
			$world_object->delayed_actions();
			//print('$world_object->LOM after emigration: ');var_dump_full($world_object->tagstring($world_object->LOM));
			//$world_object->validate();
			//$areas = $world_object->_('area');
			//foreach($to_new_array as $index => $to_new) {
			//	$world_object->new_($to_new[0], $world_object->_('area')[$to_new[1]]);
			//}

			// eating (and starvation)
			// harshly say that if a creature doesn't get its full requirement of food that it dies
			$areas = $world_object->_('area');
			//print('$areas before eating: ');var_dump($areas);
			$area_counter = sizeof($areas) - 1;
			while($area_counter > -1) {
				$area = $areas[$area_counter];
				//print('$area_counter, $area: ');var_dump_full($area_counter, $area);
				$area_plants = $world_object->_('plants', $area);
				$area_killed_prey = array();
				$creatures_in_area = $world_object->_('creature', $area);
				if($creatures_in_area !== false) {
					$counter = sizeof($creatures_in_area) - 1;
					while($counter > -1) {
						$creature_in_area = $creatures_in_area[$counter];
						$eats = $world_object->_('eats', $world_object->_('.creaturetype_name=' . $world_object->_('name', $creature_in_area)));
						$food = $world_object->_('name', $eats);
						//print('$food, $eats, $area_plants: ');var_dump($food, $eats, $area_plants);
						if($food === 'plants') {
							//print('$world_object->_(\'number\', $eats): ');var_dump($world_object->_('number', $eats));
							if($area_plants >= $world_object->_('number', $eats)) {
								// it lives
								$area_plants -= $world_object->_('number', $eats);
								print_event_message('a ' . $world_object->_('name', $creature_in_area) . ' ate ' . $world_object->_('number', $eats) . ' plants.<br>');
							} else {
								// it dies
								print_event_message('a ' . $world_object->_('name', $creature_in_area) . ' starved.<br>');
								//print('$creature_in_area, $world_object->_(\'area\')[$area_counter] before delete2.5: ');var_dump($creature_in_area, $world_object->_('area')[$area_counter]);
								$world_object->delayed_delete($creature_in_area);
							}
						} elseif(sizeof($world_object->_('.creature_name=' . $food, $area)) >= $world_object->_('number', $eats)) {
							// no consideration given to which creatures are eaten... not problematic when they are undifferentiated but would be if they had different properties, as they would in an interesting game
							// its prey dies and it lives
							$prey_creatures = $world_object->_('.creature_name=' . $food, $area);
							//print('$prey_creatures: ');var_dump($prey_creatures);
							if(sizeof($prey_creatures) - $world_object->_('number', $eats) >= $area_killed_prey[$food]) {
								$prey_counter = sizeof($prey_creatures) - 1;
								//print('$world_object->_(\'number\', $eats), $food, $prey_creatures, $prey_counter: ');var_dump($world_object->_('number', $eats), $food, $prey_creatures, $prey_counter);
								$killed_prey = 0;
								while($prey_counter > -1) {
									//print('$food, $prey_counter: ');var_dump($food, $prey_counter);
									//print('$prey_creatures[$prey_counter] before delete3: ');var_dump($prey_creatures[$prey_counter]);
									//$world_object->delayed_delete($prey_creatures[$prey_counter]);
									$world_object->delayed_delete($prey_creatures[$prey_counter]); // cannot be delayed so that it cannot be eaten more than once?
									$killed_prey++;
									$area_killed_prey[$food]++;
									if($killed_prey == $world_object->_('number', $eats)) {
										break;
									}
									$prey_counter--;
								}
								print_event_message('a ' . $world_object->_('name', $creature_in_area) . ' ate ' . $killed_prey . ' ' . $food . '.<br>');
							} else {
								print_event_message('a ' . $world_object->_('name', $creature_in_area) . ' starved.<br>');
								//print('$creature_in_area, $world_object->_(\'area\')[$area_counter] before delete4: ');var_dump($creature_in_area, $world_object->_('area')[$area_counter]);
								$world_object->delayed_delete($creature_in_area);
							}
						} else {
							// it dies
							print_event_message('a ' . $world_object->_('name', $creature_in_area) . ' starved.<br>');
							//print('$creature_in_area, $world_object->_(\'area\')[$area_counter] before delete4: ');var_dump($creature_in_area, $world_object->_('area')[$area_counter]);
							$world_object->delayed_delete($creature_in_area);
						}
						$counter--;
					}
				}
				$area_counter--;
			}
			//print('before delayed_actions after eating<br>');
			$world_object->delayed_actions();
			//print('$world_object->LOM after eating: ');var_dump_full($world_object->tagstring($world_object->LOM));
			$world_object->validate();
			//$world_object->save_LOM_to_file('ecology_test.xml');
			//print('$world_object->LOM string: ');var_dump_full($world_object->tagstring($world_object->LOM));
		}
		
		// updating attributes
		$attributetypes_object = new O('attributetypes.xml');
		$attributetypes = $attributetypes_object->_('attributetype');
		//print('$_REQUEST: ');var_dump($_REQUEST);
		// experience requirements for current and maximum could also scale, but for now they are linear
		// experiencecosts and scalepowers could vary by player but for now have it be fair
		$attribute_properties = array('current', 'maximum', 'experience'); // hard-coded for now
		foreach($attributetypes as $attributetype) {
			$attribute = $O->_('.attribute_name=' . $O->_('name', $attributetype));
			if(sizeof($attribute) > 0) {
				foreach($attribute_properties as $attribute_property) {
					$request_result = $_REQUEST[attribute_url_encode($O->_('name', $attribute)) . '_' . $attribute_property];
					if($request_result !== NULL) {
						print('$request_result: ');var_dump($request_result);
						//print($O->_('name', $attribute) . '_' . $attribute_property . ' was changed to ' . $request_result);var_dump($request_result);exit(0);
						if($attribute_property === 'experience') { // this test doesn't worry about how attribute experience is gained
							//print('$O->_(\'name\', $attribute), $attribute_property, \'attribute@name=\' . $O->_(\'name\', $attribute) . \'_\' . $attribute_property, $request_result, $attribute: ');var_dump($O->_('name', $attribute), $attribute_property, 'attribute@name=' . $O->_('name', $attribute) . '_' . $attribute_property, $request_result, $attribute);
							//$O->__('attribute@name=' . $O->_('name', $attribute) . '_' . $attribute_property, $request_result);
							$O->__($attribute_property, $request_result, $attribute);
						} elseif($attribute_property === 'current') {
							if($request_result - $O->_($attribute_property, $attribute) > 0) {
								$experience_required = ($request_result - $O->_($attribute_property, $attribute)) * $attributetypes_object->_('replenishment_experiencecost', $attributetype); // <replenishment><experiencecost>1</experiencecost></replenishment>
								$experience_available = $O->_('experience', $attribute);
								print('$experience_required, $experience_available (current): ');var_dump($experience_required, $experience_available);
								if($experience_available >= $experience_required) {
									if($request_result <= $O->_('maximum', $attribute)) {
										$O->subtract('experience', $experience_required, $attribute);
										$O->__($attribute_property, $request_result, $attribute);
										print($O->_('name', $attribute) . ' ' . $attribute_property . ' was set to ' . $request_result . ', costing ' . $experience_required . ' ' . $O->_('name', $attribute) . ' experience.<br>');
									} else {
										print('cannot exceed ' . $O->_('name', $attribute) . ' maximum.<br>');
									}
								} else {
									print('insufficient ' . $O->_('name', $attribute) . ' experience to change ' . $O->_('name', $attribute) . ' ' . $attribute_property . '.<br>');
								}
							} else {
								print('attributes may only increase.<br>'); // debatable decision but defensible in the context of an RPG where characters should grow by user choices
							}
						} elseif($attribute_property === 'maximum') {
							if($request_result - $O->_($attribute_property, $attribute) > 0) {
								$experience_required = ($request_result - $O->_($attribute_property, $attribute)) * $attributetypes_object->_('capacity_experiencecost', $attributetype); // <capacity><experiencecost>10</experiencecost><attributefractioncost>0.1</attributefractioncost></capacity>
								$experience_available = $O->_('experience', $attribute);
								print('$experience_required, $experience_available (maximum): ');var_dump($experience_required, $experience_available);
								if($experience_available >= $experience_required) {
									$current_required = floor(($request_result - $O->_($attribute_property, $attribute)) * $O->_('maximum', $attribute) * (1 - (1 / pow($O->_('maximum', $attribute), $attributetypes_object->_('capacity_scalepower', $attributetype))))); // seems great
									$current_available = $O->_('current', $attribute);
									print('$request_result, $current_available, $current_required: ');var_dump($request_result, $current_available, $current_required);
									if($current_available >= $current_required) {
										$O->subtract('experience', $experience_required, $attribute);
										$O->subtract('current', $current_required, $attribute);
										$O->__($attribute_property, $request_result, $attribute);
										print($O->_('name', $attribute) . ' ' . $attribute_property . ' was set to ' . $request_result . ', costing ' . $experience_required . ' ' . $O->_('name', $attribute) . ' experience and ' . $current_required . ' ' . $O->_('name', $attribute) . ' current.<br>');
									} else {
										print('insufficient ' . $O->_('name', $attribute) . ' current to change ' . $O->_('name', $attribute) . ' ' . $attribute_property . '.<br>');
									}
								} else {
									print('insufficient ' . $O->_('name', $attribute) . ' experience to change ' . $O->_('name', $attribute) . ' ' . $attribute_property . '.<br>');
								}
							} else {
								print('attributes may only increase.<br>'); // debatable decision but defensible in the context of an RPG where characters should grow by user choices
							}
						} else {
							print('unknown attribute property: ');var_dump($attribute_property);exit(0);
						}
						break 2;
					}
				}
			}
		}
		//$attributetypes_object->save_LOM_to_file('attributetypes.xml');
		
		$sell_id_by_request = get_by_request('sell_id');
		//print('$sell_id_by_request: ');var_dump($sell_id_by_request);
		$item_to_sell = $O->_('player/items/.*/id=' . $sell_id_by_request);
		//print('$item_to_sell: ');var_dump($item_to_sell);
		if($item_to_sell !== false) {
			$O->add($O->_('currencyvalue', $item_to_sell), 'player_currency');
			print($O->get_tag_name($item_to_sell) . ' was sold for ' . $O->_('currencyvalue', $item_to_sell) . '.<br>
');
			$O->delete($item_to_sell);
		}
		
		$drink_id_by_request = get_by_request('drink_id');
		$item_to_drink = $O->_('player/items/.potion/id=' . $drink_id_by_request);
		if($item_to_drink !== false) {
			// build the missing volumes array
			$missing_volumes_array = array();
			$bodypart_tags = $O->_('bodyparts_.*');
			foreach($bodypart_volume_names as $index => $bodypart_tag) {
				$missing_volumes_array[$O->tagname($bodypart_tag)] = $O->_('volume_maximum', $bodypart_tag) - $O->_('volume_current', $bodypart_tag);
			}
			$restored_volumes = restore_volume($O->_('recovery', $item_to_drink), $missing_volumes_array);
			foreach($restored_volumes as $bodypart_tagname => $restored_volume) {
				$O->add($restored_volume, 'bodyparts_' . $bodypart_tagname . '_volume_current');
			}
			//$O->add($O->_('recovery', $item_to_drink), 'player_health');
			print($O->_('player_name') . ' recovered ' . $O->_('recovery', $item_to_drink) . ' health.<br>
');
			$O->delete($item_to_drink);
		}
		
		$repair_id_by_request = get_by_request('repair_id');
		if($repair_id_by_request !== false) {
			$item_to_repair = $O->_('player/items/.*/id=' . $repair_id_by_request);
			//$repair_cost = ceil($item_value_matches[1] / 2); // should the item value loss be proportional to the maximum durability loss?
			$repair_cost = $O->_('currencyvalue', $item_to_repair); // should the item value loss be proportional to the maximum durability loss?
			if($O->_('player_currency') < $repair_cost) {
				print('Not enough currency to repair ' . $O->get_tag_name($item_to_repair) . '.<br>
');
			} else {
				warning_once('should stats that have been increased (as indicated by their experience) be preferentially less destroyed upon repair?');
				$balance_factor = 0.5 + rand(0, 500)) / 1000;
				//print('$balance_factor: ');var_dump($balance_factor);
				$changes = array();
				$stats = $O->_('stat', $item_to_repair);
				foreach($stats as $stat_index => $stat) {
					//print('getrandmax(): ');var_dump(getrandmax());exit(0);
					if($stat_index === sizeof($stats) - 1) {
						$balance_factor_portion = $balance_factor;
					} else {
						$balance_factor_portion = round(rand($balance_factor * 1000, 1000)) / 1000;
						$balance_factor /= $balance_factor_portion;
					}
					//print('$balance_factor, $balance_factor_portion: ');var_dump($balance_factor, $balance_factor_portion);
					$values = $O->_('value', $stat);
					foreach($values as $to_impair_index => $to_impair_value) {
						$changes[] = array($to_impair_index, $to_impair_value, $balance_factor_portion, $stat);
					}
				}
				foreach($changes as $index => $value) {
					$to_impair_index = $value[0];
					$to_impair_value = $value[1];
					$balance_factor = $value[2];
					$stat = $value[3];
					$O->multiply($to_impair_index, $balance_factor); // would like a threshhold instead of balancing every value?
					if(sizeof($O->_('name', $stat)) > 0) {
						//print($O->_('name', $stat) . ' ' . $O->tagname($element_to_balance) . ' multiplier was multiplied by ' . $balance_factor . '<br>');
						print($O->_('name', $stat) . ' multiplier was multiplied by ' . $balance_factor . ' while repairing.<br>');
					} else {
						//print($O->tagname($stat) . ' ' . $O->tagname($element_to_balance) . ' multiplier was multiplied by ' . $balance_factor . '<br>');
						print($O->tagname($O->_('*[0]', $stat)) . ' multiplier was multiplied by ' . $balance_factor . ' while repairing.<br>');
					}
				}
				//$new_item_value = floor($O->_('currencyvalue', $item_to_repair) / 2) + rand(0, (ceil($O->_('currencyvalue', $item_to_repair) / 2) - 1));
				//$new_item_maximumdurability = floor($O->_('maximumdurability', $item_to_repair) / 2) + rand(0, (ceil($O->_('maximumdurability', $item_to_repair) / 2) - 1));
				if($O->_('volume', $item_to_repair) < $item_destruction_threshhold) {
					print($O->_('name', $item_to_repair) . ' was destroyed while attempting repair.<br>
');
					$O->delete($item_to_repair);
				} else {
					//$O->__('maximumdurability', $new_item_maximumdurability, $item_to_repair);
					//$O->__('durability', $new_item_maximumdurability, $item_to_repair);
					//$O->__('currencyvalue', $new_item_value, $item_to_repair);
					$O->subtract('player_currency', $repair_cost);
					print($O->_('name', $item_to_repair) . ' was repaired for ' . $repair_cost . ' (stats were reduced).<br>
');
				}
			}
		}
		
		$weapon_id_by_request = get_by_request('weapon_id');
		if($weapon_id_by_request !== false) {
			$items = $O->_('items', $O->_('.game_name=' . $O->enc($game_name_by_request)));
			$weapon = $O->_('.weapon/id=' . $weapon_id_by_request, $items);
			//$weapon_damage = $O->_('damage', $weapon); // can further shorthand be developed?
			//$enemy = $O->_('enemy', $O->_('.game_name=' . $O->enc($game_name_by_request)));
			$enemy = $O->_('.enemy/id=' . get_by_request('enemy_id'), $O->_('.game_name=' . $O->enc($game_name_by_request)));
			//$enemy_armor = $O->_('armor', $enemy);
			//print('$weapon_id_by_request, $items, $weapon, $weapon_damage, $enemy_armor: ');var_dump($weapon_id_by_request, $items, $weapon, $weapon_damage, $enemy_armor);
			warning_once('quickness not taken into account at all');
			
			// should attacking code be functionalized? yes but I'm preferring muscles to smarts and connections in this instance
			warning_once('will want to have different bodyparts definitions for different types of enemies, but this sort of detail would like to be extracted from creaturetype or item models rather than creating many specifications abstractly');
			$hit_slot = roll($array_surface_associations);
			$item_hit = false;
			foreach($O->_(implode('|', $array_item_types), $enemy) as $enemy_item) {
				if($O->_('slot', $enemy_item) == $hit_slot) {
					$item_hit = $enemy_item;
					break;
				}
			}
			//if(isset($slot_hits_record[$hit_slot])) {
			//	$slot_hits_record[$hit_slot]++;
			//} else {
			//	$slot_hits_record[$hit_slot] = 1;
			//}
			//$magnitude_rand = rand(1, 20);
			//$strength_rand = rand(1, 20);
			$strength_of_attack = $O->_('current', $O->_('player_.attribute_name=strength')) - $O->_('used', $O->_('player_.attribute_name=strength'));
			//$mass_rand = rand(1, 5);
			// crude approximations of how the different dimensionalities of the attacks will affect the resulting damage that should be calculated from weapon stats and not hard-coded
			$attack_type = get_by_request('attack_type');
			$mass_of_attack = $O->_('mass_value', $weapon) * $O->_('mass_multiplier', $weapon); // would like to include mass of the swinging arm eventually
			// a scalpel should dull quickly but be effective while a butter knife should last a long time but be ineffective
			// how to approximate this reasonably when the eventual solution would like something like repairing a spearhead makes the furthest point sharp again while removing material and attacking with a 
			// spearhead rounds the point by removing pointlength
			if($attack_type === 'impact') {
				$attack_type_verb = $attack_type . 'ed';
				//$attack_surface = 100;
				$attack_surface = $O->_('facesurface_value', $weapon) * $O->_('facesurface_multiplier', $weapon);
				//$mass_rand = rand(1, 50);
				//$attack_resistance = $impact_resistance;
				// this is beyond crude. where is the material hardness factor?
				$impact_attack_dimensionality = 2;
				$new_weapon_integrity = 1 - (1 / $O->_('facesurface_value', $weapon)) / $impact_attack_dimensionality);
				$O->multiply($new_weapon_integrity, 'facesurface_value', $weapon);
				if(sizeof($O->_('impactresistance', $item_hit)) > 0) {
					$attack_resistance = $O->_('impactresistance_value', $item_hit) * $O->_('impactresistance_multiplier', $item_hit);
				} else {
					$attack_resistance = 0;
				}
			} elseif($attack_type === 'slash') {
				$attack_type_verb = $attack_type . 'ed';
				$attack_surface = $O->_('edgebasesurface_value', $weapon) * $O->_('edgebasesurface_multiplier', $weapon);
				// this is beyond crude. where is the material hardness factor?
				$slash_attack_dimensionality = 1.5;
				$new_weapon_integrity = 1 - (($O->_('edgelength_value', $weapon) / ($O->_('edgebasesurface_value', $weapon) * $O->_('edgelength_value', $weapon))) / $slash_attack_dimensionality);
				$O->multiply($new_weapon_integrity, 'edgelength_value', $weapon);
				if(sizeof($O->_('slashresistance', $item_hit)) > 0) {
					$attack_resistance = $O->_('slashresistance_value', $item_hit) * $O->_('slashresistance_multiplier', $item_hit);
				} else {
					$attack_resistance = 0;
				}
			} elseif($attack_type === 'pierce') {
				$attack_type_verb = $attack_type . 'd';
				$attack_surface = $O->_('pointbasesurface_value', $weapon) * $O->_('pointbasesurface_multiplier', $weapon);
				// this is beyond crude. where is the material hardness factor?
				$pierce_attack_dimensionality = 1;
				$new_weapon_integrity = 1 - (($O->_('pointlength_value', $weapon) / ($O->_('pointbasesurface_value', $weapon) * $O->_('pointlength_value', $weapon))) / $pierce_attack_dimensionality);
				$O->multiply($new_weapon_integrity, 'pointlength_value', $weapon);
				if(sizeof($O->_('pierceresistance', $item_hit)) > 0) {
					$attack_resistance = $O->_('pierceresistance_value', $item_hit) * $O->_('pierceresistance_multiplier', $item_hit);
				} else {
					$attack_resistance = 0;
				}
			} else {
				print('unknown $attack_type: ' . $attack_type);exit(0);
			}
			// crudely equate this with acceleration for the purposes of determining force
			$force = $mass_of_attack * $strength_of_attack;
			// should the attack resistance be applied to the force or the volume damaged?
			$volume_damaged = $force - $attack_resistance;
			if($volume_damaged < 0) {
				$volume_damaged = 0;
			}
			// coming to realize armor volume remaining is more relevant than a flat durability stat, which makes sense when considering how repairing would affect the item: there is less material to work with so stats (durability, others) are reduced
			$armor_volume_remaining = $O->_('volumeremaining_value', $item_hit) * $O->_('volumeremaining_multiplier', $item_hit);
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
			$volume_damaged *= $wearableslots_object->_($attack_type . 'effectiveness', $hit_slot) / 100;
			//$volume_damaged = $magnitude_rand * $array_attack_volumes[$attack_type] * $O->_($attack_type . 'effectiveness', $hit_slot) / 100;
			// gain experience
			// add experience to the attribute and to the slot is applicable
			$O->add($force, 'experience', $O->_('player_.attribute_name=strength'))
			warning_once('how to incorporate experience stats on items to attributes?');
			if(sizeof($O->_('.stat_name=' . $O->enc($attack_type . ' attack experience'), $weapon)) > 0) {
				$O->add($force, 'current', $O->_('.stat_name=' . $O->enc($attack_type . ' attack experience'), $weapon));
			}
			if(sizeof($O->_('.stat_name=' . $O->enc('strength experience'), $weapon)) > 0) {
				$O->add($force, 'current', $O->_('.stat_name=' . $O->enc('strength experience'), $weapon));
			}
			// damage to demons etc.
			print($force . ' strength experience gained.<br>
');
			print($hit_slot . ' was ' . $attack_type_verb . ' with force ' . $force . ' (strength: ' . $strength_of_attack . ' x mass: ' . $mass_of_attack . ') for armor volume remaining of ' . $armor_volume_remaining . ' and damaging a volume of ' . $volume_damaged . '.<br>');
			//$destruction_string = '';
			//$killed_string = '';
			if($volume_damaged >= $O->_($hit_slot . '_volume', $hit_slot)) {
				//$destruction_string = ' (' . $O->_($attack_type . 'destruction', $hit_slot) . ')';
				print($O->_('name', $enemy) . ' ' . $O->_($attack_type . 'destruction', $hit_slot) . '.<br>
');
				$O->new_('<destroyed>true</destroyed>', $O->_('bodyparts_' . $hit_slot, $enemy));
				if($O->_('destructionisfatal', $hit_slot) === 'true') {
					//$killed_string = ' (killed)';
					print($O->_('name', $enemy) . ' was killed.<br>
');
					// now for delicious loot
					warning_once('should there be a chance for items to be destroyed when looting bodies? how to handle loot overload given that enemies are as complex as players?');
					foreach($O->_(implode('|', $array_item_types), $enemy) as $enemy_item) {
						print($O->_('name', $enemy_item) . ' acquired.<br>
');
						$O->new_($enemy_item, 'player_items');
					}
					//generate_loot($O->_('player_name'), $O->_('id', $enemy), $array_item_types, $O);
					$O->delete($enemy);
				}
			} else {
				$O->subtract($volume_damaged, $O->_($hit_slot . '_volume', $hit_slot));
				// enemies attack back
				// probably want to functionalize pieces rather than a full attack() function
				
				foreach($O->_('enemy') as $enemy) {
				// look in carried slots then at body parts
				$array_carried_weapons = array();
				foreach($O->_(implode('|', $array_item_types), $enemy) as $enemy_item) {
					if(strpos($O->_('slot', $enemy_item), 'carried') !== false) {
						$array_carried_weapons[] = $enemy_item;
					}
				}
				if(sizeof($array_carried_weapons) > 0) { // full random is kind of lazy and unrealistic
					$weapon = $array_carried_weapons[rand(0, sizeof($array_carried_weapons) - 1)];
				} else {
					$bodyparts_for_rolling = array();
					foreach($O->_('bodyparts', $enemy) as $enemy_bodypart) {
						$bodyparts_for_rolling[$O->tagname($enemy_bodypart)] = $O->_('volume');
					}
					$weapon_name = roll($bodyparts_for_rolling);
					$weapon = $O->_('bodyparts_' . $weapon_name, $enemy);
				}
				
				$hit_slot = roll($array_surface_associations);
			$item_hit = false;
			foreach($O->_(implode('|', $array_item_types), $player) as $player_item) {
				if($O->_('slot', $player_item) == $hit_slot) {
					$item_hit = $player_item;
					break;
				}
			}
			$strength_of_attack = $O->_('current', $O->_('enemy_.attribute_name=strength')) - $O->_('used', $O->_('enemy_.attribute_name=strength'));
			$attack_type = get_by_request('attack_type');
			$mass_of_attack = $O->_('mass_value', $weapon) * $O->_('mass_multiplier', $weapon); // would like to include mass of the swinging arm eventually
			warning_once('will have to functionalize this code for ease of maintenance and will need to be able to accept body parts as weapons and for that to work will need to request bodypart of the player');
			if($attack_type === 'impact') {
				$attack_type_verb = $attack_type . 'ed';
				//$attack_surface = 100;
				$attack_surface = $O->_('facesurface_value', $weapon) * $O->_('facesurface_multiplier', $weapon);
				//$mass_rand = rand(1, 50);
				//$attack_resistance = $impact_resistance;
				// this is beyond crude. where is the material hardness factor?
				$impact_attack_dimensionality = 2;
				$new_weapon_integrity = 1 - (1 / $O->_('facesurface_value', $weapon)) / $impact_attack_dimensionality);
				$O->multiply($new_weapon_integrity, 'facesurface_value', $weapon);
				if(sizeof($O->_('impactresistance', $item_hit)) > 0) {
					$attack_resistance = $O->_('impactresistance_value', $item_hit) * $O->_('impactresistance_multiplier', $item_hit);
				} else {
					$attack_resistance = 0;
				}
			} elseif($attack_type === 'slash') {
				$attack_type_verb = $attack_type . 'ed';
				$attack_surface = $O->_('edgebasesurface_value', $weapon) * $O->_('edgebasesurface_multiplier', $weapon);
				// this is beyond crude. where is the material hardness factor?
				$slash_attack_dimensionality = 1.5;
				$new_weapon_integrity = 1 - (($O->_('edgelength_value', $weapon) / ($O->_('edgebasesurface_value', $weapon) * $O->_('edgelength_value', $weapon))) / $slash_attack_dimensionality);
				$O->multiply($new_weapon_integrity, 'edgelength_value', $weapon);
				if(sizeof($O->_('slashresistance', $item_hit)) > 0) {
					$attack_resistance = $O->_('slashresistance_value', $item_hit) * $O->_('slashresistance_multiplier', $item_hit);
				} else {
					$attack_resistance = 0;
				}
			} elseif($attack_type === 'pierce') {
				$attack_type_verb = $attack_type . 'd';
				$attack_surface = $O->_('pointbasesurface_value', $weapon) * $O->_('pointbasesurface_multiplier', $weapon);
				// this is beyond crude. where is the material hardness factor?
				$pierce_attack_dimensionality = 1;
				$new_weapon_integrity = 1 - (($O->_('pointlength_value', $weapon) / ($O->_('pointbasesurface_value', $weapon) * $O->_('pointlength_value', $weapon))) / $pierce_attack_dimensionality);
				$O->multiply($new_weapon_integrity, 'pointlength_value', $weapon);
				if(sizeof($O->_('pierceresistance', $item_hit)) > 0) {
					$attack_resistance = $O->_('pierceresistance_value', $item_hit) * $O->_('pierceresistance_multiplier', $item_hit);
				} else {
					$attack_resistance = 0;
				}
			} else {
				print('unknown $attack_type: ' . $attack_type);exit(0);
			}
			// crudely equate this with acceleration for the purposes of determining force
			$force = $mass_of_attack * $strength_of_attack;
			// should the attack resistance be applied to the force or the volume damaged?
			$volume_damaged = $force - $attack_resistance;
			if($volume_damaged < 0) {
				$volume_damaged = 0;
			}
			// coming to realize armor volume remaining is more relevant than a flat durability stat, which makes sense when considering how repairing would affect the item: there is less material to work with so stats (durability, others) are reduced
			$armor_volume_remaining = $O->_('volumeremaining_value', $item_hit) * $O->_('volumeremaining_multiplier', $item_hit);
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
			$volume_damaged *= $wearableslots_object->_($attack_type . 'effectiveness', $hit_slot) / 100;
			//$volume_damaged = $magnitude_rand * $array_attack_volumes[$attack_type] * $O->_($attack_type . 'effectiveness', $hit_slot) / 100;
			// gain experience
			// add experience to the attribute and to the slot is applicable
			$O->add($force, 'experience', $O->_('player_.attribute_name=strength'))
			warning_once('how to incorporate experience stats on items to attributes?');
			if(sizeof($O->_('.stat_name=' . $O->enc($attack_type . ' attack experience'), $weapon)) > 0) {
				$O->add($force, 'current', $O->_('.stat_name=' . $O->enc($attack_type . ' attack experience'), $weapon));
			}
			if(sizeof($O->_('.stat_name=' . $O->enc('strength experience'), $weapon)) > 0) {
				$O->add($force, 'current', $O->_('.stat_name=' . $O->enc('strength experience'), $weapon));
			}
			// damage to demons etc.
			print($force . ' strength experience gained.<br>
');
			print($hit_slot . ' was ' . $attack_type_verb . ' with force ' . $force . ' (strength: ' . $strength_of_attack . ' x mass: ' . $mass_of_attack . ') for armor volume remaining of ' . $armor_volume_remaining . ' and damaging a volume of ' . $volume_damaged . '.<br>');
			//$destruction_string = '';
			//$killed_string = '';
			if($volume_damaged >= $O->_($hit_slot . '_volume', $hit_slot)) {
				//$destruction_string = ' (' . $O->_($attack_type . 'destruction', $hit_slot) . ')';
				print($O->_('name', $enemy) . ' ' . $O->_($attack_type . 'destruction', $hit_slot) . '.<br>
');
				$O->new_('<destroyed>true</destroyed>', $O->_('bodyparts_' . $hit_slot, $enemy));
				if($O->_('destructionisfatal', $hit_slot) === 'true') {
					//$killed_string = ' (killed)';
					print($O->_('name', $enemy) . ' was killed.<br>
');
					// now for delicious loot
					warning_once('should there be a chance for items to be destroyed when looting bodies? how to handle loot overload given that enemies are as complex as players?');
					foreach($O->_(implode('|', $array_item_types), $enemy) as $enemy_item) {
						print($O->_('name', $enemy_item) . ' acquired.<br>
');
						$O->new_($enemy_item, 'player_items');
					}
					//generate_loot($O->_('player_name'), $O->_('id', $enemy), $array_item_types, $O);
					$O->delete($enemy);
				}
			}
				
				
				
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
			//print($destruction_string . $killed_string . '.<br>');
		}
		$location = get_by_request('location');
		//print('$location: ');var_dump($location);
		if($location !== false) {
			$O->__('player_location', $location);
			if($location !== 'town') { // encounter creatures
				generate_enemy($O->_('idcounter'), $O);
				$O->increment('idcounter');
			} elseif($location === 'in town') {
				if($O->_('enemy') === false) {
					
				} else {
				//	$O->__('enemy', '');
					$O->delete('enemy');
				}
			}
		}
	}
	
	// print the attributes modification form
	$attributes = $O->_('attribute');
	print('<table border="0" cellpadding="4" cellspacing="0">
<thead>
<tr>
<th scope="col" style="text-align: left;">attribute</th>
<th scope="col">bar graph</th><th scope="col"><abbr title="used">U</abbr>/<abbr title="current">C</abbr>/<abbr title="maximum">M</abbr></th>
<th scope="col">change attribute current value</th>
<th scope="col">change attribute maximum value</th>
<th scope="col">change attribute experience value</th>
</tr>
</thead>
<tbody>');
	foreach($attributes as $attribute) {
		print('<th scope="row" style="text-align: left;">' . $O->_('name', $attribute) . '</th>');
		print('<td><div style="height: 16px; width: ' . $O->_('maximum', $attribute) . '; background: green;"><div style="height: 12px; width: ' . $O->_('current', $attribute) . '; position: relative; top: 2px; background: yellow;"><div style="height: 8px; width: ' . $O->_('used', $attribute) . '; position: relative; top: 2px; background: red;"></div></div></div></td><td style="text-align: right;">' . $O->_('used', $attribute) . '/' . $O->_('current', $attribute) . '/' . $O->_('maximum', $attribute) . '</td>');
		print('<td><form method="post" action="attributes_test.php"><input type="hidden" name="' . attribute_url_encode($O->_('name', $attribute)) . '_current" value="' . $O->_('current', $attribute) + 1 . '" /><input type="submit" value="+ (costs ?)" /></form><form method="post" action="attributes_test.php">current=<input type="text" name="' . attribute_url_encode($O->_('name', $attribute)) . '_current" value="' . $O->_('current', $attribute) . '" /><input type="submit" value="update" /></form></td>');
		print('<td><form method="post" action="attributes_test.php"><input type="hidden" name="' . attribute_url_encode($O->_('name', $attribute)) . '_maximum" value="' . $O->_('maximum', $attribute) + 1 . '" /><input type="submit" value="+ (costs ?)" /></form><form method="post" action="attributes_test.php">maximum=<input type="text" name="' . attribute_url_encode($O->_('name', $attribute)) . '_maximum" value="' . $O->_('maximum', $attribute) . '" /><input type="submit" value="update" /></form></td>');
		print('<td><form method="post" action="attributes_test.php"><input type="hidden" name="' . attribute_url_encode($O->_('name', $attribute)) . '_experience" value="' . $O->_('experience', $attribute) + 1 . '" /><input type="submit" value="+ (costs ?)" /></form><form method="post" action="attributes_test.php">experience=<input type="text" name="' . attribute_url_encode($O->_('name', $attribute)) . '_experience" value="' . $O->_('experience', $attribute) . '" /><input type="submit" value="update" /></form></td>');
		print('</tr>');
	}
	print('</tbody>
</table>
');

	// print the world map
	$world_object = new O('world.xml');
	$areas = $world_object->_('world_area');
	//print('$areas when printing the map: ');var_dump($areas);
	print('<table border="1">
');
	foreach($areas as $area) {
		//print('$area: ');var_dump($area);
		//print('$world_object->_(\'area_.name\', $area): ');var_dump($world_object->_('area_.name', $area));
		// notice that we are specifying the name of the area so that names of creatures are not also found
		$creatures_in_area = $world_object->_('creature', $area);
		print('<tr>
<th scope="row">' . $world_object->_('area_.name', $area) . '<br>plants: ' . $world_object->_('area_.plants', $area) . '<br>number of creatures: ' . sizeof($creatures_in_area) . '</th>
<td>');
		//print('$creatures_in_area: ');var_dump($creatures_in_area);exit(0);
		if(sizeof($creatures_in_area) > 0 && $creatures_in_area !== false) {
			foreach($creatures_in_area as $creature_in_area) {
				print($world_object->_('name', $creature_in_area) . ' ');
			}
		}
		print('</td>
</tr>');
	}
	warning_once('want to make the scouting reports imprecise once we are sure that having fights is properly decreasing creature numbers and growth is increasing them etc.');
	/*include('../LOM/O.php');
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
print('</div>');*/
	
	// print the player
	warning_once('should be printing the volume remaining of each of the player\'s body parts');
	print('<hr>
');
	/*print('Health: ' . $O->_('player_health') . '<br>
');*/
	print('Currency: ' . $O->_('player_currency') . '<br>
');
	/*print('Experience: ' . $O->_('player_experience') . '<br>
');
	print('Level: ' . $O->_('player_level') . '<br>
');*/
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
			print('<td>Damage: ' . $O->_('damage', $weapon) . '<br>Durability: ' . $O->_('durability', $weapon) . '/' . $O->_('maximumdurability', $weapon) . '<br>Value: ' . $O->_('currencyvalue', $weapon));
			if($O->_('game_status') === 'in town') {
				print('<form method="post" action="balancelof.php">
<input type="hidden" name="game_name" value="' . $O->_('game_name') . '" />
<input type="hidden" name="sell_id" value="' . $O->_('id', $weapon) . '" />
<input type="submit" value="Sell" />
</form>
');
				print('<form method="post" action="balancelof.php">
<input type="hidden" name="game_name" value="' . $O->_('game_name') . '" />
<input type="hidden" name="repair_id" value="' . $O->_('id', $weapon) . '" />
<input type="submit" value="Repair" />
</form>
');
			} elseif($O->_('game_status') === 'in dungeon' && strlen($O->node_string($O->_('enemy', $O->_('.game_name=' . $O->enc($game_name_by_request))))) > 0) {
				if($O->_('durability', $weapon) > 0) {
					print('<form method="post" action="balancelof.php">
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
			print('<td>Reduction: ' . $O->_('reduction', $armor) . '<br>Durability: ' . $O->_('durability', $armor) . '/' . $O->_('maximumdurability', $armor) . '<br>Value: ' . $O->_('currencyvalue', $armor));
			if($O->_('game_status') === 'in town') {
				print('<form method="post" action="balancelof.php">
<input type="hidden" name="game_name" value="' . $O->_('game_name') . '" />
<input type="hidden" name="sell_id" value="' . $O->_('id', $armor) . '" />
<input type="submit" value="Sell" />
</form>
');
				print('<form method="post" action="balancelof.php">
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
			print('<td>Recovery: ' . $O->_('recovery', $potion) . '<br>Value: ' . $O->_('currencyvalue', $potion));
			if($O->_('game_status') === 'in town') {
				print('<form method="post" action="balancelof.php">
<input type="hidden" name="game_name" value="' . $O->_('game_name') . '" />
<input type="hidden" name="sell_id" value="' . $O->_('id', $potion) . '" />
<input type="submit" value="Sell" />
</form>
');
			}
			if($O->_('game_status') !== 'dead') {
				print('<form method="post" action="balancelof.php">
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
		print('<form method="post" action="balancelof.php">
<input type="hidden" name="game_name" value="' . $O->_('game_name') . '" />
<input type="hidden" name="location" value="in dungeon" />
<input type="submit" value="Enter Dungeon" />
</form>
');
	} elseif($O->_('game_status') === 'in dungeon') {
		if($game_enemy === false) {
			print('<form method="post" action="balancelof.php">
<input type="hidden" name="game_name" value="' . $O->_('game_name') . '" />
<input type="hidden" name="location" value="in dungeon" />
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
		print('<form method="post" action="balancelof.php">
<input type="hidden" name="game_name" value="' . $O->_('game_name') . '" />
<input type="hidden" name="location" value="in town" />
<input type="submit" value="Back to Town" />
</form>
');
	}
	// very important to save the game after all these contextual changes ;p
	$O->save_LOM_to_file('balancelof_games.xml');
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

function restore_volume($volume_to_restore, $missing_volumes_array) {
	print('$volume_to_restore, $missing_volumes_array at start of restore_volume: ');var_dump($volume_to_restore, $missing_volumes_array at start of restore_volume);
	warning_once('would like to build this dependency array up from what is declared but for now it is hard-coded');
	/*$dependency_array = array(
	'gut' => array(
	'breast' => array(
	'neck' => array('head'),
	'leftshoulder' => array('leftelbow' => array('leftwrist' => array('lefthand' => array('finger1', 'finger2', 'finger3', 'finger4', 'finger5')))),
	'rightshoulder' => array('rightelbow' => array('rightwrist' => array('righthand' => array('finger6', 'finger7', 'finger8', 'finger9', 'finger10')))),
	),
	'waist',
	'leftthigh' => array('leftknee' => array('leftshin' => array('leftfoot'))),
	'rightthigh' => array('rightknee' => array('rightshin' => array('rightfoot'))),
	)
	);*/
	$body_parts_order_array = array(
	array('gut'),
	array('breast', 'waist'),
	array('neck', 'leftthigh', 'rightthigh'),
	array('head', 'leftshoulder', 'rightshoulder', 'leftknee', 'rightknee'),
	array('leftelbow', 'rightelbow', 'leftshin', 'rightshin'),
	array('leftwrist', 'rightwrist', 'leftfoot', 'rightfoot'),
	array('lefthand', 'righthand'),
	array('finger1', 'finger2', 'finger3', 'finger4', 'finger5', 'finger6', 'finger7', 'finger8', 'finger9', 'finger10'),
	);
	$volumes_restored_array = array();
	$volume_remaining = $volume_to_restore;
	foreach($body_parts_order_array as $body_parts_order_index => $body_parts_at_this_level) {
		if($body_parts_order_index === sizeof($body_parts_order_array) - 1) {
			$volume_to_restore_at_this_level = $volume_remaining;
		} else {
			$volume_to_restore_at_this_level = $volume_remaining / 2;
		}
		$there_is_missing_volume_at_this_level = false;
		$volumes_missing_at_this_level = array();
		foreach($body_parts_at_this_level as $body_part) {
			$volumes_restored_array[$body_part] = 0;
			$volumes_missing_at_this_level[$body_part] = $missing_volumes_array[$body_part];
			if($missing_volumes_array[$body_part] > 0) {
				$there_is_missing_volume_at_this_level = true;
			} else {
				unset($volumes_missing_at_this_level[$body_part]);
			}
		}
		while($there_is_missing_volume_at_this_level) {
			$there_is_missing_volume_at_this_level = false;
			$volume_of_this_body_part_to_restore = $volume_to_restore_at_this_level / sizeof($volumes_missing_at_this_level);
			foreach($body_parts_at_this_level as $body_part) {
				if(!isset($volumes_missing_at_this_level[$body_part])) {
					continue;
				} elseif($volume_of_this_body_part_to_restore <= $missing_volumes_array[$body_part]) {
					$volume_remaining -= $volume_of_this_body_part_to_restore;
					$volumes_restored_array[$body_part] += $volume_of_this_body_part_to_restore;
					$missing_volumes_array[$body_part] -= $volume_of_this_body_part_to_restore;
					$volumes_missing_at_this_level[$body_part] -= $volume_of_this_body_part_to_restore;
				} else {
					$volume_remaining -= $missing_volumes_array[$body_part];
					$volumes_restored_array[$body_part] += $missing_volumes_array[$body_part];
					$missing_volumes_array[$body_part] = 0;
					unset($volumes_missing_at_this_level[$body_part]);
				}
				if($missing_volumes_array[$body_part] > 0) {
					$there_is_missing_volume_at_this_level = true;
				}
			}
		}
	}
	print('$volumes_restored_array: ');var_dump($volumes_restored_array);exit(0);
	return $volumes_restored_array;
}

?>