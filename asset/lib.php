<?php
include 'init.php';
include 'csv.php';
include 'image.php';
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
