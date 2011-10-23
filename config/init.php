<?php

// veritabanı bilgilerimiz ve pek çok bilgimiz
F3::config(".f3.ini");
F3::set('DB', new DB('mysql:host=localhost;port=3306;dbname=' . F3::get('dbname'), F3::get('dbuser'), F3::get('dbpass')));
F3::set('SR', '/' . strtok($_SERVER["SCRIPT_NAME"], '/'));


$sql = explode(';', file_get_contents('config/schema.db'));
foreach ($sql as $raw)
	if ($query = trim($raw))
		DB::sql($query);

$table = new Axon('ondokuz');
if (count($table->find()) == 0)
	DB::sql("insert into ondokuz (username, password, status, photo) values('19', 'ondokuz', 1, 'default.png');");
?>
