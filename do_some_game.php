<?php

$action = $_REQUEST['action'];
if($action == false) {
	//print('Action is false; cannot proceed.');exit(0);
	print('<h1>Some game</h1>
<h2>Results</h2>

<h2>Options for the day</h2>

<h2>Debug</h2>');
} else {
	include('some_game.php');
	$some_game = new some_game();
	$res = call_user_func(array($some_game, $action));
}

?>