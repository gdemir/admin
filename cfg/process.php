<?php

function process($key, $notice) {
	$process = new Axon(F3::get('TABLEPROCESS'));
	$process->username = F3::get('SESSION.adminusername');
	$process->tablename = F3::get('SESSION.TABLE');
	$process->keyname = $key;
	$process->processtime = date("Y-m-d h:i:s");
	$process->content = $notice;
	$process->save();
}

?>
