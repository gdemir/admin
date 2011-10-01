<?php
include 'init.php';
include 'csv.php';
include 'image.php';

F3::config(".f3.ini");
F3::set('DB', new DB('mysql:host=localhost;port=3306;dbname=' . F3::get('dbname'), F3::get('dbuser'), F3::get('dbpass')));
F3::set('SERVICEROOT', '/' . strtok($_SERVER["SCRIPT_NAME"], '/'));

function in($item, $fields) {
	foreach ($fields as $field => $type) {
		if ($type) {
			if (preg_match('/'.$field.'/',$item)) return true;
		} else {
			if ($field == $item) return true;
		}
	}
	return false;
}
?>
