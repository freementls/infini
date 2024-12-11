<?php

define('DS', DIRECTORY_SEPARATOR);
include('..' . DS . 'LOM' . DS . 'O.php');
$O = new O('choice_connections.xml');
$player = $_REQUEST['player'];
$you_or_opponent = $_REQUEST['you_or_opponent'];
$connections = $O->_('connection');
if($you_or_opponent == 'you') {
	foreach($connections as $connection) {
		//print('$O->_(\'player\', $connection), $player, time(), $O->_(\'time\', $connection): ');var_dump($O->_('player', $connection), $player, time(), $O->_('time', $connection));
		if($O->_('player', $connection) === $player && time() - $O->_('time', $connection) < 2) { // timeout of 2 seconds. not sure how much this matters; a problem may lie in 2 connection monitoring checks colliding with each other
			print('connected');
			return;
		}
	}
	print('<button type="submit">connect</button>');
} else {
	
	foreach($connections as $connection) {
		if($O->_('player', $connection) != $player && time() - $O->_('time', $connection) < 2) { // timeout of 2 seconds
			//print('connected	' . $O->_('player', $connection) . '	' . $O->get_attribute('player', $last_choice_index - 1) . '	' . $last_choice);
			print('connected	' . $O->_('player', $connection));
			return;
		}
	}
	//print('not connected	 	' . $O->get_attribute('player', $last_choice_index - 1) . '	' . $last_choice);
	print('not connected	 ');
}

?>