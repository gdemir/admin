<?php

// table & key
$TABLE = F3::get('SESSION.TABLE');
$KEY = F3::get('SESSION.KEY');
<<<<<<< HEAD
$key = F3::get('SESSION.key');
=======
$key = F3::get('REQUEST.key') ? F3::get('REQUEST.key') : F3::get('SESSION.key');
F3::set('SESSION.key', $key);
>>>>>>> de65aee3a127560e2d80eaef2d986171cd2fe5fa

$table = new Axon($TABLE);

$datas = $table->afind("$KEY='$key'");

F3::set('data', $datas[0]);
return F3::call('edit');

?>
