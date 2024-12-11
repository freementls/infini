<?php

//DEFINE('DS', DIRECTORY_SEPARATOR);
//$folder = 'P:' . DS . 'Games' . DS . 'Age of Wonders III';
$folder = $_REQUEST['folder'];
//print('$folder: ');var_dump($folder);exit(0);
$handle = opendir($folder);
//$array_entries = array();
$did_first = false;
while(($entry = readdir($handle)) !== false) {
	//if($entry === '.' || $entry === '..') {
	if($entry === '.') {
		
	} else {
		//$array_entries[] = $folder . DS . $entry;
		//print($folder . DS . $entry . '	');
		if($did_first) {
			print('	' . $entry);
		} else {
			$did_first = true;
			print($entry);
		}
	}
}
closedir($handle);

?>