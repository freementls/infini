<html>
<head>
<style type="text/css">
form { display: inline; }
</style>
</head>

<?php

include('../LOM/O.php');
$O = new O('relations.xml');
$stats = $O->_('stat');

/*print('$O->get_indices(\'stattype\'): ');var_dump($O->get_indices('stattype'));
print('$O->get_indices(\'creaturetype\'): ');var_dump($O->get_indices('creaturetype'));
print('$O->get_indices(\'stattype_damage_multiplier\'): ');var_dump($O->get_indices('stattype_damage_multiplier'));
print('$O->get_indices(\'creaturetype_defense_multiplier\'): ');var_dump($O->get_indices('creaturetype_defense_multiplier'));
print('$O->get_indices(\'multiplier\', $O->get(\'stattype_attack\')): ');var_dump($O->get_indices('multiplier', $O->get('stattype_attack')));
print('$O->get_indices(\'multiplier\', $O->get(\'creaturetype_defense\')): ');var_dump($O->get_indices('multiplier', $O->get('creaturetype_defense')));
print('$O->context: ');var_dump($O->context);
print('$O->get(\'stattype\'): ');var_dump($O->get('stattype'));
print('$O->get(\'creaturetype\'): ');var_dump($O->get('creaturetype'));
print('$O->get(\'stattype_damage_multiplier\'): ');var_dump($O->get('stattype_damage_multiplier'));
print('$O->get(\'creaturetype_defense_multiplier\'): ');var_dump($O->get('creaturetype_defense_multiplier'));
//print('$O->get(\'stattype_attack\'): ');var_dump($O->get('stattype_attack'));
//print('$O->get(\'multiplier\', array()): ');var_dump($O->get('multiplier', array()));
print('$O->get(\'multiplier\', $O->get(\'stattype_attack\')): ');var_dump($O->get('multiplier', $O->get('stattype_attack')));
print('$O->get(\'multiplier\', $O->get(\'creaturetype_defense\')): ');var_dump($O->get('multiplier', $O->get('creaturetype_defense')));
exit(0);*/

// should stats (attributes) balance based on whether equipped? kept? increased?
// player that wins (something) gets to design a unique item, of which only 1 can drop on the server at a time
// update data
//print('$_REQUEST: ');var_dump($_REQUEST);
// experience requirements for current and maximum could also scale, but for now they are linear
//$attribute_properties = array('current', 'maximum', 'experience'); // hard-coded for now
// maybe current durability should also have a cost instead of only maximumdurability

$input = 10; // simulating when stattypes would be instantiated
$balanceables = array('stattype', 'creaturetype');

print('$_REQUEST: ');var_dump($_REQUEST);

if($_REQUEST['balance'] === 'true') {
	$multiplier_changes = array();
	foreach($balanceables as $balanceable) {
		$balancers = array_unique($O->_('with', $O->_($balanceable)));
		//print('$balanceable, $balancers: ');var_dump($balanceable, $balancers);
		//$balancings = $O->_($balanceable);
		
		/*$first_tag = attribute_url_decode(substr($query, 0, strpos($query, '_')));
		$first_tag_is_a_balanceable = false;
		if($balanceable === $first_tag) {
			$first_tag_is_a_balanceable = true;
		}*/
		
		foreach($balancers as $balancer) {
			$balancings_with_balancer = $O->_('.' . $balanceable . '_' . $balancer);
			$balancer_sum = $O->sum($O->_($balanceable . '_' . $balancer));
			//print('$balancer, $balancings_with_balancer, $balancer_sum: ');var_dump($balancer, $balancings_with_balancer, $balancer_sum);
			if($balancer_sum > 0) {
				foreach($balancings_with_balancer as $index => $value) {
					//print('$value: ');var_dump($value);
					$balancer_of_value = $O->_($balancer, $value);
					if($balancer_of_value == 0) {
						$balancer_of_value = 1;
					}
					//print('$balancer_of_value: ');var_dump($balancer_of_value);
					$balance_factor = $balancer_of_value / ($balancer_sum / sizeof($balancings_with_balancer));
					print('$balance_factor: ');var_dump($balance_factor);
					//$inverse_balance_factor = 1 / $balance_factor;
					//print('$O->_(\'stat_*[0]\', $value): ');var_dump($O->_('stat_*[0]', $value));
					// have to check what kind of relation is defined
					// <cost><value>$input</value><multiplier>1</multiplier><relations><type>direct</type><with>used</with></relations></cost>
					// assuming used
					//print('$O->get_index(\'multiplier\', $O->_(\'stat_*[0]\', $value)): ');var_dump($O->get_index('multiplier', $O->_('stat_*[0]', $value)));
					//$O->__($O->get_index('multiplier', $O->_('stat_*[0]', $value)), $O->_('used', $value) * $balance_factor); // would like a threshhold instead of balancing every value?
					
					/*if($first_tag_is_a_balanceable) {
						$O->__(substr($query, strpos_last($query, '_') + 1), $value, $O->_('.' . substr($query, 0, strpos_last($query, '_'))));
					} else {
						$tag_to_modify = $O->_('.*_name=' . $O->enc($first_tag));
						//print('$tag_to_modify, substr($query, strpos_last($query, \'_\') + 1): ');var_dump($tag_to_modify, substr($query, strpos_last($query, '_') + 1));
						$O->__(substr($query, strpos_last($query, '_') + 1), $value, $tag_to_modify);
					}*/
					
					// would like monte carlo approach when there is more than one multiplier related to a given thing; how to code this?
					// especially have to notice that we are over-balancing when there is more than one thing to balance. have to build a balance distribution from balance points is a way similar to building an item from item points
					//if($O->_('name', $value) !== false) { // giving 'name' a priviledged status in that it cannot be used in offspring
					//if(sizeof($O->_('name', $value)) > 0) { // giving 'name' a priviledged status in that it cannot be used in offspring
						$elements_to_balance = $O->_('.*_*_*_with=' . $balancer, $value);
						//print('$value, $balancer, $balance_factor, $elements_to_balance, $O->_(\'multiplier\', $elements_to_balance): ');var_dump($value, $balancer, $balance_factor, $elements_to_balance, $O->_('multiplier', $elements_to_balance));
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
							$relation_types = $O->_('relations_relation_type', $element_to_balance);
							//print('$relation_types: ');var_dump($relation_types);
							if(is_string($relation_types)) {
								if($relation_types === 'direct') {
									//$O->multiply('multiplier', $balance_factor, $element_to_balance); // would like a threshhold instead of balancing every value?
									//print($O->_('name', $value) . ' ' . $O->tagname($element_to_balance) . ' multiplier was multiplied by ' . $balance_factor . '<br>');
									$multiplier_changes[] = array($balance_factor_portion, $element_to_balance, $value);
								} else {
									//$O->divide('multiplier', $balance_factor, $element_to_balance); // would like a threshhold instead of balancing every value?
									//print($O->_('name', $value) . ' ' . $O->tagname($element_to_balance) . ' multiplier was divided by ' . $balance_factor . '<br>');
									$multiplier_changes[] = array(1 / $balance_factor_portion, $element_to_balance, $value);
								}
							} else {
								foreach($relation_types as $relation_type) {
									//$O->_('multiplier', $element_to_balance); // for magic? puts it into the context, I guess. just indicates that there is a problem with get_indices
									if($relation_type === 'direct') {
										//$O->multiply('multiplier', $balance_factor, $element_to_balance); // would like a threshhold instead of balancing every value?
										//print($O->_('name', $value) . ' ' . $O->tagname($element_to_balance) . ' multiplier was multiplied by ' . $balance_factor . '<br>');
										$multiplier_changes[] = array($balance_factor_portion, $element_to_balance, $value);
									} else {
										//$O->divide('multiplier', $balance_factor, $element_to_balance); // would like a threshhold instead of balancing every value?
										//print($O->_('name', $value) . ' ' . $O->tagname($element_to_balance) . ' multiplier was divided by ' . $balance_factor . '<br>');
										$multiplier_changes[] = array(1 / $balance_factor_portion, $element_to_balance, $value);
									}
								}
							}
						}
					/*} else {					
						//$relation_type = $O->_('relation_type', $O->_($balanceable . '_*[0]', $value));
						//$relation_types = $O->_('relation_type', $O->_($balanceable . '_*', $value));
						//$elements_to_balance = $O->_($balanceable . '_*', $value);
						$elements_to_balance = $O->_('.*_*_*_with=' . $balancer, $value);
						//print('$elements_to_balance: ');var_dump($elements_to_balance);
						foreach($elements_to_balance as $element_index => $element_to_balance) {
							//$O->_('multiplier', $element_to_balance); // for magic? puts it into the context, I guess. just indicates that there is a problem with get_indices
							$relation_types = $O->_('relations_relation_type', $element_to_balance);
							if(is_string($relation_types)) {
								if($relation_types === 'direct') {
									$O->multiply('multiplier', $balance_factor, $element_to_balance); // would like a threshhold instead of balancing every value?
									print($O->tagname($value) . ' ' . $O->tagname($element_to_balance) . ' multiplier was multiplied by ' . $balance_factor . '<br>');
								} else {
									$O->divide('multiplier', $balance_factor, $element_to_balance); // would like a threshhold instead of balancing every value?
									print($O->tagname($value) . ' ' . $O->tagname($element_to_balance) . ' multiplier was divided by ' . $balance_factor . '<br>');
								}
							} else {
								foreach($relation_types as $relation_type) {
									if($relation_type === 'direct') {
										$O->multiply('multiplier', $balance_factor, $element_to_balance); // would like a threshhold instead of balancing every value?
										print($O->tagname($value) . ' ' . $O->tagname($element_to_balance) . ' multiplier was multiplied by ' . $balance_factor . '<br>');
									} else {
										$O->divide('multiplier', $balance_factor, $element_to_balance); // would like a threshhold instead of balancing every value?
										print($O->tagname($value) . ' ' . $O->tagname($element_to_balance) . ' multiplier was divided by ' . $balance_factor . '<br>');
									}
								}
							}
						}
					}*/
					//print('unipeg attack multiplier: ');var_dump($O->_('attack_multiplier', $O->_('.creaturetype_name=' . $O->enc('unipeg rider')))); // debug
				}
			}
			//print('unipeg attack multiplier: ');var_dump($O->_('attack_multiplier', $O->_('.creaturetype_name=' . $O->enc('unipeg rider')))); // debug
		}
	}
	foreach($multiplier_changes as $index => $value) {
		$balance_factor = $value[0];
		$element_to_balance = $value[1];
		$value = $value[2];
		$O->multiply('multiplier', $balance_factor, $element_to_balance); // would like a threshhold instead of balancing every value?
		if(sizeof($O->_('name', $value)) > 0) {
			print($O->_('name', $value) . ' ' . $O->tagname($element_to_balance) . ' multiplier was multiplied by ' . $balance_factor . '<br>');
		} else {
			print($O->tagname($value) . ' ' . $O->tagname($element_to_balance) . ' multiplier was multiplied by ' . $balance_factor . '<br>');
		}
	}
	// reset values that are being balanced from
	//$O->__('*@resets=true', 0);
}

//$request_result = $_REQUEST[attribute_url_encode($O->_('name', $attribute)) . '_' . $attribute_property];
//if($request_result !== NULL) {
foreach($_REQUEST as $query => $value) {
	if($query === 'balance') {
		continue;
	}
	//print('$request_result: ');var_dump($request_result);
	//print('$O->_(\'name\', $attribute), $attribute_property, \'attribute@name=\' . $O->_(\'name\', $attribute) . \'_\' . $attribute_property, $request_result, $attribute: ');var_dump($O->_('name', $attribute), $attribute_property, 'attribute@name=' . $O->_('name', $attribute) . '_' . $attribute_property, $request_result, $attribute);
	//$O->__('attribute@name=' . $O->_('name', $attribute) . '_' . $attribute_property, $request_result);
	$first_tag = attribute_url_decode(substr($query, 0, strpos($query, '_')));
	//print('$first_tag: ');var_dump($first_tag);
	$first_tag_is_a_balanceable = false;
	foreach($balanceables as $balanceable) {
		if($balanceable === $first_tag) {
			$first_tag_is_a_balanceable = true;
			break;
		}
	}
	if($first_tag_is_a_balanceable) {
		$O->__(substr($query, strpos_last($query, '_') + 1), $value, $O->_('.' . substr($query, 0, strpos_last($query, '_'))));
	} else {
		$tag_to_modify = $O->_('.*_name=' . $O->enc($first_tag));
		//print('$tag_to_modify, substr($query, strpos_last($query, \'_\') + 1): ');var_dump($tag_to_modify, substr($query, strpos_last($query, '_') + 1));
		$O->__(substr($query, strpos_last($query, '_') + 1), $value, $tag_to_modify);
	}
	print($query . ' was changed to ' . $value);
}

print('<form method="post" action="relations.php?balance=true">
<input type="submit" value="balance!" />
</form>
');
//$O->save_LOM_to_file('relations.xml');


foreach($balanceables as $balanceable) {
	//print('$balanceable: ');var_dump($balanceable);
	$balancings = $O->_($balanceable);
	print('<table border="1" cellspacing="0" cellpadding="4">
');
	foreach($balancings as $balancing) {
		print('<tr>
');
		$tags_without_multipliers = $O->_($balanceable . '_*', $balancing);
		$tags_with_multipliers = $O->_($balanceable . '_.*_multiplier', $balancing);
		//$tags = $O->_('stat__*');
		//print('$tags_without_multipliers, $tags_with_multipliers: ');var_dump($tags_without_multipliers, $tags_with_multipliers);
		foreach($tags_with_multipliers as $index => $tag) {
			if($index === 0) {
				//if($O->_($balanceable . '_name', $balancing) !== false) {
				if(sizeof($O->_($balanceable . '_name', $balancing)) > 0) {
					//print('<th scope="row">' . $O->_($balanceable . '_name', $balancing) . ' ' . $O->tagname($tag) . '</th>');
					print('<th scope="row">' . $O->_($balanceable . '_name', $balancing) . '</th>');
				} else {
					print('<th scope="row">' . $O->tagname($tag) . '</th>');
				}
			}
			$multiplier = $O->_('multiplier', $tag);
			//print('$tag, $O->tagname($tag), $multiplier: ');var_dump($tag, $O->tagname($tag), $multiplier);
			//if($multiplier == false) {
			//	print('<td>' . $O->tagname($tag) . ': ' . $tag . '</td>');
			//} else {
				// what about rounding of values that aren't integers?
				eval('$value = ' . $O->_('value', $tag) . ';');
				print('<td>' . $O->tagname($tag) . ': ' . $value . ' with multiplier: ' . $multiplier . ' for an effective value of ' . ceil($value * $multiplier) . '</td>');
			//}
		}
		foreach($tags_without_multipliers as $index => $value) {
			//print('<td>' . $O->tagname($value) . ': ' . $value . '</td>');
			//print('$balanceable, $O->tagname($tags_with_multipliers[0]), $O->tagname($index - 1), $balanceable . \'_\' . $O->tagname($tags_with_multipliers[0]) . \'_\' . $O->tagname($index - 1): ');var_dump($balanceable, $O->tagname($tags_with_multipliers[0]), $O->tagname($index - 1), $balanceable . '_' . $O->tagname($tags_with_multipliers[0]) . '_' . $O->tagname($index - 1));
			//if($O->_($balanceable . '_name', $balancing) !== false) {
			if(sizeof($O->_($balanceable . '_name', $balancing)) > 0) {
				//print('<th scope="row">' . $O->_($balanceable . '_name', $balancing) . ' ' . $O->tagname($tag) . '</th>');
				//print('<th scope="row">' . $O->_($balanceable . '_name', $balancing) . '</th>');
				$input_name = attribute_url_encode($O->_($balanceable . '_name', $balancing) . '_' . $O->tagname($tags_with_multipliers[0]) . '_' . $O->tagname($index - 1));
			} else {
				//print('<th scope="row">' . $O->tagname($tag) . '</th>');
				$input_name = attribute_url_encode($balanceable . '_' . $O->tagname($tags_with_multipliers[0]) . '_' . $O->tagname($index - 1));
			}
			if(strlen($value) < 10) {
				print('<td><form method="post" action="relations.php">' . $O->tagname($index - 1) . ':<input type="text" name="' . $input_name . '" value="' . $value . '" size="10" /><input type="submit" value="update" /></form></td>');
			} else {
				print('<td><form method="post" action="relations.php">' . $O->tagname($index - 1) . ':<input type="text" name="' . $input_name . '" value="' . $value . '" /><input type="submit" value="update" /></form></td>');
			}
		}
		print('</tr>
');
	}
	print('</table>
');
}

function attribute_url_encode($string) {
    $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
    $replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
    return str_replace($entities, $replacements, urlencode($string));
}

function attribute_url_decode($string) {
    $entities = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
    $replacements = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
    return str_replace($entities, $replacements, urldecode($string));
}

function signedpow($base, $exponent) {
	if($base < 0 && $exponent % 2 == 0) {
		return -1 * pow($base, $exponent);
	} else {
		return pow($base, $exponent);
	}
}

function strpos_last($haystack, $needle) {
	//print('$haystack, $needle: ');var_dump($haystack, $needle);
	if(strlen($needle) === 0) {
		return false;
	}
	$len_haystack = strlen($haystack);
	$len_needle = strlen($needle);		
	$pos = strpos(strrev($haystack), strrev($needle));
	if($pos === false) {
		return false;
	}
	return $len_haystack - $pos - $len_needle;
}

?>