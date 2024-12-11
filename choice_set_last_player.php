<?php

define('DS', DIRECTORY_SEPARATOR);
include('..' . DS . 'LOM' . DS . 'O.php');
$O = new O('choices.xml');
$choices = $O->_('choice');
foreach($choices as $last_choice_index => $last_choice) {  }
// also add in previous move
$choices_object = new O('choices.xml');
$choices = $choices_object->_('choice');
//print('$choices in choice_connections: ');var_dump($choices);
if(is_string($choices)) {
	$last_choice = $choices;
} elseif(sizeof($choices) > 0) {
	foreach($choices as $last_choice_index => $last_choice) {  }
}
print($O->get_attribute('player', $last_choice_index - 1) . '	' . $last_choice);

?>