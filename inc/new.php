<?php

<<<<<<< HEAD
=======
include 'init.php';

>>>>>>> de65aee3a127560e2d80eaef2d986171cd2fe5fa
// table
$TABLE = F3::get('SESSION.TABLE');

F3::get('DB')->schema($TABLE, 0);// 0 nolu kayıt gibi Field alanlarını al.

F3::clear('error');
F3::clear('SESSION.key'); // yeni veri(kullanıcı) öncesi eskiyi veriyi sil

return F3::call('add'); // f3.php'den fonksiyon çağırımı

?>
