<?php

define('DS', DIRECTORY_SEPARATOR);
include('..' . DS . 'LOM' . DS . 'O.php');
$units = new O('bba' . DS . 'units.xml');
$unit_id_by_request = $_REQUEST['unit_id'];
$new_unit_location_by_request = $_REQUEST['new_unit_location'];
//print('$unit_id_by_request, $new_unit_location_by_request: ');var_dump($unit_id_by_request, $new_unit_location_by_request);
$units->__('location', $new_unit_location_by_request, '.unit_id=' . $unit_id_by_request);
$units->save();

?>