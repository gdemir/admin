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

	$process = new Axon(F3::get('TABLEPROCESS'));
	DB::sql('select * from ' . F3::get('TABLEPROCESS') .' ORDER BY time DESC LIMIT 10');
	F3::set('SESSION.PROCESS', F3::get('DB->result'));

	$table = new Axon(F3::get('TABLE'));
	DB::sql('select * from ' . F3::get('TABLE') .' ORDER BY login DESC LIMIT 3');
	F3::set('SESSION.ADMIN', F3::get('DB->result'));
}

?>
