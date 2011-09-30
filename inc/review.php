<?php

<<<<<<< HEAD
=======
include 'init.php';

>>>>>>> de65aee3a127560e2d80eaef2d986171cd2fe5fa
// table
$TABLE = F3::get('SESSION.TABLE');

F3::get('DB')->schema($TABLE, 0);// 0 nolu kayıt gibi Field alanlarını al.

$fields = array();
foreach (F3::get('DB->result') as $gnl => $blg)
	array_push($fields, $blg['Field']);

<<<<<<< HEAD
F3::set('fields', $fields);
=======
// özel gösterim FIXME
$request_fields = array('id', 'tc', 'ad', 'soyad', 'kizliksoyad', 'tarih', 'saat', 'username', 'photo');
F3::set('fields', $fields);
F3::set('request_fields', $request_fields);
>>>>>>> de65aee3a127560e2d80eaef2d986171cd2fe5fa

$table = new Axon($TABLE);
$table->afind();

if ($table->dry())
	F3::call('review');
else
	F3::set('error', "$TABLE tablosunda kayıt yok");

?>
