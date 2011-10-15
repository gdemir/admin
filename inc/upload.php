<?php

// table & key
$TABLE = F3::get('SESSION.TABLE');
$KEY = F3::get('SESSION.KEY');
$csv_key = F3::get('REQUEST.csv_key');
$csv_file = F3::get('FILES.csv_file');

$csv_file_name = date("Y-m-d_h:i:s");
$up = new Upload(array('csv'));

if (! $response = ($up->load($TABLE, $csv_file_name, $csv_file, true)))
	F3::set('error', "$TABLE tablosuna csv yükleme isteğiniz yok");

if (F3::exists('error')) // yükleme sırasında hata var mı?
	return F3::call('upload');

F3::get('DB')->schema($TABLE, 0);// 0 nolu kayıt gibi Field alanlarını al.

//F3::get('DB->result');
$fp = 'public/upload/' . $TABLE . '/' . $csv_file_name;


// if ($table->dry())
// 	Csv::download($TABLE, F3::get('DB->result'), $csv_key);
// else
// 	F3::set('error', "$TABLE tablosunda kayıt yok");

?>
