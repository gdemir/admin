<?php

<<<<<<< HEAD
// table
=======
include 'init.php';

>>>>>>> de65aee3a127560e2d80eaef2d986171cd2fe5fa
$TABLE = F3::get('SESSION.TABLE');

$table = new Axon($TABLE);
$table->afind();

if ($table->dry())
	csv($TABLE, $table);
else
	F3::set('error', "$TABLE tablosunda kayıt yok");

?>
