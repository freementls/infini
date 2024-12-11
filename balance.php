<html>
<head>
<title>balance</title>
<style>
body { background: #220044; }
* { color: #9999CC; }
</style>
</head>

<?php

include('../LOM/O.php');
// attributes
// creaturetypes
// areas
// stats
// should there be a distinction between stats and attributes? attributes are directly on creatures and stats are on other things. an item can't have strength, a player character can't have sharpness?
// attributes and item stat rolls aren't directly balanced; rather multipliers on stats and numbers depending on attributes are (for example, with damage = strength x attack speed, damage would have the multiplier)
// just define where stats and attributes can appear.
// should there be overarching rules determining experience?
// further divide attributes: arm strength, leg strength, right arm strength, calf strength
//$items_to_balance = $O->_('stat');
$things_to_balance = array(
'attribute' => 'attributes.xml', //
'creaturetype' => 'areas.xml',
'area' => 'areas.xml',
'stat' => 'balance.xml',
);
foreach($things_to_balance as $thing_to_balance => $file) {
	print('<h2>' . $thing_to_balance . 's</h2>');
	$O = new O($file);
	//$items_to_balance = $O->_('stat|monstertype|attacktype');
	$items_to_balance = $O->_($thing_to_balance);
	//print('$items_to_balance: ');var_dump($items_to_balance);
	$input = '';
	foreach($items_to_balance as $to_balance) {
		print('$to_balance: ');var_dump($to_balance);
		// should LOM just recognize that we want the tags in this case, instead of the values?
		$values_of_elements_with_multipliers = $O->_('*@multiplier', $to_balance);
		print('$values_of_elements_with_multipliers: ');var_dump($values_of_elements_with_multipliers);
		if($values_of_elements_with_multipliers !== false) {
			foreach($values_of_elements_with_multipliers as $LOM_index => $value_of_element_with_multiplier) {
				$element_with_multiplier = $O->_($LOM_index - 1);
				//print('$element_with_multiplier: ');var_dump($element_with_multiplier);
				$rand = rand(-50, 50);
				$multiplier = $O->get_attribute('multiplier', $element_with_multiplier);
				$new_multiplier = $multiplier * (1 + ($rand / 100));
				print(remove_variable_references($O->_('text', $to_balance)) . ' ' . $O->tagname($to_balance) . ' multiplier went from ' . $multiplier . ' to ' . $new_multiplier . '.<br>');
				//$O->__($multiplier, $new_multiplier);
			}
		}
	}
	//$O->save_LOM_to_file($file);
}

function remove_variable_references($string) {
	return preg_replace('/[^\s]{0,}\$[a-zA-Z]+/is', '', $string);
}

?>