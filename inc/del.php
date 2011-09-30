<?php

// table
$TABLE = F3::get('SESSION.TABLE');
$KEY = F3::get('SESSION.KEY');
<<<<<<< HEAD
$key = F3::get('SESSION.key');
=======
$key = F3::get('REQUEST.key') ? F3::get('REQUEST.key') : F3::get('SESSION.key');

if (!adminsuper()) return F3::call('home');
>>>>>>> de65aee3a127560e2d80eaef2d986171cd2fe5fa

$table = new Axon($TABLE);

$table->load("$KEY='$key'");

// resmi bu olsa gerek.
<<<<<<< HEAD
Image::delete($TABLE, $table->$KEY);
=======
$resim = F3::get('uploaddir') . "$TABLE/" . $table->$KEY . '.jpg';
if (file_exists($resim)) // ama hakiketen var mı?
	unlink($resim);
>>>>>>> de65aee3a127560e2d80eaef2d986171cd2fe5fa

$table->erase();
F3::set('SESSION.SAVE', F3::get('SESSION.SAVE') - 1);

F3::clear('error');
<<<<<<< HEAD
=======
F3::clear('SESSION.key');
>>>>>>> de65aee3a127560e2d80eaef2d986171cd2fe5fa
F3::set('correct', "'$key'ye ait bilgiler başarıyla silindi");
return F3::call('find'); // f3.php'den fonksiyon çağırımı

?>
