<?php

<<<<<<< HEAD
// table & key
=======
>>>>>>> de65aee3a127560e2d80eaef2d986171cd2fe5fa
$TABLE = F3::get('SESSION.TABLE');
$KEY = F3::get('SESSION.KEY');
$key = F3::get('SESSION.key');

$table = new Axon($TABLE);

$datas = $table->afind("$KEY='$key'");

F3::set('SESSION.data', $datas[0]);
return F3::reroute('/show');

?>
