<?php

define('DS', DIRECTORY_SEPARATOR);
include('..' . DS . 'LOM' . DS . 'O.php');
$O = new O('choice_connections.xml');
$O->delete('connection');
$O->save_LOM();
$O = new O('choices.xml');
$O->delete('choice');
$O->save_LOM();
header('choice.php');

?>