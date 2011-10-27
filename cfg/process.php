<?php

function process($keyname, $content, $name) {
	$process = new Axon(F3::get('TABLEPROCESS'));
	$process->username = F3::get('SESSION.adminusername');
	$process->tablename = F3::get('SESSION.TABLE');
	$process->keyname = $keyname;
	$process->time = date("Y-m-d h:i:s");
	$process->content = $content;
	$process->name = $name;
	$process->save();
}

?>
