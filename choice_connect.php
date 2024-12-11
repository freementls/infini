<?php

$player = $_REQUEST['player'];
if($player != false) {
	//print('here29481<br>');
	define('DS', DIRECTORY_SEPARATOR);
	include('..' . DS . 'LOM' . DS . 'O.php');
	$O = new O('choice_connections.xml');
	//print('here29485<br>');
	$O->__('time', time(), '.connection_player=' . $O->enc($player));
	//print('here29486<br>');
	$O->save_LOM();
	//print('here29487<br>');
}

?>