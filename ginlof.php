<?php

//print('test');
define('DS', DIRECTORY_SEPARATOR);
include('..' . DS . 'LOM' . DS . 'O.php');
$shiptypes = new O('ginlof_shiptypes.xml');
$weapontypes = new O('ginlof_weapontypes.xml');
$teams = new O('ginlof_teams.xml');

foreach($teams->_('fleet', '.team_id=0') as $team0_fleet) {
	print('$team0_fleet: ');var_dump($team0_fleet);
	print($teams->_('shiptype', $team0_fleet) . ': ' . $teams->_('number', $team0_fleet) . '<br>');
	foreach($weapontypes->_('._name=' . $shiptypes->_('$teams->_('weapontype', $team0_fleet) as $weapontype) {
		print($weapontype . '<br>');exit(0);
	}
}
print('versus<br>');

?>