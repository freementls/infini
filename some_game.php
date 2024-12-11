<?php

$game = array();
if(!isset($_REQUEST['action'])) {
	$game['options'][] = array('200 gold', array('gold' => 200));
	$game['options'][] = array('some fight', array('gold' => 500));
} else {
	
}

?>

<h1>Some game</h1>
<h2>Results</h2>

<h2>Options for the day</h2>

<h2>Debug</h2>