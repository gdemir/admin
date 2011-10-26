<?php
function process($notice) {
	$process = new Axon('process');

	$process->username = F3::get('SESSION.adminusername');
	$process->tablename = F3::get('SESSION.TABLE');
	$process->keyname = F3::get('SESSION.key');
	$process->datetime = date("Y-m-d h:i:s");
	$process->content = $notice;

	$process->save();
}
?>
