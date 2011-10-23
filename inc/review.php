<?php

// table
$TABLE = F3::get('SESSION.TABLE');

F3::get('DB')->schema($TABLE, 0);// 0 nolu kayıt gibi Field alanlarını al.

$fields = array();
foreach (F3::get('DB->result') as $index => $field)
	array_push($fields, $field['Field']);

F3::set('fields', $fields);

$table = new Axon($TABLE);
$table->afind();

F3::call('review');
?>
